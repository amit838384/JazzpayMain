import 'package:flutter/gestures.dart';
import 'package:flutter/material.dart';
import 'package:jazz_smart_pay/app/utils/app_const_colors.dart';
import 'package:get/get.dart';

import '../utils/app_size.dart';

class AppText {
  static Widget heading1(String data,
      {double? getfontSize,
      Color? color,
      TextAlign? textAlign,
      FontWeight? fontWeight}) {
    return Text(
      data,
      style: TextStyle(
        fontWeight: fontWeight ?? FontWeight.w900,
        fontSize: getFontSize(getfontSize ?? 22),
        color: color,
      ),
      textAlign: textAlign,
    );
  }

  static Widget heading1Sub(String data,
      {double? getfontSize,
      Color? color,
      TextAlign? textAlign,
      FontWeight? fontWeight}) {
    return Text(
      data,
      style: TextStyle(
        fontWeight: fontWeight ?? FontWeight.w600,
        fontSize: getFontSize(getfontSize ?? 22),
        color: color,
      ),
      textAlign: textAlign,
    );
  }

  static Widget heading2(String data,
      {double? getfontSize,
      Color? color,
      FontWeight? fontWeight,
      TextAlign? textAlign,
      int? maxLines,
      TextOverflow? overflow}) {
    return Text(
      data,
      style: TextStyle(
        fontWeight: fontWeight ?? FontWeight.w500,
        fontSize: getFontSize(getfontSize ?? 20),
        color: color,
      ),
      textAlign: textAlign,
      maxLines: maxLines,
      overflow: overflow,
    );
  }

  static TextSpan heading2Span({
    String? text,
    double? getfontSize,
    Color? color,
    FontWeight? fontWeight,
    TextAlign? textAlign,
    int? maxLines,
    TextOverflow? overflow,
    List<InlineSpan>? children,
    GestureRecognizer? recognizer,
  }) {
    return TextSpan(
        text: text,
        style: TextStyle(
          fontWeight: fontWeight ?? FontWeight.w500,
          fontSize: getFontSize(getfontSize ?? 19),
          color: color,
          overflow: overflow,
        ),
        children: children,
        recognizer: recognizer);
  }

  static Widget heading3(
    String data, {
    TextAlign? textAlign,
    Color? color,
    double? getfontSize,
    FontWeight? fontWeight,
    int? maxLines,
    TextOverflow? overflow,
  }) {
    return Text(
      data,
      style: TextStyle(
        fontSize: getFontSize(getfontSize ?? 18),
        color: color,
        fontWeight: fontWeight ?? FontWeight.w500,
      ),
      textAlign: textAlign,
      maxLines: maxLines,
      overflow: overflow,
    );
  }

  static Widget heading4(String data,
      {TextAlign? textAlign,
      Color? color,
      FontWeight? fontWeight,
      double? getfontSize,
      int? maxLines,
      TextOverflow? overflow}) {
    return Text(
      data,
      style: TextStyle(
          fontSize: getFontSize(getfontSize ?? 16),
          color: color,
          fontWeight: fontWeight),
      textAlign: textAlign,
      overflow: overflow,
      maxLines: maxLines,
    );
  }

  static Widget paragraph(
    String data, {
    bool isUnderline = false,
    TextAlign? textAlign,
    Color? color,
    Color? decorationColor,
    FontWeight? fontWeight,
    int? maxLines,
    TextDecoration? decoration,
    double? getfontSize,
    double? height,
    TextOverflow? overflow,
  }) {
    return Text(
      data,
      style: TextStyle(
        fontSize: getFontSize(getfontSize ?? 15),
        decoration: isUnderline ? TextDecoration.underline : decoration,
        decorationColor: decorationColor ?? whiteColor,
        color: color,
        fontWeight: fontWeight,
        height: height ?? 1.3,
      ),
      maxLines: maxLines,
      textAlign: textAlign,
      overflow: overflow,
    );
  }

  static Widget smallParagraph(
    String data, {
    bool isUnderline = false,
    Color? color,
    int? maxLines,
    TextOverflow? overflow,
    double? getfontSize,
    TextDecoration? decoration,
    FontWeight? fontWeight,
    double? height,
  }) {
    return Text(
      data,
      style: TextStyle(
        fontSize: getFontSize(getfontSize ?? 13),
        decoration: isUnderline ? TextDecoration.underline : decoration,
        color: color,
        fontWeight: fontWeight,
        height: height,
      ),
      maxLines: maxLines ?? 2,
      overflow: overflow,
    );
  }
}
