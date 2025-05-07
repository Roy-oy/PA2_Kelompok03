import 'package:flutter/material.dart';
import 'package:flutter/services.dart';

class ArticleScreen extends StatefulWidget {
  const ArticleScreen({Key? key}) : super(key: key);

  @override
  State<ArticleScreen> createState() => _ArticleScreenState();
}

class _ArticleScreenState extends State<ArticleScreen>
    with SingleTickerProviderStateMixin {
  late TabController _tabController;

  @override
  void initState() {
    super.initState();
    _tabController = TabController(length: 3, vsync: this);
  }

  @override
  void dispose() {
    _tabController.dispose();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    // Set status bar to be transparent with dark icons
    SystemChrome.setSystemUIOverlayStyle(const SystemUiOverlayStyle(
      statusBarColor: Colors.transparent,
      statusBarIconBrightness: Brightness.dark,
    ));

    return Scaffold(
      backgroundColor: Colors.white,
      body: SafeArea(
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            // Search Bar
            Container(
              padding: const EdgeInsets.fromLTRB(16.0, 14.0, 16.0, 10.0),
              child: Container(
                height: 38,
                decoration: BoxDecoration(
                  color: Colors.grey.shade200,
                  borderRadius: BorderRadius.circular(19),
                ),
                child: TextField(
                  textAlignVertical: TextAlignVertical.center,
                  style: TextStyle(
                    fontFamily: 'KohSantepheap',
                    fontSize: 12,
                    color: Colors.grey.shade800,
                  ),
                  decoration: InputDecoration(
                    isDense: true,
                    border: InputBorder.none,
                    hintText: 'Cari berita, tips sehat atau gaya hidup',
                    hintStyle: TextStyle(
                      fontFamily: 'KohSantepheap',
                      color: Colors.grey.shade500,
                      fontSize: 12,
                    ),
                    prefixIcon: Padding(
                      padding: const EdgeInsets.symmetric(horizontal: 8.0),
                      child: Icon(Icons.search,
                          color: Color(0xFF06489F), size: 18),
                    ),
                    prefixIconConstraints:
                        BoxConstraints(minWidth: 36, minHeight: 36),
                    contentPadding: EdgeInsets.zero,
                  ),
                ),
              ),
            ),

            // Artikel Title
            Padding(
              padding: const EdgeInsets.only(left: 16.0, bottom: 12.0),
              child: Text(
                'Artikel',
                style: TextStyle(
                  fontFamily: 'KohSantepheap',
                  fontSize: 24,
                  fontWeight: FontWeight.bold,
                  color: const Color(0xFF06489F),
                ),
              ),
            ),

            // Tab Bar
            Container(
              margin: const EdgeInsets.symmetric(horizontal: 16.0),
              decoration: const BoxDecoration(),
              child: Column(
                children: [
                  TabBar(
                    controller: _tabController,
                    indicator: const BoxDecoration(),
                    unselectedLabelColor: Colors.grey.shade600,
                    labelColor: const Color(0xFF06489F),
                    labelStyle: const TextStyle(
                      fontFamily: 'KohSantepheap',
                      fontWeight: FontWeight.bold,
                    ),
                    unselectedLabelStyle: const TextStyle(
                      fontFamily: 'KohSantepheap',
                      fontWeight: FontWeight.w500,
                    ),
                    padding: EdgeInsets.zero,
                    labelPadding: const EdgeInsets.symmetric(horizontal: 4.0),
                    tabs: const [
                      Padding(
                        padding: EdgeInsets.symmetric(vertical: 8.0),
                        child: Text('Rekomendasi'),
                      ),
                      Padding(
                        padding: EdgeInsets.symmetric(vertical: 8.0),
                        child: Text('Berita Utama'),
                      ),
                      Padding(
                        padding: EdgeInsets.symmetric(vertical: 8.0),
                        child: Text('Berita'),
                      ),
                    ],
                  ),
                  // Custom animated indicator
                  Container(
                    height: 4,
                    margin: const EdgeInsets.only(top: 2.0),
                    child: AnimatedBuilder(
                      animation: _tabController.animation!,
                      builder: (context, child) {
                        // Calculate the position based on animation value
                        final position = _tabController.animation!.value;
                        // Calculate the width of each tab section
                        final tabWidth =
                            (MediaQuery.of(context).size.width - 32) / 3;

                        return Stack(
                          children: [
                            // Subtle background line
                            Container(
                              height: 1,
                              color: Colors.grey.shade200,
                            ),
                            // Animated indicator
                            CustomPaint(
                              size: Size(
                                  MediaQuery.of(context).size.width - 32, 4),
                              painter: _TabIndicatorPainter(
                                position: position,
                                tabWidth: tabWidth,
                                color: const Color(0xFF06489F),
                              ),
                            ),
                          ],
                        );
                      },
                    ),
                  ),
                ],
              ),
            ),

            // Featured Article
            Padding(
              padding: const EdgeInsets.all(16.0),
              child: Material(
                color: Colors.transparent,
                borderRadius: BorderRadius.circular(12.0),
                child: InkWell(
                  onTap: () {
                    // Handle featured article tap
                  },
                  splashColor: const Color(0xFF06489F).withOpacity(0.1),
                  highlightColor: const Color(0xFF06489F).withOpacity(0.05),
                  borderRadius: BorderRadius.circular(12.0),
                  child: Container(
                    decoration: BoxDecoration(
                      borderRadius: BorderRadius.circular(12.0),
                      boxShadow: [
                        BoxShadow(
                          color: Colors.grey.withOpacity(0.2),
                          spreadRadius: 1,
                          blurRadius: 4,
                          offset: const Offset(0, 2),
                        ),
                      ],
                    ),
                    child: ClipRRect(
                      borderRadius: BorderRadius.circular(12.0),
                      child: Stack(
                        children: [
                          // Article Image
                          Image.asset(
                            'assets/images/carousel-1.jpg', // Using existing carousel image
                            height: 200,
                            width: double.infinity,
                            fit: BoxFit.cover,
                          ),
                          // Article Text Overlay
                          Positioned(
                            bottom: 0,
                            left: 0,
                            right: 0,
                            child: Container(
                              padding: const EdgeInsets.all(16.0),
                              decoration: BoxDecoration(
                                gradient: LinearGradient(
                                  begin: Alignment.topCenter,
                                  end: Alignment.bottomCenter,
                                  colors: [
                                    Colors.transparent,
                                    Colors.black.withOpacity(0.7),
                                  ],
                                ),
                              ),
                              child: const Text(
                                'Lanjutkan Kerja Sama dengan 40 FKTP, BPJS Kesehatan Dorong komitmen FKTP...',
                                style: TextStyle(
                                  fontFamily: 'KohSantepheap',
                                  color: Colors.white,
                                  fontSize: 14,
                                  fontWeight: FontWeight.w500,
                                ),
                              ),
                            ),
                          ),
                        ],
                      ),
                    ),
                  ),
                ),
              ),
            ),

            // Berita Lainnya Title
            Padding(
              padding: const EdgeInsets.only(left: 16.0, bottom: 12.0),
              child: Text(
                'Berita Lainnya',
                style: TextStyle(
                  fontFamily: 'KohSantepheap',
                  fontSize: 20,
                  fontWeight: FontWeight.bold,
                  color: const Color(0xFF06489F),
                ),
              ),
            ),

            // News List
            Expanded(
              child: ListView(
                padding: const EdgeInsets.symmetric(horizontal: 16.0),
                children: [
                  _buildNewsItem(
                    title:
                        'Imunisasi Dasar Posyandu Siborongborong dilaksanakan dengan lancar',
                    date: '18-03-2025',
                    views: '3',
                    thumbnail: 'assets/images/carousel-2.jpg',
                    category: 'IMUNISASI DASAR',
                  ),
                  _buildNewsItem(
                    title:
                        'Dengan BPJS Kesehatan Siborongborong Tetap Optimis Melawan Penyakit Jantung dan Saraf',
                    date: '18-03-2025',
                    views: '3',
                    thumbnail: 'assets/images/carousel-3.jpg',
                    category: null,
                  ),
                  _buildNewsItem(
                    title:
                        'Mobile JKN Penerangan Perjalanan Widi dalam Melawan Strok',
                    date: '18-03-2025',
                    views: '3',
                    thumbnail: 'assets/images/carousel-1.jpg',
                    category: null,
                  ),
                ],
              ),
            ),
          ],
        ),
      ),
    );
  }

  Widget _buildNewsItem({
    required String title,
    required String date,
    required String views,
    required String thumbnail,
    String? category,
  }) {
    return Padding(
      padding: const EdgeInsets.symmetric(vertical: 8.0),
      child: Material(
        color: Colors.transparent,
        child: InkWell(
          onTap: () {
            // Handle article tap
          },
          splashColor: const Color(0xFF06489F).withOpacity(0.1),
          highlightColor: const Color(0xFF06489F).withOpacity(0.05),
          borderRadius: BorderRadius.circular(12.0),
          child: Padding(
            padding: const EdgeInsets.symmetric(vertical: 4.0, horizontal: 4.0),
            child: Row(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                // Thumbnail with category label
                Stack(
                  children: [
                    // Thumbnail
                    ClipRRect(
                      borderRadius: BorderRadius.circular(12.0),
                      child: Image.asset(
                        thumbnail,
                        width: 100,
                        height: 100,
                        fit: BoxFit.cover,
                      ),
                    ),
                    // Category label (if provided)
                    if (category != null)
                      Positioned(
                        top: 5,
                        left: 5,
                        child: Container(
                          padding: const EdgeInsets.symmetric(
                              horizontal: 8.0, vertical: 4.0),
                          decoration: BoxDecoration(
                            color: const Color(0xFF06489F),
                            borderRadius: BorderRadius.circular(4.0),
                          ),
                          child: Text(
                            category,
                            style: const TextStyle(
                              fontFamily: 'KohSantepheap',
                              color: Colors.white,
                              fontSize: 10,
                              fontWeight: FontWeight.bold,
                            ),
                          ),
                        ),
                      ),
                  ],
                ),
                const SizedBox(width: 12),
                // Article details
                Expanded(
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      Text(
                        title,
                        style: const TextStyle(
                          fontFamily: 'KohSantepheap',
                          fontSize: 14,
                          fontWeight: FontWeight.bold,
                        ),
                        maxLines: 3,
                        overflow: TextOverflow.ellipsis,
                      ),
                      const SizedBox(height: 8),
                      Row(
                        children: [
                          const Icon(Icons.remove_red_eye,
                              size: 16, color: Colors.grey),
                          const SizedBox(width: 4),
                          Text(
                            views,
                            style: const TextStyle(
                              fontFamily: 'KohSantepheap',
                              color: Colors.grey,
                              fontSize: 14,
                            ),
                          ),
                          const Spacer(),
                          Text(
                            date,
                            style: const TextStyle(
                              fontFamily: 'KohSantepheap',
                              color: Colors.grey,
                              fontSize: 14,
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
      ),
    );
  }
}

class _TabIndicatorPainter extends CustomPainter {
  final double position;
  final double tabWidth;
  final Color color;

  _TabIndicatorPainter({
    required this.position,
    required this.tabWidth,
    required this.color,
  });

  @override
  void paint(Canvas canvas, Size size) {
    final Paint paint = Paint()
      ..color = color
      ..style = PaintingStyle.fill;

    // Calculate the left position of the indicator based on the current tab position
    final double left = position * tabWidth;

    // Indicator width with responsive sizing
    final double indicatorWidth = tabWidth * 0.5;

    // Center the indicator within the tab
    final double startX = left + (tabWidth - indicatorWidth) / 2;

    // Draw a rounded rectangle indicator with a soft shadow
    final RRect indicatorRect = RRect.fromRectAndRadius(
      Rect.fromLTWH(startX, 0, indicatorWidth, 3),
      const Radius.circular(1.5),
    );

    // Add subtle shadow
    canvas.drawShadow(
      Path()..addRRect(indicatorRect),
      color.withOpacity(0.3),
      2.0,
      true,
    );

    // Draw the indicator
    canvas.drawRRect(indicatorRect, paint);
  }

  @override
  bool shouldRepaint(_TabIndicatorPainter oldDelegate) {
    return position != oldDelegate.position ||
        tabWidth != oldDelegate.tabWidth ||
        color != oldDelegate.color;
  }
}
