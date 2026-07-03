import 'package:flutter_localization/flutter_localization.dart';
import 'package:jazz_smart_pay/app/custom_widget/app_text.dart';
import 'package:jazz_smart_pay/app/custom_widget/bouncing_button.dart';
import 'package:jazz_smart_pay/app/custom_widget/my_app_bar.dart';
import 'package:jazz_smart_pay/app/custom_widget/primary_button.dart';
import 'package:jazz_smart_pay/app/custom_widget/tablet_padding_widget.dart';
import 'package:jazz_smart_pay/app/modules/add_child/controllers/add_child_controller.dart';
import 'package:jazz_smart_pay/app/utils/app_const_colors.dart';
import 'package:jazz_smart_pay/app/utils/index.dart';
import 'package:jazz_smart_pay/exports.dart';

import '../../../custom_widget/app_dropdown.dart';
import '../../../custom_widget/app_text_field.dart';
import '../../../custom_widget/loading_circular_component.dart';
import '../../../utils/form_validation.dart';

class AddChildView extends GetView<AddChildLogic> {
  const AddChildView({super.key});
  @override
  Widget build(BuildContext context) {
    return GetBuilder<AddChildLogic>(
        init: AddChildLogic(),
        builder: (logic) {
          return GestureDetector(
            onTap: () => FocusScope.of(context).unfocus(),
            child: Scaffold(
              appBar: myAppBar(
                title: logic.student != null
                    ? "update_child_details".getString(context)
                    : "add_child_details".getString(context),
              ),
              backgroundColor: bgColor,
              body: logic.isGrades
                  ? const Center(child: LoadingCircularComponent())
                  : TabPadding(
                      child: SingleChildScrollView(
                        padding: EdgeInsets.all(getWidth(20)!),
                        child: Form(
                          key: logic.addFormKey,
                          child: Column(
                            crossAxisAlignment: CrossAxisAlignment.start,
                            children: [
                              Gap(getWidth(20)!),
                              Bouncing(
                                onTap: () {
                                  logic.uploadProfilePicture(context);
                                },
                                child: Center(
                                  child: Container(
                                    height: getWidth(120),
                                    width: getWidth(120),
                                    decoration: const BoxDecoration(
                                      color: textColor,
                                      shape: BoxShape.circle,
                                    ),
                                    child: logic.selectedFile != null
                                        ? ClipRRect(
                                            borderRadius:
                                                BorderRadiusGeometry.circular(
                                                    100),
                                            child:
                                                Image.file(logic.selectedFile!))
                                        : logic.studentImage != null &&
                                                logic.studentImage!.isNotEmpty
                                            ? ClipRRect(
                                                borderRadius:
                                                    BorderRadiusGeometry
                                                        .circular(100),
                                                child: Image.network(
                                                    logic.studentImage!))
                                            : Image.asset(
                                                ImagePath.camera,
                                                scale: 4.5,
                                                color: bgColor,
                                              ),
                                  ),
                                ),
                              ),
                              Gap(getWidth(40)!),
                              AppTextField(
                                controller: logic.nameController,
                                hintText: "name".getString(context),
                                validator: (value) =>
                                    FormValidation.notEmptyValidator(value),
                                textCapitalization: TextCapitalization.words,
                              ),
                              Gap(getWidth(20)!),
                              AppDropdownField(
                                items: logic.grades,
                                selectedValue: logic.selectedGrade,
                                hintText: "select_grade".getString(context),
                                onChanged: (value) {
                                  logic.selectedGrade = value;
                                  logic.update();
                                },
                              ),
                              Gap(getWidth(20)!),
                              AppTextField(
                                controller: logic.addmissionController,
                                hintText: "admission_no".getString(context),
                                keyboardType: TextInputType.text,
                              ),
                              Gap(getWidth(20)!),
                              AppTextField(
                                controller: logic.limitController,
                                hintText:
                                    "daily_spend_limit".getString(context),
                                validator: (value) =>
                                    FormValidation.notEmptyValidator(value),
                                keyboardType: TextInputType.number,
                              ),
                              Gap(getWidth(20)!),
                              AppTextField(
                                controller: logic.dobController,
                                hintText: "dob".getString(context),
                                validator: (value) =>
                                    FormValidation.notEmptyValidator(value),
                                keyboardType: TextInputType.text,
                                onTap: () {
                                  logic.selectDate(context);
                                },
                                readOnly: true,
                              ),
                              Gap(getWidth(20)!),
                              genderChips(
                                context,
                                genderOptions: logic.gender,
                                onSelected: (gender) {
                                  logic.selectedGender = gender;
                                  logic.update();
                                },
                                selectedGender: logic.selectedGender,
                              ),
                              Gap(getWidth(100)!),
                              PrimaryButton(
                                text: logic.student != null
                                    ? "update_child".getString(context)
                                    : "add_child".getString(context),
                                onTap: () {
                                  logic.loginSubmit();
                                },
                                isLoading: logic.isLoading,
                              ),
                            ],
                          ),
                        ),
                      ),
                    ),
            ),
          );
        });
  }

  Widget genderChips(
    BuildContext context, {
    required List<String> genderOptions,
    required String selectedGender,
    required Function(String) onSelected,
  }) {
    return Row(
      children: [
        AppText.heading2("${"gender".getString(context)}   : ",
            color: textColor),
        const Spacer(),
        Wrap(
          spacing: getWidth(24)!,
          children: genderOptions.map((gender) {
            final bool isSelected = selectedGender == gender;

            return GestureDetector(
              onTap: () => onSelected(gender),
              child: Container(
                alignment: Alignment.center,
                width: getWidth(100),
                padding: EdgeInsets.symmetric(vertical: getWidth(8)!),
                decoration: BoxDecoration(
                  color: isSelected ? buttonColor : Colors.transparent,
                  borderRadius: BorderRadius.circular(getWidth(12)!),
                  border: Border.all(
                      color: !isSelected ? hintColor : Colors.transparent,
                      width: 1.5),
                ),
                child: AppText.smallParagraph(
                  gender,
                  color: isSelected ? textColor : hintColor,
                  fontWeight: FontWeight.w600,
                ),
              ),
            );
          }).toList(),
        ),
      ],
    );
  }
}
