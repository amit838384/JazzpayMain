import 'package:flutter/material.dart';
import 'package:flutter/services.dart';
import 'package:get/get_connect/http/src/utils/utils.dart';
import 'package:jazz_smart_pay/app/utils/app_const_colors.dart';

import '../utils/app_size.dart';

class AppTextField extends StatelessWidget {
  final TextEditingController? controller;
  final String? hintText;
  // final double? hintFontSize;
  final double? textFontSize;
  final double? fieldRadius;
  final Function(String)? onChanged;
  final String? helperText;
  final Widget? suffixIcon;
  final bool readOnly;
  final void Function()? onTap;
  final String? Function(String?)? validator;
  final TextInputType? keyboardType;
  final bool isPassword;
  final List<TextInputFormatter>? inputFormatters;
  final String? initialValue;
  final String? prefixText;
  final String? suffixText;
  final void Function()? onSuffixTap;
  final MaxLengthEnforcement? maxLengthEnforcement;
  final int? maxLength;
  final Color? borderColor;
  final Color? textStyleColor;
  final Color? hintTextColor;
  final TextCapitalization textCapitalization;
  final TextAlign textAlign;
  final FocusNode? focusNode;
  final Iterable<String>? autofillHints;
  final bool autofocus;
  final Widget? prefixWidget;
  final EdgeInsetsGeometry? contentPadding;
  final bool? showBorder;
  final void Function(String)? onFieldSubmitted;
  final TextInputAction? textInputAction;
  final Widget? suffix;
  const AppTextField({
    super.key,
    this.controller,
    this.hintText,
    this.onChanged,
    this.suffixIcon,
    this.readOnly = false,
    this.onTap,
    this.onFieldSubmitted,
    this.validator,
    this.keyboardType,
    this.isPassword = false,
    this.inputFormatters,
    this.initialValue,
    this.prefixText,
    this.suffixText,
    this.onSuffixTap,
    this.maxLengthEnforcement,
    this.maxLength,
    this.borderColor,
    this.textFontSize,
    this.helperText,
    this.textCapitalization = TextCapitalization.none,
    this.textAlign = TextAlign.start,
    this.focusNode,
    this.autofillHints,
    this.autofocus = false,
    this.showBorder = false,
    this.prefixWidget,
    this.contentPadding,
    this.fieldRadius,
    this.suffix,
    this.textInputAction,
    this.hintTextColor,
    this.textStyleColor,
  });

  @override
  Widget build(BuildContext context) {
    final outlineBorder = OutlineInputBorder(
      borderSide: BorderSide(color: borderColor ?? textColor, width: 1),
      borderRadius: BorderRadius.circular(getWidth(16)!),
    );
    final focusBorder = OutlineInputBorder(
      borderSide: BorderSide(color: borderColor ?? textColor, width: 1.5),
      borderRadius: BorderRadius.circular(getWidth(16)!),
    );
    return TextFormField(
      autovalidateMode: AutovalidateMode.onUserInteraction,
      autocorrect: true,
      autofocus: autofocus,
      autofillHints: autofillHints,
      focusNode: focusNode,
      textAlign: textAlign,
      textInputAction: textInputAction ?? TextInputAction.next,
      textCapitalization: textCapitalization,
      maxLengthEnforcement: maxLengthEnforcement,
      maxLength: maxLength,
      initialValue: initialValue,
      obscureText: isPassword,
      readOnly: readOnly,
      controller: controller,
      validator: validator,
      enableInteractiveSelection: true,
      enableSuggestions: true,
      style: TextStyle(
        fontSize: getFontSize(17),
        fontWeight: FontWeight.w500,
        color: textStyleColor ?? textColor,
      ),
      decoration: InputDecoration(
        errorStyle: TextStyle(
          fontSize: getFontSize(15),
          fontWeight: FontWeight.w500,
          color: textColor,
        ),
        hintText: hintText,
        hintStyle: TextStyle(
          fontSize: getFontSize(17),
          color: hintTextColor ?? hintColor,
          fontWeight: FontWeight.w500,
        ),
        suffix: suffix,
        suffixIcon: suffixIcon,
        suffixText: suffixText,
        prefixText: prefixText,
        prefixIcon: prefixWidget,
        filled: false,
        fillColor: fillColor,
        prefixStyle: TextStyle(fontSize: getFontSize(17)),
        border: outlineBorder,
        enabledBorder: outlineBorder,
        errorBorder: outlineBorder,
        focusedBorder: focusBorder,
        focusedErrorBorder: focusBorder,
        disabledBorder: outlineBorder,
        contentPadding: contentPadding ??
            EdgeInsets.symmetric(
              horizontal: getWidth(20)!,
              vertical: getWidth(16)!,
            ),
        helperText: helperText,
        helperMaxLines: 3,
      ),
      cursorHeight: getWidth(25),
      cursorColor: borderColor ?? textColor,
      cursorErrorColor: borderColor ?? textColor,
      onChanged: onChanged,
      onTap: onTap,
      onFieldSubmitted: onFieldSubmitted,
      keyboardType: keyboardType,
      inputFormatters: inputFormatters,
    );
  }
}
