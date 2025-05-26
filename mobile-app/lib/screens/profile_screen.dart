import 'package:flutter/material.dart';
import 'package:iconsax/iconsax.dart';
import 'package:mobile_puskesmas/models/user_model.dart';
import 'package:mobile_puskesmas/screens/auth/login_screen.dart';
import 'package:mobile_puskesmas/services/auth_service.dart';

const Color primaryColor = Color(0xFF06489F);
const Color greyColor = Colors.grey;
const String fontFamily = 'KohSantepheap';

class ProfileScreen extends StatefulWidget {
  const ProfileScreen({super.key});

  @override
  State<ProfileScreen> createState() => _ProfileScreenState();
}

class _ProfileScreenState extends State<ProfileScreen> {
  bool _isLoading = true;
  bool _isRefreshing = false;
  UserModel? _user;

  @override
  void initState() {
    super.initState();
    _loadUserData(forceRefresh: true);
  }

  Future<void> _loadUserData({bool forceRefresh = false}) async {
    if (!mounted) return;

    setState(() {
      _isLoading = !_isRefreshing;
      _isRefreshing = forceRefresh;
    });

    try {
      final user = await _fetchUserData(forceRefresh);
      if (mounted) {
        setState(() {
          _user = user;
          _isLoading = false;
          _isRefreshing = false;
        });
      }
    } catch (e) {
      debugPrint('Error loading user data: $e');
      if (mounted) {
        setState(() {
          _isLoading = false;
          _isRefreshing = false;
        });
        _showErrorSnackBar('Gagal memuat data pengguna');
      }
    }
  }

  Future<UserModel?> _fetchUserData(bool forceRefresh) async {
    UserModel? user;
    if (forceRefresh) {
      debugPrint('Refreshing profile data from server');
      try {
        user = await AuthService().getProfile();
        debugPrint('Profile refreshed successfully');
      } catch (e) {
        debugPrint('Server refresh failed: $e');
        user = await AuthService().getUserData();
      }
    } else {
      user = await AuthService().getUserData();
    }
    return user;
  }

  Future<void> _logout() async {
    final confirmed = await showDialog<bool>(
      context: context,
      builder: (context) => AlertDialog(
        title: const Text(
          'Konfirmasi Keluar',
          style: TextStyle(fontFamily: fontFamily, fontWeight: FontWeight.w600),
        ),
        content: const Text(
          'Apakah Anda yakin ingin keluar dari akun ini?',
          style: TextStyle(fontFamily: fontFamily),
        ),
        actions: [
          TextButton(
            onPressed: () => Navigator.pop(context, false),
            child: const Text('Batal',
                style: TextStyle(fontFamily: fontFamily, color: greyColor)),
          ),
          TextButton(
            onPressed: () => Navigator.pop(context, true),
            child: const Text(
              'Ya, Keluar',
              style: TextStyle(fontFamily: fontFamily, color: Colors.red),
            ),
          ),
        ],
      ),
    );

    if (confirmed ?? false) {
      setState(() => _isLoading = true);
      await AuthService().logout();
      if (mounted) {
        Navigator.pushNamedAndRemoveUntil(
          context,
          '/login',
          (route) => false,
        );
      }
    }
  }

  void _onLoginSuccess() => _loadUserData();

  void _showErrorSnackBar(String message) {
    ScaffoldMessenger.of(context).showSnackBar(
      SnackBar(
        content: Text(message, style: const TextStyle(fontFamily: fontFamily)),
        backgroundColor: Colors.red[700],
        behavior: SnackBarBehavior.floating,
        margin: EdgeInsets.all(16),
        shape: RoundedRectangleBorder(
          borderRadius: BorderRadius.circular(10),
        ),
      ),
    );
  }

