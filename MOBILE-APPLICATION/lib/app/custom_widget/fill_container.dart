import 'package:flutter/material.dart';
import 'package:jazz_smart_pay/app/utils/app_const_colors.dart';

import '../utils/app_size.dart';

class FillContainer extends StatelessWidget {
  final EdgeInsets? padding;
  final EdgeInsets? margin;
  final double? borderRadius;
  final double? height;
  final double? width;
  final BorderRadiusGeometry? borderRadiusGeometry;
  final Color? backgroundColor;
  final Widget child;
  final BoxBorder? border;
  const FillContainer({
    super.key,
    this.padding,
    this.margin,
    this.height,
    this.width,
    this.borderRadius,
    this.borderRadiusGeometry,
    this.backgroundColor,
    this.border,
    required this.child,
  });

  @override
  Widget build(BuildContext context) {
    return Container(
      height: height,
      width: width,
      margin: margin,
      padding: padding ??
          EdgeInsets.all(
            getWidth(20)!,
          ),
      decoration: BoxDecoration(
        borderRadius: borderRadiusGeometry ??
            BorderRadius.circular(
              borderRadius ?? getWidth(12)!,
            ),
        border: border,
        color: backgroundColor ?? lightBgColor,
      ),
      child: child,
    );
  }
}
