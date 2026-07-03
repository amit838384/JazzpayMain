import 'package:flutter/material.dart';
import 'package:flutter_localization/flutter_localization.dart';
import 'package:gap/gap.dart';
import 'package:get/get.dart';
import 'package:jazz_smart_pay/app/custom_widget/my_app_bar.dart';
import 'package:jazz_smart_pay/app/utils/app_const_colors.dart';
import '../../../custom_widget/app_text.dart';
import '../../../custom_widget/fill_container.dart';
import '../../../custom_widget/loading_circular_component.dart';
import '../../../utils/app_size.dart';
import '../../../utils/constant_vars.dart';
import '../../../utils/image_path.dart';
import '../controllers/top_up_controller.dart';

class TopUpView extends GetView<TopUpLogic> {
  const TopUpView({super.key});
  @override
  Widget build(BuildContext context) {
    return GetBuilder<TopUpLogic>(
        init: TopUpLogic(),
        builder: (logic) {
          return GestureDetector(
            onTap: () => FocusScope.of(context).unfocus(),
            child: Scaffold(
              appBar: myAppBar(
                  title: "top_up".getString(context),
                  color: textColor,
                  contentColor: bgColor),
              backgroundColor: textColor,
              body: logic.isPayLoading
                  ? Center(
                      child: Column(
                      children: [
                        Gap(getWidth(400)!),
                        const LoadingCircularComponent(
                            indicatorColor: buttonColor),
                        Gap(getWidth(12)!),
                        AppText.paragraph("Payment Processing",
                            color: buttonColor,
                            getfontSize: 18,
                            fontWeight: FontWeight.w600),
                      ],
                    ))
                  : logic.isLoading
                      ? const Center(
                          child: LoadingCircularComponent(
                              indicatorColor: buttonColor))
                      : SingleChildScrollView(
                          padding:
                              EdgeInsets.symmetric(horizontal: getWidth(20)!),
                          child: Column(
                            children: [
                              (logic.topRes != null &&
                                      logic.topRes!['data'].isNotEmpty)
                                  ? Column(
                                      children: [
                                        if (Constants.isIpad) _gridView(logic),
                                        if (!Constants.isIpad)
                                          Column(
                                            children: List.generate(
                                              logic.topRes!['data'].length,
                                              (index) {
                                                var topUp = logic
                                                    .topRes!['data'][index];
                                                return _topUpItem(topUp);
                                              },
                                            ),
                                          ),
                                      ],
                                    )
                                  : Constants.emptyPlaceHolder(
                                      icon: ImagePath.food,
                                      title: "Cafeteria Topups",
                                      description:
                                          "You haven’t topped up your cafeteria balance yet. Add funds now to enjoy seamless food purchases.",
                                    ),
                              Gap(getWidth(60)!),
                            ],
                          ),
                        ),
              floatingActionButton: logic.isPayLoading || logic.isLoading
                  ? null
                  : FloatingActionButton(
                      onPressed: () {
                        logic.amountController.clear();
                        logic.selectedAmount = "";
                        logic.bottomSheetWithHandle(context);
                      },
                      backgroundColor: bgColor, // your custom white
                      splashColor: Colors.transparent,
                      elevation: 6,
                      child: Image.asset(
                        ImagePath.plus,
                        height: getWidth(32),
                        width: getWidth(32),
                        color: textColor,
                      )),
            ),
          );
        });
  }

  _gridView(TopUpLogic logic) {
    return GridView.builder(
      itemCount: logic.topRes!['data'].length,
      gridDelegate: const SliverGridDelegateWithFixedCrossAxisCount(
        crossAxisCount: 2, // Number of columns in grid
        crossAxisSpacing: 10,
        mainAxisSpacing: 10,
        childAspectRatio: 3.1, // Adjust for card shape
      ),
      shrinkWrap: true,
      physics:
          const NeverScrollableScrollPhysics(), // Prevents nested scrolling
      itemBuilder: (context, index) {
        var topUp = logic.topRes!['data'][index];
        return _topUpItem(topUp);
      },
    );
  }

  Widget _topUpItem(dynamic topup) {
    final double spacing = getWidth(16)!;
    final double pillPaddingV = getWidth(6)!;
    final double pillPaddingH = spacing;
    final double borderRadius = getWidth(100)!;

    return FillContainer(
      margin: EdgeInsets.only(bottom: getWidth(20)!),
      child: Row(
        children: [
          Expanded(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Row(
                  mainAxisAlignment: MainAxisAlignment.spaceBetween,
                  children: [
                    AppText.heading2(topup['amount'],
                        fontWeight: FontWeight.w700, getfontSize: 18),
                    Container(
                      padding: EdgeInsets.symmetric(
                          horizontal: pillPaddingH, vertical: pillPaddingV),
                      decoration: BoxDecoration(
                        color:
                            Constants.parseColorFromHex(topup['status_color']),
                        borderRadius: BorderRadius.circular(borderRadius),
                      ),
                      child: AppText.smallParagraph(topup['status'],
                          fontWeight: FontWeight.w500, color: textColor),
                    ),
                  ],
                ),

                // Admission Info
                AppText.paragraph(
                  topup['date'].toString().toUpperCase(),
                  fontWeight: FontWeight.w500,
                ),
                Gap(getWidth(4)!),
                AppText.paragraph(
                  topup['transactionid'].toString().toUpperCase(),
                  fontWeight: FontWeight.w500,
                ),
              ],
            ),
          ),
        ],
      ),
    );
  }
}
