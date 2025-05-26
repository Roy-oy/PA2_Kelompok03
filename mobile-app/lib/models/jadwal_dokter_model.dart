class JadwalDokterModel {
  final int id;
  final String namaDokter;
  final String spesialis;
  final String email;
  final String scheduleDay; 
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
    required this.scheduleDay,
    required this.jamMulai,
    required this.jamSelesai,
    required this.ruangan,
    required this.status,
    required this.fotoProfil,
  });

  factory JadwalDokterModel.fromJson(Map<String, dynamic> json) {
    return JadwalDokterModel(
      id: json['id'] ?? 0,
      namaDokter: json['namaDokter'] ?? '-',
      spesialis: json['spesialis'] ?? '-',
      email: json['email'] ?? '-',
      scheduleDay: json['schedule_day'] ?? '-',
      jamMulai: json['jamMulai'] ?? '-',
      jamSelesai: json['jamSelesai'] ?? '-',
      ruangan: json['ruangan'] ?? '-',
      status: json['status'] ?? 'Tidak Aktif',
      // Use placeholder if foto_profil is null, empty, or invalid
      fotoProfil: (json['foto_profil'] != null &&
              json['foto_profil'].isNotEmpty &&
              Uri.tryParse(json['foto_profil'])?.hasScheme == true)
          ? json['foto_profil']
          : 'https://via.placeholder.com/150',
    );
  }
}