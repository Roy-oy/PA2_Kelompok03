import 'package:flutter/material.dart';
import 'package:flutter_svg/flutter_svg.dart';

class BottomNavBar extends StatelessWidget {
  final int selectedIndex;
  final Function(int) onItemTapped;

  const BottomNavBar({
    Key? key,
    required this.selectedIndex,
    required this.onItemTapped,
  }) : super(key: key);

  @override
  Widget build(BuildContext context) {
    // Define navbar items
    final List<NavItem> items = [
      NavItem(
        iconPath: 'assets/icons/home-icons.svg',
        label: 'Beranda',
        icon: Icons.home,
      ),
      NavItem(
        iconPath: 'assets/icons/article-icons.svg',
        label: 'Berita',
        icon: Icons.article,
        scaleFactor: 0.9,
      ),
      NavItem(
        iconPath: 'assets/icons/faq-icons.svg',
        label: 'FAQ',
        icon: Icons.question_answer,
      ),
      NavItem(
        iconPath: 'assets/icons/profile-icons.svg',
        label: 'Profil',
        icon: Icons.person,
      ),
    ];

    return Container(
      decoration: const BoxDecoration(
        color: Color(0xFFFFFFFF),
        boxShadow: [
          BoxShadow(
            color: Color(0x22000000),
            blurRadius: 10,
            spreadRadius: 1,
            offset: Offset(0, -2),
          ),
        ],
        border: Border(
          top: BorderSide(
            color: Color(0xFFE0E0E0),
            width: 1.0,
          ),
        ),
      ),
      child: SafeArea(
        child: SizedBox(
          height: 60,
          child: Row(
            mainAxisAlignment: MainAxisAlignment.spaceAround,
            children: List.generate(
              items.length,
              (index) => Expanded(
                child: _buildNavItem(items[index], index),
              ),
            ),
          ),
        ),
      ),
    );
  }

  Widget _buildNavItem(NavItem item, int index) {
    final bool isSelected = selectedIndex == index;
    final Color activeColor = const Color(0xFF06489F);
    final Color inactiveColor = Colors.grey;

    // Define sizes for both states
    final double selectedSize = 36.0 * (item.scaleFactor ?? 1.0);
    final double normalSize = 24.0 * (item.scaleFactor ?? 1.0);

    return InkWell(
      onTap: () => onItemTapped(index),
      child: Column(
        mainAxisAlignment: MainAxisAlignment.center,
        crossAxisAlignment: CrossAxisAlignment.center,
        mainAxisSize: MainAxisSize.min,
        children: [
          // Fixed size container to prevent layout shifts
          SizedBox(
            height: 36,
            width: 36,
            child: Center(
              child: AnimatedContainer(
                duration: const Duration(milliseconds: 200),
                height: isSelected ? selectedSize : normalSize,
                width: isSelected ? selectedSize : normalSize,
                child: _buildIcon(item.iconPath, item.icon, isSelected,
                    activeColor, inactiveColor, selectedSize, normalSize),
              ),
            ),
          ),
          const SizedBox(height: 1),
          Text(
            item.label,
            style: TextStyle(
              fontSize: 11,
              fontWeight: isSelected ? FontWeight.w600 : FontWeight.normal,
              color: isSelected ? activeColor : inactiveColor,
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildIcon(
      String path,
      IconData fallbackIcon,
      bool isSelected,
      Color activeColor,
      Color inactiveColor,
      double selectedSize,
      double normalSize) {
    try {
      if (path.isEmpty) {
        return Icon(
          fallbackIcon,
          color: isSelected ? activeColor : inactiveColor,
          size: isSelected ? selectedSize : normalSize,
        );
      }
      return SvgPicture.asset(
        path,
        colorFilter: ColorFilter.mode(
          isSelected ? activeColor : inactiveColor,
          BlendMode.srcIn,
        ),
      );
    } catch (e) {
      return Icon(
        fallbackIcon,
        color: isSelected ? activeColor : inactiveColor,
        size: isSelected ? selectedSize : normalSize,
      );
    }
  }
}

class NavItem {
  final String iconPath;
  final String label;
  final IconData icon;
  final double? scaleFactor;

  NavItem({
    required this.iconPath,
    required this.label,
    required this.icon,
    this.scaleFactor = 1.0,
  });
}
