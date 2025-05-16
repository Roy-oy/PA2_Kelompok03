import 'package:flutter/material.dart';
import 'package:flutter/services.dart';
import 'package:carousel_slider/carousel_slider.dart';
import 'package:mobile_puskesmas/screens/faskes_rujukan_screen.dart';
import 'package:mobile_puskesmas/screens/feedback_1.dart';
import 'package:mobile_puskesmas/screens/jadwal_dokter_screen.dart';
import 'package:mobile_puskesmas/screens/medical_detail_screen.dart';
import 'package:mobile_puskesmas/screens/medical_history_screen.dart';
import 'package:mobile_puskesmas/screens/pengumuman.dart';
// import 'package:mobile_puskesmas/screens/patient_registration_screen.dart';
import 'package:mobile_puskesmas/services/auth_service.dart';
import 'package:mobile_puskesmas/screens/patient_form_screen.dart';
import 'package:mobile_puskesmas/services/pasien_service.dart';

class HomeScreen extends StatefulWidget {
  const HomeScreen({Key? key}) : super(key: key);

  @override
  State<HomeScreen> createState() => _HomeScreenState();
}

class _HomeScreenState extends State<HomeScreen> {
  int _currentIndex = 0;
  bool _isLoading = true;
  List<Map<String, dynamic>> _medicalRecords = [];

  @override
  void initState() {
    super.initState();
    _loadMedicalRecords();
  }

  Future<void> _loadMedicalRecords() async {
    try {
      setState(() {
        _isLoading = true;
      });

      // Cek apakah pengguna sudah login dan merupakan pasien
      final bool isLoggedIn = await AuthService().isLoggedIn();
      final bool isPatient = await AuthService().isAppUser();

      if (!isLoggedIn || !isPatient) {
        setState(() {
          _isLoading = false;
          _medicalRecords = [];
        });
        return;
      }

      // Jika sudah login dan merupakan pasien, ambil data rekam medis

    
    } catch (e) {
      print('Error loading medical records: $e');
      setState(() {
        _isLoading = false;
        _medicalRecords = [];
      });
    }
  }

