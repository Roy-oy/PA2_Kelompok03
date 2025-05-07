import 'package:flutter/material.dart';
import 'package:mobile_puskesmas/services/auth_service.dart';
import 'package:mobile_puskesmas/screens/auth/register_screen.dart';
import 'package:iconsax/iconsax.dart';
import 'dart:math' as math;
import 'package:mobile_puskesmas/config/api_config.dart';

class LoginScreen extends StatefulWidget {
  final Function() onLoginSuccess;

  const LoginScreen({Key? key, required this.onLoginSuccess}) : super(key: key);

  @override
  State<LoginScreen> createState() => _LoginScreenState();
}

class _LoginScreenState extends State<LoginScreen>
    with SingleTickerProviderStateMixin {
  final _formKey = GlobalKey<FormState>();
  late TextEditingController _usernameController;
  late TextEditingController _passwordController;
  bool _isPasswordVisible = false;
  bool _isLoading = false;
  String _errorMessage = '';

  @override
  void initState() {
    super.initState();
    _usernameController = TextEditingController();
    _passwordController = TextEditingController();
    _isPasswordVisible = false;
    _isLoading = false;
  }

  @override
  void dispose() {
    _usernameController.dispose();
    _passwordController.dispose();
    super.dispose();
  }

  Future<void> _login() async {
    if (!_formKey.currentState!.validate()) {
      return;
    }

    setState(() {
      _isLoading = true;
    });

    try {
      String nik = _usernameController.text.trim();
      String password = _passwordController.text;

      if (nik.isEmpty || password.isEmpty) {
        _showErrorSnackBar('NIK dan password tidak boleh kosong');
        return;
      }

      // Call login API with NIK
      final userModel = await AuthService().loginWithNik(nik, password);

      if (userModel != null) {
        // Navigate to home screen on success
        Navigator.pushNamedAndRemoveUntil(
          context,
          '/home',
          (route) => false,
        );
      } else {
        _showErrorSnackBar(
            'Login gagal. Periksa kembali NIK dan password Anda.');
      }
    } catch (e) {
      _showErrorSnackBar(e.toString().replaceAll('Exception: ', ''));
    } finally {
      setState(() {
        _isLoading = false;
      });
    }
  }

  // Show error message
  void _showErrorSnackBar(String message) {
    ScaffoldMessenger.of(context).showSnackBar(
      SnackBar(
        content: Text(message),
        backgroundColor: Colors.red[700],
        behavior: SnackBarBehavior.floating,
        margin: EdgeInsets.all(16),
        shape: RoundedRectangleBorder(
          borderRadius: BorderRadius.circular(10),
        ),
      ),
    );
    setState(() {
      _isLoading = false;
    });
  }

  @override
  Widget build(BuildContext context) {
    final screenSize = MediaQuery.of(context).size;

    return Scaffold(
      backgroundColor: Colors.white,
      body: Stack(
        children: [
          // Main content with scrolling
          SingleChildScrollView(
            physics: const BouncingScrollPhysics(),
            child: Column(
              children: [
                // Spacer for header
                Container(
                  height: screenSize.height *
                      0.23, // Slightly less than header size
                  color: Colors.transparent,
                ),
                
                // Content that will appear below the header
                Padding(
                  padding: const EdgeInsets.symmetric(horizontal: 24),
                  child: Column(
                    mainAxisAlignment: MainAxisAlignment.center,
                    crossAxisAlignment: CrossAxisAlignment.center,
                    children: [
                      SizedBox(height: screenSize.height * 0.05),

                      // Login prompt text (on white background)
                      Container(
                        alignment: Alignment.centerLeft,
                        margin: EdgeInsets.only(bottom: 24),
                        child: Column(
                          crossAxisAlignment: CrossAxisAlignment.start,
                          children: [
                            Text(
                              'Selamat Datang!',
                              style: TextStyle( 
                                fontSize: 28,
                                fontWeight: FontWeight.bold,
                                color: Color(0xFF06489F),
                                fontFamily: 'KohSantepheap',
                              ),
                            ),
                            SizedBox(height: 6),
                            Text(
                              'Silakan masuk menggunakan NIK Anda',
                              style: TextStyle(
                                fontSize: 15,
                                color: Colors.grey[700],
                                fontFamily: 'KohSantepheap',
                              ),
                            ),
                          ],
                        ),
                      ),

                      // Login form without card container
                      Form(
                        key: _formKey,
                        child: Column(
                          crossAxisAlignment: CrossAxisAlignment.start,
                          children: [
                            // Error message if any
                            if (_errorMessage.isNotEmpty)
                              Container(
                                padding: const EdgeInsets.all(12),
                                margin: const EdgeInsets.only(bottom: 16),
                                decoration: BoxDecoration(
                                  color: Colors.red.shade50,
                                  borderRadius: BorderRadius.circular(12),
                                  border:
                                      Border.all(color: Colors.red.shade200),
                                ),
                                child: Row(
                                  crossAxisAlignment: CrossAxisAlignment.start,
                                  children: [
                                    Icon(
                                      Iconsax.warning_2,
                                      color: Colors.red.shade700,
                                      size: 20,
                                    ),
                                    const SizedBox(width: 12),
                                    Expanded(
                                      child: Text(
                                        _errorMessage,
                                        style: TextStyle(
                                          fontFamily: 'KohSantepheap',
                                          color: Colors.red.shade700,
                                          fontSize: 13,
                                        ),
                                      ),
                                    ),
                                  ],
                                ),
                              ),

                            // NIK field
                            _buildInputField(
                              controller: _usernameController,
                              labelText: 'NIK',
                              hintText: 'Masukkan NIK Anda',
                              prefixIcon: Iconsax.card,
                              keyboardType: TextInputType.number,
                              validator: (value) {
                                if (value == null || value.isEmpty) {
                                  return 'NIK tidak boleh kosong';
                                }
                                if (value.length != 16) {
                                  return 'NIK harus 16 digit';
                                }
                                return null;
                              },
                            ),
                            const SizedBox(height: 18),

                            // Password field
                            _buildInputField(
                              controller: _passwordController,
                              labelText: 'Password',
                              hintText: 'Masukkan password Anda',
                              prefixIcon: Iconsax.lock,
                              isPassword: true,
                              isPasswordVisible: _isPasswordVisible,
                              onTogglePasswordVisibility: () {
                                setState(() {
                                  _isPasswordVisible = !_isPasswordVisible;
                                });
                              },
                              validator: (value) {
                                if (value == null || value.isEmpty) {
                                  return 'Password tidak boleh kosong';
                                }
                                return null;
                              },
                            ),
                            const SizedBox(height: 12),

                            // Forgot password link
                            Align(
                              alignment: Alignment.centerRight,
                              child: TextButton(
                                onPressed: () {
                                  // Navigate to forgot password screen
                                },
                                style: TextButton.styleFrom(
                                  foregroundColor: Color(0xFF06489F),
                                  padding: EdgeInsets.zero,
                                  minimumSize: Size(0, 30),
                                ),
                                child: const Text(
                                  'Lupa Password?',
                                  style: TextStyle(
                                    fontSize: 14,
                                    fontWeight: FontWeight.w600,
                                    fontFamily: 'KohSantepheap',
                                  ),
                                ),
                              ),
                            ),
                            const SizedBox(height: 24),

                            // Login button with animation
                            SizedBox(
                              width: double.infinity,
                              height: 56,
                              child: ElevatedButton(
                                onPressed: _isLoading ? null : _login,
                                style: ElevatedButton.styleFrom(
                                  backgroundColor: const Color(0xFF06489F),
                                  foregroundColor: Colors.white,
                                  elevation: 2,
                                  shadowColor:
                                      const Color(0xFF06489F).withOpacity(0.4),
                                  shape: RoundedRectangleBorder(
                                    borderRadius: BorderRadius.circular(12),
                                  ),
                                ),
                                child: _isLoading
                                    ? const SizedBox(
                                        height: 24,
                                        width: 24,
                                        child: CircularProgressIndicator(
                                          color: Colors.white,
                                          strokeWidth: 2.5,
                                        ),
                                      )
                                    : Row(
                                        mainAxisAlignment:
                                            MainAxisAlignment.center,
                                        children: [
                                          Icon(
                                            Iconsax.login,
                                            size: 20,
                                            color: Colors.white,
                                          ),
                                          SizedBox(width: 8),
                                          Text(
                                            'MASUK',
                                            style: TextStyle(
                                              fontFamily: 'KohSantepheap',
                                              fontSize: 15,
                                              fontWeight: FontWeight.bold,
                                              letterSpacing: 1,
                                            ),
                                          ),
                                        ],
                                      ),
                              ),
                            ),

                            const SizedBox(height: 20),

                            // Register link
                            Row(
                              mainAxisAlignment: MainAxisAlignment.center,
                              children: [
                                Text(
                                  'Belum punya akun?',
                                  style: TextStyle(
                                    fontSize: 14,
                                    color: Colors.grey[700],
                                    fontFamily: 'KohSantepheap',
                                  ),
                                ),
                                TextButton(
                                  onPressed: () {
                                    // Navigate to register screen
                                    Navigator.pushNamed(context, '/register');
                                  },
                                  style: TextButton.styleFrom(
                                    foregroundColor: Color(0xFF06489F),
                                    padding: EdgeInsets.only(left: 4),
                                    minimumSize: Size(0, 30),
                                  ),
                                  child: const Text(
                                    'Daftar',
                                    style: TextStyle(
                                      fontSize: 14,
                                      fontWeight: FontWeight.bold,
                                      fontFamily: 'KohSantepheap',
                                    ),
                                  ),
                                ),
                              ],
                            ),
                          ],
                        ),
                      ),

                      // Bottom spacing
                      SizedBox(height: screenSize.height * 0.05),
                    ],
                  ),
                ),
              ],
            ),
          ),

          // Top wave decoration - fixed position
          Positioned(
            top: 0,
            left: 0,
            right: 0,
            child: Container(
              height: screenSize.height * 0.28,
              child: CustomPaint(
                size: Size(screenSize.width, screenSize.height * 0.28),
                painter: _TopWavePainter(),
              ),
            ),
          ),

          // App title - fixed position on top of wave
          Positioned(
            top: 80,
            left: 0,
            right: 0,
            child: Column(
              children: [
                const Text(
                  'PUSMASIB',
                  style: TextStyle(
                    fontFamily: 'KohSantepheap',
                    fontSize: 32,
                    fontWeight: FontWeight.bold,
                    color: Colors.white,
                    letterSpacing: 2,
                    shadows: [
                      Shadow(
                        blurRadius: 4.0,
                        color: Color.fromRGBO(0, 0, 0, 0.3),
                        offset: Offset(0, 2),
                      ),
                    ],
                  ),
                ),
                const SizedBox(height: 8),
                const Text(
                  'PUSKESMAS SIBORONGBORONG',
                  style: TextStyle(
                    fontFamily: 'KohSantepheap',
                    fontSize: 14,
                    color: Colors.white,
                    letterSpacing: 1,
                    fontWeight: FontWeight.w500,
                  ),
                ),
              ],
            ),
          ),
        ],
      ),
    );
  }

  // Build an animated text field with label
  Widget _buildInputField({
    required TextEditingController controller,
    required String labelText,
    required String hintText,
    required IconData prefixIcon,
    bool isPassword = false,
    bool isPasswordVisible = false,
    Function()? onTogglePasswordVisibility,
    TextInputType keyboardType = TextInputType.text,
    String? Function(String?)? validator,
  }) {
    return AnimatedContainer(
      duration: Duration(milliseconds: 300),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(12),
        boxShadow: [
          BoxShadow(
            color: Colors.black.withOpacity(0.05),
            blurRadius: 10,
            offset: Offset(0, 4),
          ),
        ],
      ),
      child: Stack(
        children: [
          TextFormField(
            controller: controller,
            keyboardType: keyboardType,
            obscureText: isPassword ? !isPasswordVisible : false,
            style: TextStyle(
              fontSize: 16,
              fontFamily: 'KohSantepheap',
              color: const Color(0xFF333333),
            ),
            validator: validator,
            autovalidateMode: AutovalidateMode.onUserInteraction,
            decoration: InputDecoration(
              contentPadding:
                  EdgeInsets.symmetric(vertical: 16, horizontal: 20),
              border: OutlineInputBorder(
                borderRadius: BorderRadius.circular(12),
                borderSide: BorderSide(color: Colors.grey[300]!, width: 1),
              ),
              enabledBorder: OutlineInputBorder(
                borderRadius: BorderRadius.circular(12),
                borderSide: BorderSide(color: Colors.grey[300]!, width: 1),
              ),
              focusedBorder: OutlineInputBorder(
                borderRadius: BorderRadius.circular(12),
                borderSide: BorderSide(color: Color(0xFF06489F), width: 1.5),
              ),
              errorBorder: OutlineInputBorder(
                borderRadius: BorderRadius.circular(12),
                borderSide: BorderSide(color: Colors.red[400]!, width: 1),
              ),
              focusedErrorBorder: OutlineInputBorder(
                borderRadius: BorderRadius.circular(12),
                borderSide: BorderSide(color: Colors.red[400]!, width: 1.5),
              ),
              labelText: labelText,
              labelStyle: TextStyle(
                color: Colors.grey[700],
                fontFamily: 'KohSantepheap',
                fontWeight: FontWeight.w500,
                fontSize: 15,
              ),
              errorStyle: TextStyle(
                color: Colors.red[700],
                fontSize: 12,
                fontFamily: 'KohSantepheap',
                height: 0.8, // Reduce the height of error text
              ),
              hintText: hintText,
              hintStyle: TextStyle(
                color: Colors.grey[400],
                fontFamily: 'KohSantepheap',
                fontSize: 14,
              ),
              prefixIcon: Container(
                margin: const EdgeInsets.only(left: 12, right: 8),
                child: Icon(
                  prefixIcon,
                  color: const Color(0xFF06489F),
                  size: 22,
                ),
              ),
              prefixIconConstraints: BoxConstraints(minWidth: 45),
              suffixIcon: isPassword
                  ? Container(
                      margin: const EdgeInsets.only(right: 12),
                      child: IconButton(
                        icon: Icon(
                          isPasswordVisible ? Iconsax.eye : Iconsax.eye_slash,
                          color: const Color(0xFF06489F),
                          size: 22,
                        ),
                        onPressed: onTogglePasswordVisibility,
                      ),
                    )
                  : null,
              floatingLabelBehavior: FloatingLabelBehavior.auto,
              filled: true,
              fillColor: Colors.grey.shade50,
              // Reduce error message container effects
              isDense: true,
              errorMaxLines: 1,
            ),
          ),
        ],
      ),
    );
  }
}

