import 'package:flutter/material.dart';
import 'package:gap/gap.dart';
import 'package:get/get.dart';
import 'package:jazz_smart_pay/app/custom_widget/fill_container.dart';
import 'package:jazz_smart_pay/app/custom_widget/my_app_bar.dart';
import 'package:jazz_smart_pay/app/custom_widget/primary_button.dart';
import 'package:jazz_smart_pay/app/modules/profile/controllers/profile_controller.dart';
import 'package:jazz_smart_pay/app/utils/app_const_colors.dart';
import 'package:jazz_smart_pay/app/utils/index.dart';

import '../../../custom_widget/app_text_field.dart';
import '../../../custom_widget/profile_image_circle.dart';
import '../../../utils/constant_vars.dart';
import '../../../utils/form_validation.dart';

class UpdateProfileView extends StatelessWidget {
  const UpdateProfileView({super.key});

  @override
  Widget build(BuildContext context) {
    return GetBuilder<ProfileController>(builder: (logic) {
      return Scaffold(
        backgroundColor: bgColor,
        appBar: myAppBar(title: "Update Profile"),
        body: Padding(
          padding: EdgeInsets.all(getWidth(20)!),
          child: Column(
            children: [
              Gap(getWidth(80)!),
              Center(
                child: Stack(
                  children: [
                    profileImageCircle(
                      imageUrl: Constants.profile!['image'],
                      name: Constants.profile!['name'],
                      circleSize: 150,
                    ),
                    Positioned(
                      right: 6,
                      bottom: 6,
                      child: FillContainer(
                        padding: EdgeInsets.all(getWidth(10)!),
                        backgroundColor: blackColor.withValues(alpha: .8),
                        borderRadius: 100,
                        child: Icon(
                          Icons.camera_enhance,
                          size: getWidth(20),
                          color: whiteColor,
                        ),
                      ),
                    )
                  ],
                ),
              ),
              Gap(getWidth(30)!),
              // AppTextField(
              //   controller: logic.name,
              //   hintText: "Name",
              //   prefixWidget: Icon(Icons.person, color: hintColor),
              //   validator: (value) => FormValidation.notEmptyValidator(value),
              // ),
              // Gap(getWidth(20)!),
              // AppTextField(
              //   controller: logic.email,
              //   hintText: "Email",
              //   prefixWidget: Icon(Icons.email, color: hintColor),
              //   validator: (value) => FormValidation.emailValidator(value),
              //   readOnly: true,
              //   keyboardType: TextInputType.emailAddress,
              // ),
              // Gap(getWidth(20)!),
              // AppTextField(
              //   controller: logic.phone,
              //   hintText: "Mobile Number",
              //   prefixWidget: Icon(Icons.phone_iphone, color: hintColor),
              //   validator: (value) => FormValidation.phoneValidator(value),
              //   keyboardType: TextInputType.phone,
              // ),
              Gap(getWidth(60)!),
              PrimaryButton(
                text: "Save Changes",
                onTap: () {},
              )
            ],
          ),
        ),
      );
    });
  }
}
