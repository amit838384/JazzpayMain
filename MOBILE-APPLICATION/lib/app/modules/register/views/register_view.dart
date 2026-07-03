import 'package:flutter/material.dart';
import 'package:flutter_localization/flutter_localization.dart';
import 'package:jazz_smart_pay/app/custom_widget/app_text.dart';
import 'package:jazz_smart_pay/app/custom_widget/app_text_field.dart';
import 'package:jazz_smart_pay/app/custom_widget/primary_button.dart';
import 'package:jazz_smart_pay/app/custom_widget/tablet_padding_widget.dart';
import 'package:jazz_smart_pay/app/utils/app_const_colors.dart';
import 'package:jazz_smart_pay/app/utils/app_size.dart';
import 'package:jazz_smart_pay/app/utils/form_validation.dart';
import 'package:jazz_smart_pay/app/utils/image_path.dart';
import 'package:gap/gap.dart';
import 'package:get/get.dart';
import '../../../custom_widget/icon_button.dart';
import '../../../utils/constant_vars.dart';
import '../../support/support_view.dart';
import '../controllers/register_controller.dart';

class RegisterView extends GetView<RegisterController> {
  const RegisterView({super.key});
  @override
  Widget build(BuildContext context) {
    return GetBuilder<RegisterController>(builder: (logic) {
      return Scaffold(
          backgroundColor: bgColor,
          body: TabPadding(
            child: SafeArea(
              child: SingleChildScrollView(
                padding: EdgeInsets.all(getWidth(20)!),
                child: Form(
                  key: logic.registerFormKey,
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
                      _registerWidget(logic, context),
                    ],
                  ),
                ),
              ),
            ),
          ));
    });
  }

  _registerWidget(RegisterController logic, BuildContext context) {
    return Column(
      children: [
        AppText.heading2(
          'welcome'.getString(context),
          color: textColor,
          getfontSize: 32,
          fontWeight: FontWeight.w700,
        ),
        AppText.heading2(
          'sign_up_continue'.getString(context),
          color: textColor,
          getfontSize: 18,
        ),
        Gap(getWidth(32)!),
        AppTextField(
          controller: logic.registerName,
          hintText: 'name'.getString(context),
          validator: (value) => FormValidation.notEmptyValidator(value),
          textCapitalization: TextCapitalization.words,
        ),
        Gap(getWidth(20)!),
        AppTextField(
          controller: logic.registerPhone,
          hintText: 'phone_number'.getString(context),
          validator: (value) => FormValidation.phoneValidator(value),
          keyboardType: TextInputType.phone,
        ),
        Gap(getWidth(20)!),
        AppTextField(
          controller: logic.inviteCode,
          hintText: "invite_code".getString(context),
          validator: (value) => FormValidation.notEmptyValidator(value),
          keyboardType: TextInputType.text,
        ),
        Gap(getWidth(20)!),
        AppTextField(
          controller: logic.registerPassword,
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
              color: logic.registerPassword.text.trim().isNotEmpty
                  ? textColor
                  : hintColor,
              scale: 6,
            ),
          ),
        ),
        Gap(getWidth(40)!),
        PrimaryButton(
          text: "register".getString(context),
          onTap: () {
            logic.registerSubmit();
          },
          isLoading: logic.isLoading,
        ),
        Gap(getWidth(30)!),
        GestureDetector(
          onTap: () {
            Get.back();
          },
          child: Row(
            mainAxisAlignment: MainAxisAlignment.center,
            children: [
              AppText.heading1(
                "already_have_an_account".getString(context),
                getfontSize: 16,
                color: textColor,
                fontWeight: FontWeight.w700,
              ),
              AppText.heading1(
                "sign_in".getString(context),
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
              icon: ImagePath.scanQr,
              onTap: () {},
            ),
            Gap(getWidth(32)!),
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
        Gap(getWidth(140)!),
      ],
    );
  }
}
