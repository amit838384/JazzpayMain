// Flutter imports:
import 'package:flutter/services.dart';
import 'package:jazz_smart_pay/app/utils/app_const_colors.dart';
// Package imports:
import 'package:jazz_smart_pay/app/utils/index.dart';
import 'package:jazz_smart_pay/exports.dart';
import '../utils/constant_vars.dart';
import 'app_text.dart';

// Project imports:
AppBar myAppBar({
  String? title,
  bool visibleBack = true,
  List<Widget>? actions,
  Color? contentColor,
  Color? color,
  SystemUiOverlayStyle? systemOverlayStyle,
  void Function()? backTap,
  bool centerTitle = false,
}) {
  return AppBar(
    iconTheme: const IconThemeData(color: whiteColor),
    automaticallyImplyLeading: false,
    // title: Row(
    //   children: [
    //     if (visibleBack) ...[
    //       GestureDetector(
    //         behavior: HitTestBehavior.translucent,
    //         onTap: () {
    //           if (backTap == null) {
    //             Get.back();
    //           } else {
    //             backTap.call();
    //           }
    //         },
    //         child: Image.asset(
    //           Constants.localization!.currentLocale?.languageCode == "en"
    //               ? ImagePath.backArrow
    //               : ImagePath.backArrow01,
    //           scale: 4.7,
    //           color: contentColor ?? whiteColor,
    //         ),
    //       ),
    //       Gap(getWidth(20)!)
    //     ],
    //     AppText.heading2(
    //       title ?? "",
    //       color: contentColor ?? whiteColor,
    //       fontWeight: FontWeight.w700,
    //       getfontSize: 20,
    //       maxLines: 1,
    //       overflow: TextOverflow.ellipsis,
    //     ),
    //   ],
    // ),
    leading: visibleBack
        ? GestureDetector(
      behavior: HitTestBehavior.translucent,
      onTap: () {
        if (backTap == null) {
          Get.back();
        } else {
          backTap.call();
        }
      },
      child: Padding(
        padding: const EdgeInsets.symmetric(horizontal: 12),
        child: Image.asset(
          Constants.localization!.currentLocale?.languageCode == "en"
              ? ImagePath.backArrow
              : ImagePath.backArrow01,
          scale: 4.7,
          color: contentColor ?? whiteColor,
        ),
      ),
    )
        : null,
    title: AppText.heading2(
      title ?? "",
      color: contentColor ?? whiteColor,
      fontWeight: FontWeight.w700,
      getfontSize: 20,
      maxLines: 1,
      overflow: TextOverflow.ellipsis,
    ),
    centerTitle: centerTitle,
    actions: actions,
    // systemOverlayStyle: systemOverlayStyle,
    backgroundColor: color ?? bgColor,
    surfaceTintColor: color ?? bgColor,
  );
}
