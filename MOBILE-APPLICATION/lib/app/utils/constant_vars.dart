import 'dart:io';
import 'dart:ui';
// import 'package:geolocator/geolocator.dart';
import 'package:flutter_localization/flutter_localization.dart';
import 'package:image_picker/image_picker.dart';
import 'package:jazz_smart_pay/app/models/profile_response.dart';
import 'package:jazz_smart_pay/app/utils/index.dart';
import '../../app.dart';
import '../../exports.dart';
import '../custom_widget/app_divider.dart';
import '../custom_widget/app_text.dart';
import '../custom_widget/bouncing_button.dart';
import '../custom_widget/fill_container.dart';
import '../custom_widget/primary_button.dart';
import 'app_const_colors.dart';

class Constants {
  static Size? screenSize;
  static String appPackageName = 'com.app.package';
  static String fcmToken = 'test_token';
  static bool isIpad = false;
  static FlutterLocalization? localization;
  //

  static ProfileResponse? profileRes;
  static String walletBalance = '0';

  //
  static String productBuyId = '';
  static String latitude = "";
  static String longitude = "";
  static bool orderSuccess = false;
  static String googleMapKey = 'AIzaSyCPb6gUmpu4XcD_HrV6PO9DiSGHDBhzZsQ';
  static String noImage =
      'https://static.vecteezy.com/system/resources/thumbnails/010/039/910/small_2x/sticker-of-a-cartoon-no-symbol-vector.jpg';
  static Map? profile;
  static Color parseColorFromHex(String hexColor) {
    hexColor = hexColor.toUpperCase().replaceAll("#", "");
    if (hexColor.length == 6) {
      hexColor = "FF$hexColor"; // Add opacity if not provided
    }
    return Color(int.parse(hexColor, radix: 16));
  }

  static emptyPlaceHolder(
      {required String icon,
      required String title,
      required String description}) {
    return Center(
      child: Padding(
        padding: EdgeInsets.all(getWidth(30)!),
        child: Column(
          mainAxisSize: MainAxisSize.min,
          children: [
            Gap(getWidth(220)!),
            Image.asset(
              icon,
              scale: 1.4,
              color: bgColor,
            ),
            Gap(getWidth(20)!),
            AppText.heading2(title,
                color: bgColor, fontWeight: FontWeight.w700),
            Gap(getWidth(12)!),
            AppText.paragraph(
              description,
              color: buttonColor,
              textAlign: TextAlign.center,
              fontWeight: FontWeight.w500,
            ),
          ],
        ),
      ),
    );
  }

  static errorDialog({String? message}) {
    showDialog(
      context: Get.context!,
      builder: (BuildContext context) {
        return AlertDialog(
          backgroundColor: textColor,
          insetPadding: EdgeInsets.symmetric(horizontal: getWidth(16)!),
          shape: RoundedRectangleBorder(
            borderRadius:
                BorderRadius.circular(getWidth(20)!), // Rounded corners
          ),
          actionsPadding: EdgeInsets.zero,
          actions: [
            Padding(
              padding: EdgeInsets.all(getWidth(20)!),
              child: Column(
                mainAxisAlignment: MainAxisAlignment.spaceBetween,
                children: [
                  Gap(getWidth(20)!),
                  AppText.heading2(
                    "Error!",
                    color: bgColor,
                    getfontSize: 26,
                    fontWeight: FontWeight.w800,
                    textAlign: TextAlign.center,
                  ),
                  Gap(getWidth(12)!),
                  AppText.paragraph(
                    message ?? "Something went wrong.",
                    color: bgColor,
                    getfontSize: 17,
                    fontWeight: FontWeight.w600,
                    textAlign: TextAlign.center,
                  ),
                  Gap(getWidth(24)!),
                  PrimaryButton(
                    text: "Okay",
                    onTap: () {
                      // Prefs().removeToken();
                      // Constants.profile = null;
                      Get.close(1);
                    },
                  ),
                  Gap(getWidth(20)!),
                ],
              ),
            ),
          ],
        );
      },
    );
  }

  static Future<File?> pickImage({ImageSource? source}) async {
    final XFile? image =
        await ImagePicker().pickImage(source: source ?? ImageSource.camera);
    if (image == null) return null;
    return File(image.path);
  }

