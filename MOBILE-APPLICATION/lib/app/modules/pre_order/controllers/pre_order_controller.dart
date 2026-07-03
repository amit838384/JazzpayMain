import 'dart:io';
import 'dart:math';

import 'package:intl/intl.dart';
import 'package:jazz_smart_pay/app/custom_widget/bouncing_button.dart';
import 'package:jazz_smart_pay/app/custom_widget/dialog_loader.dart';
import 'package:jazz_smart_pay/app/custom_widget/fill_container.dart';
import 'package:jazz_smart_pay/app/custom_widget/primary_button.dart';
import 'package:omni_datetime_picker/omni_datetime_picker.dart';
import '../../../../exports.dart';
import '../../../custom_widget/app_divider.dart';
import '../../../custom_widget/app_text.dart';
import '../../../dio_api/dio_api.dart';
import '../../../utils/app_const_colors.dart';
import '../../../utils/app_size.dart';
import '../../../utils/constant_vars.dart';
import '../../../utils/image_path.dart';

class PreOrderLogic extends GetxController {
  String selectedAmount = '';
  int cartQuantity = 0;
  @override
  void onInit() {
    initDate();
    getCategories();
    super.onInit();
  }

  List<dynamic> students = [];
  int selectedStudent = 0;
  bool isLoading = false;
  Map<String, dynamic>? catRes;
  getCategories() {
    isLoading = true;
    update();
    API().get("/all-category").then((value) async {
      Map<String, dynamic>? res = value.data;
      if (res != null) {
        catRes = res;
        if (res['status'] ?? false) {
          if (catRes!['data'].isNotEmpty) {
            students.clear();
            students.addAll(catRes!['Student']);
            getCategoryData();
          } else {
            isLoading = false;
            update();
          }
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

  Map<String, dynamic>? dataRes;
  getCategoryData() {
    isLoading = true;
    update();
    API().post(
      "/category-wise-dish",
      data: {
        "category_id": catRes!['data'][selectedCategory]['id'],
        "student_id": students[selectedStudent]['id'].toString(),
        "date": selectedDateValue,
      },
    ).then((value) async {
      Map<String, dynamic>? res = value.data;
      if (res != null) {
        dataRes = res;
        if (res['status'] ?? false) {
          cartQuantity = res['total_dish_qty'];
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

  preOrderAPI({required dynamic data}) {
    LoadingBuilder.showLoadingIndicator();
    API().post(
      "/pre-order",
      data: {
        "student_id": students[selectedStudent]['id'].toString(),
        "dish_id": data['id'].toString(),
        "date": selectedDateValue,
        "qty": "1",
        "addons": selectedItemsString,
      },
    ).then((value) async {
      Map<String, dynamic>? res = value.data;
      if (res != null) {
        if (res['status'] ?? false) {
          cartQuantity = res['total_dish_qty'];
          data['qty'] = data['qty'] + 1;
          selectedItemsString = "";
          update();
          LoadingBuilder.hideOpenDialog();
        } else {
          LoadingBuilder.hideOpenDialog();
          Constants.errorDialog(message: res['message']);
        }
      } else {
        LoadingBuilder.hideOpenDialog();
        Constants.errorDialog();
      }
      update();
    });
  }

  preOrderDecreaseAPI({required dynamic data}) {
    LoadingBuilder.showLoadingIndicator();
    API().post(
      "/dish-decrease",
      data: {
        "student_id": students[selectedStudent]['id'].toString(),
        "dish_id": data['id'].toString(),
        "date": selectedDateValue,
      },
    ).then((value) async {
      Map<String, dynamic>? res = value.data;
      if (res != null) {
        if (res['status'] ?? false) {
          cartQuantity = res['total_dish_qty'];
          data['qty'] = data['qty'] - 1;
          update();
          LoadingBuilder.hideOpenDialog();
        } else {
          LoadingBuilder.hideOpenDialog();
          Constants.errorDialog(message: res['message']);
        }
      } else {
        LoadingBuilder.hideOpenDialog();
        Constants.errorDialog();
      }
      update();
    });
  }

  String selectedDateValue = "";

  void initDate() {
    DateTime today = DateTime.now();
    DateTime candidateDate = today.add(const Duration(days: 1));

    // Skip if Friday (5) or Saturday (6)
    while (candidateDate.weekday == DateTime.friday ||
        candidateDate.weekday == DateTime.saturday) {
      candidateDate = candidateDate.add(const Duration(days: 1));
    }

    String formattedDate = DateFormat('dd MMM yyyy').format(candidateDate);
    selectedDateValue = formattedDate;
  }

  DateTime? dateValue;

  Future<void> selectDate(BuildContext context) async {
    final DateTime today = DateTime.now();
    DateTime candidate = today.add(const Duration(days: 1));

    // Skip Friday (5) and Saturday (6)
    while (candidate.weekday == DateTime.friday ||
        candidate.weekday == DateTime.saturday) {
      candidate = candidate.add(const Duration(days: 1));
    }

    final DateTime initialDate = dateValue ?? candidate;

    DateTime? pickedDate = await showOmniDateTimePicker(
      context: context,
      is24HourMode: false,
      isShowSeconds: false,
      isForce2Digits: true,
      borderRadius: const BorderRadius.all(Radius.circular(16)),
      constraints: const BoxConstraints(
        maxWidth: 350,
        maxHeight: 650,
      ),
      type: OmniDateTimePickerType.date,
      firstDate: candidate,
      lastDate: DateTime(2100),
      initialDate: initialDate,
    );

    if (pickedDate != null) {
      if (pickedDate.weekday == DateTime.friday ||
          pickedDate.weekday == DateTime.saturday) {
        showCustomSnackbar(
          "We’re sorry, but we don’t accept pre-orders for Fridays and Saturdays as the café remains closed on those days.",
        );
        return;
      }

      if (dateValue == null || !isSameDate(dateValue!, pickedDate)) {
        String formattedDate = DateFormat('dd MMM yyyy').format(pickedDate);
        dateValue = pickedDate;
        selectedDateValue = formattedDate;
        // Call API here only if the date has changed
        await getCategoryData();
      }
    }

    update();
  }

// Utility to compare dates without time
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
  // Future<void> selectDate(BuildContext context) async {
  //   final DateTime today = DateTime.now();
  //   final DateTime tomorrow = today.add(const Duration(days: 1));

  //   // Use stored value if available, else default to tomorrow
  //   final DateTime initialDate = dateValue ?? tomorrow;

  //   DateTime? pickedDate = await showOmniDateTimePicker(
  //     context: context,
  //     is24HourMode: false,
  //     isShowSeconds: false,
  //     isForce2Digits: true,
  //     borderRadius: const BorderRadius.all(Radius.circular(16)),
  //     constraints: const BoxConstraints(
  //       maxWidth: 350,
  //       maxHeight: 650,
  //     ),
  //     type: OmniDateTimePickerType.date,
  //     firstDate: tomorrow,
  //     lastDate: DateTime(2100),
  //     initialDate: initialDate,
  //   );

  //   if (pickedDate != null) {
  //     String formattedDate = DateFormat('dd MMM yyyy').format(pickedDate);
  //     dateValue = pickedDate; // Hold the selected date
  //     selectedDateValue = formattedDate; // optional: update UI field
  //   }

  //   update(); // assuming this is inside a GetX controller
  // }
  List<String>? selectedItems = [];
  String selectedItemsString = "";
  void multiSelectBottomSheet(
    BuildContext context, {
    required List<dynamic> items,
    required dynamic food,
  }) {
    // ❗ Clear previous selections every time
    selectedItems = [];
    selectedItemsString = "";

    Constants.bottomSheetWithHandle(context, builder: (context) {
      return GetBuilder<PreOrderLogic>(
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
              child: Column(
                mainAxisSize: MainAxisSize.min,
                children: [
                  /// Handle
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

                  /// List
                  Flexible(
                    child: SingleChildScrollView(
                      child: Padding(
                        padding:
                            EdgeInsets.symmetric(horizontal: getWidth(20)!),
                        child: Column(
                          children: List.generate(items.length, (index) {
                            final name = items[index];
                            final isSelected = selectedItems!.contains(name);

                            return Column(
                              children: [
                                Bouncing(
                                  onTap: () {
                                    if (isSelected) {
                                      selectedItems!.remove(name);
                                    } else {
                                      selectedItems!.add(name);
                                    }
                                    update();
                                  },
                                  child: Row(
                                    children: [
                                      AppText.paragraph(
                                        name,
                                        fontWeight: FontWeight.w600,
                                        color: textColor,
                                      ),
                                      const Spacer(),
                                      Checkbox(
                                        value: isSelected,
                                        checkColor: whiteColor,
                                        activeColor: buttonColor,
                                        onChanged: (v) {
                                          if (v == true) {
                                            selectedItems!.add(name);
                                          } else {
                                            selectedItems!.remove(name);
                                          }
                                          update();
                                        },
                                      ),
                                    ],
                                  ),
                                ),
                                if (index != items.length - 1) ...[
                                  Gap(getWidth(12)!),
                                  appDivider(),
                                  Gap(getWidth(12)!),
                                ],
                              ],
                            );
                          }),
                        ),
                      ),
                    ),
                  ),

                  /// Add Button
                  Padding(
                      padding: EdgeInsets.symmetric(
                        horizontal: getWidth(20)!,
                        vertical: getWidth(14)!,
                      ),
                      child: PrimaryButton(
                        text: "Add",
                        onTap: () {
                          selectedItemsString = selectedItems!.join(", ");
                          update();
                          Get.back();
                          logic.preOrderAPI(data: food);
                        },
                      )),
                ],
              ),
            ),
          );
        },
      );
    });
  }

  int selectedCategory = 0;
  transferCreditBottomSheet(BuildContext context) {
    Constants.bottomSheetWithHandle(context, builder: (context) {
      return GetBuilder<PreOrderLogic>(
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
                      ...List.generate(
                        logic.catRes!['data'].length,
                        (index) {
                          var cat = logic.catRes!['data'][index];
                          return Column(
                            children: [
                              Bouncing(
                                onTap: () {
                                  logic.selectedCategory = index;
                                  logic.update();
                                  Get.back();
                                  getCategoryData();
                                },
                                child: Row(
                                  children: [
                                    AppText.paragraph(
                                      cat['name'],
                                      fontWeight: FontWeight.w600,
                                      color: textColor,
                                    ),
                                    const Spacer(),
                                    if (index == logic.selectedCategory)
                                      Image.asset(
                                        ImagePath.tick,
                                        color: textColor,
                                        height: getWidth(20),
                                        width: getWidth(20),
                                      )
                                  ],
                                ),
                              ),
                              if (index !=
                                  logic.catRes!['data'].length - 1) ...[
                                Gap(getWidth(12)!),
                                appDivider(),
                                Gap(getWidth(12)!)
                              ],
                            ],
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

  studentSelectionBottomSheet(BuildContext context) {
    final double imageSize = getWidth(48)!;
    Constants.bottomSheetWithHandle(context, builder: (context) {
      return GetBuilder<PreOrderLogic>(
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
                              logic.getCategoryData();
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
}
