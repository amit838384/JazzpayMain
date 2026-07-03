import 'package:flutter/material.dart';
import 'package:jazz_smart_pay/app/utils/app_const_colors.dart';
import '../custom_widget/app_text.dart';
import 'app_size.dart';

class CheckboxComponent extends StatelessWidget {
  final String? text;
  final bool isChecked;
  final void Function()? onTap;
  final MainAxisSize mainAxisSize;
  final double? fontSize;
  final double? iconSize;
  final FontWeight? fontWeight;

  const CheckboxComponent({
    super.key,
    this.text,
    this.isChecked = false,
    this.onTap,
    this.fontWeight,
    this.fontSize,
    this.iconSize,
    this.mainAxisSize = MainAxisSize.max,
  });

  @override
  Widget build(BuildContext context) {
    return GestureDetector(
      onTap: onTap,
      child: Row(
        mainAxisSize: mainAxisSize,
        children: [
          isChecked
              ? Icon(
                  Icons.check_box,
                  color: whiteColor,
                  size: getWidth(iconSize ?? 28),
                )
              : Icon(
                  Icons.check_box_outline_blank,
                  color: hintColor,
                  size: getWidth(iconSize ?? 28),
                ),
          SizedBox(
            width: getWidth(10),
          ),
          AppText.heading3(
            text ?? "Checkbox",
            getfontSize: fontSize ?? 14,
            color: hintColor,
            fontWeight: fontWeight ?? FontWeight.w600,
          ),
        ],
      ),
    );
  }
}