  static successDialog({String? message, void Function()? onTap}) {
    showDialog(
      context: Get.context!,
      builder: (BuildContext context) {
        return WillPopScope(
          onWillPop: () {
            return Future.value(false);
          },
          child: AlertDialog(
            backgroundColor: bgColor,
            insetPadding: EdgeInsets.symmetric(horizontal: getWidth(16)!),
            shape: RoundedRectangleBorder(
              borderRadius:
                  BorderRadius.circular(getWidth(20)!), // Rounded corners
            ),
            actionsPadding: EdgeInsets.zero,
            actions: [
              Padding(
                padding: EdgeInsets.all(getWidth(20)!),
                child: Column(
                  mainAxisAlignment: MainAxisAlignment.spaceBetween,
                  children: [
                    Gap(getWidth(20)!),
                    AppText.heading2(
                      "Success",
                      color: primaryColor,
                      getfontSize: 26,
                      fontWeight: FontWeight.w800,
                      textAlign: TextAlign.center,
                    ),
                    Gap(getWidth(12)!),
                    AppText.paragraph(
                      message ?? "Something went wrong.",
                      getfontSize: 17,
                      fontWeight: FontWeight.w600,
                      textAlign: TextAlign.center,
                    ),
                    Gap(getWidth(24)!),
                    PrimaryButton(
                      text: "Okay",
                      onTap: onTap ??
                          () {
                            Get.close(1);
                          },
                    ),
                    Gap(getWidth(20)!),
                  ],
                ),
              ),
            ],
          ),
        );
      },
    );
  }

  // static Future<void> getCurrentLocation() async {
  //   bool serviceEnabled = await Geolocator.isLocationServiceEnabled();
  //   if (!serviceEnabled) {
  //     return Future.error('Location services are disabled.');
  //   }
  //   LocationPermission permission = await Geolocator.checkPermission();
  //   if (permission == LocationPermission.denied) {
  //     permission = await Geolocator.requestPermission();
  //     if (permission == LocationPermission.denied) {
  //       return Future.error('Location permission denied');
  //     }
  //   }
  //   if (permission == LocationPermission.deniedForever) {
  //     return Future.error('Location permissions are permanently denied.');
  //   }
  //   Position position = await Geolocator.getCurrentPosition(
  //       desiredAccuracy: LocationAccuracy.high);

  //   latitude = position.latitude.toString();
  //   longitude = position.longitude.toString();
  // }

  static Future<dynamic> yesNoDialogRevise(
    BuildContext context, {
    String? contentText,
    String? titleText,
    void Function()? onTapYes,
    void Function()? onTapNo,
    String? confirmText,
    String? cancelText,
    double? buttonWidth,
    double? fontSizeGet,
    Color? colorOne,
    Color? colortwo,
    bool? isDismissable,
    bool showBothButton = true,
  }) {
    return showDialog(
      context: context,
      barrierDismissible: isDismissable ?? true,
      builder: (context) {
        return Dialog(
          backgroundColor: Colors.transparent,
          child: Stack(children: [
            ClipRRect(
              borderRadius: BorderRadius.circular(12.0),
              child: BackdropFilter(
                filter: ImageFilter.blur(sigmaX: 20.0, sigmaY: 20.0),
                child: Container(
                  decoration: BoxDecoration(
                    color: hintColor,
                    borderRadius: BorderRadius.circular(12.0),
                  ),
                  child: Column(
                    mainAxisSize: MainAxisSize.min,
                    crossAxisAlignment: CrossAxisAlignment.center,
                    children: [
                      Padding(
                        padding: const EdgeInsets.all(24.0),
                        child: Column(
                          children: [
                            if (titleText != null) ...[
                              AppText.heading2(
                                titleText,
                                fontWeight: FontWeight.w700,
                                textAlign: TextAlign.center,
                                maxLines: 50,
                                getfontSize: 20,
                              ),
                              Gap(getWidth(8)!),
                            ],
                            AppText.heading3(
                              contentText ?? "",
                              fontWeight: FontWeight.w500,
                              textAlign: TextAlign.center,
                              maxLines: 50,
                              getfontSize: fontSizeGet,
                            ),
                          ],
                        ),
                      ),
                      const Divider(),
                      IntrinsicHeight(
                        child: Row(
                          children: [
                            if (showBothButton)
                              Expanded(
                                child: GestureDetector(
                                  onTap: onTapNo ??
                                      () => Navigator.of(context).pop(),
                                  child: AbsorbPointer(
                                    child: Container(
                                      padding: const EdgeInsets.all(16.0),
                                      alignment: Alignment.center,
                                      child: AppText.heading3(
                                        cancelText ?? 'No', color: colorOne,
                                        // color: const Color(0xFFFF0000),
                                      ),
                                    ),
                                  ),
                                ),
                              ),
                            if (showBothButton)
                              VerticalDivider(
                                color: Colors.grey[200],
                                width: 2,
                              ),
                            Expanded(
                              child: GestureDetector(
                                onTap: onTapYes ??
                                    () => Navigator.of(context).pop(),
                                child: AbsorbPointer(
                                  child: Container(
                                    padding: const EdgeInsets.all(16.0),
                                    alignment: Alignment.center,
                                    child: AppText.heading3(
                                      confirmText ?? 'Yes',
                                      color: colortwo,
                                    ),
                                  ),
                                ),
                              ),
                            ),
                          ],
                        ),
                      ),
                    ],
                  ),
                ),
              ),
            ),
          ]),
        );
      },
    );
  }

