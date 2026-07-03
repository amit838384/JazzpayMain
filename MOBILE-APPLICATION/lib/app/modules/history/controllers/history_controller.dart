import 'dart:io';

import 'package:flutter_localization/flutter_localization.dart';
import 'package:intl/intl.dart';
import 'package:omni_datetime_picker/omni_datetime_picker.dart';

import '../../../../exports.dart';
import '../../../custom_widget/app_divider.dart';
import '../../../custom_widget/app_text.dart';
import '../../../custom_widget/app_text_field.dart';
import '../../../custom_widget/primary_button.dart';
import '../../../dio_api/dio_api.dart';
import '../../../utils/app_const_colors.dart';
import '../../../utils/constant_vars.dart';
import '../../../utils/form_validation.dart';
import '../../../utils/index.dart';

class HistoryLogic extends GetxController {
  late TextEditingController reasonController;
  String? studentId;
  int selectedTab = 0;
  final List<String> historyTabs = [
    'credit_transfer'.getString(Get.context!),
    'pre_order'.getString(Get.context!),
    'consumptions'.getString(Get.context!),
    'pay_for_service'.getString(Get.context!),
    'cafeteria_topups'.getString(Get.context!),
    'wallet_transactions'.getString(Get.context!),
  ];
  final List<String> reportsTabs = [
    'credit_transfer'.getString(Get.context!),
    'pre_order'.getString(Get.context!),
    'consumptions'.getString(Get.context!),
    'pay_for_service'.getString(Get.context!),
    'cafeteria_topups'.getString(Get.context!),
  ];

  String selectedAmount = '';
  @override
  void onInit() {
    studentId = Get.arguments;
    reasonController = TextEditingController();
    getCreditTransferData();
    super.onInit();
  }

