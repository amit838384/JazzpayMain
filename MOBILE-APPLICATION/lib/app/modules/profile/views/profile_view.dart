import 'package:flutter/material.dart';
import 'package:jazz_smart_pay/app/custom_widget/app_divider.dart';
import 'package:jazz_smart_pay/app/custom_widget/app_text.dart';
import 'package:jazz_smart_pay/app/custom_widget/profile_image_circle.dart';
import 'package:gap/gap.dart';
import 'package:get/get.dart';
import 'package:jazz_smart_pay/app/custom_widget/tablet_padding_widget.dart';
import 'package:jazz_smart_pay/app/modules/support/support_view.dart';
import 'package:jazz_smart_pay/app/utils/constant_vars.dart';
import 'package:jazz_smart_pay/app/utils/index.dart';
import '../../../custom_widget/bouncing_button.dart';
import '../../../custom_widget/fill_container.dart';
import '../../../custom_widget/loading_circular_component.dart';
import '../../../routes/app_pages.dart';
import '../../../utils/app_const_colors.dart';
import '../controllers/profile_controller.dart';

class ProfileView extends GetView<ProfileController> {
  const ProfileView({super.key});
  @override
  Widget build(BuildContext context) {
    return GetBuilder<ProfileController>(
        init: ProfileController(),
        builder: (logic) {
          return Scaffold(
              backgroundColor: textColor,
              body: logic.isLoading == true
                  ? const Center(
                      child: LoadingCircularComponent(
                      indicatorColor: bgColor,
                    ))
                  : TabPadding(
                      child: SafeArea(
                        child: SingleChildScrollView(
                          padding: EdgeInsets.all(getWidth(20)!),
                          child: Column(
                            crossAxisAlignment: CrossAxisAlignment.start,
                            children: [
                              Gap(getWidth(20)!),
                              _headerWidget(logic),
                              Gap(getWidth(30)!),
                              appDivider(
                                  thickness: getWidth(4), color: lightBgColor),
                              Gap(getWidth(30)!),
                              Column(
                                children: List.generate(
                                  logic.tileSettins.length,
                                  (index) {
                                    var tile = logic.tileSettins[index];
                                    return Bouncing(
                                      onTap: () {
                                        if (index == 0) {
                                          Get.toNamed(Routes.MY_PROFILE);
                                        }
                                        if (index == 1) {
                                          Constants.updateLanguageBottomSheet(
                                              context);
                                        }
                                        if (index == 2) {
                                          Get.to(() => const SupportView());
                                        }
                                        if (index == 3) {
                                          Get.toNamed(Routes.FEEDBACK);
                                        }
                                        if (index == 4) {}
                                        if (index == 5) {
                                          logic.bottomSheetWithHandle(context);
                                        }
                                        if (index == 6) {
                                          Constants.yesNoDialogRevise(
                                            context,
                                            contentText:
                                                "Are you sure you want to\nlog out?",
                                            confirmText: "Cancel",
                                            cancelText: "Log out",
                                            colorOne: buttonColor,
                                            onTapNo: () {
                                              Prefs().removeToken();
                                              Constants.profileRes = null;
                                              Get.offAllNamed(Routes.LOGIN);
                                            },
                                            onTapYes: () => Get.back(),
                                          );
                                        }
                                      },
                                      child: FillContainer(
                                        padding: EdgeInsets.symmetric(
                                            horizontal: getWidth(20)!,
                                            vertical: getWidth(16)!),
                                        margin: EdgeInsets.only(
                                            bottom: getWidth(20)!),
                                        child: Row(
                                          children: [
                                            Image.asset(
                                              tile.icon,
                                              height: getWidth(28),
                                              width: getWidth(28),
                                              color: index > 4
                                                  ? buttonColor
                                                  : bgColor,
                                            ),
                                            Gap(getWidth(16)!),
                                            AppText.paragraph(
                                              tile.name,
                                              fontWeight: FontWeight.w600,
                                              getfontSize: 18,
                                              color: index > 4
                                                  ? buttonColor
                                                  : bgColor,
                                            ),
                                            const Spacer(),
                                            Image.asset(
                                              Constants
                                                          .localization!
                                                          .currentLocale
                                                          ?.languageCode ==
                                                      "en"
                                                  ? ImagePath.arrowRight
                                                  : ImagePath.arrowLeft,
                                              height: getWidth(28),
                                              width: getWidth(28),
                                              color: index > 4
                                                  ? buttonColor
                                                  : bgColor,
                                            ),
                                          ],
                                        ),
                                      ),
                                    );
                                  },
                                ),
                              ),
                            ],
                          ),
                        ),
                      ),
                    ));
        });
  }

  _headerWidget(ProfileController logic) {
    return GestureDetector(
      onTap: () {
        // Get.toNamed(Routes.PROFILE);
      },
      child: FillContainer(
        child: Row(
          children: [
            profileImageCircle(
              imageUrl: "",
              name: Constants.profileRes?.name,
              circleSize: 60,
            ),
            Gap(getWidth(16)!),
            Expanded(
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  AppText.heading2(Constants.profileRes?.name ?? "",
                      getfontSize: 18, fontWeight: FontWeight.w700),
                  AppText.paragraph(
                    Constants.profileRes?.schoolName ?? "",
                    fontWeight: FontWeight.w500,
                  )
                ],
              ),
            ),
            Gap(getWidth(16)!),
          ],
        ),
      ),
    );
  }
}
