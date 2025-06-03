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
            _buildSearchBar(),
            _buildHeader(),
            _buildTabBar(),
            _buildFeaturedArticle(),
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
            Expanded(
              child: TabBarView(
                controller: _tabController,
                children: [
                  _buildNewsList(recommendedArticles),
                  _buildNewsList(mainNews),
                  _buildNewsList(otherNews),
                ],
              ),
            ),
          ],
        ),
      ),
    );
  }

  Widget _buildSearchBar() {
    return Container(
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
              child: Icon(Icons.search, color: Color(0xFF06489F), size: 18),
            ),
            prefixIconConstraints: BoxConstraints(minWidth: 36, minHeight: 36),
            contentPadding: EdgeInsets.zero,
          ),
        ),
      ),
    );
  }

  Widget _buildHeader() {
    return Padding(
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
    );
  }

  Widget _buildTabBar() {
    return Container(
      margin: const EdgeInsets.symmetric(horizontal: 16.0),
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
            tabs: const [
              Tab(text: 'Rekomendasi'),
              Tab(text: 'Berita Utama'),
              Tab(text: 'Berita'),
            ],
          ),
          Container(
            height: 4,
            margin: const EdgeInsets.only(top: 2.0),
            child: AnimatedBuilder(
              animation: _tabController.animation!,
              builder: (context, child) {
                final position = _tabController.animation!.value;
                final tabWidth = (MediaQuery.of(context).size.width - 32) / 3;
                return Stack(
                  children: [
                    Container(height: 1, color: Colors.grey.shade200),
                    CustomPaint(
                      size: Size(MediaQuery.of(context).size.width - 32, 4),
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
    );
  }

  Widget _buildFeaturedArticle() {
    return Padding(
      padding: const EdgeInsets.all(16.0),
      child: Material(
        color: Colors.transparent,
        borderRadius: BorderRadius.circular(12.0),
        child: InkWell(
          onTap: () {},
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
                  Image.asset(
                    'assets/images/carousel-1.jpg',
                    height: 200,
                    width: double.infinity,
                    fit: BoxFit.cover,
                  ),
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
                        'Lanjutkan Kerja Sama dengan 40 FKTP, BPJS Kesehatan Dorong Komitmen FKTP...',
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
    );
  }

  Widget _buildNewsList(List<Map<String, String?>> articles) {
    return ListView.builder(
      itemCount: articles.length,
      padding: const EdgeInsets.symmetric(horizontal: 16.0),
      itemBuilder: (context, index) {
        final article = articles[index];
        return _buildNewsItem(
          title: article['title']!,
          date: article['date']!,
          views: article['views']!,
          thumbnail: article['thumbnail']!,
          category: article['category'],
        );
      },
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
          onTap: () {},
          splashColor: const Color(0xFF06489F).withOpacity(0.1),
          highlightColor: const Color(0xFF06489F).withOpacity(0.05),
          borderRadius: BorderRadius.circular(12.0),
          child: Padding(
            padding: const EdgeInsets.symmetric(vertical: 4.0, horizontal: 4.0),
            child: Row(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Stack(
                  children: [
                    ClipRRect(
                      borderRadius: BorderRadius.circular(12.0),
                      child: Image.asset(
                        thumbnail,
                        width: 100,
                        height: 100,
                        fit: BoxFit.cover,
                      ),
                    ),
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

    final double left = position * tabWidth;
    final double indicatorWidth = tabWidth * 0.5;
    final double startX = left + (tabWidth - indicatorWidth) / 2;

    final RRect indicatorRect = RRect.fromRectAndRadius(
      Rect.fromLTWH(startX, 0, indicatorWidth, 3),
      const Radius.circular(1.5),
    );

    canvas.drawShadow(Path()..addRRect(indicatorRect), color.withOpacity(0.3), 2.0, true);
    canvas.drawRRect(indicatorRect, paint);
  }

  @override
  bool shouldRepaint(_TabIndicatorPainter oldDelegate) {
    return position != oldDelegate.position ||
        tabWidth != oldDelegate.tabWidth ||
        color != oldDelegate.color;
  }
}
