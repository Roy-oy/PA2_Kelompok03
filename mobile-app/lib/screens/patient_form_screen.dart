import 'package:flutter/material.dart';
import 'package:iconsax/iconsax.dart';
import 'package:mobile_puskesmas/services/auth_service.dart';

class PatientFormScreen extends StatefulWidget {
  const PatientFormScreen({Key? key}) : super(key: key);

  @override
  State<PatientFormScreen> createState() => _PatientFormScreenState();
}

class _PatientFormScreenState extends State<PatientFormScreen> {
  final _formKey = GlobalKey<FormState>();

  // Update controllers
  final _namaController = TextEditingController();
  final _tempatLahirController = TextEditingController();
  final _tanggalLahirController = TextEditingController();
  final _alamatController = TextEditingController();
  final _noTeleponController = TextEditingController();
  final _pekerjaanController = TextEditingController();
  final _noBPJSController = TextEditingController();
  final _golonganDarahController = TextEditingController();
  final _alergiController = TextEditingController();
  final _riwayatPenyakitController = TextEditingController();

  // Update dropdown options
  final List<String> _genderOptions = ['Laki-laki', 'Perempuan'];
  final List<String> _bloodTypeOptions = ['A', 'B', 'AB', 'O', 'Tidak Diketahui'];

  // Update form fields status tracking
  final Map<String, bool> _fieldFilled = {};

  bool _isLoading = false;
  DateTime? _selectedDate;
  DateTime? _selectedBirthDate;
  String? _gender;
  String? _bloodType;
  String? _occupation;
  bool _isOtherBloodType = false;
  bool _isOtherOccupation = false;

  @override
  void initState() {
    super.initState();
    _loadUserData();
    _setupTextFieldListeners();
  }

