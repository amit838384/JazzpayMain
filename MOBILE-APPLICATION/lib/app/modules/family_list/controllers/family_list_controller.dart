import 'dart:io';
import 'package:flutter_localization/flutter_localization.dart';
import 'package:jazz_smart_pay/app/models/student_response.dart';
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
import '../sub_module/child_details.dart';

class FamilyListLogic extends GetxController {
  late TextEditingController searchController;
  late TextEditingController amountController;
  late TextEditingController foodController;

  @override
  void onInit() {
    searchController = TextEditingController();
    amountController = TextEditingController();
    foodController = TextEditingController();
    getStudents();
    foodsAPI();
    super.onInit();
  }

  bool isLoading = false;
  List<StudentResponse> students = [];

  getStudents() {
    isLoading = true;
    update();
    API().post("/student-list").then((value) async {
      Map<String, dynamic>? res = value.data;
      if (res != null) {
        if (res['status'] ?? false) {
          final List<dynamic> dataList = res['data'] ?? [];
          students = dataList.map((e) => StudentResponse.fromJson(e)).toList();
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

  void getStudentById(String id) {
    student = students.firstWhere(
      (s) => s.id == id,
      orElse: () => StudentResponse(),
    );

    if (student?.id == null) {
      student = null;
    }
  }

  StudentResponse? student;
  bool isDetailsLoading = false;
  getStudent(String id) {
    isDetailsLoading = true;
    update();
    API().post("/student-detail", data: {"studentID": id}).then((value) async {
      Map<String, dynamic>? res = value.data;
      if (res != null) {
        if (res['status'] ?? false) {
          student = StudentResponse.fromJson(res['data']);
          await Get.to(() => const ChildDetailsView());
          isDetailsLoading = false;
          update();
          ;
        } else {
          isDetailsLoading = false;
          update();
          Constants.errorDialog(message: res['message']);
        }
      } else {
        isDetailsLoading = false;
        update();
        Constants.errorDialog();
      }
    });
  }

  moveToChildDetails(StudentResponse studentRes) async {
    student = studentRes;
    for (var food in student!.restrictedFood ?? []) {
      apiSelectedFoods.add(food);
    }
    await Get.to(() => const ChildDetailsView());
    update();
  }

  List<String> allFoods = [];
  List<String> selectedFoods = [];
  List<String> apiSelectedFoods = [];
  List<String> filteredFoods = [];
  bool isFoods = false;

  foodsAPI() {
    isFoods = true;
    update();
    API().get("/restricted-food").then((value) async {
      Map<String, dynamic>? res = value.data;
      if (res != null) {
        if (res['status'] ?? false) {
          for (String food in res['data']) {
            allFoods.add(food);
          }
          filteredFoods = List.from(allFoods);
        } else {
          Constants.errorDialog(message: res['message']);
        }
      } else {
        Constants.errorDialog();
      }
      isFoods = false;
      update();
    });
  }

  updateFoodsAPI() {
    isLoading = true;
    update();
    API().post(
      "/add-restricted-food",
      data: {
        "id": student?.id,
        "food": selectedFoods.join(','),
      },
    ).then((value) async {
      Map<String, dynamic>? res = value.data;
      if (res != null) {
        if (res['status'] ?? false) {
          apiSelectedFoods.addAll(selectedFoods);
          getStudents();
        } else {
          isLoading = false;
          update();
          Constants.errorDialog(message: res['message']);
        }
      } else {
        isLoading = false;
        update();
        Constants.errorDialog();
      }
    });
  }

  void onSearchChanged(String query) {
    filteredFoods = allFoods
        .where((food) => food.toLowerCase().contains(query.toLowerCase()))
        .toList();
    update();
  }

  void toggleSelection(String food) {
    if (selectedFoods.contains(food)) {
      selectedFoods.remove(food);
    } else {
      selectedFoods.add(food);
    }
    update();
  }

  updateSpendLimit() {
    Get.back();
    isLoading = true;
    update();
    API().post(
      "/update-spend-limit-student",
      data: {
        "studentID": student?.id,
        "money": amountController.text.trim(),
      },
    ).then((value) async {
      Map<String, dynamic>? res = value.data;
      if (res != null) {
        if (res['status'] ?? false) {
          getStudents();
          ;
        } else {
          isLoading = false;
          update();
          Constants.errorDialog(message: res['message']);
        }
      } else {
        isLoading = false;
        update();
        Constants.errorDialog();
      }
    });
  }

  updateSpendLimitBottomSheet(BuildContext context) {
    Constants.bottomSheetWithHandle(context, builder: (context) {
      return GetBuilder<FamilyListLogic>(
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
                      AppText.heading2("daily_spend_limit".getString(context),
                          color: textColor, fontWeight: FontWeight.w700),
                      Gap(getWidth(12)!),
                      appDivider(color: textColor),
                      Gap(getWidth(12)!),
                      AppTextField(
                        controller: logic.amountController,
                        hintText: "amount".getString(context),
                        validator: (value) =>
                            FormValidation.notEmptyValidator(value),
                        keyboardType: TextInputType.number,
                        borderColor: textColor,
                        hintTextColor: textColor,
                        textStyleColor: textColor,
                        onChanged: (p0) {
                          update();
                        },
                      ),
                      Gap(getWidth(32)!),
                      PrimaryButton(
                        text: "update".getString(context),
                        onTap: () {
                          updateSpendLimit();
                        },
                        isDisabled: amountController.text.trim().isEmpty,
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

  updateRestrictedFoodtBottomSheet(BuildContext context) {
    selectedFoods.clear();
    selectedFoods.addAll(apiSelectedFoods);
    Constants.bottomSheetWithHandle(context, builder: (context) {
      return GetBuilder<FamilyListLogic>(
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
              child: ConstrainedBox(
                constraints: const BoxConstraints(maxHeight: 500),
                child: Padding(
                  padding: EdgeInsets.symmetric(horizontal: getWidth(20)!),
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      // Handle
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

                      // Title and search
                      AppText.paragraph(
                        "select_ingredients".getString(context),
                        color: textColor,
                        fontWeight: FontWeight.w500,
                        getfontSize: 18,
                      ),
                      Gap(getWidth(12)!),
                      appDivider(color: textColor),
                      Gap(getWidth(12)!),
                      AppTextField(
                        controller: logic.foodController,
                        hintText: "search".getString(context),
                        keyboardType: TextInputType.text,
                        borderColor: textColor,
                        hintTextColor: textColor,
                        textStyleColor: textColor,
                        onChanged: logic.onSearchChanged,
                      ),
                      Gap(getWidth(20)!),

                      // Scrollable list
                      Expanded(
                        child: ListView.builder(
                          padding: EdgeInsets.zero,
                          itemCount: logic.filteredFoods.length,
                          itemBuilder: (context, index) {
                            final food = logic.filteredFoods[index];
                            final isSelected =
                                logic.selectedFoods.contains(food);
                            return GestureDetector(
                              behavior: HitTestBehavior.translucent,
                              onTap: () => logic.toggleSelection(food),
                              child: Padding(
                                padding: EdgeInsets.only(
                                  right: getWidth(20)!,
                                  left: getWidth(20)!,
                                ),
                                child: Column(
                                  children: [
                                    Row(
                                      mainAxisAlignment:
                                          MainAxisAlignment.spaceBetween,
                                      children: [
                                        AppText.paragraph(
                                          food,
                                          getfontSize: 17,
                                          fontWeight: FontWeight.w600,
                                          color: textColor,
                                        ),
                                        if (isSelected)
                                          Image.asset(
                                            ImagePath.tick,
                                            color: textColor,
                                            height: getWidth(20),
                                            width: getWidth(20),
                                          )
                                      ],
                                    ),
                                    if (index != logic.filteredFoods.length - 1)
                                      Column(
                                        children: [
                                          Gap(getWidth(12)!),
                                          appDivider(color: textColor),
                                          Gap(getWidth(12)!),
                                        ],
                                      ),
                                  ],
                                ),
                              ),
                            );
                          },
                        ),
                      ),

                      // Sticky Button
                      Gap(getWidth(24)!),
                      PrimaryButton(
                        text: "update".getString(context),
                        onTap: () {
                          Get.back();
                          updateFoodsAPI();
                        },
                        isDisabled: selectedFoods.isEmpty,
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

  @override
  void dispose() {
    super.dispose();
  }
}
