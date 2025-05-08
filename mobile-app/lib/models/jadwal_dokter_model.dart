class JadwalDokterModel {
  final int id;
  final String namaDokter;
  final String spesialis;
  final String email;
  final String schedule_date;
  final String jamMulai;
  final String jamSelesai;
  final String ruangan;
  final String status;
  final String fotoProfil;

  JadwalDokterModel({
    required this.id,
    required this.namaDokter,
    required this.spesialis,
    required this.email,
    required this.schedule_date,
    required this.jamMulai,
    required this.jamSelesai,
    required this.ruangan,
    required this.status,
    required this.fotoProfil,
  });

  factory JadwalDokterModel.fromJson(Map<String, dynamic> json) {
    return JadwalDokterModel(
      id: json['id'],
      namaDokter: json['namaDokter'] ?? '-',
      spesialis: json['spesialis'] ?? '-',
      email: json['email'] ?? '-',
      schedule_date: json['schedule_date'] ?? '-',
      jamMulai: json['jamMulai'] ?? '-',
      jamSelesai: json['jamSelesai'] ?? '-',
      ruangan: json['ruangan'] ?? '-',
      status: json['status'] ?? 'Tidak Aktif',
      fotoProfil: json['foto_profil'] ?? '-',
    );
  }
}