class _TopWavePainter extends CustomPainter {
  @override
  void paint(Canvas canvas, Size size) {
    // Main background - solid color base with button matching color
    final mainPath = Path();
    mainPath.moveTo(0, 0);
    mainPath.lineTo(size.width, 0);
    mainPath.lineTo(size.width, size.height * 0.8);

    // Create a subtle organic curve at the bottom
    final firstControlPoint = Offset(size.width * 0.75, size.height * 0.95);
    final firstEndPoint = Offset(size.width * 0.5, size.height * 0.85);
    mainPath.quadraticBezierTo(firstControlPoint.dx, firstControlPoint.dy,
        firstEndPoint.dx, firstEndPoint.dy);

    final secondControlPoint = Offset(size.width * 0.25, size.height * 0.75);
    final secondEndPoint = Offset(0, size.height * 0.85);
    mainPath.quadraticBezierTo(secondControlPoint.dx, secondControlPoint.dy,
        secondEndPoint.dx, secondEndPoint.dy);

    mainPath.close();

    // Draw main shape with the primary color (same as button color)
    final mainPaint = Paint()
      ..color = const Color(0xFF06489F)
      ..style = PaintingStyle.fill;
    canvas.drawPath(mainPath, mainPaint);
  }

  @override
  bool shouldRepaint(covariant CustomPainter oldDelegate) => false;
}
