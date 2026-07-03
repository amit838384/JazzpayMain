import 'dart:io';

import 'package:flutter_localization/flutter_localization.dart';
import 'package:jazz_smart_pay/app/utils/app_const_colors.dart';
import 'package:jazz_smart_pay/app/utils/app_methods.dart';
import 'package:jazz_smart_pay/app/utils/image_path.dart';
import '../../../../exports.dart';
import '../../../custom_widget/app_divider.dart';
import '../../../custom_widget/app_text.dart';
import '../../../custom_widget/app_text_field.dart';
import '../../../custom_widget/primary_button.dart';
import '../../../dio_api/dio_api.dart';
import '../../../models/profile_response.dart';
import '../../../utils/app_size.dart';
import '../../../utils/constant_vars.dart';
import '../../../utils/form_validation.dart';
import '../../../utils/prefrence.dart';

class ProfileController extends GetxController {
  late TextEditingController deleteController;
  @override
  void onInit() {
    deleteController = TextEditingController();
    profileAPI();
    super.onInit();
  }

  bool isLoading = false;

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

  List<ProfileTile> tileSettins = [
    ProfileTile(
        name: "my_profile".getString(Get.context!), icon: ImagePath.account),
    ProfileTile(
        name: "language".getString(Get.context!), icon: ImagePath.language),
    ProfileTile(
        name: "contact_us".getString(Get.context!), icon: ImagePath.support),
    ProfileTile(
        name: "feedback".getString(Get.context!), icon: ImagePath.feedback),
    ProfileTile(
        name: "user_guide".getString(Get.context!), icon: ImagePath.bot),
    ProfileTile(
        name: "delete_account".getString(Get.context!), icon: ImagePath.delete),
    ProfileTile(name: "logout".getString(Get.context!), icon: ImagePath.logout),
  ];

  Future<T?> bottomSheetWithHandle<T>(BuildContext context) {
    return showModalBottomSheet<T>(
        context: context,
        elevation: 10,
        isScrollControlled: true,
        backgroundColor: hintColor,
        useSafeArea: false,
        isDismissible: true,
        enableDrag: true,
        builder: (context) {
          return GetBuilder<ProfileController>(
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
                    // <-- Wrap whole body
                    child: Padding(
                      padding: EdgeInsets.symmetric(horizontal: getWidth(20)!),
                      child: Column(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          Center(
                            child: Container(
                              height: 4,
                              width: 40,
                              margin: const EdgeInsets.symmetric(vertical: 24),
                              decoration: BoxDecoration(
                                color: textColor,
                                borderRadius: BorderRadius.circular(40),
                              ),
                            ),
                          ),
                          AppText.heading2("delete_account".getString(context),
                              color: textColor, fontWeight: FontWeight.w700),
                          Gap(getWidth(12)!),
                          appDivider(color: textColor),
                          Gap(getWidth(12)!),
                          AppText.heading2(
                              "delete_account_text".getString(context),
                              color: textColor,
                              getfontSize: 16),
                          Gap(getWidth(32)!),
                          AppTextField(
                            controller: logic.deleteController,
                            hintText: "as_delete".getString(context),
                            validator: (value) =>
                                FormValidation.deleteValidator(value),
                            keyboardType: TextInputType.text,
                            borderColor: textColor,
                            hintTextColor: hintColor,
                            textStyleColor: textColor,
                          ),
                          Gap(getWidth(32)!),
                          PrimaryButton(
                            text: "confirm".getString(context),
                            onTap: () {},
                          ),
                          if (Platform.isAndroid) Gap(getWidth(20)!),
                        ],
                      ),
                    ),
                  ),
                ),
              );
            },
          );
        });
  }

  var addFormKey = GlobalKey<FormState>();
  void updateEmailSubmit() {
    final isValid = addFormKey.currentState!.validate();
    if (!isValid) {
      return;
    } else {
      deleteAccountAPI();
    }
    addFormKey.currentState!.save();
  }

  deleteAccountAPI() {
    Get.back();
    isLoading = true;
    update();
    API().post("/delete-account").then((value) async {
      Map<String, dynamic>? res = value.data;
      if (res != null) {
        if (res['status'] ?? false) {
          AppMethods.showCustomSnackbar(
              "account_delete".getString(Get.context!));
          Prefs().removeToken();
          Constants.profileRes = null;
          Get.offAllNamed(Routes.LOGIN);
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
}

class ProfileTile {
  final String name;
  final String icon;
  ProfileTile({required this.name, required this.icon});
}
