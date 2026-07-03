import 'package:flutter/material.dart';
import 'package:jazz_smart_pay/app/utils/app_const_colors.dart';

import '../utils/app_size.dart';
import 'app_text.dart';

class AppDropdownField extends StatelessWidget {
  final String? selectedValue;
  final List<String> items;
  final String? hintText;
  final Function(String?)? onChanged;
  final String? Function(String?)? validator;
  final EdgeInsetsGeometry? padding;
  final Color? borderColor;
  final Color? textStyleColor;
  final Color? hintTextColor;

  const AppDropdownField({
    super.key,
    required this.items,
    required this.selectedValue,
    this.hintText,
    this.onChanged,
    this.validator,
    this.padding,
    this.borderColor,
    this.textStyleColor,
    this.hintTextColor,
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

    return DropdownButtonFormField<String>(
      value: selectedValue,
      hint: AppText.paragraph(
        hintText ?? "",
        getfontSize: 17,
        fontWeight: FontWeight.w500,
        color: hintColor,
      ),
      validator: validator ??
          (value) {
            if (value == null || value.isEmpty) {
              return 'This field is required';
            }
            return null;
          },
      autovalidateMode: AutovalidateMode.onUserInteraction,
      onChanged: onChanged,
      iconEnabledColor: hintColor,
      decoration: InputDecoration(
        contentPadding: padding ??
            EdgeInsets.symmetric(
              horizontal: getWidth(20)!,
              vertical: getWidth(16)!,
            ),
        hintText: hintText ?? 'Select a value',
        hintStyle: TextStyle(
          fontSize: getFontSize(17),
          color: hintTextColor ?? hintColor,
          fontWeight: FontWeight.w500,
        ),
        filled: false,
        fillColor: fillColor,
        border: outlineBorder,
        enabledBorder: outlineBorder,
        focusedBorder: focusBorder,
        errorBorder: outlineBorder,
        focusedErrorBorder: focusBorder,
      ),
      style: TextStyle(
        fontSize: getFontSize(17),
        fontWeight: FontWeight.w500,
        color: textStyleColor ?? textColor,
      ),
      dropdownColor: buttonColor,
      items: items
          .map(
            (item) => DropdownMenuItem<String>(
              value: item,
              child: Padding(
                padding: EdgeInsets.symmetric(horizontal: getWidth(8)!),
                child: AppText.paragraph(
                  item,
                  getfontSize: 17,
                  fontWeight: FontWeight.w500,
                  color: textColor,
                ),
              ),
            ),
          )
          .toList(),
    );
  }
}
