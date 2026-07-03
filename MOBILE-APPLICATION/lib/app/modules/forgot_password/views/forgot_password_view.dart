import 'package:flutter/material.dart';
import 'package:flutter_localization/flutter_localization.dart';
import 'package:gap/gap.dart';
import 'package:get/get.dart';
import 'package:jazz_smart_pay/app/custom_widget/bouncing_button.dart';
import 'package:jazz_smart_pay/app/custom_widget/loading_circular_component.dart';
import 'package:jazz_smart_pay/app/custom_widget/my_app_bar.dart';
import 'package:jazz_smart_pay/app/custom_widget/tablet_padding_widget.dart';
import 'package:jazz_smart_pay/app/utils/form_validation.dart';
import '../../../custom_widget/app_text.dart';
import '../../../custom_widget/app_text_field.dart';
import '../../../custom_widget/primary_button.dart';
import '../../../utils/app_const_colors.dart';
import '../../../utils/app_size.dart';
import '../../../utils/image_path.dart';
import '../controllers/forgot_password_controller.dart';

class ForgotPasswordView extends GetView<ForgotPasswordController> {
  const ForgotPasswordView({super.key});
  @override
  Widget build(BuildContext context) {
    return GetBuilder<ForgotPasswordController>(builder: (logic) {
      return Scaffold(
          backgroundColor: bgColor,
          appBar: myAppBar(),
          body: logic.isLoading || logic.verifyLoading
              ? const Center(child: LoadingCircularComponent())
              : TabPadding(
                  child: SafeArea(
                    child: Form(
                      key: logic.forgotFormKey,
                      child: SingleChildScrollView(
                        padding: EdgeInsets.all(getWidth(20)!),
                        child: Column(
                          children: [
                            Gap(getWidth(45)!),
                            ClipRRect(
                              borderRadius:
                                  BorderRadius.circular(getWidth(16)!),
                              child: Image.asset(
                                "assets/icons/app_icon1.jpg",
                                height: getWidth(200),
                                width: getWidth(200),
                              ),
                            ),
                            Gap(getWidth(30)!),
                            if (logic.type == "0") _emailWidget(context, logic),
                            if (logic.type == "1") _otpWidget(context, logic),
                          ],
                        ),
                      ),
                    ),
                  ),
                ));
    });
  }

  _emailWidget(BuildContext context, ForgotPasswordController logic) {
    return Column(
      children: [
        AppText.heading2(
          "forgot_password".getString(context),
          color: textColor,
          getfontSize: 28,
        ),
        Gap(getWidth(10)!),
        AppText.heading2(
          "forgot_password_text".getString(context),
          color: textColor,
          getfontSize: 16,
          textAlign: TextAlign.center,
        ),
        Gap(getWidth(40)!),
        AppTextField(
          controller: logic.emailController,
          hintText: "email".getString(context),
          validator: (value) => FormValidation.emailValidator(value),
          keyboardType: TextInputType.phone,
        ),
        Gap(getWidth(40)!),
        PrimaryButton(
          text: "send_otp".getString(context),
          onTap: () {
            logic.forgotPasswordSubmit();
          },
        ),
      ],
    );
  }

  _otpWidget(BuildContext context, ForgotPasswordController logic) {
    return Column(
      children: [
        AppText.heading2(
          "enter_otp".getString(context),
          color: textColor,
          getfontSize: 28,
        ),
        Gap(getWidth(10)!),
        AppText.heading2(
          "enter_otp_text".getString(context),
          color: textColor,
          getfontSize: 16,
          textAlign: TextAlign.center,
        ),
        Gap(getWidth(40)!),
        AppTextField(
          controller: logic.otpController,
          hintText: "enter_otp_here".getString(context),
          keyboardType: TextInputType.number,
          validator: (value) => FormValidation.otpValidator(value),
        ),
        Gap(getWidth(20)!),
        AppTextField(
          controller: logic.passController,
          hintText: "new_pass".getString(context),
          validator: (value) => FormValidation.notEmptyValidator(value),
          isPassword: !logic.showPsss,
          suffixIcon: GestureDetector(
            onTap: () {
              logic.showPsss = !logic.showPsss;
              logic.update();
            },
            child: Image.asset(
              logic.showPsss == true ? ImagePath.viewOff : ImagePath.view,
              color: logic.passController.text.trim().isNotEmpty
                  ? textColor
                  : hintColor,
              scale: 6,
            ),
          ),
        ),
        Gap(getWidth(20)!),
        AppTextField(
          controller: logic.confirmPassController,
          hintText: "confirm_pass".getString(context),
          validator: (value) => FormValidation.confirmPasswordValidator(
              value, logic.passController.text.trim()),
          isPassword: !logic.showConfirmPsss,
          suffixIcon: GestureDetector(
            onTap: () {
              logic.showConfirmPsss = !logic.showConfirmPsss;
              logic.update();
            },
            child: Image.asset(
              logic.showConfirmPsss == true
                  ? ImagePath.viewOff
                  : ImagePath.view,
              color: logic.confirmPassController.text.trim().isNotEmpty
                  ? textColor
                  : hintColor,
              scale: 6,
            ),
          ),
        ),
        Gap(getWidth(40)!),
        PrimaryButton(
          text: "change_pass".getString(context),
          onTap: () {
            logic.forgotPasswordSubmit();
          },
        ),
        Gap(getWidth(20)!),
        Bouncing(
          onTap: () {
            logic.sendOtpAPI();
          },
          child: AppText.heading2(
            "resend_otp".getString(context),
            color: textColor,
            getfontSize: 18,
          ),
        ),
      ],
    );
  }
}
