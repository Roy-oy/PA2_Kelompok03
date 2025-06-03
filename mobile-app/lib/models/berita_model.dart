class Berita {
  final int id;
  final String? judul; // Changed to nullable
  final String? isiBerita; // Changed to nullable
  final String? tanggalUpload; // Changed to nullable
  final String? photo;
  final int totalVisitors;
  final int kategoriBeritaId;
  final KategoriBerita? kategoriBerita;

  Berita({
    required this.id,
    this.judul, // No longer required
    this.isiBerita, // No longer required
    this.tanggalUpload, // No longer required
    this.photo,
    required this.totalVisitors,
    required this.kategoriBeritaId,
    this.kategoriBerita,
  });

  factory Berita.fromJson(Map<String, dynamic> json) {
    return Berita(
      id: json['id'] ?? 0, // Default to 0 if null
      judul: json['judul']?.toString() ?? '', // Default to empty string if null
      isiBerita: json['isi_berita']?.toString() ?? '', // Default to empty string if null
      tanggalUpload: json['tanggal_upload']?.toString() ?? '', // Default to empty string if null
      photo: json['photo']?.toString(),
      totalVisitors: json['total_visitors'] ?? 0, // Default to 0 if null
      kategoriBeritaId: json['kategori_berita_id'] ?? 0, // Default to 0 if null
      kategoriBerita: json['kategori_berita'] != null
          ? KategoriBerita.fromJson(json['kategori_berita'])
          : null,
    );
  }
}

class KategoriBerita {
  final int id;
  final String? nama; // Changed to nullable

  KategoriBerita({
    required this.id,
    this.nama, // No longer required
  });

  factory KategoriBerita.fromJson(Map<String, dynamic> json) {
    return KategoriBerita(
      id: json['id'] ?? 0, // Default to 0 if null
      nama: json['nama']?.toString() ?? '', // Default to empty string if null
    );
  }
}