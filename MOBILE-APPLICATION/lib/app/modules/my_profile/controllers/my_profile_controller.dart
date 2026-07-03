import 'dart:io';
import 'package:flutter_localization/flutter_localization.dart';
import '../../../../exports.dart';
import '../../../custom_widget/app_text_field.dart';
import '../../../custom_widget/primary_button.dart';
import '../../../dio_api/dio_api.dart';
import '../../../models/profile_response.dart';
import '../../../utils/app_const_colors.dart';
import '../../../utils/app_size.dart';
import '../../../utils/constant_vars.dart';
import '../../../utils/form_validation.dart';

class MyProfileLogic extends GetxController {
  late TextEditingController email;
  @override
  void onInit() {
    email = TextEditingController();
    super.onInit();
  }

  bool isLoading = false;

  Future<T?> bottomSheetWithHandle<T>(BuildContext context) {
    email.text = Constants.profileRes?.email ?? "";
    return showModalBottomSheet<T>(
        context: context,
        elevation: 10,
        isScrollControlled: true,
        backgroundColor: hintColor,
        useSafeArea: false,
        isDismissible: true,
        enableDrag: true,
        builder: (context) {
          return GetBuilder<MyProfileLogic>(
            builder: (logic) {
              return AnimatedContainer(
                duration: 100.milliseconds,
                padding: EdgeInsets.only(
                  bottom: MediaQuery.of(context).viewInsets.bottom,
                ),
                decoration: const BoxDecoration(
                  color: bgColor,
                  borderRadius: BorderRadius.vertical(top: Radius.circular(24)),
                  boxShadow: [
                    BoxShadow(
                      blurRadius: 8,
                      color: Colors.black26,
                    )
                  ],
                ),
                child: SafeArea(
                  child: SingleChildScrollView(
                    child: Padding(
                      padding: EdgeInsets.symmetric(horizontal: getWidth(20)!),
                      child: Form(
                        key: logic.addFormKey,
                        child: Column(
                          crossAxisAlignment: CrossAxisAlignment.start,
                          children: [
                            Center(
                              child: Container(
                                height: 4,
                                width: 40,
                                margin:
                                    const EdgeInsets.symmetric(vertical: 24),
                                decoration: BoxDecoration(
                                  color: textColor,
                                  borderRadius: BorderRadius.circular(40),
                                ),
                              ),
                            ),
                            AppTextField(
                              controller: logic.email,
                              hintText: "email".getString(context),
                              validator: (value) =>
                                  FormValidation.emailValidator(value),
                              keyboardType: TextInputType.emailAddress,
                              borderColor: textColor,
                              hintTextColor: hintColor,
                              textStyleColor: textColor,
                            ),
                            Gap(getWidth(32)!),
                            PrimaryButton(
                              text: "update".getString(context),
                              onTap: () {
                                updateEmailSubmit();
                              },
                            ),
                            if (Platform.isAndroid) Gap(getWidth(20)!),
                          ],
                        ),
                      ),
                    ),
                  ),
                ),
              );
            },
          );
        });
  }

  updateEmailAPI() {
    Get.back();
    isLoading = true;
    update();
    API().post(
      "/update-email",
      data: {
        "email": email.text.trim(),
      },
    ).then((value) async {
      Map<String, dynamic>? res = value.data;
      if (res != null) {
        if (res['status'] ?? false) {
          profileAPI();
        } else {
          Constants.errorDialog(message: res['message']);
          isLoading = false;
          update();
        }
      } else {
        Constants.errorDialog();
        isLoading = false;
        update();
      }
    });
  }

  profileAPI() {
    isLoading = true;
    update();
    API().get("/parent-profile").then((value) async {
      Map<String, dynamic>? res = value.data;
      if (res != null) {
        if (res['status'] ?? false) {
          Constants.profileRes = ProfileResponse.fromJson(res['data']);
          isLoading = false;
          update();
        } else {
          Constants.errorDialog(message: res['message']);
          isLoading = false;
          update();
        }
      } else {
        Constants.errorDialog();
        isLoading = false;
        update();
      }
    });
  }

  var addFormKey = GlobalKey<FormState>();
  void updateEmailSubmit() {
    final isValid = addFormKey.currentState!.validate();
    if (!isValid) {
      return;
    } else {
      updateEmailAPI();
    }
    addFormKey.currentState!.save();
  }
}
