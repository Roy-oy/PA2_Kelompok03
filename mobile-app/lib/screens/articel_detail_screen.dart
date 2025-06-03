import 'package:flutter/material.dart';
import 'package:flutter/services.dart';
import 'package:flutter_html/flutter_html.dart';
import 'package:mobile_puskesmas/models/berita_model.dart';
import 'package:mobile_puskesmas/services/berita_service.dart';
import 'package:cached_network_image/cached_network_image.dart';
import 'package:share_plus/share_plus.dart';
import 'package:intl/intl.dart';
import 'package:mobile_puskesmas/config/api_config.dart';

class ArticleDetailScreen extends StatefulWidget {
  final int beritaId;

  const ArticleDetailScreen({
    Key? key,
    required this.beritaId,
  }) : super(key: key);

  @override
  State<ArticleDetailScreen> createState() => _ArticleDetailScreenState();
}

class _ArticleDetailScreenState extends State<ArticleDetailScreen> {
  final BeritaService _beritaService = BeritaService();
  Berita? _berita;
  bool _isLoading = true;
  bool _isFavorite = false;

  @override
  void initState() {
    super.initState();
    _loadBerita();
  }

  Future<void> _loadBerita() async {
    setState(() {
      _isLoading = true;
    });

    try {
      final berita = await _beritaService.getBeritaById(widget.beritaId);
      setState(() {
        _berita = berita;
        _isLoading = false;
      });
    } catch (e) {
      setState(() {
        _isLoading = false;
      });
      _showErrorDialog('Gagal memuat berita: ${e.toString()}');
    }
  }

  void _showErrorDialog(String message) {
    showDialog(
      context: context,
      builder: (context) => AlertDialog(
        title: const Text('Error'),
        content: Text(message),
        actions: [
          TextButton(
            onPressed: () => Navigator.pop(context),
            child: const Text('OK'),
          ),
        ],
      ),
    );
  }

  void _toggleFavorite() {
    setState(() {
      _isFavorite = !_isFavorite;
    });

    ScaffoldMessenger.of(context).showSnackBar(
      SnackBar(
        content: Text(_isFavorite 
            ? 'Berita ditambahkan ke favorit' 
            : 'Berita dihapus dari favorit'
        ),
        duration: const Duration(seconds: 2),
        behavior: SnackBarBehavior.floating,
      ),
    );
  }

  void _shareBerita() {
    if (_berita != null) {
      Share.share(
        '${_berita!.judul}\n\nBaca selengkapnya di aplikasi Puskesmas SBB.',
        subject: _berita!.judul,
      );
    }
  }

  String _formatDate(String dateString) {
    final date = DateTime.parse(dateString);
    return DateFormat('dd MMMM yyyy', 'id_ID').format(date);
  }

