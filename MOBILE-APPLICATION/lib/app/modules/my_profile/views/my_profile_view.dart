import 'package:flutter/material.dart';
import 'package:flutter_localization/flutter_localization.dart';
import 'package:gap/gap.dart';
import 'package:get/get.dart';
import 'package:jazz_smart_pay/app/custom_widget/my_app_bar.dart';
import 'package:jazz_smart_pay/app/utils/app_const_colors.dart';
import 'package:jazz_smart_pay/app/utils/constant_vars.dart';
import '../../../custom_widget/app_divider.dart';
import '../../../custom_widget/app_text.dart';
import '../../../custom_widget/bouncing_button.dart';
import '../../../custom_widget/fill_container.dart';
import '../../../custom_widget/loading_circular_component.dart';
import '../../../custom_widget/profile_image_circle.dart';
import '../../../utils/app_size.dart';
import '../../../utils/image_path.dart';
import '../controllers/my_profile_controller.dart';

class MyProfileView extends GetView<MyProfileLogic> {
  const MyProfileView({super.key});
  @override
  Widget build(BuildContext context) {
    return GetBuilder<MyProfileLogic>(
        init: MyProfileLogic(),
        builder: (logic) {
          return GestureDetector(
            onTap: () => FocusScope.of(context).unfocus(),
            child: Scaffold(
                appBar: myAppBar(
                    title: "my_profile".getString(context),
                    color: textColor,
                    contentColor: bgColor),
                backgroundColor: textColor,
                body: logic.isLoading
                    ? const Center(
                        child: LoadingCircularComponent(
                        indicatorColor: bgColor,
                      ))
                    : SingleChildScrollView(
                        padding:
                            EdgeInsets.symmetric(horizontal: getWidth(20)!),
                        child: Column(
                          children: [
                            _headerWidget(logic),
                            Gap(getWidth(30)!),
                            appDivider(
                                thickness: getWidth(4), color: lightBgColor),
                            Gap(getWidth(30)!),
                            FillContainer(
                              width: width(),
                              padding: EdgeInsets.symmetric(
                                  horizontal: getWidth(20)!,
                                  vertical: getWidth(16)!),
                              margin: EdgeInsets.only(bottom: getWidth(20)!),
                              child: Column(
                                crossAxisAlignment: CrossAxisAlignment.start,
                                children: [
                                  AppText.paragraph(
                                    "phone_number".getString(context),
                                    fontWeight: FontWeight.w700,
                                    color: bgColor,
                                  ),
                                  Gap(getWidth(6)!),
                                  AppText.paragraph(
                                    Constants.profileRes?.mobile ?? "",
                                    fontWeight: FontWeight.w600,
                                    getfontSize: 15,
                                  )
                                ],
                              ),
                            ),
                            FillContainer(
                              width: width(),
                              padding: EdgeInsets.symmetric(
                                  horizontal: getWidth(20)!,
                                  vertical: getWidth(16)!),
                              margin: EdgeInsets.only(bottom: getWidth(20)!),
                              child: Column(
                                crossAxisAlignment: CrossAxisAlignment.start,
                                children: [
                                  Row(
                                    children: [
                                      AppText.paragraph(
                                        "email".getString(context),
                                        fontWeight: FontWeight.w700,
                                        color: bgColor,
                                      ),
                                      const Spacer(),
                                      Bouncing(
                                        onTap: () {
                                          logic.bottomSheetWithHandle(context);
                                        },
                                        child: Image.asset(
                                          ImagePath.edit,
                                          height: getWidth(20),
                                          width: getWidth(20),
                                          color: bgColor,
                                        ),
                                      )
                                    ],
                                  ),
                                  Gap(getWidth(6)!),
                                  AppText.paragraph(
                                    Constants.profileRes?.email ?? "",
                                    fontWeight: FontWeight.w600,
                                    getfontSize: 15,
                                  )
                                ],
                              ),
                            ),
                          ],
                        ),
                      )),
          );
        });
  }

  _headerWidget(MyProfileLogic logic) {
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
