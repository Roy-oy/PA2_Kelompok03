import 'package:flutter/material.dart';
import 'package:flutter_svg/flutter_svg.dart';

class RegistrationTypeSheet extends StatelessWidget {
  final Function(String) onTypeSelected;

  const RegistrationTypeSheet({
    Key? key,
    required this.onTypeSelected,
  }) : super(key: key);

  @override
  Widget build(BuildContext context) {
    return Container(
      padding: const EdgeInsets.symmetric(vertical: 24, horizontal: 16),
      decoration: const BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.only(
          topLeft: Radius.circular(20),
          topRight: Radius.circular(20),
        ),
      ),
      child: Column(
        mainAxisSize: MainAxisSize.min,
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          // Header
          Center(
            child: Container(
              width: 40,
              height: 5,
              decoration: BoxDecoration(
                color: Colors.grey.shade300,
                borderRadius: BorderRadius.circular(3),
              ),
            ),
          ),
          const SizedBox(height: 20),
          const Text(
            'Pilih Jenis Pendaftaran',
            style: TextStyle(
              fontSize: 18,
              fontWeight: FontWeight.bold,
              color: Color(0xFF06489F),
              fontFamily: 'KohSantepheap',
            ),
          ),
          const SizedBox(height: 5),
          Text(
            'Silakan pilih jenis pendaftaran yang sesuai',
            style: TextStyle(
              fontSize: 14,
              color: Colors.grey.shade700,
              fontFamily: 'KohSantepheap',
            ),
          ),
          const SizedBox(height: 24),

          // Registration Type Cards
          Row(
            children: [
              // Umum Card
              Expanded(
                child: _buildTypeCard(
                  context,
                  title: 'Umum',
                  description: 'Pendaftaran tanpa BPJS',
                  iconPath: 'assets/icons/general-medical-icon.svg',
                  iconFallback: Icons.local_hospital,
                  onTap: () => onTypeSelected('Umum'),
                ),
              ),
              const SizedBox(width: 16),
              // BPJS Card
              Expanded(
                child: _buildTypeCard(
                  context,
                  title: 'BPJS',
                  description: 'Pendaftaran dengan BPJS',
                  iconPath: 'assets/icons/bpjs-icon.svg',
                  iconFallback: Icons.health_and_safety,
                  onTap: () => onTypeSelected('BPJS'),
                ),
              ),
            ],
          ),
          const SizedBox(height: 30),
        ],
      ),
    );
  }

  Widget _buildTypeCard(
    BuildContext context, {
    required String title,
    required String description,
    required String iconPath,
    required IconData iconFallback,
    required VoidCallback onTap,
  }) {
    return InkWell(
      onTap: onTap,
      borderRadius: BorderRadius.circular(12),
      child: Container(
        padding: const EdgeInsets.symmetric(vertical: 20, horizontal: 16),
        decoration: BoxDecoration(
          color: Colors.white,
          borderRadius: BorderRadius.circular(12),
          border: Border.all(color: const Color(0xFF06489F).withOpacity(0.3)),
          boxShadow: [
            BoxShadow(
              color: Colors.black.withOpacity(0.05),
              blurRadius: 10,
              offset: const Offset(0, 5),
            ),
          ],
        ),
        child: Column(
          mainAxisSize: MainAxisSize.min,
          children: [
            _buildIcon(iconPath, iconFallback),
            const SizedBox(height: 12),
            Text(
              title,
              style: const TextStyle(
                fontSize: 16,
                fontWeight: FontWeight.bold,
                color: Color(0xFF06489F),
                fontFamily: 'KohSantepheap',
              ),
            ),
            const SizedBox(height: 4),
            Text(
              description,
              style: TextStyle(
                fontSize: 12,
                color: Colors.grey.shade700,
                fontFamily: 'KohSantepheap',
              ),
              textAlign: TextAlign.center,
            ),
          ],
        ),
      ),
    );
  }

  Widget _buildIcon(String iconPath, IconData fallbackIcon) {
    try {
      return SvgPicture.asset(
        iconPath,
        width: 40,
        height: 40,
        colorFilter: const ColorFilter.mode(
          Color(0xFF06489F),
          BlendMode.srcIn,
        ),
      );
    } catch (e) {
      return Icon(
        fallbackIcon,
        size: 40,
        color: const Color(0xFF06489F),
      );
    }
  }
}
