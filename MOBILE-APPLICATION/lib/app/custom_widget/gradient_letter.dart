import 'package:flutter/material.dart';

import '../utils/app_const_colors.dart';
import '../utils/app_size.dart';
import 'app_text.dart';

class GradientLetterPlaceholder extends StatelessWidget {
  const GradientLetterPlaceholder({
    super.key,
    required this.name,
    this.size,
    this.fontSize,
  });

  final String name;
  final double? size;
  final double? fontSize;

  @override
  Widget build(BuildContext context) {
    return Container(
      alignment: Alignment.center,
      height: size ?? getWidth(40),
      width: size ?? getWidth(40),
      decoration: BoxDecoration(
        shape: BoxShape.circle,
        gradient: LinearGradient(
          colors: [
            bgColor.withValues(alpha: .6),
            bgColor.withValues(alpha: .8),
            bgColor.withValues(alpha: .9),
          ],
          begin: Alignment.topRight,
          end: Alignment.bottomLeft,
        ),
      ),
      child: AppText.heading1(
        name[0].toUpperCase(),
        color: whiteColor,
        fontWeight: FontWeight.w700,
        getfontSize: fontSize ?? 20,
      ),
    );
  }
}
