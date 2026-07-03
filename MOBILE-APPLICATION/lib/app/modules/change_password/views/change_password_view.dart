import 'package:flutter/material.dart';
import 'package:gap/gap.dart';
import 'package:get/get.dart';
import 'package:jazz_smart_pay/app/custom_widget/bouncing_button.dart';
import 'package:jazz_smart_pay/app/custom_widget/loading_circular_component.dart';
import 'package:jazz_smart_pay/app/custom_widget/my_app_bar.dart';
import 'package:jazz_smart_pay/app/utils/form_validation.dart';
import '../../../custom_widget/app_text.dart';
import '../../../custom_widget/app_text_field.dart';
import '../../../custom_widget/primary_button.dart';
import '../../../utils/app_const_colors.dart';
import '../../../utils/app_size.dart';
import '../../../utils/image_path.dart';
import '../controllers/change_password_controller.dart';

class ChangePasswordView extends GetView<ChangePasswordController> {
  const ChangePasswordView({super.key});
  @override
  Widget build(BuildContext context) {
    return GetBuilder<ChangePasswordController>(builder: (logic) {
      return Scaffold(
          backgroundColor: bgColor,
          appBar: myAppBar(),
          body: logic.isLoading || logic.isLoading
              ? const Center(child: LoadingCircularComponent())
              : SafeArea(
                  child: Form(
                    key: logic.changeFormKey,
                    child: SingleChildScrollView(
                      padding: EdgeInsets.all(getWidth(20)!),
                      child: Column(
                        children: [
                          Gap(getWidth(40)!),
                          Center(
                              child:
                                  Image.asset(ImagePath.jazzPayLogo, scale: 5)),
                          Gap(getWidth(30)!),
                          _otpWidget(logic),
                        ],
                      ),
                    ),
                  ),
                ));
    });
  }

  _otpWidget(ChangePasswordController logic) {
    return Column(
      children: [
        AppText.heading2(
          "Change Your Password",
          color: primaryColor,
          getfontSize: 28,
        ),
        Gap(getWidth(10)!),
        AppText.heading2(
          "Secure your account by updating your password. Choose a strong password to keep your data safe.",
          color: hintColor,
          getfontSize: 16,
          textAlign: TextAlign.center,
        ),
        Gap(getWidth(40)!),
        AppTextField(
          controller: logic.oldPassController,
          hintText: "old Password",
          prefixWidget: Icon(Icons.lock, color: hintColor),
          validator: (value) => FormValidation.notEmptyValidator(value),
        ),
        Gap(getWidth(20)!),
        AppTextField(
          controller: logic.passController,
          hintText: "password",
          prefixWidget: Icon(Icons.lock, color: hintColor),
          validator: (value) => FormValidation.notEmptyValidator(value),
        ),
        Gap(getWidth(20)!),
        AppTextField(
          controller: logic.confirmPassController,
          hintText: "Confirm Password",
          prefixWidget: Icon(Icons.lock, color: hintColor),
          validator: (value) => FormValidation.confirmPasswordValidator(
              value, logic.passController.text.trim()),
        ),
        Gap(getWidth(40)!),
        PrimaryButton(
          text: "Change Password",
          onTap: () {
            logic.changePasswordSubmit();
          },
        ),
      ],
    );
  }
}
