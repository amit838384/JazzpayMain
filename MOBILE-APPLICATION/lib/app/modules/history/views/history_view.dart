import 'package:flutter/material.dart';
import 'package:flutter_localization/flutter_localization.dart';
import 'package:gap/gap.dart';
import 'package:get/get.dart';
import 'package:jazz_smart_pay/app/custom_widget/bouncing_button.dart';
import 'package:jazz_smart_pay/app/custom_widget/my_app_bar.dart';
import 'package:jazz_smart_pay/app/custom_widget/status_button.dart';
import 'package:jazz_smart_pay/app/utils/app_const_colors.dart';
import 'package:jazz_smart_pay/app/utils/index.dart';
import '../../../custom_widget/app_text.dart';
import '../../../custom_widget/fill_container.dart';
import '../../../custom_widget/loading_circular_component.dart';
import '../../../custom_widget/primary_button.dart';
import '../../../utils/constant_vars.dart';
import '../controllers/history_controller.dart';

class HistoryView extends GetView<HistoryLogic> {
  const HistoryView({super.key});
  @override
  Widget build(BuildContext context) {
    return GetBuilder<HistoryLogic>(
        init: HistoryLogic(),
        builder: (logic) {
          return GestureDetector(
            onTap: () => FocusScope.of(context).unfocus(),
            child: Scaffold(
              appBar: myAppBar(
                title: logic.studentId != null
                    ? 'reports'.getString(context)
                    : 'history'.getString(context),
                color: textColor,
                contentColor: bgColor,
              ),
              backgroundColor: textColor,
              body: Column(
                children: [
                  SingleChildScrollView(
                    scrollDirection: Axis.horizontal,
                    child: Row(
                      children: List.generate(
                        logic.studentId != null
                            ? logic.reportsTabs.length
                            : logic.historyTabs.length,
                        (index) {
                          final bool isLastTab = logic.studentId != null
                              ? index == logic.reportsTabs.length - 1
                              : index == logic.historyTabs.length - 1;
                          return Bouncing(
                            onTap: () {
                              logic.updateTabIndex(index);
                            },
                            child: Container(
                                margin: EdgeInsetsDirectional.only(
                                    start: index == 0
                                        ? getWidth(20)!
                                        : getWidth(10)!,
                                    end: isLastTab
                                        ? getWidth(20)!
                                        : getWidth(10)!),
                                padding: EdgeInsets.symmetric(
                                  horizontal: getWidth(16)!,
                                  vertical: getWidth(12)!,
                                ),
                                decoration: BoxDecoration(
                                  borderRadius: BorderRadius.circular(
                                    getWidth(12)!,
                                  ),
                                  color: logic.selectedTab == index
                                      ? buttonColor
                                      : lightBgColor,
                                ),
                                child: AppText.paragraph(
                                  logic.historyTabs[index],
                                  fontWeight: FontWeight.w600,
                                  color: logic.selectedTab == index
                                      ? textColor
                                      : bgColor,
                                )),
                          );
                        },
                      ),
                    ),
                  ),
                  Gap(getWidth(12)!),
                  logic.isLoading
                      ? Center(
                          child: Column(
                          children: [
                            Gap(getWidth(400)!),
                            const LoadingCircularComponent(
                                indicatorColor: buttonColor),
                          ],
                        ))
                      : Expanded(
                          child: SingleChildScrollView(
                            child: Column(
                              crossAxisAlignment: CrossAxisAlignment.start,
                              children: [
                                Padding(
                                  padding: EdgeInsets.symmetric(
                                      horizontal: getWidth(20)!),
                                  child: Column(
                                    children: [
                                      Gap(getWidth(20)!),
                                      if (logic.selectedTab == 0) ...[
                                        if (Constants.isIpad)
                                          GridView.builder(
                                            itemCount:
                                                logic.creditRes!['data'].length,
                                            gridDelegate:
                                                const SliverGridDelegateWithFixedCrossAxisCount(
                                              crossAxisCount:
                                                  2, // Number of columns in grid
                                              crossAxisSpacing: 10,
                                              mainAxisSpacing: 10,
                                              childAspectRatio:
                                                  3.7, // Adjust for card shape
                                            ),
                                            shrinkWrap: true,
                                            physics:
                                                const NeverScrollableScrollPhysics(), // Prevents nested scrolling
                                            itemBuilder: (context, index) {
                                              var credit = logic
                                                  .creditRes!['data'][index];
                                              return _creditTransferTile(
                                                  logic, context, credit);
                                            },
                                          ),
                                        if (!Constants.isIpad)
                                          ...List.generate(
                                            logic.creditRes!['data'].length,
                                            (index) {
                                              var credit = logic
                                                  .creditRes!['data'][index];
                                              return _creditTransferTile(
                                                  logic, context, credit);
                                            },
                                          ),
                                        if (logic.creditRes!['data'].isEmpty)
                                          _emptyPlaceHolder(
                                            icon: ImagePath.credit,
                                            title: 'credit_transfer'
                                                .getString(context),
                                            description: 'credit_transfer_text'
                                                .getString(context),
                                          ),
                                        Gap(getWidth(40)!),
                                      ],
                                      if (logic.selectedTab == 1) ...[
                                        if (Constants.isIpad)
                                          GridView.builder(
                                            itemCount: logic
                                                .preOrderRes!['data'].length,
                                            gridDelegate:
                                                const SliverGridDelegateWithFixedCrossAxisCount(
                                              crossAxisCount:
                                                  2, // Number of columns in grid
                                              crossAxisSpacing: 10,
                                              mainAxisSpacing: 10,
                                              childAspectRatio:
                                                  2.5, // Adjust for card shape
                                            ),
                                            shrinkWrap: true,
                                            physics:
                                                const NeverScrollableScrollPhysics(), // Prevents nested scrolling
                                            itemBuilder: (context, index) {
                                              var order = logic
                                                  .preOrderRes!['data'][index];
                                              return _preOrderTile(
                                                  logic, context, order);
                                            },
                                          ),
                                        if (!Constants.isIpad)
                                          ...List.generate(
                                            logic.preOrderRes!['data'].length,
                                            (index) {
                                              var order = logic
                                                  .preOrderRes!['data'][index];
                                              return _preOrderTile(
                                                  logic, context, order);
                                            },
                                          ),
                                        if (logic.preOrderRes!['data'].isEmpty)
                                          _emptyPlaceHolder(
                                            icon: ImagePath.cancel,
                                            title:
                                                'pre_order'.getString(context),
                                            description: 'pre_order_text'
                                                .getString(context),
                                          ),
                                        Gap(getWidth(40)!),
                                      ],
                                      if (logic.selectedTab == 2) ...[
                                        _emptyPlaceHolder(
                                          icon: ImagePath.menu,
                                          title:
                                              'consumptions'.getString(context),
                                          description: 'consumptions_text'
                                              .getString(context),
                                        )
                                        // _cunsumptionTile(logic, context),
                                        // _cunsumptionTile(logic, context)
                                      ],
                                      if (logic.selectedTab == 3) ...[
                                        if (logic.serviceRes!['data'].isEmpty)
                                          _emptyPlaceHolder(
                                            icon: ImagePath.service,
                                            title: 'pay_for_service'
                                                .getString(context),
                                            description: 'pay_for_service_text'
                                                .getString(context),
                                          ),
                                        if (!Constants.isIpad)
                                          ...List.generate(
                                            logic.serviceRes!['data'].length,
                                            (index) {
                                              var service = logic
                                                  .serviceRes!['data'][index];
                                              return _payForServiceTile(
                                                  logic, context, service);
                                            },
                                          ),
                                      ],
                                      if (logic.selectedTab == 4) ...[
                                        _emptyPlaceHolder(
                                          icon: ImagePath.food,
                                          title: 'cafeteria_topups'
                                              .getString(context),
                                          description: 'cafeteria_topups_text'
                                              .getString(context),
                                        )
                                      ],
                                      if (logic.selectedTab == 5) ...[
                                        if (Constants.isIpad)
                                          GridView.builder(
                                            itemCount:
                                                logic.walletRes!['data'].length,
                                            gridDelegate:
                                                const SliverGridDelegateWithFixedCrossAxisCount(
                                              crossAxisCount: 2,
                                              crossAxisSpacing: 10,
                                              mainAxisSpacing: 10,
                                              childAspectRatio: 3.8,
                                            ),
                                            shrinkWrap: true,
                                            physics:
                                                const NeverScrollableScrollPhysics(),
                                            itemBuilder: (context, index) {
                                              var wallet = logic
                                                  .walletRes!['data'][index];
                                              return _walletTransactionTile(
                                                  logic, context, wallet);
                                            },
                                          ),
                                        if (!Constants.isIpad)
                                          ...List.generate(
                                            logic.walletRes!['data'].length,
                                            (index) {
                                              var wallet = logic
                                                  .walletRes!['data'][index];
                                              return _walletTransactionTile(
                                                  logic, context, wallet);
                                            },
                                          ),
                                        if (logic.walletRes!['data'].isEmpty)
                                          _emptyPlaceHolder(
                                            icon: ImagePath.walletRemove,
                                            title: 'wallet_transactions'
                                                .getString(context),
                                            description:
                                                'wallet_transactions_text'
                                                    .getString(context),
                                          ),
                                        Gap(getWidth(40)!),
                                      ],
                                    ],
                                  ),
                                ),
                              ],
                            ),
                          ),
                        ),
                ],
              ),
            ),
          );
        });
  }