  @override
  Widget build(BuildContext context) {
    if (_isLoading) {
      return const Scaffold(
        backgroundColor: Colors.white,
        body: Center(child: CircularProgressIndicator(color: primaryColor)),
      );
    }

    if (_user == null) {
      return LoginScreen(onLoginSuccess: _onLoginSuccess);
    }

    return Scaffold(
      backgroundColor: Colors.white,
      appBar: AppBar(
        backgroundColor: primaryColor,
        elevation: 0,
        centerTitle: true,
        title: const Text(
          'Data Pengguna',
          style: TextStyle(
            fontFamily: fontFamily,
            fontWeight: FontWeight.w500,
            fontSize: 18,
            color: Colors.white,
          ),
        ),
        actions: [
          IconButton(
            icon: const Icon(Iconsax.refresh),
            onPressed: _isRefreshing ? null : () => _loadUserData(forceRefresh: true),
            tooltip: 'Refresh data',
          ),
        ],
      ),
      body: RefreshIndicator(
        onRefresh: () => _loadUserData(forceRefresh: true),
        color: primaryColor,
        child: Column(
          children: [
            _ProfileHeader(user: _user!),
            Expanded(
              child: ListView(
                padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 20),
                children: [
                  if (_isRefreshing)
                    const Center(
                      child: Padding(
                        padding: EdgeInsets.all(8),
                        child: Text(
                          'Memperbarui data...',
                          style: TextStyle(
                            fontFamily: fontFamily,
                            fontSize: 14,
                            color: primaryColor,
                          ),
                        ),
                      ),
                    ),
                  const SizedBox(height: 20),
                  _PersonalDataSection(user: _user!),
                  const SizedBox(height: 20),
                  _ButtonsSection(onLogout: _logout),
                ],
              ),
            ),
          ],
        ),
      ),
    );
  }
}

class _ProfileHeader extends StatelessWidget {
  final UserModel user;

  const _ProfileHeader({required this.user});

  @override
  Widget build(BuildContext context) {
    return Container(
      padding: const EdgeInsets.only(bottom: 20),
      decoration: const BoxDecoration(
        color: primaryColor,
        borderRadius: BorderRadius.vertical(bottom: Radius.circular(20)),
      ),
      child: Column(
        children: [
          const SizedBox(height: 15),
          Row(
            children: [
              const SizedBox(width: 20),
              Container(
                padding: const EdgeInsets.all(3),
                decoration: BoxDecoration(
                  shape: BoxShape.circle,
                  color: Colors.white,
                  border: Border.all(color: Colors.white, width: 2),
                ),
                child: const CircleAvatar(
                  radius: 40,
                  backgroundColor: Colors.white,
                  child: Icon(Iconsax.user, size: 50, color: primaryColor),
                ),
              ),
              const SizedBox(width: 15),
              Expanded(
                child: Text(
                  user.name?.toUpperCase() ?? 'NAMA PENGGUNA',
                  style: const TextStyle(
                    fontFamily: fontFamily,
                    fontSize: 14,
                    fontWeight: FontWeight.w600,
                    color: Colors.white,
                  ),
                ),
              ),
            ],
          ),
        ],
      ),
    );
  }
}

class _PersonalDataSection extends StatelessWidget {
  final UserModel user;

  const _PersonalDataSection({required this.user});

  @override
  Widget build(BuildContext context) {
    return Container(
      padding: const EdgeInsets.all(16),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(10),
        boxShadow: [
          BoxShadow(
            color: greyColor.withOpacity(0.1),
            spreadRadius: 1,
            blurRadius: 4,
            offset: const Offset(0, 1),
          ),
        ],
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          const Text(
            'Data Pribadi',
            style: TextStyle(
              fontFamily: fontFamily,
              fontSize: 16,
              fontWeight: FontWeight.bold,
              color: primaryColor,
            ),
          ),
          const SizedBox(height: 15),
          _DataItem(
            label: 'Nama',
            value: user.name ?? '-',
          ),
          _DataItem(
            label: 'Tanggal Lahir',
            value: user.getFormattedTanggalLahir(),
          ),
          _DataItem(
            label: 'Jenis Kelamin',
            value: user.jenisKelamin ?? '-',
          ),
          _DataItem(
            label: 'Alamat',
            value: user.alamat ?? '-',
          ),
          _DataItem(
            label: 'No. Telepon',
            value: user.noHp ?? '-',
          ),
          _DataItem(
            label: 'Email',
            value: user.email ?? '-',
          ),
        ],
      ),
    );
  }
}

