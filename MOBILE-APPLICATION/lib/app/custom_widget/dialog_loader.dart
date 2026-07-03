import 'package:flutter/cupertino.dart';
import 'package:jazz_smart_pay/app/utils/app_const_colors.dart';
import '../../exports.dart';
import '../utils/index.dart';
import 'app_text.dart';

class LoadingBuilder {
  LoadingBuilder();

  static void showLoadingIndicator({BuildContext? context}) {
    showDialog(
      context: context ?? Get.overlayContext!,
      barrierDismissible: false,
      builder: (context) {
        return const PopScope(
          canPop: false,
          child: AlertDialog(
            surfaceTintColor: Colors.transparent,
            backgroundColor: Colors.transparent,
            content: _LoadingIndicator(),
          ),
        );
      },
    );
  }

  static void hideOpenDialog() => Get.close(1);
}

class _LoadingIndicator extends StatelessWidget {
  const _LoadingIndicator();

  @override
  Widget build(BuildContext context) {
    return Center(
      child: Container(
        height: getWidth(180),
        width: getWidth(180),
        decoration: BoxDecoration(
          color: buttonColor.withValues(alpha: 0.8),
          borderRadius: BorderRadius.circular(16),
        ),
        child: Column(
          mainAxisSize: MainAxisSize.min,
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            const CupertinoActivityIndicator(radius: 12, color: whiteColor),
            const Gap(24),
            AppText.heading3(
              'Loading...',
              color: whiteColor,
              fontWeight: FontWeight.w500,
              getfontSize: 18,
            )
          ],
        ),
      ),
    );
  }
}
