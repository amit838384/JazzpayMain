import 'dart:developer';
import 'dart:io';
import 'package:flutter_localization/flutter_localization.dart';

import '../../../../exports.dart';
import '../../../custom_widget/app_divider.dart';
import '../../../custom_widget/app_text.dart';
import '../../../custom_widget/bouncing_button.dart';
import '../../../custom_widget/fill_container.dart';
import '../../../dio_api/dio_api.dart';
import '../../../utils/app_const_colors.dart';
import '../../../utils/constant_vars.dart';
import '../../../utils/index.dart';

class PayForServiceLogic extends GetxController {
  @override
  void onInit() {
    getPlansAPI();
    getCategories();
    super.onInit();
  }

  bool isLoading = false;
  Map<String, dynamic>? servicePlans;
  getPlansAPI() {
    isLoading = true;
    update();
    API().post("/list-plans").then((value) async {
      Map<String, dynamic>? res = value.data;
      if (res != null) {
        if (res['success'] ?? false) {
          servicePlans = res;
          log("Response  :  $res");
        } else {
          Constants.errorDialog(message: res['message']);
        }
      } else {
        Constants.errorDialog();
      }

      isLoading = false;
      update();
    });
  }

  purchasePlanAPI(BuildContext context) {
    isLoading = true;
    update();
    API().post("/parent/subscribe", data: {
      'plan_id': servicePlans!['data'][selectedIndex]['id'],
      'student_id': students[selectedStudent]['id'],
      'payment_status': "paid",
    }).then((value) async {
      Map<String, dynamic>? res = value.data;
      if (res != null) {
        if (res['success'] ?? false) {
          Constants.successDialog(
            message: 'plan_success'.getString(context),
            onTap: () {
              Get.back();
              Get.back(result: true);
            },
          );
        } else {
          Constants.errorDialog(message: res['message']);
        }
      } else {
        Constants.errorDialog();
      }

      isLoading = false;
      update();
    });
  }

  int selectedIndex = -1;

  studentSelectionBottomSheet(BuildContext context) {
    final double imageSize = getWidth(48)!;
    Constants.bottomSheetWithHandle(context, builder: (context) {
      return GetBuilder<PayForServiceLogic>(
        builder: (logic) {
          return AnimatedContainer(
            duration: 100.milliseconds,
            padding: EdgeInsets.only(
              bottom: MediaQuery.of(context).viewInsets.bottom,
            ),
            decoration: const BoxDecoration(
              color: bgColor,
              borderRadius: BorderRadius.vertical(top: Radius.circular(24)),
              boxShadow: [BoxShadow(blurRadius: 8, color: Colors.black26)],
            ),
            child: SafeArea(
              child: SingleChildScrollView(
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
                      AppText.heading2("Select Member For Booking",
                          color: textColor, fontWeight: FontWeight.w700),
                      Gap(getWidth(12)!),
                      appDivider(color: textColor),
                      Gap(getWidth(12)!),
                      ...List.generate(
                        logic.students.length,
                        (index) {
                          var stu = logic.students[index];
                          return Bouncing(
                            onTap: () {
                              Get.back();
                              logic.selectedStudent = index;
                              logic.update();
                            },
                            child: FillContainer(
                                borderRadius: 18,
                                backgroundColor:
                                    textColor.withValues(alpha: .2),
                                margin: EdgeInsets.only(bottom: getWidth(20)!),
                                child: Row(
                                  children: [
                                    Image.asset(ImagePath.student,
                                        height: imageSize, width: imageSize),
                                    Gap(getWidth(16)!),
                                    Expanded(
                                      child: Column(
                                        crossAxisAlignment:
                                            CrossAxisAlignment.start,
                                        children: [
                                          AppText.heading2(stu['name'],
                                              fontWeight: FontWeight.w700,
                                              getfontSize: 17,
                                              color: textColor),
                                          AppText.heading2(stu['admission_no'],
                                              fontWeight: FontWeight.w600,
                                              getfontSize: 16,
                                              color: textColor),
                                        ],
                                      ),
                                    ),
                                    Gap(getWidth(12)!),
                                    if (logic.selectedStudent == index)
                                      Image.asset(
                                        ImagePath.tick,
                                        color: textColor,
                                        height: getWidth(20),
                                        width: getWidth(20),
                                      )
                                  ],
                                )),
                          );
                        },
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

  List<dynamic> students = [];
  int selectedStudent = 0;
  Map<String, dynamic>? catRes;
  bool isStudents = false;
  getCategories() {
    isStudents = true;
    update();
    API().get("/all-category").then((value) async {
      Map<String, dynamic>? res = value.data;
      if (res != null) {
        catRes = res;
        if (res['status'] ?? false) {
          if (catRes!['data'].isNotEmpty) {
            students.clear();
            students.addAll(catRes!['Student']);
          }
          isStudents = false;
          update();
        } else {
          Constants.errorDialog(message: res['message']);
          isStudents = false;
          update();
        }
      } else {
        Constants.errorDialog();
        isStudents = false;
        update();
      }
    });
  }
}
