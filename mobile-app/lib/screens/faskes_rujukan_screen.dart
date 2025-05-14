import 'package:flutter/material.dart';

class FaskesRujukanScreen extends StatelessWidget {
  const FaskesRujukanScreen({Key? key}) : super(key: key);

  @override
  Widget build(BuildContext context) {
    final List<Map<String, String>> rujukanList = [
      {
        'hospital': 'RS SINT LUCIA',
        'type': 'Rujukan Antar RS',
        'specialty': 'Penyakit Dalam',
        'date': '05 Mei 2024',
      },
      {
        
        'hospital': 'RS ADAM MALIK',
        'type': 'Rujukan Antar RS',
        'specialty': 'GINJAL-HIPERTENSI',
        'date': '05 Mei 2024',
      },
    ];

    return Scaffold(
      appBar: AppBar(
        title: const Text(
          'Rujukan Faskes Tingkat Lanjut',
          style: TextStyle(fontWeight: FontWeight.bold),
        ),
        flexibleSpace: Container(
          decoration: const BoxDecoration(
            gradient: LinearGradient(
              colors: [Color(0xFF06489F), Color(0xFF0A74DA)],
              begin: Alignment.topLeft,
              end: Alignment.bottomRight,
            ),
          ),
        ),
      ),
      body: Padding(
        padding: const EdgeInsets.all(16.0),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            const Text(
              'Febiola Alya Hutagalung',
              style: TextStyle(
                fontSize: 18,
                fontWeight: FontWeight.bold,
                color: Color(0xFF06489F),
              ),
            ),
            const SizedBox(height: 4),
            const Text(
              '1202091602050001',
              style: TextStyle(fontSize: 14, color: Colors.black54),
            ),
            const SizedBox(height: 16),
            Expanded(
              child: ListView.builder(
                itemCount: rujukanList.length,
                itemBuilder: (context, index) {
                  final rujukan = rujukanList[index];
                  return _buildRujukanCard(
                    hospital: rujukan['hospital']!,
                    type: rujukan['type']!,
                    specialty: rujukan['specialty']!,
                    date: rujukan['date']!,
                  );
                },
              ),
            ),
          ],
        ),
      ),
    );
  }

  Widget _buildRujukanCard({
    required String hospital,
    required String type,
    required String specialty,
    required String date,
  }) {
    return Card(
      margin: const EdgeInsets.only(bottom: 16),
      shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
      elevation: 4,
      child: Padding(
        padding: const EdgeInsets.all(16.0),
        child: Row(
          children: [
            ClipRRect(
              borderRadius: BorderRadius.circular(8),
              child: Image.asset(
                'assets/images/hospital-illustration.png',
                width: 60,
                height: 60,
                fit: BoxFit.cover,
              ),
            ),
            const SizedBox(width: 16),
            Expanded(
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Text(
                    hospital,
                    style: const TextStyle(
                      fontSize: 16,
                      fontWeight: FontWeight.bold,
                      color: Color(0xFF06489F),
                    ),
                  ),
                  const SizedBox(height: 4),
                  Row(
                    children: [
                      const Icon(Icons.mail_outline, size: 16, color: Colors.grey),
                      const SizedBox(width: 4),
                      Text(
                        type,
                        style: const TextStyle(fontSize: 14, color: Colors.black54),
                      ),
                    ],
                  ),
                  const SizedBox(height: 4),
                  Row(
                    children: [
                      const Icon(Icons.local_hospital_outlined, size: 16, color: Colors.grey),
                      const SizedBox(width: 4),
                      Text(
                        specialty,
                        style: const TextStyle(fontSize: 14, color: Colors.black54),
                      ),
                    ],
                  ),
                  const SizedBox(height: 4),
                  Row(
                    children: [
                      const Icon(Icons.calendar_today_outlined, size: 16, color: Colors.grey),
                      const SizedBox(width: 4),
                      Text(
                        'Dirujuk: $date',
                        style: const TextStyle(fontSize: 14, color: Colors.black54),
                      ),
                    ],
                  ),
                ],
              ),
            ),
            const SizedBox(width: 8),
            Column(
              children: [
                IconButton(
                  icon: const Icon(Icons.visibility, color: Colors.green),
                  onPressed: () {
                    // Handle view action
                  },
                ),
                IconButton(
                  icon: const Icon(Icons.print, color: Colors.green),
                  onPressed: () {
                    // Handle print action
                  },
                ),
              ],
            ),
          ],
        ),
      ),
    );
  }
}
