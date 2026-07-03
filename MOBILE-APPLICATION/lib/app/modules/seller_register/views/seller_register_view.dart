import 'package:flutter/material.dart';
import 'package:jazz_smart_pay/app/custom_widget/app_text.dart';
import 'package:jazz_smart_pay/app/custom_widget/app_text_field.dart';
import 'package:jazz_smart_pay/app/custom_widget/bouncing_button.dart';
import 'package:jazz_smart_pay/app/custom_widget/primary_button.dart';
import 'package:jazz_smart_pay/app/utils/app_const_colors.dart';
import 'package:jazz_smart_pay/app/utils/app_size.dart';
import 'package:jazz_smart_pay/app/utils/constant_vars.dart';
import 'package:jazz_smart_pay/app/utils/form_validation.dart';
import 'package:jazz_smart_pay/app/utils/image_path.dart';
import 'package:gap/gap.dart';
import 'package:get/get.dart';
import '../controllers/seller_register_controller.dart';

class SellerRegisterView extends GetView<SellerRegisterController> {
  const SellerRegisterView({super.key});
  @override
  Widget build(BuildContext context) {
    return GetBuilder<SellerRegisterController>(builder: (logic) {
      return Scaffold(
          backgroundColor: bgColor,
          body: SafeArea(
            child: SingleChildScrollView(
              padding: EdgeInsets.all(getWidth(20)!),
              child: Form(
                key: logic.registerFormKey,
                child: Column(
                  children: [
                    Gap(getWidth(40)!),
                    Center(child: Image.asset(ImagePath.jazzPayLogo, scale: 5)),
                    Gap(getWidth(30)!),
                    if (logic.showOtp == false) _registerWidget(logic, context),
                    if (logic.showOtp == true) _otpWidget(logic),
                  ],
                ),
              ),
            ),
          ));
    });
  }

