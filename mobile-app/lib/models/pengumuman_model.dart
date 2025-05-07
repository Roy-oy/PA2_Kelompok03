class PengumumanModel {
final int id;
final String judul;
final String isi;
final String tanggal;

  PengumumanModel({
    required this.id,
    required this.judul,
    required this.isi,
    required this.tanggal,
  });

  factory PengumumanModel.fromJson(Map<String, dynamic> json) {
    return PengumumanModel(
      id: json['id'],
      judul: json['judul'] ?? '-',
      isi: json['isi'] ?? '-', 
      tanggal: json['tanggal'] ?? '-',
    );
  }
}