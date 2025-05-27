import 'package:flutter/material.dart';
import 'package:iconsax/iconsax.dart';
import 'package:intl/intl.dart';
import 'package:mobile_puskesmas/models/pendaftaran_model.dart';
import 'package:mobile_puskesmas/screens/pendaftaran_success_screen.dart';
import 'package:mobile_puskesmas/services/auth_service.dart';
import 'package:mobile_puskesmas/services/pendaftaran_service.dart';

class PatientFormScreen extends StatefulWidget {
  const PatientFormScreen({Key? key}) : super(key: key);

  @override
  State<PatientFormScreen> createState() => _PatientFormScreenState();
}

class _PatientFormScreenState extends State<PatientFormScreen> {
  final _formKey = GlobalKey<FormState>();

  // Controllers
  final _nikController = TextEditingController();
  final _noKkController = TextEditingController();
  final _namaController = TextEditingController();
  final _keluhanController = TextEditingController();
  final _tempatLahirController = TextEditingController();
  final _tanggalLahirController = TextEditingController();
  final _tanggalDaftarController = TextEditingController();
  final _alamatController = TextEditingController();
  final _noHpController = TextEditingController();
  final _pekerjaanController = TextEditingController();
  final _noBpjsController = TextEditingController();
  final _riwayatAlergiController = TextEditingController();
  final _riwayatPenyakitController = TextEditingController();

  // Form state
  String? _jenisPasien;
  String? _jenisPembayaran;
  String? _jenisKelamin;
  String? _golonganDarah;
  String? _clusterId;
  bool _isLoading = false;
  bool _isLoadingClusters = false;
  String? _clusterError;
  DateTime? _selectedBirthDate;
  DateTime? _selectedRegistrationDate;
  List<ClusterModel> _clusters = [];

  // Options
  final List<String> _jenisPasienOptions = ['baru', 'lama'];
  final List<String> _jenisPembayaranOptions = ['bpjs', 'umum'];
  final List<String> _jenisKelaminOptions = ['laki-laki', 'perempuan'];
  final List<String> _golonganDarahOptions = ['A', 'B', 'AB', 'O'];

  final Map<String, bool> _fieldFilled = {};

  @override
  void initState() {
    super.initState();
    _loadUserData();
    _fetchClusters();
    _setupTextFieldListeners();
    _tanggalDaftarController.text = DateTime.now().toIso8601String().split('T')[0];
    _selectedRegistrationDate = DateTime.now();
  }

  void _setupTextFieldListeners() {
    _nikController.addListener(() => _updateFieldStatus('nik', _nikController.text.isNotEmpty));
    _noKkController.addListener(() => _updateFieldStatus('noKk', _noKkController.text.isNotEmpty));
    _namaController.addListener(() => _updateFieldStatus('nama', _namaController.text.isNotEmpty));
    _keluhanController.addListener(() => _updateFieldStatus('keluhan', _keluhanController.text.isNotEmpty));
    _tempatLahirController.addListener(() => _updateFieldStatus('tempatLahir', _tempatLahirController.text.isNotEmpty));
    _tanggalLahirController.addListener(() => _updateFieldStatus('tanggalLahir', _tanggalLahirController.text.isNotEmpty));
    _tanggalDaftarController.addListener(() => _updateFieldStatus('tanggalDaftar', _tanggalDaftarController.text.isNotEmpty));
    _alamatController.addListener(() => _updateFieldStatus('alamat', _alamatController.text.isNotEmpty));
    _noHpController.addListener(() => _updateFieldStatus('noHp', _noHpController.text.isNotEmpty));
    _pekerjaanController.addListener(() => _updateFieldStatus('pekerjaan', _pekerjaanController.text.isNotEmpty));
    _noBpjsController.addListener(() => _updateFieldStatus('noBpjs', _noBpjsController.text.isNotEmpty));
    _riwayatAlergiController.addListener(() => _updateFieldStatus('riwayatAlergi', _riwayatAlergiController.text.isNotEmpty));
    _riwayatPenyakitController.addListener(() => _updateFieldStatus('riwayatPenyakit', _riwayatPenyakitController.text.isNotEmpty));
  }