  void _setupTextFieldListeners() {
    _namaController.addListener(() => _updateFieldStatus('nama', _namaController.text.isNotEmpty));
    _tempatLahirController.addListener(() => _updateFieldStatus('tempatLahir', _tempatLahirController.text.isNotEmpty));
    _tanggalLahirController.addListener(() => _updateFieldStatus('tanggalLahir', _tanggalLahirController.text.isNotEmpty));
    _alamatController.addListener(() => _updateFieldStatus('alamat', _alamatController.text.isNotEmpty));
    _noTeleponController.addListener(() => _updateFieldStatus('noTelepon', _noTeleponController.text.isNotEmpty));
    _pekerjaanController.addListener(() => _updateFieldStatus('pekerjaan', _pekerjaanController.text.isNotEmpty));
    _noBPJSController.addListener(() => _updateFieldStatus('noBPJS', _noBPJSController.text.isNotEmpty));
    _golonganDarahController.addListener(() => _updateFieldStatus('golonganDarah', _golonganDarahController.text.isNotEmpty));
    _alergiController.addListener(() => _updateFieldStatus('alergi', _alergiController.text.isNotEmpty));
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
          _noTeleponController.text = user.phone ?? '';
          _gender = user.gender;
        });
      }
    } catch (e) {
      // Handle error
    } finally {
      setState(() {
        _isLoading = false;
      });
    }
  }

  @override
  void dispose() {
    _namaController.dispose();
    _tempatLahirController.dispose();
    _tanggalLahirController.dispose();
    _alamatController.dispose();
    _noTeleponController.dispose();
    _pekerjaanController.dispose();
    _noBPJSController.dispose();
    _golonganDarahController.dispose();
    _alergiController.dispose();
    _riwayatPenyakitController.dispose();
    super.dispose();
  }

  Future<void> _selectBirthDate(BuildContext context) async {
    final DateTime? picked = await showDatePicker(
      context: context,
      initialDate: _selectedBirthDate ?? DateTime(2000),
      firstDate: DateTime(1940),
      lastDate: DateTime.now(),
      builder: (context, child) {
        return Theme(
          data: ThemeData.light().copyWith(
            colorScheme: const ColorScheme.light(
              primary: Color(0xFF06489F),
            ),
            dialogBackgroundColor: Colors.white,
          ),
          child: child!,
        );
      },
    );

    if (picked != null && picked != _selectedBirthDate) {
      setState(() {
        _selectedBirthDate = picked;
        // Format tanggal menjadi YYYY-MM-DD seperti di contoh
        _tanggalLahirController.text = picked.toIso8601String().split('T')[0];
      });
    }
  }

  Future<void> _submitForm() async {
    if (!_formKey.currentState!.validate()) {
      return;
    }

    try {
      setState(() {
        _isLoading = true;
      });

      // Remove no_rm from form data since it will be generated on backend
      final Map<String, dynamic> formData = {
        'nama': _namaController.text,
        'jenis_kelamin': _gender,
        'tempat_lahir': _tempatLahirController.text,
        'tanggal_lahir': _tanggalLahirController.text,
        'alamat': _alamatController.text,
        'no_telepon': _noTeleponController.text,
        'pekerjaan': _pekerjaanController.text,
        'no_bpjs': _noBPJSController.text,
        'golongan_darah': _bloodType,
        'alergi': _alergiController.text,
        'riwayat_penyakit': _riwayatPenyakitController.text,
      };

      // Submit data to API
      // The API will generate no_rm automatically

      // Tampilkan pesan sukses
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(
          content: Text('Pendaftaran berhasil!'),
          backgroundColor: Colors.green,
        ),
      );

      // Kembali ke halaman sebelumnya
      Navigator.pop(context);
    } catch (e) {
      // Handle error
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(
          content: Text('Terjadi kesalahan: ${e.toString()}'),
          backgroundColor: Colors.red,
        ),
      );
    } finally {
      setState(() {
        _isLoading = false;
      });
    }
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
          onPressed: () => Navigator.of(context).pop(),
        ),
      ),
      body: _isLoading
          ? const Center(
              child: CircularProgressIndicator(color: Color(0xFF06489F)))
          : SingleChildScrollView(
              padding: const EdgeInsets.all(20),
              child: Form(
                key: _formKey,
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    // Personal Information Section
                    _buildSectionTitle(
                      'Data Identitas Pasien',
                      'Lengkapi informasi data diri Anda',
                    ),

                    _buildTextField(
                      controller: _namaController,
                      label: 'Nama Lengkap',
                      hint: 'Masukkan nama lengkap',
                      icon: Iconsax.user,
                      validator: (value) {
                        if (value == null || value.isEmpty) {
                          return 'Nama tidak boleh kosong';
                        }
                        return null;
                      },
                      fieldName: 'nama',
                    ),

                    _buildDropdownField(
                      label: 'Jenis Kelamin',
                      value: _gender,
                      hint: 'Pilih jenis kelamin',
                      items: _genderOptions.map((item) {
                        return DropdownMenuItem<String>(
                          value: item,
                          child: Text(item),
                        );
                      }).toList(),
                      icon: Iconsax.woman,
                      onChanged: (value) {
                        if (value != null) {
                          setState(() {
                            _gender = value;
                          });
                        }
                      },
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
                    ),

                    _buildDateField(
                      controller: _tanggalLahirController,
                      label: 'Tanggal Lahir',
                      hint: 'DD/MM/YYYY',
                      icon: Iconsax.calendar,
                      onTap: () => _selectBirthDate(context),
                      validator: (value) {
                        if (value == null || value.isEmpty) {
                          return 'Tanggal lahir tidak boleh kosong';
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
                        if (value == null || value.isEmpty) {
                          return 'Alamat tidak boleh kosong';
                        }
                        return null;
                      },
                      fieldName: 'alamat',
                    ),

                    _buildTextField(
                      controller: _noTeleponController,
                      label: 'No. Telepon',
                      hint: 'Masukkan nomor telepon',
                      icon: Iconsax.call,
                      validator: (value) {
                        if (value == null || value.isEmpty) {
                          return 'No. telepon tidak boleh kosong';
                        }
                        return null;
                      },
                      fieldName: 'noTelepon',
                    ),

                    _buildTextField(
                      controller: _noBPJSController,
                      label: 'No. BPJS',
                      hint: 'Masukkan nomor BPJS',
                      icon: Iconsax.card,
                      fieldName: 'noBPJS',
                    ),

                    const SizedBox(height: 24),

                    // Medical Information Section
                    _buildSectionTitle(
                      'Informasi Medis',
                      'Lengkapi informasi kesehatan Anda',
                    ),

                    _buildDropdownField(
                      label: 'Golongan Darah',
                      value: _bloodType,
                      hint: 'Pilih golongan darah',
                      items: _bloodTypeOptions.map((item) {
                        return DropdownMenuItem<String>(
                          value: item,
                          child: Text(item),
                        );
                      }).toList(),
                      icon: Iconsax.heart,
                      onChanged: (value) {
                        if (value != null) {
                          setState(() {
                            _bloodType = value;
                          });
                        }
                      },
                    ),

                    _buildTextField(
                      controller: _alergiController,
                      label: 'Alergi',
                      hint: 'Masukkan alergi (jika ada)',
                      icon: Iconsax.danger,
                      fieldName: 'alergi',
                    ),

                    _buildTextField(
                      controller: _riwayatPenyakitController,
                      label: 'Riwayat Penyakit',
                      hint: 'Masukkan riwayat penyakit (jika ada)',
                      icon: Iconsax.health,
                      fieldName: 'riwayatPenyakit',
                    ),

                    const SizedBox(height: 40),
                    SizedBox(
                      width: double.infinity,
                      child: ElevatedButton(
                        onPressed: _submitForm,
                        style: ElevatedButton.styleFrom(
                          backgroundColor: const Color(0xFF06489F),
                          foregroundColor: Colors.white,
                          padding: const EdgeInsets.symmetric(vertical: 16),
                          shape: RoundedRectangleBorder(
                            borderRadius: BorderRadius.circular(12),
                          ),
                          elevation: 2,
                        ),
                        child: _isLoading
                            ? const SizedBox(
                                width: 20,
                                height: 20,
                                child: CircularProgressIndicator(
                                  color: Colors.white,
                                  strokeWidth: 2,
                                ),
                              )
                            : const Row(
                                mainAxisAlignment: MainAxisAlignment.center,
                                children: [
                                  Icon(Iconsax.tick_circle,
                                      size: 20, color: Colors.white),
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
            style: const TextStyle(
              fontSize: 17,
              fontWeight: FontWeight.bold,
              color: Color(0xFF06489F),
            ),
          ),
          const SizedBox(height: 3),
          Text(
            subtitle,
            style: TextStyle(
              fontSize: 13,
              color: Colors.grey[600],
            ),
          ),
          const SizedBox(height: 4),
          Container(
            height: 2,
            width: 60,
            decoration: BoxDecoration(
              color: const Color(0xFF06489F),
              borderRadius: BorderRadius.circular(2),
            ),
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
  }) {
    bool isFilled = fieldName != null
        ? _fieldFilled[fieldName] ?? false
        : controller.text.isNotEmpty;

    return Container(
      margin: const EdgeInsets.only(bottom: 15),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Text(
            label,
            style: const TextStyle(
              fontSize: 14,
              fontWeight: FontWeight.w500,
              color: Colors.black87,
            ),
          ),
          const SizedBox(height: 8),
          Stack(
            alignment: Alignment.centerRight,
            children: [
              TextFormField(
                controller: controller,
                decoration: InputDecoration(
                  hintText: hint,
                  hintStyle:
                      TextStyle(color: Colors.grey.shade400, fontSize: 13),
                  prefixIcon:
                      Icon(icon, color: const Color(0xFF06489F), size: 20),
                  filled: true,
                  fillColor: Colors.grey.shade50,
                  contentPadding:
                      const EdgeInsets.symmetric(vertical: 15, horizontal: 15),
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
                    decoration: const BoxDecoration(
                      color: Color(0xFF06489F),
                      shape: BoxShape.circle,
                    ),
                    child: const Center(
                      child: Icon(
                        Icons.check,
                        color: Colors.white,
                        size: 12,
                      ),
                    ),
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
    required Function(String?) onChanged,
    String? hint,
  }) {
    bool isFilled = value != null;

    return Container(
      margin: const EdgeInsets.only(bottom: 15),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Text(
            label,
            style: const TextStyle(
              fontSize: 14,
              fontWeight: FontWeight.w500,
              color: Colors.black87,
            ),
          ),
          const SizedBox(height: 8),
          Stack(
            alignment: Alignment.centerRight,
            children: [
              Container(
                padding: const EdgeInsets.symmetric(horizontal: 15),
                decoration: BoxDecoration(
                  color: Colors.grey.shade50,
                  borderRadius: BorderRadius.circular(10),
                  border: Border.all(
                    color: Colors.grey.shade300,
                  ),
                ),
                child: Row(
                  children: [
                    Icon(icon, color: const Color(0xFF06489F), size: 20),
                    const SizedBox(width: 10),
                    Expanded(
                      child: DropdownButtonHideUnderline(
                        child: DropdownButton<String>(
                          value: value,
                          hint: Text(
                            hint ?? 'Pilih $label',
                            style: TextStyle(
                              color: Colors.grey.shade500,
                              fontSize: 13,
                            ),
                          ),
                          items: items,
                          onChanged: onChanged,
                          isExpanded: true,
                          style: const TextStyle(
                            fontSize: 13,
                            color: Colors.black87,
                          ),
                          icon: isFilled
                              ? const SizedBox.shrink()
                              : const Icon(
                                  Icons.keyboard_arrow_down_rounded,
                                  color: Color(0xFF06489F),
                                  size: 22,
                                ),
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
                    decoration: const BoxDecoration(
                      color: Color(0xFF06489F),
                      shape: BoxShape.circle,
                    ),
                    child: const Center(
                      child: Icon(
                        Icons.check,
                        color: Colors.white,
                        size: 12,
                      ),
                    ),
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
    required Function() onTap,
    String? Function(String?)? validator,
    String? fieldName,
  }) {
    bool isFilled = fieldName != null
        ? _fieldFilled[fieldName] ?? false
        : controller.text.isNotEmpty;

    return Container(
      margin: const EdgeInsets.only(bottom: 15),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Text(
            label,
            style: const TextStyle(
              fontSize: 14,
              fontWeight: FontWeight.w500,
              color: Colors.black87,
            ),
          ),
          const SizedBox(height: 8),
          Stack(
            alignment: Alignment.centerRight,
            children: [
              TextFormField(
                controller: controller,
                decoration: InputDecoration(
                  hintText: hint,
                  hintStyle: TextStyle(color: Colors.grey.shade400, fontSize: 13),
                  prefixIcon: Icon(icon, color: const Color(0xFF06489F), size: 20),
                  suffixIcon: Icon(
                    Icons.calendar_month_rounded,
                    color: const Color(0xFF06489F),
                    size: 20,
                  ),
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
                  errorBorder: OutlineInputBorder(
                    borderRadius: BorderRadius.circular(10),
                    borderSide: BorderSide(color: Colors.red.shade300),
                  ),
                ),
                readOnly: true,
                style: const TextStyle(fontSize: 13),
                onTap: onTap,
                validator: validator,
              ),
              if (isFilled)
                Positioned(
                  right: 12,
                  child: Container(
                    width: 20,
                    height: 20,
                    decoration: const BoxDecoration(
                      color: Color(0xFF06489F),
                      shape: BoxShape.circle,
                    ),
                    child: const Center(
                      child: Icon(
                        Icons.check,
                        color: Colors.white,
                        size: 12,
                      ),
                    ),
                  ),
                ),
            ],
          ),
        ],
      ),
    );
  }
}
