class FaqModel {
  final int id;
  final String question;
  final String answer;
  final String kategori;

  FaqModel({
    required this.id,
    required this.question,
    required this.answer,
    required this.kategori,
  });

  factory FaqModel.fromJson(Map<String, dynamic> json) {
    final allowedCategories = ['umum', 'pendaftaran', 'layanan', 'pembayaran'];
    final category = json['kategori']?.toString().toLowerCase() ?? 'umum';
    return FaqModel(
      id: json['id'] is int
          ? json['id']
          : int.tryParse(json['id']?.toString() ?? '0') ?? 0,
      question: json['question']?.toString() ?? '-',
      answer: json['answer']?.toString() ?? '-',
      kategori: allowedCategories.contains(category) ? category : 'umum',
    );
  }

  static List<FaqModel> fromJsonList(Map<String, dynamic> json) {
    if (json['success'] == true && json['data'] is List) {
      return (json['data'] as List)
          .map((item) => FaqModel.fromJson(item as Map<String, dynamic>))
          .toList();
    }
    return [];
  }

  Map<String, dynamic> toJson() {
    return {
      'id': id,
      'question': question,
      'answer': answer,
      'kategori': kategori,
    };
  }
}