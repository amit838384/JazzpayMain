import 'package:flutter/services.dart';
import 'package:jazz_smart_pay/app/utils/app_const_colors.dart';
import '../../exports.dart';
import '../utils/app_size.dart';

class AppNewTextAreaRevised extends StatelessWidget {
  final String? hintText;
  final TextEditingController? controller;
  final List<TextInputFormatter>? inputFormatters;
  final MaxLengthEnforcement? maxLengthEnforcement;
  final int? maxLength;
  final bool? filled;
  final double? hintSize;
  final Color? hintColor;
  final double? borderRadius;
  final void Function(String)? onChanged;
  final TextCapitalization textCapitalization;
  final String? Function(String?)? validator;
  final int? maxLines;
  final int? minLines;
  final bool isOutlined;
  final Color? fillColor;
  final bool readOnly;
  const AppNewTextAreaRevised({
    super.key,
    this.hintText,
    this.controller,
    this.inputFormatters,
    this.maxLengthEnforcement,
    this.maxLength,
    this.borderRadius,
    this.hintSize,
    this.hintColor,
    this.filled,
    this.onChanged,
    this.textCapitalization = TextCapitalization.sentences,
    this.validator,
    this.maxLines,
    this.minLines,
    this.isOutlined = true,
    this.fillColor,
    this.readOnly = false,
  });

  @override
  Widget build(BuildContext context) {
    final outlineBorder = OutlineInputBorder(
      borderSide: BorderSide(color: textColor, width: 1),
      borderRadius: BorderRadius.circular(getWidth(16)!),
    );
    final focusBorder = OutlineInputBorder(
      borderSide: BorderSide(color: textColor, width: 1.5),
      borderRadius: BorderRadius.circular(getWidth(16)!),
    );
    return ClipRRect(
      borderRadius: BorderRadius.circular(borderRadius ?? getWidth(16)!),
      child: TextFormField(
        autovalidateMode: AutovalidateMode.onUserInteraction,
        autocorrect: true,
        maxLengthEnforcement: maxLengthEnforcement,
        controller: controller,
        maxLines: maxLines ?? 5,
        minLines: minLines,
        validator: validator,
        readOnly: readOnly,
        cursorColor: textColor,
        decoration: InputDecoration(
          hintText: hintText ?? "",
          counterStyle: Get.textTheme.titleSmall
              ?.copyWith(fontSize: getWidth(14), color: hintColor),
          isDense: true,
          contentPadding: EdgeInsets.all(
            getWidth(16)!,
          ),
          filled: true,
          fillColor: hintColor!.withValues(alpha: .1),
          hintStyle: TextStyle(
            fontSize: hintSize ?? getFontSize(17),
            color: hintColor,
            fontWeight: FontWeight.w500,
            height: 1.2,
          ),
          border: outlineBorder,
          enabledBorder: outlineBorder,
          errorBorder: outlineBorder,
          focusedBorder: focusBorder,
          focusedErrorBorder: focusBorder,
          disabledBorder: outlineBorder,
        ),
        style: TextStyle(
          fontSize: getFontSize(17),
          fontWeight: FontWeight.w500,
          color: textColor,
        ),
        textAlign: TextAlign.start,
        inputFormatters: inputFormatters,
        maxLength: maxLength,
        onChanged: onChanged,
        textCapitalization: textCapitalization,
      ),
    );
  }
}
