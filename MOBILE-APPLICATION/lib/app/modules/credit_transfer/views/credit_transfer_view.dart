import 'package:flutter/material.dart';
import 'package:flutter_localization/flutter_localization.dart';
import 'package:gap/gap.dart';
import 'package:get/get.dart';
import 'package:jazz_smart_pay/app/custom_widget/bouncing_button.dart';
import 'package:jazz_smart_pay/app/custom_widget/my_app_bar.dart';
import 'package:jazz_smart_pay/app/utils/app_const_colors.dart';
import '../../../custom_widget/app_text.dart';
import '../../../custom_widget/fill_container.dart';
import '../../../custom_widget/loading_circular_component.dart';
import '../../../utils/app_size.dart';
import '../../../utils/constant_vars.dart';
import '../../../utils/image_path.dart';
import '../controllers/credit_transfer_controller.dart';

class CreditTransferView extends GetView<CreditTransferLogic> {
  const CreditTransferView({super.key});
  @override
  Widget build(BuildContext context) {
    return GetBuilder<CreditTransferLogic>(
        init: CreditTransferLogic(),
        builder: (logic) {
          return GestureDetector(
            onTap: () => FocusScope.of(context).unfocus(),
            child: Scaffold(
              appBar: myAppBar(
                  title: "credit_transfer".getString(context),
                  color: textColor,
                  contentColor: bgColor),
              backgroundColor: textColor,
              body: logic.isLoading
                  ? const Center(
                      child:
                          LoadingCircularComponent(indicatorColor: buttonColor))
                  : SingleChildScrollView(
                      padding: EdgeInsets.symmetric(
                          horizontal: getWidth(20)!, vertical: getWidth(10)!),
                      child: Column(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          Row(
                            mainAxisAlignment: MainAxisAlignment.spaceBetween,
                            children: [
                              AppText.paragraph(
                                "topup_wallet_balance".getString(context),
                                color: bgColor,
                                getfontSize: 18,
                                fontWeight: FontWeight.w700,
                              ),
                              AppText.paragraph(
                                logic.creditRes!['parent wallet'],
                                color: bgColor,
                                getfontSize: 18,
                                fontWeight: FontWeight.w700,
                              ),
                            ],
                          ),
                          Gap(getWidth(20)!),
                          AppText.paragraph(
                            "use_in_cafeteria".getString(context),
                            color: bgColor,
                            getfontSize: 18,
                            fontWeight: FontWeight.w700,
                          ),
                          Gap(getWidth(20)!),
                          if (Constants.isIpad) _gridView(logic),
                          if (!Constants.isIpad)
                            ...List.generate(
                              logic.creditRes!['data'].length,
                              (index) {
                                var credit = logic.creditRes!['data'][index];
                                return _creditTransferTile(
                                    logic, context, credit);
                              },
                            ),
                        ],
                      ),
                    ),
            ),
          );
        });
  }

  _gridView(CreditTransferLogic logic) {
    return GridView.builder(
      itemCount: logic.creditRes!['data'].length,
      gridDelegate: const SliverGridDelegateWithFixedCrossAxisCount(
        crossAxisCount: 2, // Number of columns in grid
        crossAxisSpacing: 10,
        mainAxisSpacing: 10,
        childAspectRatio: 4, // Adjust for card shape
      ),
      shrinkWrap: true,
      physics:
          const NeverScrollableScrollPhysics(), // Prevents nested scrolling
      itemBuilder: (context, index) {
        var credit = logic.creditRes!['data'][index];
        return _creditTransferTile(logic, context, credit);
      },
    );
  }

  Widget _creditTransferTile(
      CreditTransferLogic logic, BuildContext context, dynamic credit) {
    final double spacing = getWidth(16)!;
    final double imageSize = getWidth(60)!;

    return FillContainer(
      margin: EdgeInsets.only(bottom: getWidth(20)!),
      child: Row(
        crossAxisAlignment: Constants.isIpad
            ? CrossAxisAlignment.start
            : CrossAxisAlignment.center,
        children: [
          Image.asset(ImagePath.student, height: imageSize, width: imageSize),
          Gap(spacing),
          Expanded(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Row(
                  mainAxisAlignment: MainAxisAlignment.spaceBetween,
                  children: [
                    AppText.heading2(
                      credit['name'],
                      fontWeight: FontWeight.w700,
                      getfontSize: 18,
                    ),
                  ],
                ),

                // Admission Info
                AppText.paragraph(
                  "${"balance".getString(context)} QAR ${credit['wallet_balance']}",
                  fontWeight: FontWeight.w700,
                  color: bgColor,
                ),
              ],
            ),
          ),
          Gap(spacing),
          Bouncing(
            onTap: () {
              logic.amountCreditController.clear();
              logic.resetBoth();
              logic.transferOneToOneBottomSheet(context);
            },
            child: Image.asset(
              ImagePath.arrowTransfer,
              height: getWidth(28),
              width: getWidth(28),
              color: bgColor,
            ),
          ),
          Gap(spacing),
          Transform(
            alignment: Alignment.center,
            transform: Matrix4.identity()
              ..scale(1.0, -1.0), // Flip horizontally
            child: Bouncing(
              onTap: () {
                logic.amountController.clear();
                logic.transferBackToWalletBottomSheet(
                  context,
                  amount: credit['wallet_balance'],
                  id: credit['id'].toString(),
                );
              },
              child: Image.asset(
                ImagePath.arrowTurn,
                height: getWidth(32),
                width: getWidth(32),
                color: bgColor,
              ),
            ),
          ),
          Gap(spacing),
          Transform.rotate(
            angle: 90 * 3.1416 / 180, // 90 degrees in radians
            child: Bouncing(
              onTap: () {
                logic.amountCreditController.clear();
                logic.transferCreditBottomSheet(
                  context,
                  id: credit['id'].toString(),
                );
              },
              child: Image.asset(
                ImagePath.logout05,
                height: getWidth(28),
                width: getWidth(28),
                color: bgColor,
              ),
            ),
          ),
        ],
      ),
    );
  }
}
