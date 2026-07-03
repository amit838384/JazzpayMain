import 'dart:io';

import 'package:jazz_smart_pay/app/custom_widget/app_text.dart';
import 'package:jazz_smart_pay/app/custom_widget/bouncing_button.dart';
import 'package:jazz_smart_pay/app/utils/app_const_colors.dart';
import 'package:jazz_smart_pay/app/utils/app_size.dart';
import 'package:jazz_smart_pay/exports.dart';

class AppBottomSheet {
  static imagePickerBottomSheet(BuildContext context,
      {void Function()? onCameraTap, void Function()? onGalleryTap}) {
    return showModalBottomSheet(
      context: context,
      barrierColor: Colors.transparent,
      elevation: 10,
      builder: (context) => AnimatedContainer(
        decoration: BoxDecoration(
          color: bgColor,
          borderRadius: const BorderRadius.vertical(top: Radius.circular(24)),
          boxShadow: const [BoxShadow(blurRadius: 8, color: Colors.black26)],
        ),
        duration: 300.milliseconds,
        child: SafeArea(
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            mainAxisSize: MainAxisSize.min,
            children: [
              const Gap(24),
              Center(
                child: Container(
                  height: 4,
                  width: 40,
                  decoration: BoxDecoration(
                    color: const Color(0xFFDDDDDD),
                    borderRadius: BorderRadius.circular(40),
                  ),
                ),
              ),
              const Gap(24),
              Bouncing(
                onTap: onCameraTap,
                child: Container(
                  margin: EdgeInsets.symmetric(horizontal: getWidth(20)!),
                  padding: EdgeInsets.symmetric(
                      horizontal: getWidth(20)!, vertical: getWidth(16)!),
                  decoration: BoxDecoration(
                      borderRadius: BorderRadius.circular(getWidth(12)!),
                      border:
                          Border.all(width: getWidth(1.5)!, color: textColor)),
                  child: Row(
                    children: [
                      Icon(
                        Icons.camera,
                        size: getWidth(20),
                        color: textColor,
                      ),
                      Gap(getWidth(16)!),
                      AppText.heading2("Take Picture",
                          color: textColor,
                          fontWeight: FontWeight.w500,
                          getfontSize: 16)
                    ],
                  ),
                ),
              ),
              Gap(getWidth(20)!),
              Bouncing(
                onTap: onGalleryTap,
                child: Container(
                  margin: EdgeInsets.symmetric(horizontal: getWidth(20)!),
                  padding: EdgeInsets.symmetric(
                      horizontal: getWidth(20)!, vertical: getWidth(16)!),
                  decoration: BoxDecoration(
                      borderRadius: BorderRadius.circular(getWidth(12)!),
                      border:
                          Border.all(width: getWidth(1.5)!, color: textColor)),
                  child: Row(
                    children: [
                      Icon(
                        Icons.image,
                        size: getWidth(20),
                        color: textColor,
                      ),
                      Gap(getWidth(16)!),
                      AppText.heading2(
                        "Open gallery",
                        color: textColor,
                        fontWeight: FontWeight.w500,
                        getfontSize: 16,
                      )
                    ],
                  ),
                ),
              ),
              // Container(
              //     margin: EdgeInsets.symmetric(horizontal: getWidth(20)!),
              //   padding: EdgeInsets.symmetric(
              //       horizontal: getWidth(20)!, vertical: getWidth(12)!),
              //   decoration: BoxDecoration(
              //       border:
              //           Border.all(width: getWidth(1.5)!, color: primaryColor)),
              //   child: Row(
              //     children: [
              //       Icon(
              //         Icons.camera,
              //         size: getWidth(24),
              //         color: primaryColor,
              //       ),
              //       Gap(getWidth(16)!),
              //       AppText.heading2(
              //         "Upload File",
              //         color: primaryColor,
              //         fontWeight: FontWeight.w500,
              //       )
              //     ],
              //   ),
              // ),
              if (Platform.isAndroid) Gap(getWidth(30)!),
            ],
          ),
        ),
      ),
    );
  }
}
