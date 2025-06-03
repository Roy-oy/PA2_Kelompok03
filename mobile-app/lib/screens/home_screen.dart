import 'package:flutter/material.dart';
import 'package:flutter/services.dart';
import 'package:carousel_slider/carousel_slider.dart';
import 'package:mobile_puskesmas/screens/faskes_rujukan_screen.dart';
import 'package:mobile_puskesmas/screens/feedback_1.dart';
import 'package:mobile_puskesmas/screens/jadwal_dokter_screen.dart';
import 'package:mobile_puskesmas/screens/medical_record_screen.dart';
import 'package:mobile_puskesmas/screens/pengumuman.dart';
import 'package:mobile_puskesmas/services/auth_service.dart';
import 'package:mobile_puskesmas/screens/patient_form_screen.dart';

class HomeScreen extends StatefulWidget {
  const HomeScreen({Key? key}) : super(key: key);

  @override
  State<HomeScreen> createState() => _HomeScreenState();
}

class _HomeScreenState extends State<HomeScreen> {
  int _currentIndex = 0;
  int _articleIndex = 0;

  final List<String> carouselImages = [
    'assets/images/carousel-1.jpg',
    'assets/images/carousel-2.jpg',
    'assets/images/carousel-3.jpg',
  ];

  final List<Map<String, String?>> recommendedArticles = [
    {
      'title': 'Tips Sehat Ala Dokter Keluarga',
      'date': '01-06-2025',
      'views': '12',
      'thumbnail': 'assets/images/carousel-1.jpg',
      'category': 'GAYA HIDUP'
    },
    {
      'title': 'Manfaat Tidur Berkualitas untuk Imunitas Tubuh',
      'date': '01-06-2025',
      'views': '8',
      'thumbnail': 'assets/images/carousel-2.jpg',
      'category': null
    },
  ];

  final List<Map<String, String?>> mainNews = [
    {
      'title': 'Lanjutkan Kerja Sama dengan 40 FKTP, BPJS Kesehatan Dorong Komitmen FKTP',
      'date': '01-06-2025',
      'views': '25',
      'thumbnail': 'assets/images/carousel-3.jpg',
      'category': 'BPJS'
    },
  ];

  final List<Map<String, String?>> otherNews = [
    {
      'title': 'Imunisasi Dasar Posyandu Siborongborong dilaksanakan dengan lancar',
      'date': '18-03-2025',
      'views': '3',
      'thumbnail': 'assets/images/carousel-2.jpg',
      'category': 'IMUNISASI DASAR',
    },
    {
      'title': 'Dengan BPJS Kesehatan Siborongborong Tetap Optimis Melawan Penyakit Jantung dan Saraf',
      'date': '18-03-2025',
      'views': '3',
      'thumbnail': 'assets/images/carousel-3.jpg',
      'category': null,
    },
    {
      'title': 'Mobile JKN Penerangan Perjalanan Widi dalam Melawan Strok',
      'date': '18-03-2025',
      'views': '3',
      'thumbnail': 'assets/images/carousel-1.jpg',
      'category': null,
    },
  ];