  bool isLoading = false;
  Map<String, dynamic>? creditRes;
  getCreditTransferData() {
    isLoading = true;
    update();
    API().post(
      "/credit-transfer-history",
      data: {
        "id": studentId != null && studentId!.isNotEmpty ? studentId : "0",
      },
    ).then((value) async {
      Map<String, dynamic>? res = value.data;
      if (res != null) {
        creditRes = res;
        if (res['status'] ?? false) {
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

  Map<String, dynamic>? preOrderRes;
  getPreOrderData() {
    isLoading = true;
    update();
    API().post(
      "/pre-order-history",
      data: {
        "id": studentId != null && studentId!.isNotEmpty ? studentId : "0",
      },
    ).then((value) async {
      Map<String, dynamic>? res = value.data;
      if (res != null) {
        preOrderRes = res;
        if (res['status'] ?? false) {
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

  Map<String, dynamic>? walletRes;
  getWalletData() {
    isLoading = true;
    update();
    API().post("/wallet-transaction").then((value) async {
      Map<String, dynamic>? res = value.data;
      if (res != null) {
        walletRes = res;
        if (res['status'] ?? false) {
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

  Map<String, dynamic>? serviceRes;
  getServiceData() {
    isLoading = true;
    update();
    API().post(
      "/parent/my-subscriptions",
      data: {
        "student_id":
            studentId != null && studentId!.isNotEmpty ? studentId : "0",
      },
    ).then((value) async {
      Map<String, dynamic>? res = value.data;
      if (res != null) {
        serviceRes = res;
        if (res['success'] ?? false) {
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

  pauseService(String id) {
    isLoading = true;
    update();
    API().post(
      "/parent/pause-subscription",
      data: {
        "subscription_id": id,
        "pause_date": DateTime.now().toString(),
        "reason": "Child is not well",
      },
    ).then((value) async {
      Map<String, dynamic>? res = value.data;
      if (res != null) {
        if (res['success'] ?? false) {
          getServiceData();
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

  renewService(String id) {
    isLoading = true;
    update();
    API().post(
      "/parent/renew-subscription",
      data: {"subscription_id": id},
    ).then((value) async {
      Map<String, dynamic>? res = value.data;
      if (res != null) {
        if (res['success'] ?? false) {
          getServiceData();
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

  updateTabIndex(int index) {
    if (index == selectedTab) return;

    selectedTab = index;
    update();

    switch (index) {
      case 0:
        getCreditTransferData();
        break;
      case 1:
        getPreOrderData();
        break;
      case 3:
        getServiceData();
        break;
      case 5:
        getWalletData();
        break;
    }
  }

  pauseServiceBottomSheet(
    BuildContext context,
    String id,
    String startDateString,
    String endDateString,
  ) {
    Constants.bottomSheetWithHandle(context, builder: (context) {
      return GetBuilder<HistoryLogic>(
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
                      // Handle bar
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

                      // -------------------------
                      //  Reason
                      // -------------------------
                      AppText.heading2(
                        "reason_text".getString(context),
                        color: textColor,
                        fontWeight: FontWeight.w700,
                      ),
                      Gap(getWidth(12)!),
                      appDivider(color: textColor),
                      Gap(getWidth(12)!),

                      AppTextField(
                        controller: logic.reasonController,
                        hintText: "reason".getString(context),
                        validator: (value) =>
                            FormValidation.notEmptyValidator(value),
                        borderColor: textColor,
                        hintTextColor: textColor,
                        textStyleColor: textColor,
                        onChanged: (p0) => logic.update(),
                      ),

                      Gap(getWidth(20)!),

                      // -------------------------
                      //  Date Selector
                      // -------------------------
                      AppText.heading2(
                        "Select Pause Date",
                        color: textColor,
                        fontWeight: FontWeight.w700,
                      ),
                      Gap(10),

                      GestureDetector(
                        onTap: () async {
                          await logic.selectDate(
                            context,
                            startDateString,
                            endDateString,
                          );
                        },
                        child: Container(
                          padding: const EdgeInsets.all(14),
                          decoration: BoxDecoration(
                            borderRadius: BorderRadius.circular(10),
                            border: Border.all(color: textColor),
                          ),
                          child: Row(
                            mainAxisAlignment: MainAxisAlignment.spaceBetween,
                            children: [
                              AppText.paragraph(
                                logic.selectedPauseDate != null
                                    ? DateFormat("dd MMM yyyy")
                                        .format(logic.selectedPauseDate!)
                                    : "Select date",
                                color: textColor,
                              ),
                              Icon(Icons.calendar_month, color: textColor),
                            ],
                          ),
                        ),
                      ),

                      // Error Text
                      if (logic.dateError != null)
                        Padding(
                          padding: const EdgeInsets.only(top: 8),
                          child: Text(
                            logic.dateError!,
                            style: const TextStyle(color: Colors.red),
                          ),
                        ),

                      Gap(getWidth(32)!),

                      // -------------------------
                      //  Button
                      // -------------------------
                      PrimaryButton(
                        text: "pause_service".getString(context),
                        onTap: () {
                          if (logic.selectedPauseDate == null) {
                            logic.dateError = "Please select a valid date.";
                            logic.update();
                            return;
                          }

                          // All good
                          Get.back();
                          pauseService(id);
                        },
                        isDisabled:
                            logic.reasonController.text.trim().isEmpty ||
                                logic.selectedPauseDate == null,
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

  // pauseServiceBottomSheet(BuildContext context, String id) {
  //   Constants.bottomSheetWithHandle(context, builder: (context) {
  //     return GetBuilder<HistoryLogic>(
  //       builder: (logic) {
  //         return AnimatedContainer(
  //           duration: 100.milliseconds,
  //           padding: EdgeInsets.only(
  //             bottom: MediaQuery.of(context).viewInsets.bottom,
  //           ),
  //           decoration: const BoxDecoration(
  //             color: bgColor,
  //             borderRadius: BorderRadius.vertical(top: Radius.circular(24)),
  //             boxShadow: [BoxShadow(blurRadius: 8, color: Colors.black26)],
  //           ),
  //           child: SafeArea(
  //             child: SingleChildScrollView(
  //               child: Padding(
  //                 padding: EdgeInsets.symmetric(horizontal: getWidth(20)!),
  //                 child: Column(
  //                   crossAxisAlignment: CrossAxisAlignment.start,
  //                   children: [
  //                     Center(
  //                       child: Container(
  //                         height: 4,
  //                         width: 40,
  //                         margin: const EdgeInsets.symmetric(vertical: 24),
  //                         decoration: BoxDecoration(
  //                           color: textColor,
  //                           borderRadius: BorderRadius.circular(40),
  //                         ),
  //                       ),
  //                     ),
  //                     AppText.heading2("reason_text".getString(context),
  //                         color: textColor, fontWeight: FontWeight.w700),
  //                     Gap(getWidth(12)!),
  //                     appDivider(color: textColor),
  //                     Gap(getWidth(12)!),
  //                     AppTextField(
  //                       controller: logic.reasonController,
  //                       hintText: "reason".getString(context),
  //                       validator: (value) =>
  //                           FormValidation.notEmptyValidator(value),
  //                       borderColor: textColor,
  //                       hintTextColor: textColor,
  //                       textStyleColor: textColor,
  //                       onChanged: (p0) {
  //                         update();
  //                       },
  //                     ),
  //                     Gap(getWidth(32)!),
  //                     PrimaryButton(
  //                       text: "pause_service".getString(context),
  //                       onTap: () {
  //                         Get.back();
  //                         pauseService(id);
  //                       },
  //                       isDisabled: reasonController.text.trim().isEmpty,
  //                     ),
  //                     if (Platform.isAndroid) Gap(getWidth(20)!),
  //                   ],
  //                 ),
  //               ),
  //             ),
  //           ),
  //         );
  //       },
  //     );
  //   });
  // }

  DateTime? selectedPauseDate;
  String? dateError;

  Future<void> selectDate(
    BuildContext context,
    String startDateString,
    String endDateString,
  ) async {
    DateTime allowedStart;
    DateTime allowedEnd;

    // Convert DATE STRING → DateTime
    try {
      allowedStart = DateTime.parse(startDateString); // yyyy-MM-dd
      allowedEnd = DateTime.parse(endDateString); // yyyy-MM-dd
    } catch (e) {
      dateError = "Invalid date format. Use yyyy-MM-dd.";
      update();
      return;
    }

    DateTime today = DateTime.now();
    DateTime tomorrow = DateTime(
      today.year,
      today.month,
      today.day + 1,
    );

    // Candidate should not be before tomorrow OR before allowedStart
    DateTime minSelectableDate =
        tomorrow.isAfter(allowedStart) ? tomorrow : allowedStart;

    // Skip Fri/Sat for first selected date
    while (minSelectableDate.weekday == DateTime.friday ||
        minSelectableDate.weekday == DateTime.saturday) {
      minSelectableDate = minSelectableDate.add(const Duration(days: 1));
    }

    // If range invalid
    if (minSelectableDate.isAfter(allowedEnd)) {
      dateError = "No selectable dates available.";
      update();
      return;
    }

    DateTime initialDate = selectedPauseDate ?? minSelectableDate;

    // ------------------------------------------
    // SHOW PICKER
    // ------------------------------------------
    DateTime? pickedDate = await showOmniDateTimePicker(
      context: context,
      type: OmniDateTimePickerType.date,
      firstDate: minSelectableDate, // ❌ disable previous dates
      lastDate: allowedEnd, // ❌ disable after end date
      initialDate: initialDate,
      borderRadius: const BorderRadius.all(Radius.circular(16)),
      constraints: const BoxConstraints(maxWidth: 350, maxHeight: 650),
      is24HourMode: false,
      isShowSeconds: false,
      isForce2Digits: true,

      /// THE MAIN DISABLER
      selectableDayPredicate: (day) {
        // ❌ Block all previous dates
        if (day.isBefore(minSelectableDate)) return false;

        // ❌ Block after allowedEnd
        if (day.isAfter(allowedEnd)) return false;

        // ❌ Block Friday & Saturday
        if (day.weekday == DateTime.friday || day.weekday == DateTime.saturday)
          return false;

        // ✔ VALID
        return true;
      },
    );

    if (pickedDate == null) return;

    // Safety (should not happen because disabled in UI)
    if (pickedDate.isBefore(minSelectableDate) ||
        pickedDate.isAfter(allowedEnd) ||
        pickedDate.weekday == DateTime.friday ||
        pickedDate.weekday == DateTime.saturday) {
      return;
    }

    // ------------------------------------------
    // VALID DATE SELECTED
    // ------------------------------------------
    selectedPauseDate = pickedDate;
    dateError = null;
    update();
  }

  bool isSameDate(DateTime a, DateTime b) {
    return a.year == b.year && a.month == b.month && a.day == b.day;
  }

  void showCustomSnackbar(String message) {
    ScaffoldMessenger.of(Get.context!)
        .clearSnackBars(); // Optional: clear previous
    ScaffoldMessenger.of(Get.context!).showSnackBar(
      SnackBar(
        elevation: 0,
        backgroundColor: Colors.transparent, // So we can use a custom container
        behavior: SnackBarBehavior.floating,
        // margin: const EdgeInsets.fromLTRB(16, 0, 16, 10), // Padding at bottom
        content: Container(
            padding: const EdgeInsets.all(12),
            decoration: BoxDecoration(
              color: Colors.white,
              borderRadius: BorderRadius.circular(12),
              border: Border.all(color: blackColor),
            ),
            child: AppText.smallParagraph(message,
                fontWeight: FontWeight.w500, color: buttonColor)),
        duration: const Duration(seconds: 1),
      ),
    );
  }
}