  @override
  Widget build(BuildContext context) {
    final List<String> carouselImages = [
      'assets/images/carousel-1.jpg',
      'assets/images/carousel-2.jpg',
      'assets/images/carousel-3.jpg',
    ];

    // Set status bar to be transparent with dark icons
    SystemChrome.setSystemUIOverlayStyle(const SystemUiOverlayStyle(
      statusBarColor: Colors.transparent,
      statusBarIconBrightness: Brightness.dark,
    ));

    return Scaffold(
      backgroundColor: Colors.white,
      body: Column(
        children: [
          // Custom header integrated directly
          _buildHeader(),

          // Content area
          Expanded(
            child: SingleChildScrollView(
              child: Column(
                children: [
                  // Small gap before carousel
                  const SizedBox(height: 5),

                  // Carousel section
                  Stack(
                    children: [
                      CarouselSlider(
                        options: CarouselOptions(
                          height: MediaQuery.of(context).size.height * 0.3,
                          viewportFraction: 1.0,
                          enlargeCenterPage: false,
                          autoPlay: true,
                          autoPlayInterval: const Duration(seconds: 3),
                          autoPlayAnimationDuration:
                              const Duration(milliseconds: 800),
                          autoPlayCurve: Curves.fastOutSlowIn,
                          onPageChanged: (index, reason) {
                            setState(() {
                              _currentIndex = index;
                            });
                          },
                        ),
                        items: carouselImages.map((image) {
                          return Builder(
                            builder: (BuildContext context) {
                              return Container(
                                width: MediaQuery.of(context).size.width,
                                decoration: BoxDecoration(
                                  image: DecorationImage(
                                    image: AssetImage(image),
                                    fit: BoxFit.cover,
                                  ),
                                ),
                              );
                            },
                          );
                        }).toList(),
                      ),
                      Positioned(
                        bottom: 10,
                        left: 0,
                        right: 0,
                        child: Row(
                          mainAxisAlignment: MainAxisAlignment.center,
                          children: carouselImages.asMap().entries.map((entry) {
                            return Container(
                              width: 8.0,
                              height: 8.0,
                              margin:
                                  const EdgeInsets.symmetric(horizontal: 4.0),
                              decoration: BoxDecoration(
                                shape: BoxShape.circle,
                                color: Colors.white.withOpacity(
                                  _currentIndex == entry.key ? 0.9 : 0.4,
                                ),
                              ),
                            );
                          }).toList(),
                        ),
                      ),
                    ],
                  ),
                  // Menu section
                  Padding(
                    padding: EdgeInsets.all(MediaQuery.of(context).size.width *
                        0.04), // Responsive padding
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        Row(
                          children: [
                            _buildMenuItem(
                              image: 'assets/images/pendaftaran-logo.png',
                              title: 'Pendaftaran',
                              color: const Color(0xFF06489F),
                              onTap: () {
                                // Handle pendaftaran tap
                                _handlePatientRegistration(context);
                              },
                            ),
                            _buildMenuItem(
                              image: 'assets/images/rekam-medis-logo.png',
                              title: 'Rekam Medis',
                              color: const Color(0xFF06489F),
                              onTap: () async {
                                Navigator.push(
                                  context,
                                  MaterialPageRoute(
                                    builder: (context) => MedicalHistoryScreen(
                                      records: _medicalRecords,
                                    ),
                                  ),
                                ).then((_) =>
                                    _loadMedicalRecords()); // Refresh data ketika kembali
                              },
                            ),
                            _buildMenuItem(
                              image: 'assets/images/jadwal-dokter-logo.png',
                              title: 'Jadwal Dokter',
                              color: const Color(0xFF06489F),
                              onTap: () {
                                Navigator.push(
                                  context,
                                  MaterialPageRoute(
                                      builder: (context) =>
                                          const JadwalDokterScreen()),
                                );
                              },
                            ),
                            _buildMenuItem(
                              image: 'assets/images/feedback-logo.png',
                              title: 'Feedback',
                              color: const Color(0xFF06489F),
                              onTap: () {
                                // Pastikan untuk mengirimkan context yang valid
                                _rujukan(context);
                              },
                            ),
                            _buildMenuItem(
                              image: 'assets/images/pengumuman-logo.png',
                              title: 'Pengumuman',
                              color: const Color(0xFF06489F),
                              onTap: () {
                                // Handle pengumuman tap
                                _pengumuman(context);
                              },
                            ),
                          ],
                        ),
                      ],
                    ),
                  ),

                  // Riwayat berobat section
                  _buildRiwayatBerobatSection(),
                ],
              ),
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildHeader() {
    // Get the status bar height and screen size
    final MediaQueryData mediaQuery =
        MediaQueryData.fromView(WidgetsBinding.instance.window);
    final double statusBarHeight = mediaQuery.padding.top;
    final double screenWidth = mediaQuery.size.width;

    // Calculate responsive sizes based on screen width
    final double headerHeight =
        mediaQuery.size.height * 0.09; // 9% of screen height
    final double logoSize = screenWidth * 0.24; // 24% of screen width
    final double titleFontSize = screenWidth * 0.05; // 5% of screen width
    final double subtitleFontSize = screenWidth * 0.032; // 2.8% of screen width

    return Container(
      height: headerHeight + statusBarHeight,
      color: Colors.white,
      child: Padding(
        padding: EdgeInsets.only(top: statusBarHeight),
        child: Stack(
          children: [
            // Blue background positioned to overlap with logo
            Positioned(
              left: logoSize * 0.85, // Relative to logo size
              top: 0,
              bottom: 0,
              right: screenWidth * 0.04, // 4% of screen width
              child: Container(
                decoration: const BoxDecoration(
                  color: Color(0xFF06489F),
                  borderRadius: BorderRadius.only(
                    topRight: Radius.circular(15),
                    bottomRight: Radius.circular(15),
                  ),
                ),
                child: Padding(
                  padding: EdgeInsets.only(
                      left: screenWidth * 0.055), // Responsive padding
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    mainAxisAlignment: MainAxisAlignment.center,
                    children: [
                      Text(
                        'PUSMASIB',
                        style: TextStyle(
                          fontFamily: 'YesevaOne',
                          color: Colors.white,
                          fontSize: titleFontSize,
                          height: 1.4,
                          letterSpacing: 0.5,
                        ),
                      ),
                      Text(
                        'PUSKESMAS SIBORONGBORONG',
                        style: TextStyle(
                          color: Colors.white,
                          fontSize: subtitleFontSize,
                          height: 1.0,
                          fontWeight: FontWeight.normal,
                        ),
                        maxLines: 1,
                      ),
                    ],
                  ),
                ),
              ),
            ),
            // Logo on top of the stack to ensure visibility
            Positioned(
              left: -screenWidth *
                  0.015, // Small negative value relative to screen width
              top: 0,
              bottom: 0,
              child: Center(
                child: SizedBox(
                  width: logoSize,
                  height: logoSize,
                  child: Image.asset(
                    'assets/images/logo.png',
                    fit: BoxFit.contain,
                  ),
                ),
              ),
            ),
          ],
        ),
      ),
    );
  }

  Widget _buildMenuItem({
    required String image,
    required String title,
    required Color color,
    required VoidCallback onTap,
  }) {
    // Get screen dimensions for responsive sizing
    final double screenWidth = MediaQuery.of(context).size.width;

    // Calculate sizes based on screen width
    final double iconSize = screenWidth * 0.15; // 15% of screen width
    final double fontSize = screenWidth * 0.023; // 2.5% of screen width

    return Expanded(
      child: GestureDetector(
        onTap: onTap,
        child: Column(
          children: [
            Container(
              width: iconSize,
              height: iconSize,
              padding: EdgeInsets.all(screenWidth * 0.01), // Responsive padding
              decoration: const BoxDecoration(
                color: Colors.white,
                shape: BoxShape.circle,
              ),
              child: Image.asset(
                image,
                fit: BoxFit.contain,
              ),
            ),
            SizedBox(height: screenWidth * 0.008), // Responsive spacing
            Text(
              title,
              style: TextStyle(
                fontFamily: 'KohSantepheap',
                color: color,
                fontSize: fontSize,
                fontWeight: FontWeight.w500,
              ),
              textAlign: TextAlign.center,
              maxLines: 1,
              overflow: TextOverflow.ellipsis,
            ),
          ],
        ),
      ),
    );
  }

  Widget _buildRiwayatBerobatSection() {
    final double screenWidth = MediaQuery.of(context).size.width;

    return Padding(
      padding: EdgeInsets.all(MediaQuery.of(context).size.width * 0.04),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Row(
            mainAxisAlignment: MainAxisAlignment.spaceBetween,
            children: [
              Text(
                'Riwayat Berobat',
                style: TextStyle(
                  fontFamily: 'KohSantepheap',
                  fontSize: MediaQuery.of(context).size.width * 0.045,
                  fontWeight: FontWeight.bold,
                  color: const Color(0xFF06489F),
                ),
              ),
              GestureDetector(
                onTap: () async {
                  final isLoggedIn = await AuthService().isLoggedIn();
                  final isPatient = await AuthService().isAppUser();

                  if (!isLoggedIn) {
                    ScaffoldMessenger.of(context).showSnackBar(
                      const SnackBar(
                        content: Text(
                            'Silakan login terlebih dahulu untuk melihat riwayat berobat'),
                        backgroundColor: Colors.orange,
                      ),
                    );
                    return;
                  }

                  if (!isPatient) {
                    ScaffoldMessenger.of(context).showSnackBar(
                      const SnackBar(
                        content: Text('Anda belum terdaftar sebagai pasien'),
                        backgroundColor: Colors.orange,
                      ),
                    );
                    return;
                  }

                  // Refresh data rekam medis
                  _loadMedicalRecords();
                },
                child: Icon(
                  Icons.refresh,
                  color: const Color(0xFF06489F),
                  size: screenWidth * 0.05,
                ),
              ),
            ],
          ),
          SizedBox(height: MediaQuery.of(context).size.width * 0.02),

          // Show loading indicator if loading
          if (_isLoading)
            const Center(
              child: Padding(
                padding: EdgeInsets.all(20.0),
                child: CircularProgressIndicator(
                  color: Color(0xFF06489F),
                ),
              ),
            )
          // Show message if not logged in or no records and not loading
          else if (_medicalRecords.isEmpty)
            Center(
              child: Padding(
                padding: const EdgeInsets.all(20.0),
                child: Column(
                  children: [
                    Icon(
                      Icons.history,
                      size: screenWidth * 0.15,
                      color: Colors.grey[400],
                    ),
                    SizedBox(height: 10),
                    Text(
                      'Belum ada riwayat berobat',
                      style: TextStyle(
                        fontFamily: 'KohSantepheap',
                        fontSize: screenWidth * 0.035,
                        color: Colors.grey[600],
                      ),
                    ),
                    SizedBox(height: 5),
                    ElevatedButton(
                      onPressed: () async {
                        final isLoggedIn = await AuthService().isLoggedIn();
                        if (!isLoggedIn) {
                          ScaffoldMessenger.of(context).showSnackBar(
                            const SnackBar(
                              content: Text(
                                  'Silakan login terlebih dahulu untuk mendaftar berobat'),
                              backgroundColor: Colors.orange,
                            ),
                          );
                          return;
                        }
                        _handlePatientRegistration(context);
                      },
                      style: ElevatedButton.styleFrom(
                        backgroundColor: const Color(0xFF06489F),
                        foregroundColor: Colors.white,
                        shape: RoundedRectangleBorder(
                            borderRadius: BorderRadius.circular(20)),
                        padding:
                            EdgeInsets.symmetric(horizontal: 15, vertical: 10),
                      ),
                      child: Text(
                        'Daftar Berobat Sekarang',
                        style: TextStyle(fontSize: screenWidth * 0.03),
                      ),
                    ),
                  ],
                ),
              ),
            )
          // Show records if available
          else
            Column(
              children: [
                // Tampilkan maksimal 2 rekam medis di halaman home
                ..._medicalRecords
                    .take(2)
                    .map((record) => _buildRiwayatBerobatItem(record))
                    .toList(),

                if (_medicalRecords.length > 2)
                  Align(
                    alignment: Alignment.center,
                    child: TextButton(
                      onPressed: () {
                        // Navigate to full medical history screen
                        Navigator.push(
                          context,
                          MaterialPageRoute(
                            builder: (context) => MedicalHistoryScreen(
                              records: _medicalRecords,
                            ),
                          ),
                        ).then((_) =>
                            _loadMedicalRecords()); // Refresh data when returning
                      },
                      child: Text(
                        'Lihat Semua',
                        style: TextStyle(
                          color: const Color(0xFF06489F),
                          fontSize: MediaQuery.of(context).size.width * 0.035,
                          fontWeight: FontWeight.w500,
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

  Widget _buildRiwayatBerobatItem(Map<String, dynamic> record) {
    final double screenWidth = MediaQuery.of(context).size.width;

    // Format tanggal dan waktu dari data API
    String tanggal = record['tanggal'] ?? 'Tidak tersedia';
    String waktu = record['waktu'] ?? 'Tidak tersedia';
    String diagnosis = record['diagnosis'] ?? 'Tidak ada diagnosis';
    String terapi = record['terapi'] ?? 'Tidak ada terapi';

    return GestureDetector(
      onTap: () {
        // Navigasi ke detail rekam medis jika ada ID
        if (record['id'] != null) {
          Navigator.push(
            context,
            MaterialPageRoute(
              builder: (context) => MedicalDetailScreen(recordId: record['id']),
            ),
          );
        }
      },
      child: Container(
        margin: EdgeInsets.only(bottom: screenWidth * 0.025),
        decoration: BoxDecoration(
          color: Colors.white,
          borderRadius: BorderRadius.circular(12),
          boxShadow: [
            BoxShadow(
              color: Colors.grey.withOpacity(0.1),
              spreadRadius: 0,
              blurRadius: 6,
              offset: const Offset(0, 2),
            ),
          ],
          border: Border.all(color: Colors.grey.withOpacity(0.1), width: 1),
        ),
        child: Row(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            // Left section - Hospital illustration
            ClipRRect(
              borderRadius: const BorderRadius.only(
                topLeft: Radius.circular(12),
                bottomLeft: Radius.circular(12),
              ),
              child: Image.asset(
                'assets/images/hospital-illustration.png',
                width: screenWidth * 0.22,
                height: screenWidth * 0.22,
                fit: BoxFit.cover,
              ),
            ),

            // Right section - Visit details
            Expanded(
              child: Padding(
                padding: EdgeInsets.all(screenWidth * 0.03),
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    // Date and time
                    Text(
                      '$tanggal, $waktu',
                      style: TextStyle(
                        fontFamily: 'KohSantepheap',
                        fontWeight: FontWeight.bold,
                        fontSize: screenWidth * 0.032,
                        color: const Color(0xFF06489F),
                      ),
                    ),
                    SizedBox(height: screenWidth * 0.02),

                    // Diagnosis
                    Row(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        Icon(
                          Icons.medical_information_outlined,
                          size: screenWidth * 0.04,
                          color: Colors.grey[700],
                        ),
                        SizedBox(width: screenWidth * 0.01),
                        Expanded(
                          child: Text(
                            diagnosis,
                            style: TextStyle(
                              fontFamily: 'KohSantepheap',
                              fontSize: screenWidth * 0.03,
                              color: Colors.grey[800],
                            ),
                          ),
                        ),
                      ],
                    ),
                    SizedBox(height: screenWidth * 0.01),

                    // Treatment
                    Row(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        Icon(
                          Icons.medication_outlined,
                          size: screenWidth * 0.04,
                          color: Colors.grey[700],
                        ),
                        SizedBox(width: screenWidth * 0.01),
                        Expanded(
                          child: Text(
                            terapi,
                            style: TextStyle(
                              fontFamily: 'KohSantepheap',
                              fontSize: screenWidth * 0.03,
                              color: Colors.grey[800],
                            ),
                          ),
                        ),
                      ],
                    ),
                  ],
                ),
              ),
            ),

            // See more button
            Padding(
              padding: EdgeInsets.all(screenWidth * 0.03),
              child: Align(
                alignment: Alignment.bottomRight,
                child: Text(
                  'See More ...',
                  style: TextStyle(
                    fontFamily: 'KohSantepheap',
                    fontSize: screenWidth * 0.026,
                    color: Colors.grey[500],
                    fontStyle: FontStyle.italic,
                  ),
                ),
              ),
            ),
          ],
        ),
      ),
    );
  }

  void _handlePatientRegistration(BuildContext context) async {
    // Check if user is logged in
    final bool isLoggedIn = await AuthService().isLoggedIn();

    if (!isLoggedIn) {
      // If not logged in, show message to login first
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(
          content: Text(
              'Silakan login terlebih dahulu untuk mendaftar sebagai pasien'),
          backgroundColor: Colors.orange,
        ),
      );
      return;
    }

    // Display health facility selection bottom sheet
    showModalBottomSheet(
      context: context,
      isScrollControlled: true,
      backgroundColor: Colors.white,
      shape: const RoundedRectangleBorder(
        borderRadius: BorderRadius.vertical(top: Radius.circular(25)),
      ),
      builder: (context) => DraggableScrollableSheet(
        initialChildSize: 0.45, // Lebih kecil agar informasi tidak kelihatan
        minChildSize: 0.4, // Minimal 40% dari layar
        maxChildSize: 0.95, // Maksimal 95% dari layar
        expand: false,
        builder: (context, scrollController) => Container(
          decoration: BoxDecoration(
            color: Colors.white,
            borderRadius: const BorderRadius.vertical(top: Radius.circular(25)),
            boxShadow: [
              BoxShadow(
                color: Colors.black.withOpacity(0.05),
                blurRadius: 10,
                spreadRadius: 0,
                offset: const Offset(0, -3),
              ),
            ],
          ),
          child: ListView(
            controller: scrollController,
            padding: const EdgeInsets.fromLTRB(20, 5, 20, 25),
            children: [
              // Garis kecil di bagian atas sebagai indikator
              Center(
                child: Container(
                  width: 40,
                  height: 4,
                  margin: const EdgeInsets.only(top: 10, bottom: 20),
                  decoration: BoxDecoration(
                    color: Colors.grey.shade300,
                    borderRadius: BorderRadius.circular(30),
                  ),
                ),
              ),

              // Judul
              const Text(
                'Pilih Jenis Fasilitas Kesehatan',
                textAlign: TextAlign.center,
                style: TextStyle(
                  fontSize: 18,
                  fontWeight: FontWeight.w600,
                  color: Color(0xFF06489F),
                  fontFamily: 'KohSantepheap',
                ),
              ),

              const SizedBox(height: 10),

              // Subtitle - petunjuk untuk scroll
              Container(
                padding:
                    const EdgeInsets.symmetric(horizontal: 30, vertical: 8),
                decoration: BoxDecoration(
                  color: const Color(0xFFF5F9FF),
                  borderRadius: BorderRadius.circular(20),
                  border: Border.all(
                      color: const Color(0xFF06489F).withOpacity(0.1)),
                ),
                child: const Row(
                  mainAxisSize: MainAxisSize.min,
                  mainAxisAlignment: MainAxisAlignment.center,
                  children: [
                    Icon(
                      Icons.keyboard_double_arrow_up_rounded,
                      color: Color(0xFF06489F),
                      size: 16,
                    ),
                    SizedBox(width: 6),
                    Text(
                      'Geser ke atas untuk informasi lebih lanjut',
                      textAlign: TextAlign.center,
                      style: TextStyle(
                        fontSize: 12,
                        fontWeight: FontWeight.w500,
                        color: Color(0xFF06489F),
                      ),
                    ),
                  ],
                ),
              ),

              const SizedBox(height: 25),

              // Pilihan fasilitas
              Row(
                children: [
                  _buildFacilityOption(
                    context,
                    icon: 'assets/images/hospital-illustration.png',
                    title: 'Faskes\nTingkat Pertama',
                    onTap: () {
                      Navigator.pop(context);
                      _handleFaskesPertama();
                    },
                  ),
                  const SizedBox(width: 16),
                  _buildFacilityOption(
                    context,
                    icon: 'assets/images/hospital-illustration.png',
                    title: 'Faskes Rujukan\nTingkat Lanjut',
                    onTap: () {
                      Navigator.pop(context);
                      _handleFaskesRujukan();
                    },
                  ),
                ],
              ),

              // Space dengan ketinggian cukup agar content berikutnya tidak terlihat saat pertama kali dibuka
              const SizedBox(height: 40),

              // Garis pembatas dengan dekorasi
              Container(
                margin: const EdgeInsets.symmetric(vertical: 5),
                child: Stack(
                  alignment: Alignment.center,
                  children: [
                    Divider(
                      color: Colors.grey.shade300,
                      thickness: 1,
                      height: 30,
                    ),
                    Container(
                      padding: const EdgeInsets.symmetric(
                          horizontal: 15, vertical: 5),
                      decoration: BoxDecoration(
                        color: Colors.white,
                        borderRadius: BorderRadius.circular(15),
                        border: Border.all(color: Colors.grey.shade200),
                      ),
                      child: const Text(
                        'Informasi Detail',
                        style: TextStyle(
                          fontSize: 12,
                          fontWeight: FontWeight.w500,
                          color: Color(0xFF06489F),
                        ),
                      ),
                    ),
                  ],
                ),
              ),

              const SizedBox(height: 30),

              // Judul informasi penjelasan
              const Row(
                children: [
                  Icon(
                    Icons.info_outline,
                    color: Color(0xFF06489F),
                    size: 20,
                  ),
                  SizedBox(width: 10),
                  Text(
                    'Informasi Fasilitas Kesehatan',
                    style: TextStyle(
                      fontSize: 16,
                      fontWeight: FontWeight.bold,
                      color: Color(0xFF06489F),
                      fontFamily: 'KohSantepheap',
                    ),
                  ),
                ],
              ),

              const SizedBox(height: 20),

              // Informasi Faskes Tingkat Pertama
              _buildFacilityInfoSection(
                title: 'Fasilitas Kesehatan Tingkat Pertama (FKTP)',
                content:
                    '''Fasilitas Kesehatan Tingkat Pertama (FKTP) adalah fasilitas pelayanan kesehatan dasar yang menjadi kontak pertama bagi peserta BPJS Kesehatan. FKTP memberikan pelayanan kesehatan dasar yang bersifat non-spesialistik.

Kategori FKTP:
• Puskesmas
• Klinik Pratama
• Praktik Dokter
• Praktik Dokter Gigi
• Klinik TNI/POLRI
• Rumah Sakit Tipe D Pratama

Jenis Pelayanan yang ditanggung:
• Konsultasi medis dan penyuluhan
• Pemeriksaan dan pengobatan dasar
• Pemeriksaan penunjang diagnostik sederhana
• Tindakan medis non-spesialistik
• Pelayanan obat dan bahan medis habis pakai
• Pemeriksaan ibu hamil, nifas, dan menyusui
• Pelayanan program rujuk balik''',
              ),

              const SizedBox(height: 25),

              // Informasi Faskes Rujukan Tingkat Lanjut
              _buildFacilityInfoSection(
                title: 'Fasilitas Kesehatan Rujukan Tingkat Lanjut (FKRTL)',
                content:
                    '''Fasilitas Kesehatan Rujukan Tingkat Lanjut (FKRTL) adalah fasilitas pelayanan kesehatan lanjutan yang memberikan pelayanan spesialistik dan sub-spesialistik. FKRTL hanya dapat diakses melalui rujukan dari FKTP kecuali dalam kondisi gawat darurat.

Kategori FKRTL:
• Rumah Sakit Umum
• Rumah Sakit Khusus
• Balai Kesehatan
• Klinik Utama

Jenis Pelayanan yang ditanggung:
• Rawat jalan tingkat lanjutan
• Rawat inap tingkat lanjutan
• Pelayanan obat dan bahan medis habis pakai
• Pelayanan penunjang diagnostik lanjutan
• Tindakan medis spesialistik dan sub-spesialistik
• Pelayanan rehabilitasi medis
• Pelayanan kedokteran forensik
• Pelayanan jenazah di fasilitas kesehatan''',
              ),

              const SizedBox(height: 25),

              // Informasi Sistem Rujukan BPJS
              _buildFacilityInfoSection(
                title: 'Sistem Rujukan BPJS Kesehatan',
                content:
                    '''Sistem rujukan BPJS Kesehatan menggunakan pendekatan berjenjang, dimana peserta harus terlebih dahulu memperoleh pelayanan di FKTP kecuali dalam keadaan gawat darurat. Jika diperlukan penanganan lebih lanjut, FKTP akan merujuk ke FKRTL.

Prosedur Rujukan:
1. Peserta wajib memperoleh pelayanan kesehatan pada FKTP tempat peserta terdaftar.
2. Jika diperlukan pelayanan lanjutan, FKTP akan memberikan surat rujukan ke FKRTL.
3. Rujukan diberikan jika pasien memerlukan pelayanan kesehatan spesialistik.
4. Rujukan berlaku untuk satu kali kunjungan dalam waktu paling lama 30 hari.
5. Untuk beberapa kondisi kronis tertentu, seperti diabetes, hipertensi, atau penyakit jantung, pasien dapat memperoleh program rujuk balik.

Pengecualian Rujukan Berjenjang:
• Kondisi gawat darurat
• Pasien berada di luar wilayah FKTP terdaftar
• Daerah yang tidak tersedia FKTP atau kekurangan dokter
• Kondisi khusus yang diatur dalam program pemerintah''',
              ),
            ],
          ),
        ),
      ),
    );
  }

  Widget _buildFacilityOption(
    BuildContext context, {
    required String icon,
    required String title,
    required VoidCallback onTap,
  }) {
    return Expanded(
      child: InkWell(
        onTap: onTap,
        borderRadius: BorderRadius.circular(16),
        child: Container(
          padding: const EdgeInsets.all(16),
          decoration: BoxDecoration(
            color: Colors.white,
            borderRadius: BorderRadius.circular(16),
            border: Border.all(color: Colors.grey.shade300),
            boxShadow: [
              BoxShadow(
                color: Colors.grey.withOpacity(0.1),
                blurRadius: 5,
                spreadRadius: 0,
                offset: const Offset(0, 2),
              ),
            ],
          ),
          child: Column(
            mainAxisSize: MainAxisSize.min,
            children: [
              // Icon
              SizedBox(
                height: 80,
                child: Image.asset(
                  icon,
                  fit: BoxFit.contain,
                ),
              ),

              const Divider(
                color: Color(0xFFEEEEEE),
                thickness: 1,
                height: 30,
              ),

              // Title
              Text(
                title,
                textAlign: TextAlign.center,
                style: const TextStyle(
                  fontSize: 14,
                  fontWeight: FontWeight.bold,
                  color: Color(0xFF06489F),
                  height: 1.3,
                ),
              ),
            ],
          ),
        ),
      ),
    );
  }

  Widget _buildFacilityInfoSection({
    required String title,
    required String content,
  }) {
    return Container(
      margin: const EdgeInsets.only(bottom: 20),
      padding: const EdgeInsets.all(16),
      decoration: BoxDecoration(
        gradient: const LinearGradient(
          colors: [Color(0xFF06489F), Color(0xFF0A74DA)],
          begin: Alignment.topLeft,
          end: Alignment.bottomRight,
        ),
        borderRadius: BorderRadius.circular(12),
        boxShadow: [
          BoxShadow(
            color: Colors.black.withOpacity(0.1),
            blurRadius: 8,
            spreadRadius: 2,
            offset: const Offset(0, 4),
          ),
        ],
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          // Header dengan warna background
          Container(
            padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 8),
            margin: const EdgeInsets.only(bottom: 12),
            decoration: BoxDecoration(
              color: Colors.white.withOpacity(0.8),
              borderRadius: BorderRadius.circular(8),
            ),
            child: Text(
              title,
              style: const TextStyle(
                fontSize: 16,
                fontWeight: FontWeight.bold,
                color: Color(0xFF06489F),
              ),
            ),
          ),

          // Content dengan rata kiri-kanan
          RichText(
            textAlign: TextAlign.justify,
            text: TextSpan(
              style: const TextStyle(
                fontSize: 14,
                height: 1.6,
                color: Colors.white,
                fontFamily: 'KohSantepheap',
              ),
              text: content,
            ),
          ),
        ],
      ),
    );
  }

  void _handleFaskesPertama() {
    // Arahkan ke form pendaftaran pasien
    Navigator.push(
      context,
      MaterialPageRoute(builder: (context) => const PatientFormScreen()),
    );
  }

  void _rujukan(BuildContext context) {
    // Arahkan ke halaman Feedback_1
    Navigator.push(
      context,
      MaterialPageRoute(builder: (context) => const Feedback_1()),
    );
  }

  void _pengumuman(BuildContext context) {
    // Arahkan ke halaman pengummuman
    Navigator.push(
      context,
      MaterialPageRoute(builder: (context) => const Pengumuman()),
    );
  }

  void _handleFaskesRujukan() {
    Navigator.push(
      context,
      MaterialPageRoute(builder: (context) => const FaskesRujukanScreen()),
    );
  }
}
