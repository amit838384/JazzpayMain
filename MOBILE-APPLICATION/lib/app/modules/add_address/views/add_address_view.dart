import 'package:flutter/material.dart';
import 'package:jazz_smart_pay/app/custom_widget/my_app_bar.dart';
import 'package:jazz_smart_pay/app/utils/app_const_colors.dart';
import 'package:jazz_smart_pay/app/utils/app_size.dart';
import 'package:gap/gap.dart';
import 'package:get/get.dart';
import 'package:jazz_smart_pay/app/utils/form_validation.dart';
import '../../../custom_widget/app_text_field.dart';
import '../../../custom_widget/primary_button.dart';
import '../controllers/add_address_controller.dart';

class AddAddressView extends GetView<AddAddressController> {
  const AddAddressView({super.key});
  @override
  Widget build(BuildContext context) {
    return GetBuilder<AddAddressController>(builder: (logic) {
      return Scaffold(
        appBar: myAppBar(title: "Add Address"),
        backgroundColor: bgColor,
        body: SingleChildScrollView(
          padding: EdgeInsets.all(getWidth(20)!),
          child: Form(
            key: logic.addressFormKey,
            child: Column(
              children: [
                AppTextField(
                  controller: logic.name,
                  hintText: "Your Name",
                  validator: (value) => FormValidation.notEmptyValidator(value),
                  textCapitalization: TextCapitalization.words,
                ),
                Gap(getWidth(20)!),
                AppTextField(
                  controller: logic.streat,
                  hintText: "Street Address",
                  validator: (value) => FormValidation.notEmptyValidator(value),
                  textCapitalization: TextCapitalization.words,
                  readOnly: true,
                  onTap: () {
                    logic.pickAddress(context);
                  },
                ),
                Gap(getWidth(20)!),
                AppTextField(
                  controller: logic.streatTwo,
                  hintText: "Street Address 2 (Optional)",
                  textCapitalization: TextCapitalization.words,
                ),
                Gap(getWidth(20)!),
                AppTextField(
                  controller: logic.state,
                  hintText: "State",
                  validator: (value) => FormValidation.notEmptyValidator(value),
                  textCapitalization: TextCapitalization.words,
                ),
                Gap(getWidth(20)!),
                Row(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Expanded(
                      child: AppTextField(
                        controller: logic.city,
                        hintText: "City",
                        validator: (value) =>
                            FormValidation.notEmptyValidator(value),
                        textCapitalization: TextCapitalization.words,
                      ),
                    ),
                    Gap(getWidth(20)!),
                    Expanded(
                      child: AppTextField(
                        controller: logic.zipCode,
                        hintText: "Zip Code",
                        validator: (value) =>
                            FormValidation.notEmptyValidator(value),
                        keyboardType: TextInputType.number,
                      ),
                    ),
                  ],
                ),
                // Gap(getWidth(20)!),
                // AppTextField(
                //   controller: logic.country,
                //   hintText: "Country",
                //   validator: (value) => FormValidation.notEmptyValidator(value),
                //   textCapitalization: TextCapitalization.words,
                // ),
                Gap(getWidth(20)!),
                AppTextField(
                  controller: logic.phone,
                  hintText: "Phone Number",
                  validator: (value) => FormValidation.phoneValidator(value),
                  keyboardType: TextInputType.number,
                ),
                Gap(getWidth(20)!),
                AppTextField(
                  controller: logic.email,
                  hintText: "Email",
                  validator: (value) => FormValidation.emailValidator(value),
                  keyboardType: TextInputType.emailAddress,
                  textInputAction: TextInputAction.done,
                ),
                Gap(getWidth(20)!),
              ],
            ),
          ),
        ),
        bottomNavigationBar: Padding(
          padding: EdgeInsets.all(getWidth(20)!),
          child: PrimaryButton(
            text: logic.arguments['type'] == "1"
                ? "Update Address"
                : "Add address",
            onTap: () {
              logic.addressSubmit();
            },
            isLoading: logic.isLoading,
          ),
        ),
      );
    });
  }
}
