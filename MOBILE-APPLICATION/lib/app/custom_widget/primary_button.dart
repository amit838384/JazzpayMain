import 'package:flutter/cupertino.dart';
import 'package:flutter/material.dart';
import 'package:jazz_smart_pay/app/utils/app_const_colors.dart';
import 'package:jazz_smart_pay/app/utils/image_path.dart';
import 'package:gap/gap.dart';

import '../utils/app_size.dart';
import 'bouncing_button.dart';
import 'loading_circular_component.dart';

class PrimaryButton extends StatelessWidget {
  final String? text;
  final String? imageData;
  final void Function()? onTap;
  final double? width;
  final double? verticalPaddingGet;
  final double? getBorderRadius;
  final double? imageSize;
  final Color? color;
  final Color? imageColor;
  final Color? outlineColor;
  final Color? textColor;
  final Widget? leading;
  final bool isOutlined;
  final bool isDisabled;
  final bool isLoading;
  final bool isImage;
  final bool isStartImage;
  final Gradient? gradient;

  ///This is text size of button title and is wants getFontSize
  final double? textSize;

  PrimaryButton(
      {super.key,
      this.text,
      this.onTap,
      this.imageData,
      this.imageSize,
      this.imageColor,
      this.width,
      this.color,
      this.leading,
      this.isOutlined = false,
      this.outlineColor,
      this.textColor,
      this.isDisabled = false,
      this.isLoading = false,
      this.isImage = false,
      this.isStartImage = false,
      this.textSize,
      this.verticalPaddingGet,
      this.gradient,
      this.getBorderRadius});

  @override
  Widget build(BuildContext context) {
    return IntrinsicHeight(
      child: isDisabled ? _disabled(context) : _enabled(context),
    );
  }

  final double _borderRadius = getWidth(16)!;

  Widget _enabled(BuildContext context) {
    return Bouncing(
      onTap: isLoading ? () {} : onTap,
      child: Container(
        width: width ?? double.infinity,
        padding:
            EdgeInsets.symmetric(vertical: getWidth(verticalPaddingGet ?? 15)!),
        decoration: BoxDecoration(
          borderRadius: BorderRadius.circular(getBorderRadius ?? _borderRadius),
          color: isOutlined ? Colors.transparent : (color ?? buttonColor),
          border: Border.all(
            width: getWidth(1)!,
            color:
                isOutlined ? outlineColor ?? buttonColor : Colors.transparent,
          ),
        ),
        child: isLoading
            ? const LoadingCircularComponent(
                getSize: 13, indicatorColor: whiteColor)
            : Row(
                mainAxisAlignment: MainAxisAlignment.center,
                children: [
                  Text(
                    text ?? "",
                    textAlign: TextAlign.center,
                    style: TextStyle(
                      color: textColor ?? whiteColor,
                      fontSize: getFontSize(textSize ?? 18),
                      fontWeight: FontWeight.w700,
                    ),
                  ),
                  if (isImage == true) ...[
                    Gap(getWidth(16)!),
                    Image.asset(
                      imageData ?? ImagePath.shopBag,
                      height: imageSize ?? getWidth(20),
                      width: imageSize ?? getWidth(20),
                      color: imageColor ?? whiteColor,
                    )
                  ]
                ],
              ),
      ),
    );
  }

  Widget _disabled(BuildContext context) {
    return Container(
      width: width ?? double.infinity,
      decoration: BoxDecoration(
        borderRadius: BorderRadius.circular(getBorderRadius ?? _borderRadius),
        color: hintColor,
      ),
      child: Container(
        width: width ?? double.infinity,
        padding:
            EdgeInsets.symmetric(vertical: getWidth(verticalPaddingGet ?? 15)!),
        decoration: BoxDecoration(
          borderRadius: BorderRadius.circular(getBorderRadius ?? _borderRadius),
          color: blackColor.withValues(alpha: .1),
        ),
        child: Text(
          text ?? "",
          textAlign: TextAlign.center,
          style: TextStyle(
            color: whiteColor,
            fontWeight: FontWeight.w600,
            fontSize: getFontSize(textSize ?? 18),
          ),
        ),
      ),
    );
  }
}
