import 'package:flutter/material.dart';
import 'package:flutter/services.dart';
import 'package:mobile_puskesmas/components/bottom_navbar.dart';
import 'package:mobile_puskesmas/screens/home_screen.dart';
import 'package:mobile_puskesmas/screens/article_screen.dart';
import 'package:mobile_puskesmas/screens/faq_screen.dart';
import 'package:mobile_puskesmas/screens/jadwal_dokter_screen.dart';
import 'package:mobile_puskesmas/screens/profile_screen.dart';

class MainLayout extends StatefulWidget {
  const MainLayout({Key? key}) : super(key: key);

  @override
  State<MainLayout> createState() => _MainLayoutState();
}

class _MainLayoutState extends State<MainLayout> {
  int _selectedIndex = 0;

  static const List<Widget> _screens = <Widget>[
    HomeScreen(),
    ArticleScreen(),
    FAQScreen(),
    ProfileScreen(),
    JadwalDokterScreen(),
  ];

  void _onItemTapped(int index) {
    setState(() {
      _selectedIndex = index;
    });
  }

  @override
  void initState() {
    super.initState();
    // Set system UI overlay style for the entire app
    SystemChrome.setSystemUIOverlayStyle(const SystemUiOverlayStyle(
      statusBarColor: Colors.transparent,
      statusBarIconBrightness: Brightness.dark,
    ));
  }

  @override
  Widget build(BuildContext context) {
    return AnnotatedRegion<SystemUiOverlayStyle>(
      value: const SystemUiOverlayStyle(
        statusBarColor: Colors.transparent,
        statusBarIconBrightness: Brightness.dark,
      ),
      child: Scaffold(
        backgroundColor: const Color(0xFFFFFFFF),
        // No AppBar needed since the HomeScreen has its own header
        body: _screens[_selectedIndex],
        bottomNavigationBar: BottomNavBar(
          selectedIndex: _selectedIndex,
          onItemTapped: _onItemTapped,
        ),
      ),
    );
  }
}
