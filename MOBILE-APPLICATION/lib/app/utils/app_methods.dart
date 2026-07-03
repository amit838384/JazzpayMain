import 'package:jazz_smart_pay/app/custom_widget/app_text.dart';
import 'package:jazz_smart_pay/app/utils/app_const_colors.dart';
import 'package:jazz_smart_pay/app/utils/index.dart';

import '../../exports.dart';

class AppMethods {
  static void showCustomSnackbar(String message) {
    ScaffoldMessenger.of(Get.context!)
        .clearSnackBars(); // Optional: clear previous
    ScaffoldMessenger.of(Get.context!).showSnackBar(
      SnackBar(
        elevation: 0,
        backgroundColor: Colors.transparent, // So we can use a custom container
        behavior: SnackBarBehavior.floating,
        margin:
            EdgeInsets.fromLTRB(getWidth(16)!, 0, getWidth(16)!, getWidth(10)!),
        content: Container(
          padding: const EdgeInsets.all(12),
          decoration: BoxDecoration(
            color: Colors.white,
            borderRadius: BorderRadius.circular(12),
            border: Border.all(color: whiteColor),
          ),
          child: AppText.smallParagraph(message,
              color: bgColor, fontWeight: FontWeight.w600, getfontSize: 14),
        ),
        duration: 1.seconds,
      ),
    );
  }
}