  static Future<T?> bottomSheetWithHandle<T>(BuildContext context,
      {bool isScrollControlled = true,
      bool isDismissible = true,
      bool useSafeArea = false,
      bool enableDrag = true,
      required Widget Function(BuildContext) builder}) {
    return showModalBottomSheet<T>(
      context: context,
      elevation: 10,
      isScrollControlled: isScrollControlled,
      backgroundColor: hintColor,
      useSafeArea: useSafeArea,
      isDismissible: isDismissible,
      enableDrag: enableDrag,
      builder: builder,
    );
  }

  static updateLanguageBottomSheet(BuildContext context) {
    Constants.bottomSheetWithHandle(context, builder: (context) {
      return AnimatedContainer(
        duration: 100.milliseconds,
        padding: EdgeInsets.only(
          bottom: MediaQuery.of(context).viewInsets.bottom,
        ),
        decoration: const BoxDecoration(
          color: bgColor,
          borderRadius: BorderRadius.vertical(top: Radius.circular(24)),
          boxShadow: [BoxShadow(blurRadius: 8, color: Colors.black26)],
        ),
        child: ConstrainedBox(
          constraints: const BoxConstraints(maxHeight: 500),
          child: Padding(
            padding: EdgeInsets.symmetric(horizontal: getWidth(20)!),
            child: Column(
              mainAxisSize: MainAxisSize.min,
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                // Handle
                Center(
                  child: Container(
                    height: 4,
                    width: 40,
                    margin: const EdgeInsets.symmetric(vertical: 24),
                    decoration: BoxDecoration(
                      color: textColor,
                      borderRadius: BorderRadius.circular(40),
                    ),
                  ),
                ),

                // Title and search
                AppText.paragraph(
                  "Select the language to change",
                  color: textColor,
                  fontWeight: FontWeight.w500,
                  getfontSize: 18,
                ),
                Gap(getWidth(12)!),
                appDivider(color: textColor),
                Gap(getWidth(12)!),

                languageWidget(
                  lang: "English",
                  onTap: () {
                    localization?.translate('en');
                    RestartWidget.restartApp(context);
                  },
                  isSelected: localization?.currentLocale?.languageCode == 'en',
                ),
                languageWidget(
                  lang: "Arabic",
                  onTap: () {
                    localization?.translate('ar');
                    RestartWidget.restartApp(context);
                  },
                  isSelected: localization?.currentLocale?.languageCode == 'ar',
                ),
                Gap(getWidth(60)!),
              ],
            ),
          ),
        ),
      );
    });
  }

  static languageWidget({
    required String lang,
    required void Function() onTap,
    bool isSelected = false,
  }) {
    return Bouncing(
      onTap: onTap,
      child: FillContainer(
          borderRadius: 18,
          backgroundColor: textColor.withValues(alpha: .2),
          margin: EdgeInsets.only(bottom: getWidth(20)!),
          child: Row(
            children: [
              AppText.heading2(lang,
                  fontWeight: FontWeight.w600,
                  getfontSize: 16,
                  color: textColor),
              const Spacer(),
              if (isSelected)
                Image.asset(
                  ImagePath.tick,
                  color: textColor,
                  height: getWidth(20),
                  width: getWidth(20),
                )
            ],
          )),
    );
  }
}
