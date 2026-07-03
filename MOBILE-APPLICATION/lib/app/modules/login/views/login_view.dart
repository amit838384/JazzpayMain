import 'package:flutter/material.dart';
import 'package:flutter_localization/flutter_localization.dart';
import 'package:jazz_smart_pay/app/calling.dart';
import 'package:jazz_smart_pay/app/custom_widget/app_text.dart';
import 'package:jazz_smart_pay/app/custom_widget/app_text_field.dart';
import 'package:jazz_smart_pay/app/custom_widget/primary_button.dart';
import 'package:jazz_smart_pay/app/custom_widget/tablet_padding_widget.dart';
import 'package:jazz_smart_pay/app/modules/support/support_view.dart';
import 'package:jazz_smart_pay/app/routes/app_pages.dart';
import 'package:jazz_smart_pay/app/utils/app_const_colors.dart';
import 'package:jazz_smart_pay/app/utils/form_validation.dart';
import 'package:gap/gap.dart';
import 'package:get/get.dart';
import '../../../custom_widget/icon_button.dart';
import '../../../utils/constant_vars.dart';
import '../../../utils/index.dart';
import '../controllers/login_controller.dart';

class LoginView extends GetView<LoginController> {
  const LoginView({super.key});
  @override
  Widget build(BuildContext context) {
    return GetBuilder<LoginController>(builder: (logic) {
      return Scaffold(
          backgroundColor: bgColor,
          body: TabPadding(
            child: SafeArea(
              child: SingleChildScrollView(
                padding: EdgeInsets.all(getWidth(30)!),
                child: Column(
                  children: [
                    Gap(getWidth(75)!),
                    ClipRRect(
                      borderRadius: BorderRadius.circular(getWidth(16)!),
                      child: Image.asset(
                        "assets/icons/app_icon1.jpg",
                        height: getWidth(200),
                        width: getWidth(200),
                      ),
                    ),
                    Gap(getWidth(30)!),
                    _loginWidget(context, logic),
                  ],
                ),
              ),
            ),
          ));
    });
  }

  _loginWidget(BuildContext context, LoginController logic) {
    return Form(
      key: logic.loginFormKey,
      child: Column(
        children: [
          AppText.heading2(
            'welcome_back'.getString(context),
            color: textColor,
            getfontSize: 32,
            fontWeight: FontWeight.w700,
          ),
          AppText.heading2(
            'sign_continue'.getString(context),
            color: textColor,
            getfontSize: 18,
          ),
          Gap(getWidth(32)!),
          AppTextField(
            controller: logic.loginPhone,
            hintText: 'phone_number'.getString(context),
            validator: (value) => FormValidation.phoneValidator(value),
            keyboardType: TextInputType.phone,
          ),
          Gap(getWidth(20)!),
          AppTextField(
            controller: logic.loginPassword,
            hintText: "password".getString(context),
            validator: (value) => FormValidation.notEmptyValidator(value),
            keyboardType: TextInputType.visiblePassword,
            onChanged: (p0) {
              logic.update();
            },
            isPassword: !logic.showPsss,
            suffixIcon: GestureDetector(
              onTap: () {
                logic.showPsss = !logic.showPsss;
                logic.update();
              },
              child: Image.asset(
                logic.showPsss == true ? ImagePath.viewOff : ImagePath.view,
                color: logic.loginPassword.text.trim().isNotEmpty
                    ? textColor
                    : hintColor,
                scale: 6,
              ),
            ),
          ),
          Gap(getWidth(16)!),
          Align(
            alignment: Alignment.centerRight,
            child: GestureDetector(
              onTap: () {
                Get.toNamed(Routes.FORGOT_PASSWORD);
              },
              child: AppText.heading1(
                "forgot_pass".getString(context),
                getfontSize: 16,
                color: textColor,
                fontWeight: FontWeight.w700,
              ),
            ),
          ),
          Gap(getWidth(40)!),
          PrimaryButton(
            text: "login".getString(context),
            onTap: () {

              /// agent_7101kf0vfz4nfrtr29wxasnv7ke2
             /// Get.to(()=> const VoiceCallScreen());
              logic.loginSubmit();
            },
            isLoading: logic.isLoading,
          ),
          Gap(getWidth(30)!),
          GestureDetector(
            onTap: () {
              Get.toNamed(Routes.REGISTER);
            },
            child: Row(
              mainAxisAlignment: MainAxisAlignment.center,
              children: [
                AppText.heading1("do_not_have_an_account".getString(context),
                    getfontSize: 16,
                    color: textColor,
                    fontWeight: FontWeight.w700),
                AppText.heading1(
                  "sign_Up".getString(context),
                  getfontSize: 16,
                  color: yelloColor,
                  fontWeight: FontWeight.w700,
                ),
              ],
            ),
          ),
          Gap(getWidth(30)!),
          Row(
            mainAxisAlignment: MainAxisAlignment.center,
            children: [
              CustomIconButton(
                icon: ImagePath.support,
                onTap: () {
                  Get.to(() => const SupportView());
                },
              ),
              Gap(getWidth(32)!),
              CustomIconButton(
                icon: ImagePath.language,
                onTap: () {
                  Constants.updateLanguageBottomSheet(context);
                },
              ),
            ],
          ),
        ],
      ),
    );
  }
}
