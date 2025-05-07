import 'package:flutter/material.dart';
import 'package:flutter/services.dart';

class CustomHeader extends StatelessWidget implements PreferredSizeWidget {
  const CustomHeader({Key? key}) : super(key: key);

  @override
  Widget build(BuildContext context) {
    // Set status bar to be transparent with dark icons
    SystemChrome.setSystemUIOverlayStyle(const SystemUiOverlayStyle(
      statusBarColor: Colors.transparent,
      statusBarIconBrightness: Brightness.dark,
    ));

    return AppBar(
      backgroundColor: Colors.white,
      elevation: 0,
      toolbarHeight: 85, // Further reduced from 100
      automaticallyImplyLeading: false, // Remove back button
      flexibleSpace: Stack(
        children: [
          // Blue background positioned to overlap with logo
          Positioned(
            left: 80, // Adjusted for smaller logo
            top: 0,
            bottom: 0,
            right: 20, // Adjusted right padding
            child: Container(
              decoration: const BoxDecoration(
                color: Color(0xFF2196F3),
                borderRadius: BorderRadius.only(
                  topRight: Radius.circular(15),
                  bottomRight: Radius.circular(15),
                ),
              ),
              child: Padding(
                padding: const EdgeInsets.only(left: 22.0),
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  mainAxisAlignment: MainAxisAlignment.center,
                  children: const [
                    Text(
                      'PUSMASIB',
                      style: TextStyle(
                        fontFamily: 'YesevaOne',
                        color: Colors.white,
                        fontSize: 22, // Further reduced from 24
                        height: 1.1,
                        letterSpacing: 0.5,
                      ),
                    ),
                    Text(
                      'PUSKESMAS SIBORONGBORONG',
                      style: TextStyle(
                        color: Colors.white,
                        fontSize: 12, // Further reduced from 14
                        height: 1.0,
                        fontWeight: FontWeight.normal,
                      ),
                      maxLines: 1,
                    ),
                  ],
                ),
              ),
            ),
          ),
          // Logo on top of the stack to ensure visibility
          Positioned(
            left: -8, // Adjusted position
            top: 0,
            bottom: 0,
            child: Center(
              child: Image.asset(
                'assets/images/logo.png',
                fit: BoxFit.contain,
                width: 95, // Further reduced from 110
              ),
            ),
          ),
        ],
      ),
    );
  }

  @override
  Size get preferredSize =>
      const Size.fromHeight(85); // Further reduced from 100
}
