import 'package:flutter/material.dart';

import '../utils/app_const_colors.dart';
import '../utils/app_size.dart';

thikDivider({double? height}) {
  return Container(
    width: width(),
    height: getWidth(height ?? 6),
    color: fillColor,
  );
}