  _registerWidget(SellerRegisterController logic, BuildContext context) {
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Center(
          child: AppText.heading2("Seller Registration",
              color: blackColor, getfontSize: 24, fontWeight: FontWeight.w600),
        ),
        Gap(getWidth(40)!),
        checkBoxWidget(
          text: "Setup my shopfree franchise",
          selected: logic.frenchise,
          onTap: () {
            logic.frenchiseTap();
          },
        ),
        Gap(getWidth(10)!),
        checkBoxWidget(
          text: "Set my gst as Quick Commerce",
          selected: logic.quick,
          onTap: () {
            logic.quickTap();
          },
        ),
        Gap(getWidth(10)!),
        checkBoxWidget(
          text: "Set my multivendor E-Commerce",
          selected: logic.multivendor,
          onTap: () {
            logic.multivendorTap();
          },
        ),
        Gap(getWidth(20)!),
        AppTextField(
          controller: logic.personName,
          hintText: "Contact Person Name",
          validator: (value) => FormValidation.notEmptyValidator(value),
          textCapitalization: TextCapitalization.words,
        ),
        Gap(getWidth(20)!),
        AppTextField(
          controller: logic.companyName,
          hintText: "Company Name",
          validator: (value) => FormValidation.notEmptyValidator(value),
          textCapitalization: TextCapitalization.words,
        ),
        Gap(getWidth(20)!),
        AppTextField(
          controller: logic.email,
          hintText: "Email",
          validator: (value) => FormValidation.emailValidator(value),
          keyboardType: TextInputType.emailAddress,
        ),
        Gap(getWidth(20)!),
        AppTextField(
          controller: logic.mobile,
          hintText: "Mobile Number",
          validator: (value) => FormValidation.phoneValidator(value),
          keyboardType: TextInputType.phone,
        ),
        Gap(getWidth(20)!),
        AppTextField(
          controller: logic.password,
          hintText: "Password",
          validator: (value) => FormValidation.notEmptyValidator(value),
          keyboardType: TextInputType.visiblePassword,
        ),
        Gap(getWidth(20)!),
        AppTextField(
          controller: logic.confirmPassword,
          hintText: "Confirm Password",
          validator: (value) => FormValidation.confirmPasswordValidator(
              value, logic.password.text.trim()),
          keyboardType: TextInputType.visiblePassword,
        ),
        Gap(getWidth(20)!),
        AppTextField(
          controller: logic.addressLine1,
          hintText: "Address",
          validator: (value) => FormValidation.notEmptyValidator(value),
          // readOnly: true,
          onTap: () {
            // logic.pickAddress(context);
          },
        ),
        Gap(getWidth(20)!),
        AppTextField(
          controller: logic.pincode,
          hintText: "Pin code",
          validator: (value) => FormValidation.pinCodeValidator(value),
          keyboardType: TextInputType.number,
        ),
        Gap(getWidth(20)!),
        AppTextField(
          controller: logic.gstNumber,
          hintText: "GST Number",
          validator: (value) => FormValidation.gstValidator(value),
          textCapitalization: TextCapitalization.characters,
        ),
        Gap(getWidth(20)!),
        if (logic.selectedGST == null)
          PrimaryButton(
            isOutlined: true,
            onTap: () {
              logic.uploadGSTDoc(context);
            },
            text: "Upload GST document",
            textColor: blackColor,
          ),
        if (logic.selectedGST != null)
          Row(
            children: [
              ClipRRect(
                borderRadius: BorderRadius.circular(getWidth(6)!),
                child: Image.file(
                  logic.selectedGST!,
                  height: getWidth(30),
                  width: getWidth(30),
                  fit: BoxFit.cover,
                ),
              ),
              Gap(getWidth(12)!),
              AppText.heading2("GST Uploaded"),
              const Spacer(),
              Bouncing(
                onTap: () {
                  logic.selectedGST = null;
                  logic.update();
                },
                child: Icon(
                  Icons.close,
                  size: getWidth(24),
                ),
              )
            ],
          ),
        Gap(getWidth(20)!),
        if (logic.selectedPAN == null)
          PrimaryButton(
            isOutlined: true,
            onTap: () {
              logic.uploadPANDoc(context);
            },
            text: "Upload PAN document",
            textColor: blackColor,
          ),
        if (logic.selectedPAN != null)
          Row(
            children: [
              ClipRRect(
                borderRadius: BorderRadius.circular(getWidth(6)!),
                child: Image.file(
                  logic.selectedPAN!,
                  height: getWidth(30),
                  width: getWidth(30),
                  fit: BoxFit.cover,
                ),
              ),
              Gap(getWidth(12)!),
              AppText.heading2("PAN Uploaded"),
              const Spacer(),
              Bouncing(
                onTap: () {
                  logic.selectedPAN = null;
                  logic.update();
                },
                child: Icon(
                  Icons.close,
                  size: getWidth(24),
                ),
              )
            ],
          ),
        Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Gap(getWidth(20)!),
            AppText.heading2(
              "Bank Details",
              color: hintColor,
              getfontSize: 18,
            ),
            Gap(getWidth(20)!),
            AppTextField(
              controller: logic.accNumber,
              hintText: "Account No",
              validator: (value) => FormValidation.notEmptyValidator(value),
              keyboardType: TextInputType.number,
            ),
            Gap(getWidth(20)!),
            AppTextField(
              controller: logic.bankName,
              hintText: "Bank Name",
              validator: (value) => FormValidation.notEmptyValidator(value),
              textCapitalization: TextCapitalization.words,
            ),
            Gap(getWidth(20)!),
            AppTextField(
              controller: logic.ifsCode,
              hintText: "IFSC Code",
              validator: (value) => FormValidation.ifscValidator(value),
              textCapitalization: TextCapitalization.characters,
            ),
            Gap(getWidth(20)!),
            AppTextField(
              controller: logic.holderName,
              hintText: "Account Holder Name",
              validator: (value) => FormValidation.notEmptyValidator(value),
            ),
            Gap(getWidth(20)!),
            AppTextField(
              controller: logic.branchName,
              hintText: "Branch",
              validator: (value) => FormValidation.notEmptyValidator(value),
              textCapitalization: TextCapitalization.words,
            ),
            Gap(getWidth(20)!),
            Row(
              children: [
                Expanded(
                  child: AppTextField(
                    controller: logic.branchState,
                    hintText: "State",
                    validator: (value) =>
                        FormValidation.notEmptyValidator(value),
                    keyboardType: TextInputType.text,
                    textCapitalization: TextCapitalization.words,
                  ),
                ),
                Gap(getWidth(20)!),
                Expanded(
                  child: AppTextField(
                    controller: logic.branchCity,
                    hintText: "City",
                    validator: (value) =>
                        FormValidation.notEmptyValidator(value),
                    keyboardType: TextInputType.text,
                    textCapitalization: TextCapitalization.words,
                  ),
                ),
              ],
            ),
          ],
        ),
        Gap(getWidth(100)!),
        PrimaryButton(
          text: "Register ",
          onTap: () {
            logic.registerSubmit();
          },
          isLoading: logic.isLoading,
        ),
        Gap(getWidth(40)!),
        GestureDetector(
          onTap: () {
            Get.back();
          },
          child: Row(
            mainAxisAlignment: MainAxisAlignment.center,
            children: [
              AppText.heading1(
                "Already have an account? ",
                getfontSize: 16,
                color: hintColor,
                fontWeight: FontWeight.w700,
              ),
              AppText.heading1(
                "Log In.",
                getfontSize: 16,
                color: blueColor,
                fontWeight: FontWeight.w700,
              ),
            ],
          ),
        ),
        Gap(getWidth(140)!),
      ],
    );
  }

  checkBoxWidget({
    required String text,
    required void Function() onTap,
    bool selected = false,
  }) {
    return Bouncing(
      onTap: onTap,
      child: Row(
        children: [
          Icon(
            selected ? Icons.check_box_rounded : Icons.crop_square,
            size: getWidth(28),
          ),
          Gap(getWidth(12)!),
          AppText.paragraph(
            text,
            getfontSize: 17,
            fontWeight: FontWeight.w500,
          )
        ],
      ),
    );
  }

  _otpWidget(SellerRegisterController logic) {
    return Column(
      children: [
        AppText.heading2(
          "Enter Verification Code",
          color: blackColor,
          getfontSize: 28,
        ),
        Gap(getWidth(40)!),
        AppTextField(
          controller: logic.registerOtp,
          hintText: "Enter OTP here",
          keyboardType: TextInputType.number,
          validator: (value) => FormValidation.otpValidator(value),
        ),
        Gap(getWidth(40)!),
        PrimaryButton(
          text: "Confirm",
          onTap: () {
            if (logic.registerOtp.text.isNotEmpty &&
                logic.registerOtp.text.length == 6) {
              logic.verifyRegisterAPI();
            } else {
              Constants.errorDialog(message: "Please Check your OTP");
            }
          },
          isLoading: logic.iOtpLoading,
        ),
        Gap(getWidth(20)!),
        GestureDetector(
          onTap: () {
            logic.showOtp = false;
            logic.update();
          },
          child: AppText.heading2(
            "Cancel",
            color: blackColor,
            getfontSize: 18,
            fontWeight: FontWeight.w800,
          ),
        ),
      ],
    );
  }
}
