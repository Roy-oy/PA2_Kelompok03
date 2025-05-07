import 'package:flutter/material.dart';
import 'package:mobile_puskesmas/services/auth_service.dart';
import 'package:iconsax/iconsax.dart';

class RegisterScreen extends StatefulWidget {
  final Function() onRegisterSuccess;

  const RegisterScreen({Key? key, required this.onRegisterSuccess})
      : super(key: key);

  @override
  State<RegisterScreen> createState() => _RegisterScreenState();
}

class _RegisterScreenState extends State<RegisterScreen>
    with SingleTickerProviderStateMixin {
  final _formKey = GlobalKey<FormState>();
  final _nameController = TextEditingController();
  final _emailController = TextEditingController();
  final _passwordController = TextEditingController();
  final _passwordConfirmController = TextEditingController();
  final _phoneController = TextEditingController();
  final _nikController = TextEditingController();

  bool _isLoading = false;
  bool _isPasswordVisible = false;
  bool _isConfirmPasswordVisible = false;
  String _errorMessage = '';

  @override
  void initState() {
    super.initState();
  }

  @override
  void dispose() {
    _nameController.dispose();
    _emailController.dispose();
    _passwordController.dispose();
    _passwordConfirmController.dispose();
    _phoneController.dispose();
    _nikController.dispose();
    super.dispose();
  }

  Future<void> _register() async {
    if (!_formKey.currentState!.validate()) {
      return;
    }

    setState(() {
      _isLoading = true;
      _errorMessage = '';
    });

    try {
      await AuthService().register(
        _nameController.text,
        _emailController.text,
        _passwordController.text,
        _passwordConfirmController.text,
        _phoneController.text,
        _nikController.text,
      );

      // Show success snackbar
      if (mounted) {
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(
            content: const Text(
              'Registrasi berhasil!',
              style: TextStyle(fontFamily: 'KohSantepheap'),
            ),
            backgroundColor: Colors.green,
            behavior: SnackBarBehavior.floating,
            margin: const EdgeInsets.all(16),
            shape: RoundedRectangleBorder(
              borderRadius: BorderRadius.circular(10),
            ),
          ),
        );
      }

      // Navigate to home
      widget.onRegisterSuccess();
    } catch (e) {
      setState(() {
        _errorMessage = e.toString().replaceAll('Exception: ', '');
      });
    } finally {
      setState(() {
        _isLoading = false;
      });
    }
  }

  void _showErrorSnackBar(String message) {
    ScaffoldMessenger.of(context).showSnackBar(
      SnackBar(
        content: Text(message),
        backgroundColor: Colors.red[700],
        behavior: SnackBarBehavior.floating,
        margin: const EdgeInsets.all(16),
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

                      // Register prompt text
                      Container(
                        alignment: Alignment.centerLeft,
                        margin: const EdgeInsets.only(bottom: 24),
                        child: Column(
                          crossAxisAlignment: CrossAxisAlignment.start,
                          children: [
                            const Text(
                              'Daftar Akun',
                              style: TextStyle(
                                fontSize: 28,
                                fontWeight: FontWeight.bold,
                                color: Color(0xFF06489F),
                                fontFamily: 'KohSantepheap',
                              ),
                            ),
                            const SizedBox(height: 6),
                            Text(
                              'Lengkapi data berikut untuk membuat akun',
                              style: TextStyle(
                                fontSize: 15,
                                color: Colors.grey[700],
                                fontFamily: 'KohSantepheap',
                              ),
                            ),
                          ],
                        ),
                      ),

                      // Registration form without card container
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

                            // Name field
                            _buildInputField(
                              controller: _nameController,
                              labelText: 'Nama Lengkap',
                              hintText: 'Masukkan nama lengkap',
                              prefixIcon: Iconsax.user,
                              validator: (value) {
                                if (value == null || value.isEmpty) {
                                  return 'Nama lengkap tidak boleh kosong';
                                }
                                return null;
                              },
                            ),
                            const SizedBox(height: 16),

                            // NIK field
                            _buildInputField(
                              controller: _nikController,
                              labelText: 'NIK',
                              hintText: 'Masukkan NIK',
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
                            const SizedBox(height: 16),

                            // Email field
                            _buildInputField(
                              controller: _emailController,
                              labelText: 'Email',
                              hintText: 'Masukkan email',
                              prefixIcon: Iconsax.sms,
                              keyboardType: TextInputType.emailAddress,
                              validator: (value) {
                                if (value == null || value.isEmpty) {
                                  return 'Email tidak boleh kosong';
                                }
                                final emailRegex =
                                    RegExp(r'^[\w-\.]+@([\w-]+\.)+[\w-]{2,4}$');
                                if (!emailRegex.hasMatch(value)) {
                                  return 'Email tidak valid';
                                }
                                return null;
                              },
                            ),
                            const SizedBox(height: 16),

                            // Phone field
                            _buildInputField(
                              controller: _phoneController,
                              labelText: 'No. Telepon',
                              hintText: 'Masukkan no. telepon',
                              prefixIcon: Iconsax.call,
                              keyboardType: TextInputType.phone,
                              validator: (value) {
                                if (value == null || value.isEmpty) {
                                  return 'No. telepon tidak boleh kosong';
                                }
                                if (value.length < 10 || value.length > 13) {
                                  return 'No. telepon harus 10-13 digit';
                                }
                                return null;
                              },
                            ),
                            const SizedBox(height: 16),

                            // Password field
                            _buildInputField(
                              controller: _passwordController,
                              labelText: 'Password',
                              hintText: 'Masukkan password',
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
                                if (value.length < 6) {
                                  return 'Password minimal 6 karakter';
                                }
                                return null;
                              },
                            ),
                            const SizedBox(height: 16),

                            // Confirm Password field
                            _buildInputField(
                              controller: _passwordConfirmController,
                              labelText: 'Konfirmasi Password',
                              hintText: 'Masukkan ulang password',
                              prefixIcon: Iconsax.lock,
                              isPassword: true,
                              isPasswordVisible: _isConfirmPasswordVisible,
                              onTogglePasswordVisibility: () {
                                setState(() {
                                  _isConfirmPasswordVisible =
                                      !_isConfirmPasswordVisible;
                                });
                              },
                              validator: (value) {
                                if (value == null || value.isEmpty) {
                                  return 'Konfirmasi password tidak boleh kosong';
                                }
                                if (value != _passwordController.text) {
                                  return 'Password tidak sama';
                                }
                                return null;
                              },
                            ),
                            const SizedBox(height: 24),

                            // Register button
                            SizedBox(
                              width: double.infinity,
                              height: 56,
                              child: ElevatedButton(
                                onPressed: _isLoading ? null : _register,
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
                                    : const Row(
                                        mainAxisAlignment:
                                            MainAxisAlignment.center,
                                        children: [
                                          Icon(
                                            Iconsax.user_add,
                                            size: 20,
                                            color: Colors.white,
                                          ),
                                          SizedBox(width: 8),
                                          Text(
                                            'DAFTAR',
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

                            // Login link
                            Row(
                              mainAxisAlignment: MainAxisAlignment.center,
                              children: [
                                Text(
                                  'Sudah punya akun?',
                                  style: TextStyle(
                                    fontSize: 14,
                                    color: Colors.grey[700],
                                    fontFamily: 'KohSantepheap',
                                  ),
                                ),
                                TextButton(
                                  onPressed: () {
                                    Navigator.pop(context);
                                  },
                                  style: TextButton.styleFrom(
                                    foregroundColor: const Color(0xFF06489F),
                                    padding: const EdgeInsets.only(left: 4),
                                    minimumSize: const Size(0, 30),
                                  ),
                                  child: const Text(
                                    'Masuk',
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
            child: SizedBox(
              height: screenSize.height * 0.28,
              child: CustomPaint(
                size: Size(screenSize.width, screenSize.height * 0.28),
                painter: _TopWavePainter(),
              ),
            ),
          ),

          // App title - fixed position on top of wave
          const Positioned(
            top: 80,
            left: 0,
            right: 0,
            child: Column(
              children: [
                Text(
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
                SizedBox(height: 8),
                Text(
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
      duration: const Duration(milliseconds: 300),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(12),
        boxShadow: [
          BoxShadow(
            color: Colors.black.withOpacity(0.05),
            blurRadius: 10,
            offset: const Offset(0, 4),
          ),
        ],
      ),
      child: Stack(
        children: [
          TextFormField(
            controller: controller,
            keyboardType: keyboardType,
            obscureText: isPassword ? !isPasswordVisible : false,
            style: const TextStyle(
              fontSize: 16,
              fontFamily: 'KohSantepheap',
              color: Color(0xFF333333),
            ),
            validator: validator,
            autovalidateMode: AutovalidateMode.onUserInteraction,
            decoration: InputDecoration(
              contentPadding:
                  const EdgeInsets.symmetric(vertical: 16, horizontal: 20),
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
                borderSide:
                    const BorderSide(color: Color(0xFF06489F), width: 1.5),
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
              prefixIconConstraints: const BoxConstraints(minWidth: 45),
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
