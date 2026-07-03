import 'package:flutter_localization/flutter_localization.dart';
import 'package:jazz_smart_pay/app/custom_widget/bouncing_button.dart';
import 'package:jazz_smart_pay/app/custom_widget/fill_container.dart';
import 'package:jazz_smart_pay/app/custom_widget/my_app_bar.dart';
import 'package:jazz_smart_pay/app/modules/family_list/controllers/family_list_controller.dart';
import 'package:jazz_smart_pay/app/utils/app_const_colors.dart';
import 'package:jazz_smart_pay/app/utils/constant_vars.dart';
import 'package:jazz_smart_pay/app/utils/index.dart';
import '../../../../exports.dart';
import '../../../custom_widget/app_text.dart';
import '../../../custom_widget/loading_circular_component.dart';

class ChildDetailsView extends StatelessWidget {
  const ChildDetailsView({super.key});

  @override
  Widget build(BuildContext context) {
    return GetBuilder<FamilyListLogic>(builder: (logic) {
      return Scaffold(
        backgroundColor: textColor,
        appBar: myAppBar(
            title: "profile".getString(context),
            color: textColor,
            contentColor: bgColor),
        body: logic.isLoading == true
            ? const Center(
                child: LoadingCircularComponent(indicatorColor: bgColor))
            : Padding(
                padding: EdgeInsets.all(getWidth(20)!),
                child: Column(
                  children: [
                    Image.asset(
                      ImagePath.student,
                      height: getWidth(120),
                      width: getWidth(120),
                    ),
                    Gap(getWidth(24)!),
                    FillContainer(
                      margin: EdgeInsets.only(bottom: getWidth(24)!),
                      child: Row(
                        children: [
                          Expanded(
                            child: Column(
                              crossAxisAlignment: CrossAxisAlignment.start,
                              children: [
                                Row(
                                  mainAxisAlignment:
                                      MainAxisAlignment.spaceBetween,
                                  children: [
                                    AppText.heading2(logic.student!.name ?? "",
                                        fontWeight: FontWeight.w700,
                                        getfontSize: 18),
                                    _editButton(
                                      onTap: () async {
                                        final value = await Get.toNamed(
                                          Routes.ADD_CHILD,
                                          arguments: logic.student,
                                        );
                                        if (value != null && value) {
                                          Get.back();
                                          await logic.getStudents();
                                        }
                                      },
                                    ),
                                  ],
                                ),
                                // Admission Info
                                Row(
                                  children: [
                                    AppText.paragraph(
                                        "${"admission_no".getString(context)} : "),
                                    AppText.paragraph(
                                        logic.student!.admissionNo ?? "",
                                        fontWeight: FontWeight.w700),
                                  ],
                                ),
                                Gap(getWidth(4)!),
                                Row(
                                  children: [
                                    AppText.paragraph(
                                        "${"grade".getString(context)} : "),
                                    AppText.paragraph(
                                        logic.student!.grade ?? "",
                                        fontWeight: FontWeight.w700),
                                  ],
                                ),
                                Gap(getWidth(4)!),
                                Row(
                                  children: [
                                    AppText.paragraph(
                                        "${"dob".getString(context)} : "),
                                    AppText.paragraph(logic.student!.dob ?? "",
                                        fontWeight: FontWeight.w700),
                                  ],
                                ),
                              ],
                            ),
                          ),
                        ],
                      ),
                    ),
                    if (!Constants.isIpad) ...[
                      _spendLimit(context, logic),
                      _restrictedFoods(context, logic),
                    ],
                    if (Constants.isIpad)
                      IntrinsicHeight(
                        child: Row(
                          children: [
                            Expanded(child: _spendLimit(context, logic)),
                            Gap(getWidth(30)!),
                            Expanded(child: _restrictedFoods(context, logic)),
                          ],
                        ),
                      )
                  ],
                ),
              ),
      );
    });
  }

  _spendLimit(BuildContext context, FamilyListLogic logic) {
    return FillContainer(
      margin: EdgeInsets.only(bottom: getWidth(24)!),
      child: Row(
        children: [
          Expanded(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Row(
                  mainAxisAlignment: MainAxisAlignment.spaceBetween,
                  children: [
                    AppText.heading2("daily_spend_limit".getString(context),
                        fontWeight: FontWeight.w700, getfontSize: 18),
                    _editButton(
                      onTap: () async {
                        logic.updateSpendLimitBottomSheet(context);
                      },
                    ),
                  ],
                ),
                AppText.paragraph(
                  "QAR ${logic.student!.dailySpendLimit ?? ""}",
                  fontWeight: FontWeight.w700,
                  color: bgColor,
                ),
              ],
            ),
          ),
        ],
      ),
    );
  }

  _restrictedFoods(BuildContext context, FamilyListLogic logic) {
    return FillContainer(
      margin: EdgeInsets.only(bottom: getWidth(24)!),
      child: Row(
        children: [
          Expanded(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Row(
                  mainAxisAlignment: MainAxisAlignment.spaceBetween,
                  children: [
                    AppText.heading2("restricted_food".getString(context),
                        fontWeight: FontWeight.w700, getfontSize: 18),
                    _editButton(
                      onTap: () async {
                        logic.updateRestrictedFoodtBottomSheet(context);
                      },
                    ),
                  ],
                ),
                if (logic.apiSelectedFoods.isNotEmpty)
                  Padding(
                    padding: EdgeInsets.only(top: getWidth(8)!),
                    child: AppText.paragraph(
                      logic.apiSelectedFoods.join(', '),
                      fontWeight: FontWeight.w600,
                      color: bgColor,
                    ),
                  )
              ],
            ),
          ),
        ],
      ),
    );
  }

  _editButton({required void Function() onTap}) {
    return Bouncing(
      onTap: onTap,
      child: Image.asset(
        ImagePath.edit,
        height: getWidth(24),
        width: getWidth(24),
        color: bgColor,
      ),
    );
  }
}
