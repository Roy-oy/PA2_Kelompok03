import 'package:flutter/material.dart';

class Feedback_1 extends StatefulWidget {
  const Feedback_1({super.key});

  @override
  State<Feedback_1> createState() => _Feedback_1State();
}

class _Feedback_1State extends State<Feedback_1> {
  int _pageIndex = 0;
  int _rating = 3;
  final TextEditingController _feedbackController = TextEditingController();

  final List<Map<String, String>> _doctors = [
    {
      'nama': 'dr. Susiana Siahaan, Sp.A, M.Kes',
      'deskripsi': 'Cek: efisien & supportive',
    },
    {
      'nama': 'dr. Febiola Gultang, Sp.A, M.Kes',
      'deskripsi': 'Cek: informatif & cepat',
    },
    {
      'nama': 'dr. Patton, Sp.A, M.Kes',
      'deskripsi': 'Cek: ramah & jelas',
    },
  ];

  void _goToFeedbackForm(int index) {
    setState(() {
      _pageIndex = 1;
    });
  }

  void _submitFeedback() {
    if (_feedbackController.text.isEmpty) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text('Feedback tidak boleh kosong')),
      );
      return;
    }
    setState(() {
      _pageIndex = 2;
    });
    _feedbackController.clear();
  }

  Widget _buildStar(int index) {
    return IconButton(
      icon: Icon(
        index <= _rating ? Icons.star : Icons.star_border,
        color: Colors.amber,
        size: 32,
      ),
      onPressed: () {
        setState(() {
          _rating = index;
        });
      },
    );
  }

  Widget _buildFeedbackList() {
    return ListView.builder(
      padding: const EdgeInsets.all(16),
      itemCount: _doctors.length,
      itemBuilder: (context, index) {
        final doctor = _doctors[index];
        return Card(
          margin: const EdgeInsets.only(bottom: 12),
          child: ListTile(
            title: Text(doctor['nama']!),
            subtitle: Text(doctor['deskripsi']!),
            trailing: ElevatedButton(
              onPressed: () => _goToFeedbackForm(index),
              style: ElevatedButton.styleFrom(backgroundColor: Colors.green),
              child: const Text('Isi Feedback'),
            ),
          ),
        );
      },
    );
  }

  Widget _buildFeedbackForm() {
    return Padding(
      padding: const EdgeInsets.all(16),
      child: Column(
        children: [
          const Text(
            'Bagaimana Pengalaman Anda?',
            style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold),
          ),
          Row(
            mainAxisAlignment: MainAxisAlignment.center,
            children: List.generate(5, (i) => _buildStar(i + 1)),
          ),
          const SizedBox(height: 16),
          TextField(
            controller: _feedbackController,
            maxLines: 4,
            decoration: const InputDecoration(
              hintText: 'Masukkan Feedback Anda',
              border: OutlineInputBorder(),
            ),
          ),
          const SizedBox(height: 20),
          ElevatedButton(
            onPressed: _submitFeedback,
            style: ElevatedButton.styleFrom(
              backgroundColor: Colors.green,
              padding: const EdgeInsets.symmetric(horizontal: 40, vertical: 16),
            ),
            child: const Text('Kirim Feedback'),
          ),
        ],
      ),
    );
  }

  Widget _buildSuccessPage() {
    return Padding(
      padding: const EdgeInsets.all(24),
      child: Column(
        mainAxisAlignment: MainAxisAlignment.center,
        children: const [
          Icon(Icons.send, color: Colors.blue, size: 60),
          SizedBox(height: 12),
          Text(
            'Feedback Berhasil Dikirim!',
            style: TextStyle(fontSize: 20, color: Colors.blue, fontWeight: FontWeight.bold),
          ),
          SizedBox(height: 20),
          Text(
            'Terima kasih atas masukan Anda! Feedback Anda sangat berharga bagi kami untuk meningkatkan pelayanan. Kami akan menindaklanjuti segera.',
            textAlign: TextAlign.center,
            style: TextStyle(fontSize: 16),
          ),
        ],
      ),
    );
  }

  @override
  Widget build(BuildContext context) {
    Widget body;
    switch (_pageIndex) {
      case 0:
        body = _buildFeedbackList();
        break;
      case 1:
        body = _buildFeedbackForm();
        break;
      case 2:
        body = _buildSuccessPage();
        break;
      default:
        body = const Center(child: Text('Halaman tidak ditemukan'));
    }

    return Scaffold(
      appBar: AppBar(
        title: const Text('Feedback'),
        backgroundColor: const Color(0xFF06489F),
        leading: _pageIndex > 0
            ? IconButton(
                icon: const Icon(Icons.arrow_back),
                onPressed: () {
                  setState(() {
                    _pageIndex = 0;
                  });
                },
              )
            : null,
      ),
      body: body,
    );
  }
}