  @override
  Widget build(BuildContext context) {
    SystemChrome.setSystemUIOverlayStyle(const SystemUiOverlayStyle(
      statusBarColor: Colors.transparent,
      statusBarIconBrightness: Brightness.light,
    ));

    return Scaffold(
      backgroundColor: Colors.white,
      body: _isLoading
          ? const Center(
              child: CircularProgressIndicator(
                color: Color(0xFF06489F),
              ),
            )
          : _berita == null
              ? Center(
                  child: Column(
                    mainAxisAlignment: MainAxisAlignment.center,
                    children: [
                      const Text(
                        'Berita tidak ditemukan',
                        style: TextStyle(
                          fontFamily: 'KohSantepheap',
                          fontSize: 18,
                          fontWeight: FontWeight.bold,
                        ),
                      ),
                      const SizedBox(height: 16),
                      ElevatedButton(
                        onPressed: () => Navigator.pop(context),
                        style: ElevatedButton.styleFrom(
                          backgroundColor: const Color(0xFF06489F),
                          shape: RoundedRectangleBorder(
                            borderRadius: BorderRadius.circular(8),
                          ),
                        ),
                        child: const Text(
                          'Kembali',
                          style: TextStyle(
                            fontFamily: 'KohSantepheap',
                            color: Colors.white,
                          ),
                        ),
                      ),
                    ],
                  ),
                )
              : CustomScrollView(
                  slivers: [
                    // App Bar with Image
                    SliverAppBar(
                      expandedHeight: 200.0,
                      pinned: true,
                      backgroundColor: const Color(0xFF06489F),
                      flexibleSpace: FlexibleSpaceBar(
                        background: Stack(
                          fit: StackFit.expand,
                          children: [
                            // Header Image
                            _berita!.photo != null
                                ? CachedNetworkImage(
                                    imageUrl: '${ApiConfig.baseUrl}/storage/${_berita!.photo}',
                                    fit: BoxFit.cover,
                                    placeholder: (context, url) => Container(
                                      color: Colors.grey.shade300,
                                      child: const Center(
                                        child: CircularProgressIndicator(
                                          color: Colors.white,
                                        ),
                                      ),
                                    ),
                                    errorWidget: (context, url, error) => Image.asset(
                                      'assets/images/carousel-1.jpg',
                                      fit: BoxFit.cover,
                                    ),
                                  )
                                : Image.asset(
                                    'assets/images/carousel-1.jpg',
                                    fit: BoxFit.cover,
                                  ),
                            // Gradient overlay
                            Container(
                              decoration: BoxDecoration(
                                gradient: LinearGradient(
                                  begin: Alignment.topCenter,
                                  end: Alignment.bottomCenter,
                                  colors: [
                                    Colors.black.withOpacity(0.1),
                                    Colors.black.withOpacity(0.5),
                                  ],
                                ),
                              ),
                            ),
                          ],
                        ),
                      ),
                      leading: IconButton(
                        icon: Container(
                          padding: const EdgeInsets.all(8),
                          decoration: BoxDecoration(
                            color: Colors.black.withOpacity(0.3),
                            shape: BoxShape.circle,
                          ),
                          child: const Icon(
                            Icons.arrow_back,
                            color: Colors.white,
                          ),
                        ),
                        onPressed: () => Navigator.pop(context),
                      ),
                      actions: [
                        IconButton(
                          icon: Container(
                            padding: const EdgeInsets.all(8),
                            decoration: BoxDecoration(
                              color: Colors.black.withOpacity(0.3),
                              shape: BoxShape.circle,
                            ),
                            child: Icon(
                              _isFavorite
                                  ? Icons.bookmark
                                  : Icons.bookmark_border,
                              color: Colors.white,
                            ),
                          ),
                          onPressed: _toggleFavorite,
                        ),
                        IconButton(
                          icon: Container(
                            padding: const EdgeInsets.all(8),
                            decoration: BoxDecoration(
                              color: Colors.black.withOpacity(0.3),
                              shape: BoxShape.circle,
                            ),
                            child: const Icon(
                              Icons.share,
                              color: Colors.white,
                            ),
                          ),
                          onPressed: _shareBerita,
                        ),
                      ],
                    ),

                    // Content
                    SliverToBoxAdapter(
                      child: Padding(
                        padding: const EdgeInsets.all(16.0),
                        child: Column(
                          crossAxisAlignment: CrossAxisAlignment.start,
                          children: [
                            // Category and Date
                            Row(
                              children: [
                                if (_berita!.kategoriBerita != null)
                                  Container(
                                    padding: const EdgeInsets.symmetric(
                                        horizontal: 8, vertical: 4),
                                    decoration: BoxDecoration(
                                      color: const Color(0xFF06489F),
                                      borderRadius: BorderRadius.circular(4),
                                    ),
                                    child: Text(
                                      _berita!.kategoriBerita!.nama ?? '',
                                      style: const TextStyle(
                                        fontFamily: 'KohSantepheap',
                                        color: Colors.white,
                                        fontSize: 12,
                                        fontWeight: FontWeight.bold,
                                      ),
                                    ),
                                  ),
                                const SizedBox(width: 8),
                                Text(
                                  _formatDate(_berita!.tanggalUpload ?? ''),
                                  style: TextStyle(
                                    fontFamily: 'KohSantepheap',
                                    color: Colors.grey.shade600,
                                    fontSize: 12,
                                  ),
                                ),
                                const Spacer(),
                                Row(
                                  children: [
                                    const Icon(
                                      Icons.remove_red_eye,
                                      size: 16,
                                      color: Colors.grey,
                                    ),
                                    const SizedBox(width: 4),
                                    Text(
                                      _berita!.totalVisitors.toString(),
                                      style: TextStyle(
                                        fontFamily: 'KohSantepheap',
                                        color: Colors.grey.shade600,
                                        fontSize: 12,
                                      ),
                                    ),
                                  ],
                                ),
                              ],
                            ),

                            const SizedBox(height: 16),

                            // Title
                            Text(
                              _berita!.judul ?? '',
                              style: const TextStyle(
                                fontFamily: 'KohSantepheap',
                                fontSize: 22,
                                fontWeight: FontWeight.bold,
                                height: 1.3,
                              ),
                            ),

                            const SizedBox(height: 16),

                            // Content
                            Html(
                              data: _berita!.isiBerita,
                              style: {
                                "body": Style(
                                  fontFamily: 'KohSantepheap',
                                  fontSize: FontSize(16),
                                  lineHeight: LineHeight(1.6),
                                ),
                                "p": Style(
                                  margin: Margins.only(bottom: 16),
                                ),
                                "h1": Style(
                                  fontFamily: 'KohSantepheap',
                                  fontSize: FontSize(24),
                                  fontWeight: FontWeight.bold,
                                ),
                                "h2": Style(
                                  fontFamily: 'KohSantepheap',
                                  fontSize: FontSize(20),
                                  fontWeight: FontWeight.bold,
                                ),
                                "h3": Style(
                                  fontFamily: 'KohSantepheap',
                                  fontSize: FontSize(18),
                                  fontWeight: FontWeight.bold,
                                ),
                                "a": Style(
                                  color: const Color(0xFF06489F),
                                  textDecoration: TextDecoration.underline,
                                ),
                                "img": Style(
                                  margin: Margins.symmetric(vertical: 16),
                                ),
                                "ul": Style(
                                  margin: Margins.only(bottom: 16),
                                ),
                                "ol": Style(
                                  margin: Margins.only(bottom: 16),
                                ),
                                "li": Style(
                                  margin: Margins.only(bottom: 8),
                                ),
                              },
                            ),

                            const SizedBox(height: 24),

                            // Share Button
                            ElevatedButton.icon(
                              onPressed: _shareBerita,
                              icon: const Icon(Icons.share, color: Colors.white),
                              label: const Text(
                                'Bagikan Berita',
                                style: TextStyle(
                                  fontFamily: 'KohSantepheap',
                                  color: Colors.white,
                                  fontWeight: FontWeight.bold,
                                ),
                              ),
                              style: ElevatedButton.styleFrom(
                                backgroundColor: const Color(0xFF06489F),
                                shape: RoundedRectangleBorder(
                                  borderRadius: BorderRadius.circular(8),
                                ),
                                padding: const EdgeInsets.symmetric(vertical: 12),
                                minimumSize: const Size(double.infinity, 48),
                              ),
                            ),

                            const SizedBox(height: 24),
                          ],
                        ),
                      ),
                    ),
                  ],
                ),
    );
  }
}