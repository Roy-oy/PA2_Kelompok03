import 'package:flutter/material.dart';
import 'package:mobile_puskesmas/screens/main_layout.dart';
import 'package:mobile_puskesmas/screens/auth/register_screen.dart';
import 'package:mobile_puskesmas/screens/auth/login_screen.dart';

void main() {
  runApp(const MyApp());
}

class MyApp extends StatelessWidget {
  const MyApp({Key? key}) : super(key: key);

  @override
  Widget build(BuildContext context) {
    return MaterialApp(
      title: 'Puskesmas SBB',
      debugShowCheckedModeBanner: false,
      theme: ThemeData(
        primarySwatch: Colors.blue,
        primaryColor: const Color(0xFF06489F),
        fontFamily: 'KohSantepheap',
      ),
      home: const MainLayout(),
      routes: {
        '/home': (context) => const MainLayout(),
        '/register': (context) => RegisterScreen(onRegisterSuccess: () {
              Navigator.pushReplacementNamed(context, '/home');
            }),
        '/login': (context) => LoginScreen(onLoginSuccess: () {
              Navigator.pushReplacementNamed(context, '/home');
            }),
      },
    );
  }
}