  void _updateFieldStatus(String fieldName, bool isFilled) {
    setState(() {
      _fieldFilled[fieldName] = isFilled;
    });
  }

  Future<void> _loadUserData() async {
    try {
      setState(() {
        _isLoading = true;
      });
      final user = await AuthService().getUserData();
      if (user != null) {
        setState(() {
          _namaController.text = user.name ?? '';
          _noHpController.text = user.noHp ?? '';
          _alamatController.text = user.alamat ?? '';
          _jenisKelamin = user.jenisKelamin;
          _tanggalLahirController.text = user.tanggalLahir?.toIso8601String().split('T')[0] ?? '';
          _selectedBirthDate = user.tanggalLahir;
        });
      }
    } catch (e) {
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(content: Text('Gagal memuat data pengguna: $e'), backgroundColor: Colors.red),
      );
    } finally {
      setState(() {
        _isLoading = false;
      });
    }
  }

  Future<void> _fetchClusters() async {
    try {
      setState(() {
        _isLoadingClusters = true;
        _clusterError = null;
      });
      final clusters = await PendaftaranService().getClusters();
      print('Fetched clusters: ${clusters.map((c) => {'id': c.id, 'nama': c.nama}).toList()}');
      if (clusters.isNotEmpty) {
        setState(() {
          _clusters = clusters;
          if (_clusterId == null && clusters.isNotEmpty) {
            _clusterId = clusters.first.id.toString();
          }
        });
      } else {
        setState(() {
          _clusterError = 'Tidak ada cluster yang tersedia';
        });
      }
    } catch (e) {
      print('Error fetching clusters: $e');
      setState(() {
        _clusterError = 'Gagal memuat cluster: $e';
      });
    } finally {
      setState(() {
        _isLoadingClusters = false;
      });
    }
  }

  Future<void> _selectBirthDate(BuildContext context) async {
    final DateTime? picked = await showDatePicker(
      context: context,
      initialDate: _selectedBirthDate ?? DateTime(2000),
      firstDate: DateTime(1900),
      lastDate: DateTime.now(),
      builder: (context, child) {
        return Theme(
          data: ThemeData.light().copyWith(
            colorScheme: const ColorScheme.light(primary: Color(0xFF06489F)),
            dialogBackgroundColor: Colors.white,
          ),
          child: child!,
        );
      },
    );
    if (picked != null && picked != _selectedBirthDate) {
      setState(() {
        _selectedBirthDate = picked;
        _tanggalLahirController.text = picked.toIso8601String().split('T')[0];
      });
    }
  }

  Future<void> _selectRegistrationDate(BuildContext context) async {
    final DateTime? picked = await showDatePicker(
      context: context,
      initialDate: _selectedRegistrationDate ?? DateTime.now(),
      firstDate: DateTime.now(),
      lastDate: DateTime.now().add(const Duration(days: 30)),
      builder: (context, child) {
        return Theme(
          data: ThemeData.light().copyWith(
            colorScheme: const ColorScheme.light(primary: Color(0xFF06489F)),
            dialogBackgroundColor: Colors.white,
          ),
          child: child!,
        );
      },
    );
    if (picked != null && picked != _selectedRegistrationDate) {
      setState(() {
        _selectedRegistrationDate = picked;
        _tanggalDaftarController.text = picked.toIso8601String().split('T')[0];
      });
    }
  }

  Future<void> _submitForm() async {
    if (!_formKey.currentState!.validate()) {
      return;
    }
    if (_clusterId == null) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text('Cluster wajib dipilih'), backgroundColor: Colors.red),
      );
      return;
    }
    if (_jenisPasien == null) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text('Jenis pasien wajib dipilih'), backgroundColor: Colors.red),
      );
      return;
    }
    if (_jenisPembayaran == null) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text('Jenis pembayaran wajib dipilih'), backgroundColor: Colors.red),
      );
      return;
    }
    if (_jenisKelamin == null) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text('Jenis kelamin wajib dipilih'), backgroundColor: Colors.red),
      );
      return;
    }
    if (_golonganDarah == null) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text('Golongan darah wajib dipilih'), backgroundColor: Colors.red),
      );
      return;
    }

    try {
      setState(() {
        _isLoading = true;
      });
      final user = await AuthService().getUserData();
      final pendaftaranData = await PendaftaranService().createPendaftaran(
        nik: _nikController.text,
        noKk: _noKkController.text.isNotEmpty ? _noKkController.text : null,
        nama: _namaController.text,
        keluhan: _keluhanController.text,
        clusterId: int.parse(_clusterId!),
        tanggalDaftar: _tanggalDaftarController.text,
        jenisPasien: _jenisPasien!,
        jenisPembayaran: _jenisPembayaran!,
        appUserId: user?.id.toString(),
        jenisKelamin: _jenisKelamin!,
        tanggalLahir: _tanggalLahirController.text,
        tempatLahir: _tempatLahirController.text,
        alamat: _alamatController.text,
        noHp: _noHpController.text,
        pekerjaan: _pekerjaanController.text.isNotEmpty ? _pekerjaanController.text : null,
        noBpjs: _noBpjsController.text.isNotEmpty ? _noBpjsController.text : null,
        golonganDarah: _golonganDarah!,
        riwayatAlergi: _riwayatAlergiController.text.isNotEmpty ? _riwayatAlergiController.text : null,
        riwayatPenyakit: _riwayatPenyakitController.text.isNotEmpty ? _riwayatPenyakitController.text : null,
      );

      // Navigasi ke layar konfirmasi setelah pendaftaran berhasil
      Navigator.pushReplacement(
        context,
        MaterialPageRoute(
          builder: (context) => PendaftaranSuccessScreen(pendaftaranData: pendaftaranData),
        ),
      );
    } catch (e) {
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(content: Text('Terjadi kesalahan: $e'), backgroundColor: Colors.red),
      );
    } finally {
      setState(() {
        _isLoading = false;
      });
    }
  }

  @override
  void dispose() {
    _nikController.dispose();
    _noKkController.dispose();
    _namaController.dispose();
    _keluhanController.dispose();
    _tempatLahirController.dispose();
    _tanggalLahirController.dispose();
    _tanggalDaftarController.dispose();
    _alamatController.dispose();
    _noHpController.dispose();
    _pekerjaanController.dispose();
    _noBpjsController.dispose();
    _riwayatAlergiController.dispose();
    _riwayatPenyakitController.dispose();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: Colors.white,
      appBar: AppBar(
        backgroundColor: const Color(0xFF06489F),
        elevation: 0,
        title: const Text(
          'Pendaftaran Pasien',
          style: TextStyle(
            fontSize: 18,
            fontWeight: FontWeight.bold,
            color: Colors.white,
            fontFamily: 'KohSantepheap',
          ),
        ),
        leading: IconButton(
          icon: const Icon(Icons.arrow_back, color: Colors.white),
          onPressed: () => Navigator.pop(context),
        ),
      ),
      body: _isLoading || _isLoadingClusters
          ? const Center(child: CircularProgressIndicator(color: Color(0xFF06489F)))
          : SingleChildScrollView(
              padding: const EdgeInsets.all(20),
              child: Form(
                key: _formKey,
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    // Registration Section
                    _buildSectionTitle('Data Pendaftaran', 'Lengkapi informasi pendaftaran'),
                    _buildDropdownField(
                      label: 'Jenis Pasien',
                      value: _jenisPasien,
                      hint: 'Pilih jenis pasien',
                      items: _jenisPasienOptions.map((item) {
                        return DropdownMenuItem<String>(value: item, child: Text(item));
                      }).toList(),
                      icon: Iconsax.user,
                      onChanged: (value) {
                        setState(() {
                          _jenisPasien = value;
                          if (value == 'lama') {
                            // Optionally fetch patient data by NIK
                          }
                        });
                      },
                    ),
                    _buildTextField(
                      controller: _keluhanController,
                      label: 'Keluhan',
                      hint: 'Masukkan keluhan kesehatan',
                      icon: Iconsax.health,
                      validator: (value) {
                        if (value == null || value.isEmpty) {
                          return 'Keluhan tidak boleh kosong';
                        }
                        return null;
                      },
                      fieldName: 'keluhan',
                    ),
                    if (_clusterError != null)
                      Padding(
                        padding: const EdgeInsets.only(bottom: 15),
                        child: Text(
                          _clusterError!,
                          style: const TextStyle(color: Colors.red, fontSize: 14),
                        ),
                      ),
                    _buildDropdownField(
                      label: 'Cluster',
                      value: _clusterId,
                      hint: 'Pilih cluster',
                      items: _clusters.map((cluster) {
                        return DropdownMenuItem<String>(
                          value: cluster.id.toString(),
                          child: Text(cluster.nama ?? '-'),
                        );
                      }).toList(),
                      icon: Iconsax.hospital,
                      onChanged: (value) {
                        setState(() {
                          _clusterId = value;
                        });
                      },
                    ),
                    _buildDateField(
                      controller: _tanggalDaftarController,
                      label: 'Tanggal Daftar',
                      hint: 'YYYY-MM-DD',
                      icon: Iconsax.calendar,
                      onTap: () => _selectRegistrationDate(context),
                      validator: (value) {
                        if (value == null || value.isEmpty) {
                          return 'Tanggal daftar tidak boleh kosong';
                        }
                        return null;
                      },
                      fieldName: 'tanggalDaftar',
                    ),
                    _buildDropdownField(
                      label: 'Jenis Pembayaran',
                      value: _jenisPembayaran,
                      hint: 'Pilih jenis pembayaran',
                      items: _jenisPembayaranOptions.map((item) {
                        return DropdownMenuItem<String>(value: item, child: Text(item));
                      }).toList(),
                      icon: Iconsax.money,
                      onChanged: (value) {
                        setState(() {
                          _jenisPembayaran = value;
                        });
                      },
                    ),
                    _buildTextField(
                      controller: _noBpjsController,
                      label: 'No. BPJS',
                      hint: 'Masukkan nomor BPJS (13 digit)',
                      icon: Iconsax.card,
                      validator: (value) {
                        if (_jenisPembayaran == 'bpjs' && (value == null || value.isEmpty)) {
                          return 'No. BPJS wajib diisi untuk pembayaran BPJS';
                        }
                        if (value != null && value.isNotEmpty && !RegExp(r'^\d{13}$').hasMatch(value)) {
                          return 'No. BPJS harus 13 digit angka';
                        }
                        return null;
                      },
                      fieldName: 'noBpjs',
                    ),

                    // Identity Section
                    _buildSectionTitle('Data Identitas Pasien', 'Lengkapi informasi data diri'),
                    _buildTextField(
                      controller: _nikController,
                      label: 'NIK',
                      hint: 'Masukkan NIK (16 digit)',
                      icon: Iconsax.card,
                      validator: (value) {
                        if (value == null || value.isEmpty) {
                          return 'NIK tidak boleh kosong';
                        }
                        if (!RegExp(r'^\d{16}$').hasMatch(value)) {
                          return 'NIK harus 16 digit angka';
                        }
                        return null;
                      },
                      fieldName: 'nik',
                    ),
                    _buildTextField(
                      controller: _noKkController,
                      label: 'No. KK',
                      hint: 'Masukkan No. KK (16 digit)',
                      icon: Iconsax.card,
                      validator: (value) {
                        if (value != null && value.isNotEmpty && !RegExp(r'^\d{16}$').hasMatch(value)) {
                          return 'No. KK harus 16 digit angka';
                        }
                        return null;
                      },
                      fieldName: 'noKk',
                    ),
                    _buildTextField(
                      controller: _namaController,
                      label: 'Nama Lengkap',
                      hint: 'Masukkan nama lengkap',
                      icon: Iconsax.user,
                      validator: (value) {
                        if (_jenisPasien == 'baru' && (value == null || value.isEmpty)) {
                          return 'Nama tidak boleh kosong untuk pasien baru';
                        }
                        return null;
                      },
                      fieldName: 'nama',
                      enabled: _jenisPasien != 'lama',
                    ),
                    _buildDropdownField(
                      label: 'Jenis Kelamin',
                      value: _jenisKelamin,
                      hint: 'Pilih jenis kelamin',
                      items: _jenisKelaminOptions.map((item) {
                        return DropdownMenuItem<String>(value: item, child: Text(item));
                      }).toList(),
                      icon: Iconsax.woman,
                      onChanged: _jenisPasien == 'lama' ? null : (value) {
                        setState(() {
                          _jenisKelamin = value;
                        });
                      },
                    ),
                    _buildTextField(
                      controller: _tempatLahirController,
                      label: 'Tempat Lahir',
                      hint: 'Masukkan tempat lahir',
                      icon: Iconsax.location,
                      validator: (value) {
                        if (_jenisPasien == 'baru' && (value == null || value.isEmpty)) {
                          return 'Tempat lahir tidak boleh kosong untuk pasien baru';
                        }
                        return null;
                      },
                      fieldName: 'tempatLahir',
                      enabled: _jenisPasien != 'lama',
                    ),
                    _buildDateField(
                      controller: _tanggalLahirController,
                      label: 'Tanggal Lahir',
                      hint: 'YYYY-MM-DD',
                      icon: Iconsax.calendar,
                      onTap: _jenisPasien == 'lama' ? null : () => _selectBirthDate(context),
                      validator: (value) {
                        if (_jenisPasien == 'baru' && (value == null || value.isEmpty)) {
                          return 'Tanggal lahir tidak boleh kosong untuk pasien baru';
                        }
                        return null;
                      },
                      fieldName: 'tanggalLahir',
                    ),
                    _buildTextField(
                      controller: _alamatController,
                      label: 'Alamat',
                      hint: 'Masukkan alamat lengkap',
                      icon: Iconsax.location,
                      validator: (value) {
                        if (_jenisPasien == 'baru' && (value == null || value.isEmpty)) {
                          return 'Alamat tidak boleh kosong untuk pasien baru';
                        }
                        return null;
                      },
                      fieldName: 'alamat',
                      enabled: _jenisPasien != 'lama',
                    ),
                    _buildTextField(
                      controller: _noHpController,
                      label: 'No. HP',
                      hint: 'Masukkan nomor HP',
                      icon: Iconsax.call,
                      validator: (value) {
                        if (value == null || value.isEmpty) {
                          return 'No. HP tidak boleh kosong';
                        }
                        return null;
                      },
                      fieldName: 'noHp',
                    ),
                    _buildTextField(
                      controller: _pekerjaanController,
                      label: 'Pekerjaan',
                      hint: 'Masukkan pekerjaan (opsional)',
                      icon: Iconsax.briefcase,
                      fieldName: 'pekerjaan',
                      enabled: _jenisPasien != 'lama',
                    ),

                    // Medical Section
                    _buildSectionTitle('Informasi Medis', 'Lengkapi informasi kesehatan'),
                    _buildDropdownField(
                      label: 'Golongan Darah',
                      value: _golonganDarah,
                      hint: 'Pilih golongan darah',
                      items: _golonganDarahOptions.map((item) {
                        return DropdownMenuItem<String>(value: item, child: Text(item));
                      }).toList(),
                      icon: Iconsax.heart,
                      onChanged: (value) {
                        setState(() {
                          _golonganDarah = value;
                        });
                      },
                    ),
                    _buildTextField(
                      controller: _riwayatAlergiController,
                      label: 'Riwayat Alergi',
                      hint: 'Masukkan alergi (opsional)',
                      icon: Iconsax.danger,
                      fieldName: 'riwayatAlergi',
                    ),
                    _buildTextField(
                      controller: _riwayatPenyakitController,
                      label: 'Riwayat Penyakit',
                      hint: 'Masukkan riwayat penyakit (opsional)',
                      icon: Iconsax.health,
                      fieldName: 'riwayatPenyakit',
                    ),

                    const SizedBox(height: 40),
                    SizedBox(
                      width: double.infinity,
                      child: ElevatedButton(
                        onPressed: _isLoading ? null : _submitForm,
                        style: ElevatedButton.styleFrom(
                          backgroundColor: const Color(0xFF06489F),
                          foregroundColor: Colors.white,
                          padding: const EdgeInsets.symmetric(vertical: 16),
                          shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
                          elevation: 2,
                        ),
                        child: _isLoading
                            ? const SizedBox(
                                width: 20,
                                height: 20,
                                child: CircularProgressIndicator(color: Colors.white, strokeWidth: 2),
                              )
                            : const Row(
                                mainAxisAlignment: MainAxisAlignment.center,
                                children: [
                                  Icon(Iconsax.tick_circle, size: 20, color: Colors.white),
                                  SizedBox(width: 10),
                                  Text(
                                    'DAFTAR PASIEN',
                                    style: TextStyle(
                                      fontSize: 16,
                                      fontWeight: FontWeight.bold,
                                      fontFamily: 'KohSantepheap',
                                      letterSpacing: 1,
                                    ),
                                  ),
                                ],
                              ),
                      ),
                    ),
                    const SizedBox(height: 30),
                  ],
                ),
              ),
            ),
    );
  }

  Widget _buildSectionTitle(String title, String subtitle) {
    return Container(
      margin: const EdgeInsets.only(bottom: 12),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Text(
            title,
            style: const TextStyle(fontSize: 17, fontWeight: FontWeight.bold, color: Color(0xFF06489F)),
          ),
          const SizedBox(height: 3),
          Text(subtitle, style: TextStyle(fontSize: 13, color: Colors.grey[600])),
          const SizedBox(height: 4),
          Container(
            height: 2,
            width: 60,
            decoration: BoxDecoration(color: const Color(0xFF06489F), borderRadius: BorderRadius.circular(2)),
          ),
        ],
      ),
    );
  }

  Widget _buildTextField({
    required TextEditingController controller,
    required String label,
    required String hint,
    required IconData icon,
    String? Function(String?)? validator,
    String? fieldName,
    bool enabled = true,
  }) {
    bool isFilled = fieldName != null ? _fieldFilled[fieldName] ?? false : controller.text.isNotEmpty;
    return Container(
      margin: const EdgeInsets.only(bottom: 15),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Text(label, style: const TextStyle(fontSize: 14, fontWeight: FontWeight.w500, color: Colors.black87)),
          const SizedBox(height: 8),
          Stack(
            alignment: Alignment.centerRight,
            children: [
              TextFormField(
                controller: controller,
                enabled: enabled,
                decoration: InputDecoration(
                  hintText: hint,
                  hintStyle: TextStyle(color: Colors.grey.shade400, fontSize: 13),
                  prefixIcon: Icon(icon, color: const Color(0xFF06489F), size: 20),
                  filled: true,
                  fillColor: enabled ? Colors.grey.shade50 : Colors.grey.shade200,
                  contentPadding: const EdgeInsets.symmetric(vertical: 15, horizontal: 15),
                  border: OutlineInputBorder(
                    borderRadius: BorderRadius.circular(10),
                    borderSide: BorderSide(color: Colors.grey.shade300),
                  ),
                  enabledBorder: OutlineInputBorder(
                    borderRadius: BorderRadius.circular(10),
                    borderSide: BorderSide(color: Colors.grey.shade300),
                  ),
                  focusedBorder: OutlineInputBorder(
                    borderRadius: BorderRadius.circular(10),
                    borderSide: const BorderSide(color: Color(0xFF06489F)),
                  ),
                  disabledBorder: OutlineInputBorder(
                    borderRadius: BorderRadius.circular(10),
                    borderSide: BorderSide(color: Colors.grey.shade300),
                  ),
                  errorBorder: OutlineInputBorder(
                    borderRadius: BorderRadius.circular(10),
                    borderSide: BorderSide(color: Colors.red.shade300),
                  ),
                ),
                style: const TextStyle(fontSize: 13),
                validator: validator,
                onChanged: (value) {
                  if (fieldName != null) {
                    _updateFieldStatus(fieldName, value.isNotEmpty);
                  }
                },
              ),
              if (isFilled)
                Positioned(
                  right: 12,
                  child: Container(
                    width: 20,
                    height: 20,
                    decoration: const BoxDecoration(color: Color(0xFF06489F), shape: BoxShape.circle),
                    child: const Center(child: Icon(Icons.check, color: Colors.white, size: 12)),
                  ),
                ),
            ],
          ),
        ],
      ),
    );
  }

  Widget _buildDropdownField({
    required String label,
    required String? value,
    required List<DropdownMenuItem<String>> items,
    required IconData icon,
    required Function(String?)? onChanged,
    String? hint,
  }) {
    bool isFilled = value != null;
    return Container(
      margin: const EdgeInsets.only(bottom: 15),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Text(label, style: const TextStyle(fontSize: 14, fontWeight: FontWeight.w500, color: Colors.black87)),
          const SizedBox(height: 8),
          Stack(
            alignment: Alignment.centerRight,
            children: [
              Container(
                padding: const EdgeInsets.symmetric(horizontal: 15),
                decoration: BoxDecoration(
                  color: Colors.grey.shade50,
                  borderRadius: BorderRadius.circular(10),
                  border: Border.all(color: Colors.grey.shade300),
                ),
                child: Row(
                  children: [
                    Icon(icon, color: const Color(0xFF06489F), size: 20),
                    const SizedBox(width: 10),
                    Expanded(
                      child: DropdownButtonHideUnderline(
                        child: DropdownButton<String>(
                          value: value,
                          hint: Text(hint ?? 'Pilih $label', style: TextStyle(color: Colors.grey.shade500, fontSize: 13)),
                          items: items,
                          onChanged: onChanged,
                          isExpanded: true,
                          style: const TextStyle(fontSize: 13, color: Colors.black87),
                          icon: isFilled ? const SizedBox.shrink() : const Icon(Icons.keyboard_arrow_down_rounded, color: Color(0xFF06489F), size: 22),
                        ),
                      ),
                    ),
                  ],
                ),
              ),
              if (isFilled)
                Positioned(
                  right: 12,
                  child: Container(
                    width: 20,
                    height: 20,
                    decoration: const BoxDecoration(color: Color(0xFF06489F), shape: BoxShape.circle),
                    child: const Center(child: Icon(Icons.check, color: Colors.white, size: 12)),
                  ),
                ),
            ],
          ),
        ],
      ),
    );
  }

  Widget _buildDateField({
    required TextEditingController controller,
    required String label,
    required String hint,
    required IconData icon,
    required Function()? onTap,
    String? Function(String?)? validator,
    String? fieldName,
  }) {
    bool isFilled = fieldName != null ? _fieldFilled[fieldName] ?? false : controller.text.isNotEmpty;
    return Container(
      margin: const EdgeInsets.only(bottom: 15),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Text(label, style: const TextStyle(fontSize: 14, fontWeight: FontWeight.w500, color: Colors.black87)),
          const SizedBox(height: 8),
          Stack(
            alignment: Alignment.centerRight,
            children: [
              TextFormField(
                controller: controller,
                readOnly: true,
                onTap: onTap,
                decoration: InputDecoration(
                  hintText: hint,
                  hintStyle: TextStyle(color: Colors.grey.shade400, fontSize: 13),
                  prefixIcon: Icon(icon, color: const Color(0xFF06489F), size: 20),
                  filled: true,
                  fillColor: Colors.grey.shade50,
                  contentPadding: const EdgeInsets.symmetric(vertical: 15, horizontal: 15),
                  border: OutlineInputBorder(
                    borderRadius: BorderRadius.circular(10),
                    borderSide: BorderSide(color: Colors.grey.shade300),
                  ),
                  enabledBorder: OutlineInputBorder(
                    borderRadius: BorderRadius.circular(10),
                    borderSide: BorderSide(color: Colors.grey.shade300),
                  ),
                  focusedBorder: OutlineInputBorder(
                    borderRadius: BorderRadius.circular(10),
                    borderSide: const BorderSide(color: Color(0xFF06489F)),
                  ),
                  disabledBorder: OutlineInputBorder(
                    borderRadius: BorderRadius.circular(10),
                    borderSide: BorderSide(color: Colors.grey.shade300),
                  ),
                  errorBorder: OutlineInputBorder(
                    borderRadius: BorderRadius.circular(10),
                    borderSide: BorderSide(color: Colors.red.shade300),
                  ),
                ),
                style: const TextStyle(fontSize: 13),
                validator: validator,
              ),
              if (isFilled)
                Positioned(
                  right: 12,
                  child: Container(
                    width: 20,
                    height: 20,
                    decoration: const BoxDecoration(color: Color(0xFF06489F), shape: BoxShape.circle),
                    child: const Center(child: Icon(Icons.check, color: Colors.white, size: 12)),
                  ),
                ),
            ],
          ),
        ],
      ),
    );
  }
}