  List<Map<String, String?>> get allArticles => [
    ...recommendedArticles,
    ...mainNews,
    ...otherNews,
  ];

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: Colors.white,
      body: Column(
        children: [
          _buildHeader(),
          Expanded(
            child: SingleChildScrollView(
              child: Column(
                children: [
                  const SizedBox(height: 5),
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
                  Padding(
                    padding: EdgeInsets.all(MediaQuery.of(context).size.width *
                        0.04),
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
                                _handlePatientRegistration(context);
                              },
                            ),
                            _buildMenuItem(
                              image: 'assets/images/rekam-medis-logo.png',
                              title: 'Rekam Medis',
                              color: const Color(0xFF06489F),
                              onTap: () {
                                Navigator.push(
                                  context,
                                  MaterialPageRoute(
                                    builder: (context) =>
                                        const MedicalRecordScreen(
                                      records: [],
                                    ),
                                  ),
                                );
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
                                Navigator.push(
                                  context,
                                  MaterialPageRoute(
                                      builder: (context) => const Feedback_1()),
                                );
                              },
                            ),
                            _buildMenuItem(
                              image: 'assets/images/pengumuman-logo.png',
                              title: 'Pengumuman',
                              color: const Color(0xFF06489F),
                              onTap: () {
                                _pengumuman(context);
                              },
                            ),
                          ],
                        ),
                      ],
                    ),
                  ),
                  Padding(
                    padding: const EdgeInsets.symmetric(horizontal: 12.0, vertical: 4.0),
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        const Text(
                          'Berita & Artikel',
                          style: TextStyle(
                            fontFamily: 'KohSantepheap',
                            fontSize: 20,
                            fontWeight: FontWeight.bold,
                            color: Color(0xFF06489F),
                          ),
                        ),
                        const SizedBox(height: 8),
                        Stack(
                          children: [
                            CarouselSlider(
                              options: CarouselOptions(
                                height: 160,
                                viewportFraction: 0.85,
                                enlargeCenterPage: true,
                                autoPlay: true,
                                autoPlayInterval: const Duration(seconds: 5),
                                autoPlayAnimationDuration:
                                    const Duration(milliseconds: 800),
                                autoPlayCurve: Curves.fastOutSlowIn,
                                onPageChanged: (index, reason) {
                                  setState(() {
                                    _articleIndex = index;
                                  });
                                },
                              ),
                              items: allArticles.map((article) {
                                return Builder(
                                  builder: (BuildContext context) {
                                    return _buildArticleCard(
                                      title: article['title']!,
                                      date: article['date']!,
                                      views: article['views']!,
                                      thumbnail: article['thumbnail']!,
                                      category: article['category'],
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
                                children: allArticles.asMap().entries.map((entry) {
                                  return Container(
                                    width: 10.0,
                                    height: 10.0,
                                    margin:
                                        const EdgeInsets.symmetric(horizontal: 4.0),
                                    decoration: BoxDecoration(
                                      shape: BoxShape.circle,
                                      color: const Color(0xFF06489F).withOpacity(
                                        _articleIndex == entry.key ? 0.9 : 0.3,
                                      ),
                                      border: Border.all(
                                        color: Colors.white,
                                        width: 1.5,
                                      ),
                                    ),
                                  );
                                }).toList(),
                              ),
                            ),
                          ],
                        ),
                      ],
                    ),
                  ),
                ],
              ),
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildHeader() {
    final MediaQueryData mediaQuery =
        MediaQueryData.fromView(WidgetsBinding.instance.window);
    final double statusBarHeight = mediaQuery.padding.top;
    final double screenWidth = mediaQuery.size.width;

    final double headerHeight = mediaQuery.size.height * 0.09;
    final double logoSize = screenWidth * 0.24;
    final double titleFontSize = screenWidth * 0.05;
    final double subtitleFontSize = screenWidth * 0.032;

    return Container(
      height: headerHeight + statusBarHeight,
      color: Colors.white,
      child: Padding(
        padding: EdgeInsets.only(top: statusBarHeight),
        child: Stack(
          children: [
            Positioned(
              left: logoSize * 0.85,
              top: 0,
              bottom: 0,
              right: screenWidth * 0.04,
              child: Container(
                decoration: const BoxDecoration(
                  color: Color(0xFF06489F),
                  borderRadius: BorderRadius.only(
                    topRight: Radius.circular(15),
                    bottomRight: Radius.circular(15),
                  ),
                ),
                child: Padding(
                  padding: EdgeInsets.only(left: screenWidth * 0.055),
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
            Positioned(
              left: -screenWidth * 0.015,
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
    final double screenWidth = MediaQuery.of(context).size.width;
    final double iconSize = screenWidth * 0.15;
    final double fontSize = screenWidth * 0.023;

    return Expanded(
      child: GestureDetector(
        onTap: onTap,
        child: Column(
          children: [
            Container(
              width: iconSize,
              height: iconSize,
              padding: EdgeInsets.all(screenWidth * 0.01),
              decoration: const BoxDecoration(
                color: Colors.white,
                shape: BoxShape.circle,
              ),
              child: Image.asset(
                image,
                fit: BoxFit.contain,
              ),
            ),
            SizedBox(height: screenWidth * 0.008),
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

  Widget _buildArticleCard({
    required String title,
    required String date,
    required String views,
    required String thumbnail,
    String? category,
  }) {
    return Material(
      color: Colors.transparent,
      borderRadius: BorderRadius.circular(16.0),
      child: InkWell(
        onTap: () {},
        splashColor: const Color(0xFF06489F).withOpacity(0.2),
        highlightColor: const Color(0xFF06489F).withOpacity(0.1),
        borderRadius: BorderRadius.circular(16.0),
        child: Container(
          margin: const EdgeInsets.symmetric(horizontal: 6.0),
          decoration: BoxDecoration(
            borderRadius: BorderRadius.circular(16.0),
            border: Border.all(color: Colors.grey.shade200, width: 1),
            boxShadow: [
              BoxShadow(
                color: Colors.grey.withOpacity(0.3),
                spreadRadius: 2,
                blurRadius: 6,
                offset: const Offset(0, 3),
              ),
            ],
          ),
          child: ClipRRect(
            borderRadius: BorderRadius.circular(16.0),
            child: Stack(
              children: [
                Image.asset(
                  thumbnail,
                  height: 160,
                  width: double.infinity,
                  fit: BoxFit.cover,
                ),
                Positioned(
                  top: 8,
                  left: 8,
                  child: AnimatedOpacity(
                    opacity: 1.0,
                    duration: const Duration(milliseconds: 300),
                    child: category != null
                        ? Container(
                            padding: const EdgeInsets.symmetric(
                                horizontal: 10.0, vertical: 5.0),
                            decoration: BoxDecoration(
                              color: const Color(0xFF06489F),
                              borderRadius: BorderRadius.circular(6.0),
                              boxShadow: [
                                BoxShadow(
                                  color: Colors.black.withOpacity(0.2),
                                  blurRadius: 4,
                                  offset: const Offset(0, 2),
                                ),
                              ],
                            ),
                            child: Text(
                              category,
                              style: const TextStyle(
                                fontFamily: 'KohSantepheap',
                                color: Colors.white,
                                fontSize: 11,
                                fontWeight: FontWeight.bold,
                              ),
                            ),
                          )
                        : const SizedBox.shrink(),
                  ),
                ),
                Positioned(
                  bottom: 0,
                  left: 0,
                  right: 0,
                  child: Container(
                    padding: const EdgeInsets.all(12.0),
                    decoration: BoxDecoration(
                      gradient: LinearGradient(
                        begin: Alignment.topCenter,
                        end: Alignment.bottomCenter,
                        colors: [
                          Colors.black.withOpacity(0.1),
                          Colors.black.withOpacity(0.8),
                        ],
                      ),
                    ),
                    child: AnimatedOpacity(
                      opacity: 1.0,
                      duration: const Duration(milliseconds: 300),
                      child: Column(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          Text(
                            title,
                            style: const TextStyle(
                              fontFamily: 'KohSantepheap',
                              color: Colors.white,
                              fontSize: 14,
                              fontWeight: FontWeight.bold,
                              shadows: [
                                Shadow(
                                  color: Colors.black54,
                                  offset: Offset(1, 1),
                                  blurRadius: 2,
                                ),
                              ],
                            ),
                            maxLines: 2,
                            overflow: TextOverflow.ellipsis,
                          ),
                          const SizedBox(height: 6),
                          Row(
                            children: [
                              const Icon(Icons.remove_red_eye,
                                  size: 14, color: Colors.white70),
                              const SizedBox(width: 6),
                              Text(
                                views,
                                style: const TextStyle(
                                  fontFamily: 'KohSantepheap',
                                  color: Colors.white70,
                                  fontSize: 12,
                                ),
                              ),
                              const Spacer(),
                              Text(
                                date,
                                style: const TextStyle(
                                  fontFamily: 'KohSantepheap',
                                  color: Colors.white70,
                                  fontSize: 12,
                                ),
                              ),
                            ],
                          ),
                        ],
                      ),
                    ),
                  ),
                ),
              ],
            ),
          ),
        ),
      ),
    );
  }

  void _handlePatientRegistration(BuildContext context) async {
    final bool isLoggedIn = await AuthService().isLoggedIn();

    if (!isLoggedIn) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(
          content: Text(
              'Silakan login terlebih dahulu untuk mendaftar sebagai pasien'),
          backgroundColor: Colors.orange,
        ),
      );
      return;
    }

    showModalBottomSheet(
      context: context,
      isScrollControlled: true,
      backgroundColor: Colors.white,
      shape: const RoundedRectangleBorder(
        borderRadius: BorderRadius.vertical(top: Radius.circular(25)),
      ),
      builder: (context) => DraggableScrollableSheet(
        initialChildSize: 0.45,
        minChildSize: 0.4,
        maxChildSize: 0.95,
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
              const SizedBox(height: 40),
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
    Navigator.push(
      context,
      MaterialPageRoute(builder: (context) => const PatientFormScreen()),
    );
  }

  void _pengumuman(BuildContext context) {
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