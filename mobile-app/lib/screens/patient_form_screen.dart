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
  final _noRmController = TextEditingController();

  // Form state
  String? _jenisPasien;
  String? _jenisPembayaran;
  String? _jenisKelamin;
  String? _golonganDarah;
  String? _clusterId;
  bool _isLoading = false;
  bool _isLoadingClusters = false;
  String? _clusterError;
  String? _nikError;
  String? _noRmError;
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
    _nikController.addListener(() {
      _updateFieldStatus('nik', _nikController.text.isNotEmpty);
      if (_jenisPasien == 'lama' && _nikController.text.length == 16) {
        _fetchPatientDataByNik(_nikController.text);
      }
    });
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
    _noRmController.addListener(() => _updateFieldStatus('noRm', _noRmController.text.isNotEmpty));
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
      if (clusters.isNotEmpty) {
        setState(() {
          _clusters = clusters;
          if (_clusterId == null) {
            _clusterId = clusters.first.id.toString();
          }
        });
      } else {
        setState(() {
          _clusterError = 'Tidak ada cluster yang tersedia';
        });
      }
    } catch (e) {
      setState(() {
        _clusterError = 'Gagal memuat cluster: $e';
      });
    } finally {
      setState(() {
        _isLoadingClusters = false;
      });
    }
  }

  Future<void> _fetchPatientDataByNik(String nik) async {
    if (!RegExp(r'^\d{16}$').hasMatch(nik)) {
      setState(() {
        _nikError = 'NIK harus 16 digit angka';
      });
      return;
    }
    try {
      setState(() {
        _isLoading = true;
        _nikError = null;
        _noRmError = null;
      });
      final data = await PendaftaranService().getPasienByNik(nik);
      final pasien = data['pasien'];
      if (pasien != null) {
        if (pasien['no_rm'] == null || pasien['no_rm'].isEmpty) {
          setState(() {
            _noRmError = 'Pasien ini tidak memiliki nomor rekam medis';
          });
          return;
        }
        setState(() {
          _namaController.text = pasien['nama'] ?? '';
          _noKkController.text = pasien['no_kk'] ?? '';
          _noHpController.text = pasien['no_hp'] ?? '';
          _alamatController.text = pasien['alamat'] ?? '';
          _jenisKelamin = pasien['jenis_kelamin'];
          _tempatLahirController.text = pasien['tempat_lahir'] ?? '';
          _tanggalLahirController.text = pasien['tanggal_lahir'] ?? '';
          _selectedBirthDate = pasien['tanggal_lahir'] != null
              ? DateTime.parse(pasien['tanggal_lahir'])
              : null;
          _pekerjaanController.text = pasien['pekerjaan'] ?? '';
          _noBpjsController.text = pasien['no_bpjs'] ?? '';
          _golonganDarah = pasien['golongan_darah'];
          _riwayatAlergiController.text = pasien['riwayat_alergi'] ?? '';
          _riwayatPenyakitController.text = pasien['riwayat_penyakit'] ?? '';
          _noRmController.text = pasien['no_rm'] ?? '';
        });
      } else {
        setState(() {
          _nikError = 'Pasien dengan NIK ini tidak ditemukan';
        });
      }
    } catch (e) {
      setState(() {
        _nikError = e.toString();
      });
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(content: Text('Gagal memuat data pasien: $e'), backgroundColor: Colors.red),
      );
    } finally {
      setState(() {
        _isLoading = false;
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
    if (_jenisPasien == 'lama' && _noRmController.text.isEmpty) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text('Nomor rekam medis wajib diisi untuk pasien lama'), backgroundColor: Colors.red),
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

  Widget _buildSectionTitle(String title, String subtitle) {
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Text(
          title,
          style: const TextStyle(
            fontSize: 18,
            fontWeight: FontWeight.bold,
            color: Color(0xFF06489F),
            fontFamily: 'KohSantepheap',
          ),
        ),
        const SizedBox(height: 5),
        Text(
          subtitle,
          style: TextStyle(
            fontSize: 14,
            color: Colors.grey[600],
            fontFamily: 'KohSantepheap',
          ),
        ),
        const SizedBox(height: 15),
      ],
    );
  }

  Widget _buildTextField({
    required TextEditingController controller,
    required String label,
    required String hint,
    required IconData icon,
    String? Function(String?)? validator,
    required String fieldName,
    bool enabled = true,
  }) {
    return Padding(
      padding: const EdgeInsets.only(bottom: 15),
      child: TextFormField(
        controller: controller,
        enabled: enabled,
        decoration: InputDecoration(
          labelText: label,
          hintText: hint,
          prefixIcon: Icon(icon, color: const Color(0xFF06489F)),
          border: OutlineInputBorder(
            borderRadius: BorderRadius.circular(10),
            borderSide: const BorderSide(color: Color(0xFF06489F)),
          ),
          enabledBorder: OutlineInputBorder(
            borderRadius: BorderRadius.circular(10),
            borderSide: const BorderSide(color: Color(0xFF06489F)),
          ),
          focusedBorder: OutlineInputBorder(
            borderRadius: BorderRadius.circular(10),
            borderSide: const BorderSide(color: Color(0xFF06489F), width: 2),
          ),
          filled: true,
          fillColor: enabled ? Colors.white : Colors.grey[200],
        ),
        validator: validator,
        onChanged: (value) => _updateFieldStatus(fieldName, value.isNotEmpty),
      ),
    );
  }

  Widget _buildDateField({
    required TextEditingController controller,
    required String label,
    required String hint,
    required IconData icon,
    required VoidCallback onTap,
    String? Function(String?)? validator,
    required String fieldName,
  }) {
    return Padding(
      padding: const EdgeInsets.only(bottom: 15),
      child: TextFormField(
        controller: controller,
        readOnly: true,
        decoration: InputDecoration(
          labelText: label,
          hintText: hint,
          prefixIcon: Icon(icon, color: const Color(0xFF06489F)),
          border: OutlineInputBorder(
            borderRadius: BorderRadius.circular(10),
            borderSide: const BorderSide(color: Color(0xFF06489F)),
          ),
          enabledBorder: OutlineInputBorder(
            borderRadius: BorderRadius.circular(10),
            borderSide: const BorderSide(color: Color(0xFF06489F)),
          ),
          focusedBorder: OutlineInputBorder(
            borderRadius: BorderRadius.circular(10),
            borderSide: const BorderSide(color: Color(0xFF06489F), width: 2),
          ),
          filled: true,
          fillColor: Colors.white,
        ),
        onTap: onTap,
        validator: validator,
        onChanged: (value) => _updateFieldStatus(fieldName, value.isNotEmpty),
      ),
    );
  }

  Widget _buildDropdownField({
    required String label,
    String? value,
    required String hint,
    required List<DropdownMenuItem<String>> items,
    required IconData icon,
    required ValueChanged<String?>? onChanged,
  }) {
    return Padding(
      padding: const EdgeInsets.only(bottom: 15),
      child: DropdownButtonFormField<String>(
        value: value,
        decoration: InputDecoration(
          labelText: label,
          hintText: hint,
          prefixIcon: Icon(icon, color: const Color(0xFF06489F)),
          border: OutlineInputBorder(
            borderRadius: BorderRadius.circular(10),
            borderSide: const BorderSide(color: Color(0xFF06489F)),
          ),
          enabledBorder: OutlineInputBorder(
            borderRadius: BorderRadius.circular(10),
            borderSide: const BorderSide(color: Color(0xFF06489F)),
          ),
          focusedBorder: OutlineInputBorder(
            borderRadius: BorderRadius.circular(10),
            borderSide: const BorderSide(color: Color(0xFF06489F), width: 2),
          ),
          filled: true,
          fillColor: Colors.white,
        ),
        items: items,
        onChanged: onChanged,
        validator: (value) => value == null ? '$label wajib dipilih' : null,
      ),
    );
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
    _noRmController.dispose();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    final isLamaPatient = _jenisPasien == 'lama';
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
                      items: _jenisPasienOptions
                          .map((item) => DropdownMenuItem<String>(value: item, child: Text(item)))
                          .toList(),
                      icon: Iconsax.user,
                      onChanged: (value) {
                        setState(() {
                          _jenisPasien = value;
                          _nikError = null;
                          _noRmError = null;
                          if (value == 'lama' && _nikController.text.length == 16) {
                            _fetchPatientDataByNik(_nikController.text);
                          } else {
                            _namaController.clear();
                            _noKkController.clear();
                            _noHpController.clear();
                            _alamatController.clear();
                            _tempatLahirController.clear();
                            _tanggalLahirController.clear();
                            _pekerjaanController.clear();
                            _noBpjsController.clear();
                            _riwayatAlergiController.clear();
                            _riwayatPenyakitController.clear();
                            _noRmController.clear();
                            _jenisKelamin = null;
                            _golonganDarah = null;
                            _selectedBirthDate = null;
                          }
                        });
                      },
                    ),
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
                        if (_nikError != null) {
                          return _nikError;
                        }
                        return null;
                      },
                      fieldName: 'nik',
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
                      items: _clusters
                          .map((cluster) => DropdownMenuItem<String>(
                                value: cluster.id.toString(),
                                child: Text(cluster.nama ?? '-'),
                              ))
                          .toList(),
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
                      items: _jenisPembayaranOptions
                          .map((item) => DropdownMenuItem<String>(value: item, child: Text(item)))
                          .toList(),
                      icon: Iconsax.money,
                      onChanged: (value) {
                        setState(() {
                          _jenisPembayaran = value;
                          if (value != 'bpjs') {
                            _noBpjsController.clear();
                          }
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
                      enabled: !isLamaPatient || _jenisPembayaran == 'bpjs',
                    ),

                    // Patient Data Section
                    _buildSectionTitle('Data Pasien', 'Lengkapi informasi pasien'),
                    if (isLamaPatient)
                      _buildTextField(
                        controller: _noRmController,
                        label: 'Nomor Rekam Medis',
                        hint: 'Masukkan nomor rekam medis',
                        icon: Iconsax.document,
                        validator: (value) {
                          if (isLamaPatient && (value == null || value.isEmpty)) {
                            return 'Nomor rekam medis wajib diisi untuk pasien lama';
                          }
                          if (_noRmError != null) {
                            return _noRmError;
                          }
                          return null;
                        },
                        fieldName: 'noRm',
                        enabled: false,
                      ),
                    _buildTextField(
                      controller: _noKkController,
                      label: 'No. KK',
                      hint: 'Masukkan nomor KK (16 digit)',
                      icon: Iconsax.card,
                      validator: (value) {
                        if (value != null && value.isNotEmpty && !RegExp(r'^\d{16}$').hasMatch(value)) {
                          return 'No. KK harus 16 digit angka';
                        }
                        return null;
                      },
                      fieldName: 'noKk',
                      enabled: !isLamaPatient,
                    ),
                    _buildTextField(
                      controller: _namaController,
                      label: 'Nama',
                      hint: 'Masukkan nama lengkap',
                      icon: Iconsax.user,
                      validator: (value) {
                        if (value == null || value.isEmpty) {
                          return 'Nama tidak boleh kosong';
                        }
                        return null;
                      },
                      fieldName: 'nama',
                      enabled: !isLamaPatient,
                    ),
                    _buildDropdownField(
                      label: 'Jenis Kelamin',
                      value: _jenisKelamin,
                      hint: 'Pilih jenis kelamin',
                      items: _jenisKelaminOptions
                          .map((item) => DropdownMenuItem<String>(value: item, child: Text(item)))
                          .toList(),
                      icon: Iconsax.profile,
                      onChanged: isLamaPatient
                          ? null
                          : (value) {
                              setState(() {
                                _jenisKelamin = value;
                              });
                            },
                    ),
                    _buildDateField(
                      controller: _tanggalLahirController,
                      label: 'Tanggal Lahir',
                      hint: 'YYYY-MM-DD',
                      icon: Iconsax.calendar,
                      onTap: isLamaPatient ? () {} : () => _selectBirthDate(context),
                      validator: (value) {
                        if (value == null || value.isEmpty) {
                          return 'Tanggal lahir tidak boleh kosong';
                        }
                        return null;
                      },
                      fieldName: 'tanggalLahir',
                    ),
                    _buildTextField(
                      controller: _tempatLahirController,
                      label: 'Tempat Lahir',
                      hint: 'Masukkan tempat lahir',
                      icon: Iconsax.location,
                      validator: (value) {
                        if (value == null || value.isEmpty) {
                          return 'Tempat lahir tidak boleh kosong';
                        }
                        return null;
                      },
                      fieldName: 'tempatLahir',
                      enabled: !isLamaPatient,
                    ),
                    _buildTextField(
                      controller: _alamatController,
                      label: 'Alamat',
                      hint: 'Masukkan alamat lengkap',
                      icon: Iconsax.home,
                      validator: (value) {
                        if (value == null || value.isEmpty) {
                          return 'Alamat tidak boleh kosong';
                        }
                        return null;
                      },
                      fieldName: 'alamat',
                      enabled: !isLamaPatient,
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
                        if (!RegExp(r'^\+?\d{10,13}$').hasMatch(value)) {
                          return 'No. HP tidak valid';
                        }
                        return null;
                      },
                      fieldName: 'noHp',
                    ),
                    _buildTextField(
                      controller: _pekerjaanController,
                      label: 'Pekerjaan',
                      hint: 'Masukkan pekerjaan',
                      icon: Iconsax.briefcase,
                      fieldName: 'pekerjaan',
                      enabled: !isLamaPatient,
                    ),
                    _buildDropdownField(
                      label: 'Golongan Darah',
                      value: _golonganDarah,
                      hint: 'Pilih golongan darah',
                      items: _golonganDarahOptions
                          .map((item) => DropdownMenuItem<String>(value: item, child: Text(item)))
                          .toList(),
                      icon: Iconsax.drop,
                      onChanged: isLamaPatient
                          ? null
                          : (value) {
                              setState(() {
                                _golonganDarah = value;
                              });
                            },
                    ),
                    _buildTextField(
                      controller: _riwayatAlergiController,
                      label: 'Riwayat Alergi',
                      hint: 'Masukkan riwayat alergi (jika ada)',
                      icon: Iconsax.warning_2,
                      fieldName: 'riwayatAlergi',
                      enabled: !isLamaPatient,
                    ),
                    _buildTextField(
                      controller: _riwayatPenyakitController,
                      label: 'Riwayat Penyakit',
                      hint: 'Masukkan riwayat penyakit (jika ada)',
                      icon: Iconsax.health,
                      fieldName: 'riwayatPenyakit',
                      enabled: !isLamaPatient,
                    ),

                    // Submit Button
                    const SizedBox(height: 20),
                    SizedBox(
                      width: double.infinity,
                      child: ElevatedButton(
                        onPressed: _isLoading ? null : _submitForm,
                        style: ElevatedButton.styleFrom(
                          backgroundColor: const Color(0xFF06489F),
                          padding: const EdgeInsets.symmetric(vertical: 15),
                          shape: RoundedRectangleBorder(
                            borderRadius: BorderRadius.circular(15),
                          ),
                        ),
                        child: _isLoading
                            ? const CircularProgressIndicator(color: Colors.white)
                            : const Text(
                                'Daftar',
                                style: TextStyle(
                                  color: Colors.white,
                                  fontSize: 18,
                                  fontWeight: FontWeight.bold,
                                  fontFamily: 'KohSantepheap',
                                ),
                              ),
                      ),
                    ),
                  ],
              ),
            ),
            ),
    );
  }
}