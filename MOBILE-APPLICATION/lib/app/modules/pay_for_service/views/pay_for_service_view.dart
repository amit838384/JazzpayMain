import 'package:flutter/material.dart';
import 'package:flutter_localization/flutter_localization.dart';
import 'package:gap/gap.dart';
import 'package:get/get.dart';
import 'package:jazz_smart_pay/app/custom_widget/app_text.dart';
import 'package:jazz_smart_pay/app/custom_widget/fill_container.dart';
import 'package:jazz_smart_pay/app/custom_widget/my_app_bar.dart';
import 'package:jazz_smart_pay/app/custom_widget/primary_button.dart';
import 'package:jazz_smart_pay/app/utils/app_const_colors.dart';
import 'package:jazz_smart_pay/app/utils/index.dart';
import '../../../custom_widget/loading_circular_component.dart';
import '../controllers/pay_for_service_controller.dart';

class PayForServiceView extends GetView<PayForServiceLogic> {
  const PayForServiceView({super.key});
  @override
  Widget build(BuildContext context) {
    return GetBuilder<PayForServiceLogic>(
        init: PayForServiceLogic(),
        builder: (logic) {
          return GestureDetector(
            onTap: () => FocusScope.of(context).unfocus(),
            child: Scaffold(
              appBar: myAppBar(
                title: 'pay_for_service'.getString(context),
                backTap: () {
                  if (logic.selectedIndex == -1) {
                    Get.back();
                  } else {
                    logic.selectedIndex = -1;
                    logic.update();
                  }
                },
              ),
              backgroundColor: textColor,
              body: logic.isLoading || logic.isStudents
                  ? const Center(
                      child:
                          LoadingCircularComponent(indicatorColor: buttonColor))
                  : SingleChildScrollView(
                      padding: EdgeInsets.all(getWidth(12)!),
                      child: Column(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          if (logic.selectedIndex == -1) ...[
                            if (logic.servicePlans?['data'].isEmpty)
                              Center(
                                child: Column(
                                  children: [
                                    Gap(getWidth(300)!),
                                    Image.asset(
                                      ImagePath.service,
                                      scale: 2.5,
                                    ),
                                    Gap(getWidth(20)!),
                                    AppText.heading2(
                                        'no_plan'.getString(context))
                                  ],
                                ),
                              ),
                            AppText.heading2(
                              'pay_for_service'.getString(context),
                              fontWeight: FontWeight.w700,
                              getfontSize: 20,
                              color: bgColor,
                            ),
                            Gap(getWidth(6)!),
                            AppText.heading2(
                              'service_text'.getString(context),
                              fontWeight: FontWeight.w500,
                              getfontSize: 14,
                              color: bgColor,
                            ),
                            Gap(getWidth(16)!),
                            if (logic.servicePlans?['data'].isNotEmpty)
                              ...List.generate(
                                logic.servicePlans?['data'].length,
                                (index) {
                                  var plan = logic.servicePlans?['data'][index];
                                  return FillContainer(
                                    backgroundColor: lightBgColor,
                                    margin:
                                        EdgeInsets.only(bottom: getWidth(20)!),
                                    child: Column(
                                      children: [
                                        Row(
                                          mainAxisAlignment:
                                              MainAxisAlignment.spaceBetween,
                                          children: [
                                            AppText.heading2(
                                              '${'service'.getString(context)} : ${plan['name']}',
                                              fontWeight: FontWeight.w700,
                                              getfontSize: 15,
                                              color: bgColor,
                                            ),
                                            AppText.heading2(
                                              '${'meal'.getString(context)} : ${plan['meals']}',
                                              fontWeight: FontWeight.w700,
                                              getfontSize: 15,
                                              color: bgColor,
                                            ),
                                          ],
                                        ),
                                        Gap(getWidth(8)!),
                                        Row(
                                          mainAxisAlignment:
                                              MainAxisAlignment.spaceBetween,
                                          children: [
                                            AppText.heading2(
                                              '${'duration'.getString(context)} : ${plan['duration_days']} days',
                                              fontWeight: FontWeight.w700,
                                              getfontSize: 15,
                                              color: bgColor,
                                            ),
                                            AppText.heading2(
                                              '${'price'.getString(context)} : QAR ${plan['price']}',
                                              fontWeight: FontWeight.w700,
                                              getfontSize: 15,
                                              color: bgColor,
                                            ),
                                          ],
                                        ),
                                        Gap(getWidth(20)!),
                                        PrimaryButton(
                                          onTap: () {
                                            logic.selectedIndex = index;
                                            logic.update();
                                          },
                                          verticalPaddingGet: 10,
                                          getBorderRadius: 10,
                                          textSize: 14,
                                          text: 'pay_for_service'
                                              .getString(context),
                                        )
                                      ],
                                    ),
                                  );
                                },
                              ),
                          ] else ...[
                            _bookingWidget(
                                context,
                                logic,
                                logic.servicePlans?['data']
                                    [logic.selectedIndex])
                          ],
                          Gap(getWidth(50)!),
                        ],
                      ),
                    ),
              bottomNavigationBar: logic.selectedIndex == -1 || logic.isLoading
                  ? null
                  : Padding(
                      padding: EdgeInsets.all(getWidth(20)!),
                      child: Column(
                        mainAxisSize: MainAxisSize.min,
                        children: [
                          PrimaryButton(
                            onTap: () {
                              logic.purchasePlanAPI(context);
                            },
                            text: 'pay_for_service'.getString(context),
                          )
                        ],
                      ),
                    ),
            ),
          );
        });
  }

  _bookingWidget(BuildContext context, PayForServiceLogic logic, dynamic plan) {
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        AppText.heading2(
          'selected_service'.getString(context),
          fontWeight: FontWeight.w700,
          getfontSize: 16,
          color: bgColor,
        ),
        Gap(getWidth(12)!),
        FillContainer(
          backgroundColor: lightBgColor,
          margin: EdgeInsets.only(bottom: getWidth(20)!),
          child: Column(
            children: [
              Row(
                mainAxisAlignment: MainAxisAlignment.spaceBetween,
                children: [
                  AppText.heading2(
                    '${'service'.getString(context)} : ${plan['name']}',
                    fontWeight: FontWeight.w700,
                    getfontSize: 15,
                    color: bgColor,
                  ),
                  AppText.heading2(
                    '${'meal'.getString(context)} : ${plan['meals']}',
                    fontWeight: FontWeight.w700,
                    getfontSize: 15,
                    color: bgColor,
                  ),
                ],
              ),
              Gap(getWidth(8)!),
              Row(
                mainAxisAlignment: MainAxisAlignment.spaceBetween,
                children: [
                  AppText.heading2(
                    '${'duration'.getString(context)} : ${plan['duration_days']} days',
                    fontWeight: FontWeight.w700,
                    getfontSize: 15,
                    color: bgColor,
                  ),
                  AppText.heading2(
                    '${'price'.getString(context)} : QAR ${plan['price']}',
                    fontWeight: FontWeight.w700,
                    getfontSize: 15,
                    color: bgColor,
                  ),
                ],
              ),
            ],
          ),
        ),
        AppText.heading2(
          'selected_student'.getString(context),
          fontWeight: FontWeight.w700,
          getfontSize: 16,
          color: bgColor,
        ),
        Gap(getWidth(12)!),
        FillContainer(
            child: Row(
          mainAxisAlignment: MainAxisAlignment.spaceBetween,
          children: [
            AppText.heading2(
              logic.students[logic.selectedStudent]['name'],
              fontWeight: FontWeight.w700,
              getfontSize: 16,
              color: bgColor,
            ),
            GestureDetector(
              onTap: () {
                logic.studentSelectionBottomSheet(context);
              },
              child: AppText.heading2(
                'change'.getString(context),
                fontWeight: FontWeight.w700,
                getfontSize: 16,
                color: bgColor,
              ),
            ),
          ],
        )),
      ],
    );
  }
}