class _ButtonsSection extends StatelessWidget {
  final VoidCallback onLogout;

  const _ButtonsSection({required this.onLogout});

  @override
  Widget build(BuildContext context) {
    return Column(
      children: [
        const SizedBox(height: 10),
        _ActionButton(
          icon: Iconsax.edit,
          title: 'Ubah Data Profil',
          onTap: () {
            // TODO: Implement profile editing
          },
        ),
        const SizedBox(height: 20),
        _LogoutButton(onTap: onLogout),
      ],
    );
  }
}

class _DataItem extends StatelessWidget {
  final String label;
  final String value;

  const _DataItem({required this.label, required this.value});

  IconData _getIconForLabel() {
    switch (label.toLowerCase()) {
      case 'nama':
        return Iconsax.user;
      case 'tanggal lahir':
        return Iconsax.calendar_1;
      case 'jenis kelamin':
        return Iconsax.profile_2user;
      case 'alamat':
        return Iconsax.location;
      case 'no. telepon':
        return Iconsax.call;
      case 'email':
        return Iconsax.message;
      default:
        return Iconsax.document;
    }
  }

  @override
  Widget build(BuildContext context) {
    return Padding(
      padding: const EdgeInsets.only(bottom: 10),
      child: Row(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Icon(_getIconForLabel(), size: 18, color: primaryColor),
          const SizedBox(width: 8),
          SizedBox(
            width: 112,
            child: Text(
              label,
              style: TextStyle(
                fontFamily: fontFamily,
                fontSize: 13,
                color: greyColor,
              ),
            ),
          ),
          Text(': ',
              style: TextStyle(
                fontFamily: fontFamily,
                fontSize: 13,
                color: greyColor,
              )),
          Expanded(
            child: Text(
              value,
              style: const TextStyle(
                fontFamily: fontFamily,
                fontSize: 13,
                fontWeight: FontWeight.w500,
                color: Colors.black87,
              ),
            ),
          ),
        ],
      ),
    );
  }
}

class _ActionButton extends StatelessWidget {
  final IconData icon;
  final String title;
  final VoidCallback onTap;

  const _ActionButton({
    required this.icon,
    required this.title,
    required this.onTap,
  });

  @override
  Widget build(BuildContext context) {
    return InkWell(
      onTap: onTap,
      child: Container(
        padding: const EdgeInsets.symmetric(vertical: 12, horizontal: 16),
        decoration: BoxDecoration(
          color: Colors.white,
          borderRadius: BorderRadius.circular(10),
          boxShadow: [
            BoxShadow(
              color: greyColor.withOpacity(0.1),
              spreadRadius: 1,
              blurRadius: 4,
              offset: const Offset(0, 1),
            ),
          ],
        ),
        child: Row(
          children: [
            Icon(icon, color: primaryColor, size: 22),
            const SizedBox(width: 15),
            Text(
              title,
              style: const TextStyle(
                fontFamily: fontFamily,
                fontSize: 14,
                fontWeight: FontWeight.w500,
                color: Colors.black87,
              ),
            ),
            const Spacer(),
            const Icon(Iconsax.arrow_right_3, color: Colors.black38, size: 16),
          ],
        ),
      ),
    );
  }
}

class _LogoutButton extends StatelessWidget {
  final VoidCallback onTap;

  const _LogoutButton({required this.onTap});

  @override
  Widget build(BuildContext context) {
    return InkWell(
      onTap: onTap,
      child: Container(
        padding: const EdgeInsets.symmetric(vertical: 12),
        decoration: BoxDecoration(
          color: Colors.white,
          borderRadius: BorderRadius.circular(10),
          border: Border.all(color: Colors.red.shade300),
        ),
        child: const Row(
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            Icon(Iconsax.logout, color: Colors.red, size: 20),
            SizedBox(width: 8),
            Text(
              'Keluar',
              style: TextStyle(
                fontFamily: fontFamily,
                color: Colors.red,
                fontWeight: FontWeight.w500,
                fontSize: 14,
              ),
            ),
          ],
        ),
      ),
    );
  }
}