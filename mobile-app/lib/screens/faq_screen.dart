import 'package:flutter/material.dart';
import 'package:flutter/services.dart';
import '../models/faq_model.dart';
import '../services/faq_service.dart';

class FAQScreen extends StatefulWidget {
  const FAQScreen({Key? key}) : super(key: key);

  @override
  State<FAQScreen> createState() => _FAQScreenState();
}

class _FAQScreenState extends State<FAQScreen>
    with SingleTickerProviderStateMixin {
  final FaqService _service = FaqService();
  late TabController _tabController;
  TextEditingController _searchController = TextEditingController();
  List<FaqModel> _filteredItems = [];
  List<FaqModel> _faqList = [];
  bool _isSearching = false;
  bool _isLoading = true;
  List<String> _recentSearches = ['pendaftaran', 'bpjs', 'jadwal dokter'];
  bool _showSuggestions = false;
  Map<String, bool> _helpfulRatings = {};

  final List<FAQCategory> _categories = [
    FAQCategory(name: 'Umum', icon: Icons.help_outline),
    FAQCategory(name: 'Pendaftaran', icon: Icons.app_registration),
    FAQCategory(name: 'Layanan', icon: Icons.medical_services_outlined),
    FAQCategory(name: 'Pembayaran', icon: Icons.payment),
    FAQCategory(name: 'Feedback', icon: Icons.rate_review_outlined),
  ];

  @override
  void initState() {
    super.initState();
    _tabController = TabController(length: _categories.length, vsync: this);
    _searchController.addListener(_onSearchChanged);
    _loadFaqData();
  }

  Future<void> _loadFaqData() async {
    setState(() => _isLoading = true);
    try {
      final faqList = await _service.getFaq();
      setState(() {
        _faqList = faqList;
        _isLoading = false;
      });
    } catch (e) {
      setState(() => _isLoading = false);
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(
          content: Text('Error: ${e.toString()}'),
          backgroundColor: Colors.red,
          duration: const Duration(seconds: 5),
        ),
      );
    }
  }

  @override
  void dispose() {
    _tabController.dispose();
    _searchController.dispose();
    super.dispose();
  }

  void _onSearchChanged() {
    final String query = _searchController.text.toLowerCase();
    setState(() {
      if (query.isEmpty) {
        _isSearching = false;
        _filteredItems = [];
        _showSuggestions = false;
      } else {
        _isSearching = true;
        _showSuggestions = true;
        _filteredItems = _faqList.where((item) =>
            item.question.toLowerCase().contains(query) ||
            item.answer.toLowerCase().contains(query)).toList();
      }
    });
  }

  List<String> _getSuggestions(String query) {
    if (query.isEmpty) {
      return _recentSearches;
    }

    List<String> suggestions = _recentSearches
        .where((search) => search.contains(query.toLowerCase()))
        .toList();

    for (final item in _faqList) {
      if (item.question.toLowerCase().contains(query.toLowerCase()) &&
          !suggestions.contains(item.question)) {
        final words = item.question.split(' ');
        if (words.length > 3) {
          suggestions.add('${words[0]} ${words[1]} ${words[2]}...');
        } else {
          suggestions.add(item.question);
        }
        if (suggestions.length >= 5) break;
      }
    }

    return suggestions.take(5).toList();
  }

  void _addToRecentSearches(String query) {
    if (query.isNotEmpty && !_recentSearches.contains(query)) {
      setState(() {
        _recentSearches.insert(0, query);
        if (_recentSearches.length > 5) {
          _recentSearches.removeLast();
        }
      });
    }
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
        child: _isLoading
            ? const Center(child: CircularProgressIndicator())
            : Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  _buildHeader(),
                  Container(
                    padding: const EdgeInsets.fromLTRB(16.0, 10.0, 16.0, 8.0),
                    child: Column(
                      children: [
                        Container(
                          height: 38,
                          decoration: BoxDecoration(
                            color: Colors.grey.shade200,
                            borderRadius: BorderRadius.circular(19),
                          ),
                          child: TextField(
                            controller: _searchController,
                            textAlignVertical: TextAlignVertical.center,
                            style: TextStyle(
                              fontFamily: 'KohSantepheap',
                              fontSize: 12,
                              color: Colors.grey.shade800,
                            ),
                            onSubmitted: (value) {
                              if (value.isNotEmpty) {
                                _addToRecentSearches(value);
                                setState(() {
                                  _showSuggestions = false;
                                });
                              }
                            },
                            decoration: InputDecoration(
                              isDense: true,
                              border: InputBorder.none,
                              hintText: 'Cari pertanyaan',
                              hintStyle: TextStyle(
                                fontFamily: 'KohSantepheap',
                                color: Colors.grey.shade500,
                                fontSize: 12,
                              ),
                              prefixIcon: Padding(
                                padding:
                                    const EdgeInsets.symmetric(horizontal: 8.0),
                                child: Icon(Icons.search,
                                    color: const Color(0xFF06489F), size: 18),
                              ),
                              suffixIcon: _searchController.text.isNotEmpty
                                  ? IconButton(
                                      icon: Icon(Icons.clear, size: 18),
                                      onPressed: () {
                                        _searchController.clear();
                                        setState(() {
                                          _isSearching = false;
                                          _filteredItems = [];
                                          _showSuggestions = false;
                                        });
                                      },
                                    )
                                  : null,
                              prefixIconConstraints: const BoxConstraints(
                                  minWidth: 36, minHeight: 36),
                              suffixIconConstraints: const BoxConstraints(
                                  minWidth: 36, minHeight: 36),
                              contentPadding: EdgeInsets.zero,
                            ),
                          ),
                        ),
                        if (_showSuggestions && _searchController.text.isNotEmpty)
                          Container(
                            margin: const EdgeInsets.only(top: 4),
                            decoration: BoxDecoration(
                              color: Colors.white,
                              borderRadius: BorderRadius.circular(8),
                              boxShadow: [
                                BoxShadow(
                                  color: Colors.grey.withOpacity(0.2),
                                  blurRadius: 8,
                                  offset: const Offset(0, 2),
                                ),
                              ],
                            ),
                            child: Column(
                              mainAxisSize: MainAxisSize.min,
                              children: _getSuggestions(_searchController.text)
                                  .map(
                                    (suggestion) => ListTile(
                                      dense: true,
                                      visualDensity: VisualDensity.compact,
                                      leading: Icon(
                                        _recentSearches.contains(suggestion)
                                            ? Icons.history
                                            : Icons.search,
                                        size: 16,
                                        color: Colors.grey.shade600,
                                      ),
                                      title: Text(
                                        suggestion,
                                        style: TextStyle(
                                          fontFamily: 'KohSantepheap',
                                          fontSize: 12,
                                          color: Colors.grey.shade800,
                                        ),
                                      ),
                                      onTap: () {
                                        setState(() {
                                          _searchController.text = suggestion;
                                          _showSuggestions = false;
                                        });
                                        _onSearchChanged();
                                        _addToRecentSearches(suggestion);
                                      },
                                    ),
                                  )
                                  .toList(),
                            ),
                          ),
                      ],
                    ),
                  ),
                  if (_showSuggestions && _searchController.text.isNotEmpty)
                    const SizedBox(height: 8),
                  if (!_showSuggestions)
                    if (_isSearching) _buildSearchResults() else _buildCategoryTabs(),
                ],
              ),
      ),
    );
  }

  Widget _buildHeader() {
    return Padding(
      padding: const EdgeInsets.fromLTRB(16.0, 16.0, 16.0, 8.0),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Text(
            'FAQ',
            style: TextStyle(
              fontFamily: 'KohSantepheap',
              fontSize: 24,
              fontWeight: FontWeight.bold,
              color: const Color(0xFF06489F),
            ),
          ),
          const SizedBox(height: 8),
          Text(
            'Pertanyaan yang sering ditanyakan',
            style: TextStyle(
              fontFamily: 'KohSantepheap',
              fontSize: 14,
              color: Colors.grey.shade700,
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildCategoryTabs() {
    return Expanded(
      child: Column(
        children: [
          Container(
            height: 48,
            margin: const EdgeInsets.symmetric(horizontal: 16.0),
            decoration: BoxDecoration(
              color: Colors.grey.shade50,
              borderRadius: BorderRadius.circular(8),
              border: Border.all(color: Colors.grey.shade200),
            ),
            child: TabBar(
              controller: _tabController,
              indicator: const UnderlineTabIndicator(
                borderSide: BorderSide(
                  color: Color(0xFF06489F),
                  width: 3.0,
                ),
                insets: EdgeInsets.only(left: 12.0, right: 4.0),
              ),
              labelColor: const Color(0xFF06489F),
              unselectedLabelColor: Colors.grey.shade600,
              labelStyle: const TextStyle(
                fontFamily: 'KohSantepheap',
                fontWeight: FontWeight.bold,
                fontSize: 12,
              ),
              unselectedLabelStyle: const TextStyle(
                fontFamily: 'KohSantepheap',
                fontWeight: FontWeight.normal,
                fontSize: 12,
              ),
              dividerColor: Colors.transparent,
              labelPadding: const EdgeInsets.only(right: 8.0),
              isScrollable: true,
              padding: EdgeInsets.zero,
              indicatorPadding: EdgeInsets.zero,
              indicatorSize: TabBarIndicatorSize.label,
              tabAlignment: TabAlignment.start,
              tabs: _categories.map((category) {
                bool isFirstTab = category.name == 'Umum';
                return Tab(
                  height: 40,
                  child: Container(
                    padding: EdgeInsets.only(
                      left: isFirstTab ? 12.0 : 8.0,
                      right: 4.0,
                    ),
                    child: Row(
                      mainAxisSize: MainAxisSize.min,
                      children: [
                        Icon(category.icon, size: 16),
                        const SizedBox(width: 6),
                        Text(category.name),
                      ],
                    ),
                  ),
                );
              }).toList(),
            ),
          ),
          const SizedBox(height: 12),
          AnimatedBuilder(
            animation: _tabController.animation!,
            builder: (context, child) {
              final int currentIndex = _tabController.animation!.value.round();
              if (currentIndex >= 0 && currentIndex < _categories.length) {
                return Padding(
                  padding: const EdgeInsets.symmetric(horizontal: 16.0),
                  child: Container(
                    padding: const EdgeInsets.all(8.0),
                    decoration: BoxDecoration(
                      color: const Color(0xFFE8F1FF),
                      borderRadius: BorderRadius.circular(8),
                    ),
                    child: Row(
                      children: [
                        Icon(
                          _categories[currentIndex].icon,
                          size: 16,
                          color: const Color(0xFF06489F),
                        ),
                        const SizedBox(width: 8),
                        Expanded(
                          child: Text(
                            _getCategoryDescription(currentIndex),
                            style: const TextStyle(
                              fontFamily: 'KohSantepheap',
                              fontSize: 12,
                              color: Color(0xFF06489F),
                            ),
                          ),
                        ),
                      ],
                    ),
                  ),
                );
              }
              return const SizedBox.shrink();
            },
          ),
          const SizedBox(height: 12),
          Expanded(
            child: TabBarView(
              controller: _tabController,
              children: _categories.map((category) {
                final categoryItems = _faqList.where((faq) => faq.category == category.name).toList();
                return RefreshIndicator(
                  color: const Color(0xFF06489F),
                  onRefresh: _loadFaqData,
                  child: categoryItems.isEmpty
                      ? _buildEmptyCategory()
                      : ListView.builder(
                          itemCount: categoryItems.length + 1,
                          padding: const EdgeInsets.all(16.0),
                          itemBuilder: (context, index) {
                            if (index == 0) {
                              return Padding(
                                padding: const EdgeInsets.only(bottom: 16.0),
                                child: Text(
                                  '${categoryItems.length} Pertanyaan',
                                  style: TextStyle(
                                    fontFamily: 'KohSantepheap',
                                    fontSize: 12,
                                    fontWeight: FontWeight.w500,
                                    color: Colors.grey.shade600,
                                  ),
                                ),
                              );
                            }
                            return _buildFAQItem(categoryItems[index - 1]);
                          },
                        ),
                );
              }).toList(),
            ),
          ),
        ],
      ),
    );
  }

  String _getCategoryDescription(int index) {
    switch (index) {
      case 0:
        return 'Informasi umum tentang aplikasi PUSMASIB Siborongborong';
      case 1:
        return 'Panduan lengkap proses pendaftaran pasien';
      case 2:
        return 'Informasi mengenai layanan-layanan yang tersedia';
      case 3:
        return 'Detail metode pembayaran dan informasi biaya';
      case 4:
        return 'Cara memberikan masukan dan pelaporan masalah';
      default:
        return '';
    }
  }

  Widget _buildEmptyCategory() {
    return Center(
      child: Column(
        mainAxisAlignment: MainAxisAlignment.center,
        children: [
          Icon(
            Icons.question_answer_outlined,
            size: 64,
            color: Colors.grey.shade300,
          ),
          const SizedBox(height: 16),
          Text(
            'Belum ada FAQ untuk kategori ini',
            style: TextStyle(
              fontFamily: 'KohSantepheap',
              fontSize: 16,
              color: Colors.grey.shade600,
            ),
          ),
          const SizedBox(height: 8),
          Text(
            'Silakan periksa kategori lainnya',
            style: TextStyle(
              fontFamily: 'KohSantepheap',
              fontSize: 14,
              color: Colors.grey.shade500,
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildSearchResults() {
    return Expanded(
      child: _filteredItems.isEmpty
          ? Center(
              child: Column(
                mainAxisSize: MainAxisSize.min,
                children: [
                  Icon(
                    Icons.search_off,
                    size: 64,
                    color: Colors.grey.shade400,
                  ),
                  const SizedBox(height: 16),
                  Text(
                    'Tidak ada hasil yang ditemukan',
                    style: TextStyle(
                      fontFamily: 'KohSantepheap',
                      fontSize: 16,
                      color: Colors.grey.shade600,
                    ),
                  ),
                  const SizedBox(height: 8),
                  Text(
                    'Coba gunakan kata kunci lain',
                    style: TextStyle(
                      fontFamily: 'KohSantepheap',
                      fontSize: 14,
                      color: Colors.grey.shade500,
                    ),
                  ),
                ],
              ),
            )
          : Column(
              children: [
                Padding(
                  padding: const EdgeInsets.symmetric(
                      horizontal: 16.0, vertical: 8.0),
                  child: Row(
                    children: [
                      Icon(Icons.info_outline,
                          size: 16, color: Colors.grey.shade600),
                      const SizedBox(width: 8),
                      Text(
                        'Ditemukan ${_filteredItems.length} hasil untuk "${_searchController.text}"',
                        style: TextStyle(
                          fontFamily: 'KohSantepheap',
                          fontSize: 12,
                          color: Colors.grey.shade600,
                        ),
                      ),
                    ],
                  ),
                ),
                Expanded(
                  child: ListView.builder(
                    itemCount: _filteredItems.length,
                    padding: const EdgeInsets.all(16.0),
                    itemBuilder: (context, index) {
                      return _buildFAQItem(_filteredItems[index]);
                    },
                  ),
                ),
              ],
            ),
    );
  }

  Widget _buildFAQItem(FaqModel item) {
    final String key = item.question;
    final bool isRated = _helpfulRatings.containsKey(key);

    return Container(
      margin: const EdgeInsets.only(bottom: 12.0),
      decoration: BoxDecoration(
        borderRadius: BorderRadius.circular(12.0),
        border: Border.all(color: Colors.grey.shade200),
        color: Colors.white,
        boxShadow: [
          BoxShadow(
            color: Colors.grey.withOpacity(0.1),
            blurRadius: 4,
            offset: const Offset(0, 2),
          ),
        ],
      ),
      child: Theme(
        data: Theme.of(context).copyWith(
          dividerColor: Colors.transparent,
          colorScheme: ColorScheme.light(
            primary: const Color(0xFF06489F),
          ),
        ),
        child: ExpansionTile(
          tilePadding: const EdgeInsets.symmetric(
            horizontal: 16.0,
            vertical: 8.0,
          ),
          title: Text(
            item.question,
            style: const TextStyle(
              fontFamily: 'KohSantepheap',
              fontSize: 14,
              fontWeight: FontWeight.bold,
              color: Color(0xFF06489F),
            ),
          ),
          shape: const RoundedRectangleBorder(
            borderRadius: BorderRadius.all(Radius.circular(12.0)),
          ),
          trailing: CircleAvatar(
            radius: 12,
            backgroundColor: Colors.grey.shade100,
            child: Icon(
              Icons.keyboard_arrow_down,
              size: 16,
              color: const Color(0xFF06489F),
            ),
          ),
          children: [
            const Divider(height: 1, thickness: 1, indent: 16, endIndent: 16),
            Padding(
              padding: const EdgeInsets.fromLTRB(16.0, 16.0, 16.0, 16.0),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Text(
                    item.answer,
                    style: TextStyle(
                      fontFamily: 'KohSantepheap',
                      fontSize: 13,
                      color: Colors.grey.shade800,
                      height: 1.5,
                    ),
                  ),
                  const SizedBox(height: 16),
                  Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      if (!isRated)
                        Column(
                          crossAxisAlignment: CrossAxisAlignment.start,
                          children: [
                            Text(
                              'Apakah jawaban ini membantu?',
                              style: TextStyle(
                                fontFamily: 'KohSantepheap',
                                fontSize: 11,
                                color: Colors.grey.shade700,
                              ),
                            ),
                            const SizedBox(height: 8),
                            Row(
                              children: [
                                InkWell(
                                  onTap: () {
                                    setState(() {
                                      _helpfulRatings[key] = true;
                                    });
                                    ScaffoldMessenger.of(context).showSnackBar(
                                      SnackBar(
                                        content: Text(
                                          'Terima kasih atas feedback Anda',
                                          style: TextStyle(
                                              fontFamily: 'KohSantepheap'),
                                        ),
                                        backgroundColor:
                                            const Color(0xFF06489F),
                                        duration: const Duration(seconds: 2),
                                      ),
                                    );
                                  },
                                  child: Container(
                                    padding: const EdgeInsets.symmetric(
                                        horizontal: 12, vertical: 6),
                                    decoration: BoxDecoration(
                                      color: Colors.green.withOpacity(0.1),
                                      borderRadius: BorderRadius.circular(16),
                                      border: Border.all(
                                          color: Colors.green.withOpacity(0.3)),
                                    ),
                                    child: Row(
                                      children: [
                                        Icon(Icons.thumb_up,
                                            size: 14, color: Colors.green),
                                        const SizedBox(width: 4),
                                        Text(
                                          'Ya',
                                          style: TextStyle(
                                            fontFamily: 'KohSantepheap',
                                            fontSize: 11,
                                            color: Colors.green,
                                          ),
                                        ),
                                      ],
                                    ),
                                  ),
                                ),
                                const SizedBox(width: 8),
                                InkWell(
                                  onTap: () {
                                    setState(() {
                                      _helpfulRatings[key] = false;
                                    });
                                    _showImprovementDialog(context);
                                  },
                                  child: Container(
                                    padding: const EdgeInsets.symmetric(
                                        horizontal: 12, vertical: 6),
                                    decoration: BoxDecoration(
                                      color: Colors.red.withOpacity(0.1),
                                      borderRadius: BorderRadius.circular(16),
                                      border: Border.all(
                                          color: Colors.red.withOpacity(0.3)),
                                    ),
                                    child: Row(
                                      children: [
                                        Icon(Icons.thumb_down,
                                            size: 14, color: Colors.red),
                                        const SizedBox(width: 4),
                                        Text(
                                          'Tidak',
                                          style: TextStyle(
                                            fontFamily: 'KohSantepheap',
                                            fontSize: 11,
                                            color: Colors.red,
                                          ),
                                        ),
                                      ],
                                    ),
                                  ),
                                ),
                              ],
                            ),
                          ],
                        )
                      else
                        Container(
                          padding: const EdgeInsets.symmetric(
                              horizontal: 12, vertical: 6),
                          decoration: BoxDecoration(
                            color: Colors.grey.shade100,
                            borderRadius: BorderRadius.circular(16),
                          ),
                          child: Row(
                            mainAxisSize: MainAxisSize.min,
                            children: [
                              Icon(
                                _helpfulRatings[key]!
                                    ? Icons.check_circle
                                    : Icons.info,
                                size: 14,
                                color: _helpfulRatings[key]!
                                    ? Colors.green
                                    : Colors.orange,
                              ),
                              const SizedBox(width: 4),
                              Text(
                                _helpfulRatings[key]!
                                    ? 'Terima kasih atas feedback Anda'
                                    : 'Feedback Anda telah direkam',
                                style: TextStyle(
                                  fontFamily: 'KohSantepheap',
                                  fontSize: 11,
                                  color: Colors.grey.shade700,
                                ),
                              ),
                            ],
                          ),
                        ),
                      const SizedBox(height: 16),
                      Row(
                        mainAxisAlignment: MainAxisAlignment.end,
                        children: [
                          _buildActionButton(
                            icon: Icons.share_outlined,
                            label: 'Bagikan',
                            onTap: () {
                              // Share functionality would go here
                            },
                          ),
                        ],
                      ),
                    ],
                  ),
                ],
              ),
            ),
          ],
        ),
      ),
    );
  }

  void _showImprovementDialog(BuildContext context) {
    showDialog(
      context: context,
      builder: (BuildContext context) {
        return Dialog(
          shape: RoundedRectangleBorder(
            borderRadius: BorderRadius.circular(16),
          ),
          child: Container(
            padding: const EdgeInsets.all(20),
            child: Column(
              mainAxisSize: MainAxisSize.min,
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                const Text(
                  'Bantu Kami Meningkatkan',
                  style: TextStyle(
                    fontFamily: 'KohSantepheap',
                    fontSize: 16,
                    fontWeight: FontWeight.bold,
                    color: Color(0xFF06489F),
                  ),
                ),
                const SizedBox(height: 8),
                Text(
                  'Mengapa jawaban tidak membantu?',
                  style: TextStyle(
                    fontFamily: 'KohSantepheap',
                    fontSize: 14,
                    color: Colors.grey.shade800,
                  ),
                ),
                const SizedBox(height: 16),
                _buildImprovementOption(
                  'Kurang jelas',
                  'Jawaban sulit dimengerti',
                ),
                _buildImprovementOption(
                  'Tidak lengkap',
                  'Informasi yang diberikan kurang detail',
                ),
                _buildImprovementOption(
                  'Tidak akurat',
                  'Informasi yang diberikan tidak benar',
                ),
                _buildImprovementOption(
                  'Lainnya',
                  'Alasan lain',
                ),
                const SizedBox(height: 20),
                Row(
                  mainAxisAlignment: MainAxisAlignment.end,
                  children: [
                    TextButton(
                      onPressed: () {
                        Navigator.of(context).pop();
                      },
                      child: const Text(
                        'Batal',
                        style: TextStyle(
                          fontFamily: 'KohSantepheap',
                          color: Colors.grey,
                        ),
                      ),
                    ),
                    const SizedBox(width: 8),
                    ElevatedButton(
                      onPressed: () {
                        Navigator.of(context).pop();
                        ScaffoldMessenger.of(context).showSnackBar(
                          SnackBar(
                            content: Text(
                              'Terima kasih atas feedback Anda',
                              style: TextStyle(fontFamily: 'KohSantepheap'),
                            ),
                            backgroundColor: const Color(0xFF06489F),
                            duration: const Duration(seconds: 2),
                          ),
                        );
                      },
                      style: ElevatedButton.styleFrom(
                        backgroundColor: const Color(0xFF06489F),
                        shape: RoundedRectangleBorder(
                          borderRadius: BorderRadius.circular(8),
                        ),
                      ),
                      child: const Text(
                        'Kirim',
                        style: TextStyle(
                          fontFamily: 'KohSantepheap',
                          fontWeight: FontWeight.bold,
                        ),
                      ),
                    ),
                  ],
                ),
              ],
            ),
          ),
        );
      },
    );
  }

  Widget _buildImprovementOption(String title, String description) {
    return Container(
      margin: const EdgeInsets.only(bottom: 8),
      decoration: BoxDecoration(
        border: Border.all(color: Colors.grey.shade300),
        borderRadius: BorderRadius.circular(8),
      ),
      child: RadioListTile(
        value: title,
        groupValue: null,
        onChanged: (value) {
          Navigator.of(context).pop();
          ScaffoldMessenger.of(context).showSnackBar(
            SnackBar(
              content: Text(
                'Terima kasih atas feedback Anda',
                style: TextStyle(fontFamily: 'KohSantepheap'),
              ),
              backgroundColor: const Color(0xFF06489F),
              duration: const Duration(seconds: 2),
            ),
          );
        },
        title: Text(
          title,
          style: const TextStyle(
            fontFamily: 'KohSantepheap',
            fontSize: 14,
            fontWeight: FontWeight.bold,
          ),
        ),
        subtitle: Text(
          description,
          style: TextStyle(
            fontFamily: 'KohSantepheap',
            fontSize: 12,
            color: Colors.grey.shade600,
          ),
        ),
        activeColor: const Color(0xFF06489F),
        contentPadding: const EdgeInsets.symmetric(horizontal: 12, vertical: 4),
      ),
    );
  }

  Widget _buildActionButton({
    required IconData icon,
    required String label,
    required VoidCallback onTap,
  }) {
    return InkWell(
      onTap: onTap,
      borderRadius: BorderRadius.circular(16),
      child: Container(
        padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 6),
        decoration: BoxDecoration(
          color: Colors.grey.shade100,
          borderRadius: BorderRadius.circular(16),
        ),
        child: Row(
          mainAxisSize: MainAxisSize.min,
          children: [
            Icon(icon, size: 14, color: Colors.grey.shade700),
            const SizedBox(width: 4),
            Text(
              label,
              style: TextStyle(
                fontFamily: 'KohSantepheap',
                fontSize: 11,
                color: Colors.grey.shade700,
              ),
            ),
          ],
        ),
      ),
    );
  }
}

class FAQCategory {
  final String name;
  final IconData icon;

  FAQCategory({
    required this.name,
    required this.icon,
  });
}