  Widget _creditTransferTile(
      HistoryLogic logic, BuildContext context, dynamic credit) {
    return FillContainer(
      margin: EdgeInsets.only(bottom: getWidth(20)!),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Row(
            mainAxisAlignment: MainAxisAlignment.spaceBetween,
            children: [
              AppText.heading2(
                "QAR ${credit['amount']}",
                fontWeight: FontWeight.w700,
                getfontSize: 18,
                color: bgColor,
              ),
              AppText.paragraph(
                credit['transferred_at'],
                fontWeight: FontWeight.w700,
              ),
            ],
          ),
          Gap(getWidth(6)!),
          Row(
            children: [
              AppText.paragraph("${'to'.getString(context)} : "),
              AppText.paragraph(
                credit['name'],
                fontWeight: FontWeight.w600,
              ),
            ],
          ),
        ],
      ),
    );
  }

  Widget _preOrderTile(
      HistoryLogic logic, BuildContext context, dynamic order) {
    return FillContainer(
      margin: EdgeInsets.only(bottom: getWidth(20)!),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Row(
            mainAxisAlignment: MainAxisAlignment.spaceBetween,
            children: [
              AppText.heading2(
                order['Ordered_On'],
                fontWeight: FontWeight.w700,
                getfontSize: 17,
                color: bgColor,
              ),
              StatusButton(
                text: order['Payment_Status'] == "1"
                    ? 'success'.getString(context)
                    : 'failed'.getString(context),
                color: order['Payment_Status'] == "1"
                    ? const Color(0xFF28a745)
                    : redColor,
              )
            ],
          ),
          Gap(getWidth(6)!),
          Row(
            children: [
              Expanded(
                child: AppText.paragraph(
                  order['Dish_Name'],
                  fontWeight: FontWeight.w600,
                ),
              ),
              AppText.paragraph(order['qty'], fontWeight: FontWeight.w500),
              Gap(getWidth(16)!),
              AppText.paragraph(order['Dish_Price'],
                  color: bgColor, fontWeight: FontWeight.w700)
            ],
          ),
          Gap(getWidth(6)!),
          Row(
            children: [
              AppText.paragraph("${'to'.getString(context)} : "),
              Expanded(
                child: AppText.paragraph(
                  order['Student_Name'],
                  fontWeight: FontWeight.w600,
                ),
              ),
              AppText.paragraph(order['Payment'],
                  color: bgColor, fontWeight: FontWeight.w700)
            ],
          ),
          Gap(getWidth(6)!),
          Row(
            children: [
              AppText.paragraph("${'date'.getString(context)} : "),
              Expanded(
                child: AppText.paragraph(
                  order['Date'],
                  fontWeight: FontWeight.w600,
                ),
              ),
            ],
          ),
        ],
      ),
    );
  }

  Widget _cunsumptionTile(HistoryLogic logic, BuildContext context) {
    return FillContainer(
      margin: EdgeInsets.only(bottom: getWidth(20)!),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Row(
            mainAxisAlignment: MainAxisAlignment.spaceBetween,
            children: [
              AppText.heading2(
                "15 Jun 2025",
                fontWeight: FontWeight.w700,
                getfontSize: 17,
                color: bgColor,
              ),
              const StatusButton(text: "Success")
            ],
          ),
          Gap(getWidth(6)!),
          Row(
            children: [
              Expanded(
                child: AppText.paragraph(
                  "Chocolate Croissant",
                  fontWeight: FontWeight.w600,
                ),
              ),
              AppText.paragraph("x3", fontWeight: FontWeight.w500),
              Gap(getWidth(16)!),
              AppText.paragraph("QAR 4",
                  color: bgColor, fontWeight: FontWeight.w700)
            ],
          ),
          Gap(getWidth(6)!),
          Row(
            children: [
              AppText.paragraph("By : "),
              Expanded(
                child: AppText.paragraph(
                  "Jazz Cafe Admin",
                  fontWeight: FontWeight.w600,
                ),
              ),
              AppText.paragraph("Cash (QAR 12)",
                  color: bgColor, fontWeight: FontWeight.w700)
            ],
          ),
          Gap(getWidth(6)!),
          Row(
            children: [
              AppText.paragraph("Ordered On : "),
              Expanded(
                child: AppText.paragraph(
                  "13-Jun-2025 12:55 PM",
                  fontWeight: FontWeight.w600,
                ),
              ),
            ],
          ),
        ],
      ),
    );
  }

  Widget _payForServiceTile(
      HistoryLogic logic, BuildContext context, dynamic service) {
    return FillContainer(
      margin: EdgeInsets.only(bottom: getWidth(20)!),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Row(
            mainAxisAlignment: MainAxisAlignment.spaceBetween,
            children: [
              AppText.heading2(
                "${'name'.getString(context)} : ${service['student_name']}",
                fontWeight: FontWeight.w600,
                getfontSize: 17,
                color: bgColor,
              ),
              AppText.heading2(
                "DOB : ${service['student_dob']}",
                fontWeight: FontWeight.w600,
                getfontSize: 15,
                color: bgColor,
              ),
            ],
          ),
          Gap(getWidth(6)!),
          Row(
            mainAxisAlignment: MainAxisAlignment.spaceBetween,
            children: [
              AppText.paragraph(
                'status'.getString(context),
                fontWeight: FontWeight.w700,
                getfontSize: 16,
                color: bgColor,
              ),
              AppText.heading2(
                '${service['status'] ?? ""}',
                fontWeight: FontWeight.w700,
                getfontSize: 15,
                color: bgColor,
              ),
            ],
          ),
          Gap(getWidth(6)!),
          Row(
            mainAxisAlignment: MainAxisAlignment.spaceBetween,
            children: [
              AppText.heading2(
                '${'service'.getString(context)} : ${service['name'] ?? ""}',
                fontWeight: FontWeight.w700,
                getfontSize: 15,
                color: bgColor,
              ),
              AppText.heading2(
                '${'meal'.getString(context)} : ${service['meals'] ?? ""}',
                fontWeight: FontWeight.w700,
                getfontSize: 15,
                color: bgColor,
              ),
            ],
          ),
          Gap(getWidth(6)!),
          Row(
            mainAxisAlignment: MainAxisAlignment.spaceBetween,
            children: [
              AppText.heading2(
                '${'duration'.getString(context)} : ${service['duration_days']} days',
                fontWeight: FontWeight.w700,
                getfontSize: 15,
                color: bgColor,
              ),
              AppText.heading2(
                '${'price'.getString(context)} : QAR ${service['price']}',
                fontWeight: FontWeight.w700,
                getfontSize: 15,
                color: bgColor,
              ),
            ],
          ),
          Gap(getWidth(6)!),
          AppText.paragraph(
            "${'note'.getString(context)} : ${service['note']}",
            fontWeight: FontWeight.w600,
            getfontSize: 15,
            color: bgColor,
          ),
          if (service['renew'].toString() == "1" ||
              service['paused'].toString() == "0")
            Padding(
              padding: EdgeInsets.only(top: getWidth(20)!),
              child: PrimaryButton(
                onTap: () {
                  if (service['renew'].toString() == "1") {
                    logic.renewService(service['id'].toString());
                  } else {
                    logic.pauseServiceBottomSheet(
                      context,
                      service['id'].toString(),
                      service['start_date'],
                      service['end_date'],
                    );
                  }
                },
                verticalPaddingGet: 10,
                getBorderRadius: 10,
                textSize: 14,
                text: service['renew'].toString() == "1"
                    ? "renew_service".getString(context)
                    : "pause_service".getString(context),
              ),
            )
        ],
      ),
    );
  }

  Widget _walletTransactionTile(
      HistoryLogic logic, BuildContext context, dynamic wallet) {
    return FillContainer(
      margin: EdgeInsets.only(bottom: getWidth(20)!),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Row(
            mainAxisAlignment: MainAxisAlignment.spaceBetween,
            children: [
              AppText.heading2(
                wallet['date'],
                fontWeight: FontWeight.w700,
                getfontSize: 17,
                color: bgColor,
              ),
              AppText.heading2(
                "QAR ${wallet['amount']}",
                fontWeight: FontWeight.w700,
                getfontSize: 15,
                color: Constants.parseColorFromHex(wallet['color_amount']),
              ),
            ],
          ),
          Gap(getWidth(6)!),
          AppText.paragraph(
            wallet['message'],
            fontWeight: FontWeight.w500,
            getfontSize: 17,
          ),
        ],
      ),
    );
  }

  _emptyPlaceHolder(
      {required String icon,
      required String title,
      required String description}) {
    return Center(
      child: Padding(
        padding: EdgeInsets.all(getWidth(30)!),
        child: Column(
          mainAxisSize: MainAxisSize.min,
          children: [
            Gap(getWidth(220)!),
            Image.asset(
              icon,
              scale: 1.4,
              color: bgColor,
            ),
            Gap(getWidth(20)!),
            AppText.heading2(title,
                color: bgColor, fontWeight: FontWeight.w700),
            Gap(getWidth(12)!),
            AppText.paragraph(
              description,
              color: buttonColor,
              getfontSize: 16,
              textAlign: TextAlign.center,
            ),
          ],
        ),
      ),
    );
  }
}
