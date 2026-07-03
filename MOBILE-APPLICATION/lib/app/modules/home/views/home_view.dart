import 'package:flutter_localization/flutter_localization.dart';
import 'package:flutter_staggered_grid_view/flutter_staggered_grid_view.dart';
import 'package:jazz_smart_pay/app/custom_widget/app_text.dart';
import 'package:jazz_smart_pay/app/custom_widget/bouncing_button.dart';
import 'package:jazz_smart_pay/app/custom_widget/loading_circular_component.dart';
import 'package:jazz_smart_pay/app/custom_widget/my_app_bar.dart';
import 'package:jazz_smart_pay/app/utils/app_const_colors.dart';
import 'package:jazz_smart_pay/app/utils/app_size.dart';
import 'package:jazz_smart_pay/app/utils/constant_vars.dart';
import 'package:jazz_smart_pay/exports.dart';
import '../../../calling.dart';
import '../../../custom_widget/fill_container.dart';
import '../controllers/home_controller.dart';

class HomeView extends GetView<HomeController> {
  const HomeView({super.key});
  @override
  Widget build(BuildContext context) {
    return GetBuilder<HomeController>(
        init: HomeController(),
        builder: (logic) {
          return Scaffold(
            backgroundColor: textColor,
            appBar: logic.isLoading || logic.isStudentLoading
                ? null
                : myAppBar(
                    visibleBack: false,
                    title: "dashboard".getString(context),
                    color: textColor,
                    contentColor: bgColor),
            // floatingActionButtonLocation: FloatingActionButtonLocation.endFloat,
            // floatingActionButton: logic.isLoading || logic.isStudentLoading
            //     ? const SizedBox()
            //     : FloatingActionButton.extended(
            //         onPressed: () {
            //           // Get.to(()=> const VoiceCallScreen());
            //           Get.to(() => const SimpleVoiceCallScreen());
            //         },
            //         backgroundColor: const Color(0xFF1F3A8A),
            //         icon: const Icon(Icons.smart_toy, color: Colors.white),
            //         label: const Text(
            //           "Order With AI Agent",
            //           style: TextStyle(
            //             color: Colors.white,
            //             fontWeight: FontWeight.w600,
            //           ),
            //         ),
            //       ),
            body: logic.isLoading || logic.isStudentLoading
                ? const Center(
                    child: LoadingCircularComponent(indicatorColor: bgColor))
                : SingleChildScrollView(
                    padding: EdgeInsets.symmetric(horizontal: getWidth(20)!),
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        FillContainer(
                            backgroundColor: buttonColor,
                            child: Row(
                              mainAxisAlignment: MainAxisAlignment.spaceBetween,
                              children: [
                                AppText.paragraph(
                                    'wallet_balance'.getString(context),
                                    fontWeight: FontWeight.w600,
                                    color: textColor),
                                AppText.paragraph(
                                    "QAR ${Constants.walletBalance}",
                                    fontWeight: FontWeight.w700,
                                    color: yelloColor),
                              ],
                            )),
                        Gap(getWidth(20)!),
                        CustomGridLayout(
                          items: [
                            gridCard(
                              'family'.getString(context),
                              "assets/icons/family.png",
                              color: const Color(0xFFFFF3F6),
                              onTap: () {
                                Get.toNamed(Routes.FAMILY);
                              },
                            ),
                            gridCard(
                              'history'.getString(context),
                              "assets/icons/history.png",
                              color: const Color(0xFFEDFFFC),
                              onTap: () {
                                Get.toNamed(Routes.HISTORY);
                              },
                            ),
                            gridCard(
                              "top_up".getString(context),
                              "assets/icons/pay_service.png",
                              color: const Color(0xFFFFF9DE),
                              onTap: () {
                                Get.toNamed(Routes.TOP_UP)?.then(
                                  (value) {
                                    logic.update();
                                  },
                                );
                              },
                            ),
                            gridCard(
                              'credit_transfer'.getString(context),
                              "assets/icons/credit_transfer.png",
                              color: const Color(0xFFE3E2E8),
                              onTap: () {
                                Get.toNamed(Routes.CREDIT_TRANSFER)?.then(
                                  (value) {
                                    logic.update();
                                  },
                                );
                              },
                            ),
                            gridCard(
                              'pay_for_service'.getString(context),
                              "assets/icons/pay_service.png",
                              color: const Color(0xFFBCEAB7)
                                  .withValues(alpha: .42),
                              onTap: () {
                                // showMaintenanceDialog(context);
                                Get.toNamed(Routes.PAY_FOR_SERVICE)?.then(
                                  (value) {
                                    logic.update();
                                  },
                                );
                              },
                            ),
                            gridCard(
                              'pre_order'.getString(context),
                              "assets/icons/pre_order.png",
                              color: const Color(0xFFF2FFCE),
                              onTap: () {
                                Get.toNamed(Routes.PRE_ORDER);
                              },
                            ),
                          ],
                        ),
                        Gap(getWidth(20)!),
                        AppText.paragraph(
                          "cafeteria_balance".getString(context),
                          fontWeight: FontWeight.w600,
                          color: bgColor,
                          getfontSize: 18,
                        ),
                        Gap(getWidth(20)!),
                        if (Constants.isIpad) _gridView(logic),
                        if (!Constants.isIpad)
                          ...List.generate(
                            logic.creditRes!['data'].length,
                            (index) {
                              var credit = logic.creditRes!['data'][index];
                              return _creditTransferTile(context, credit);
                            },
                          ),
                        Gap(getWidth(60)!),
                      ],
                    ),
                  ),
          );
        });
  }

  _gridView(HomeController logic) {
    return GridView.builder(
      itemCount: logic.creditRes!['data'].length,
      gridDelegate: const SliverGridDelegateWithFixedCrossAxisCount(
        crossAxisCount: 2, // Number of columns in grid
        crossAxisSpacing: 10,
        mainAxisSpacing: 10,
        childAspectRatio: 5, // Adjust for card shape
      ),
      shrinkWrap: true,
      physics:
          const NeverScrollableScrollPhysics(), // Prevents nested scrolling
      itemBuilder: (context, index) {
        var credit = logic.creditRes!['data'][index];
        return _creditTransferTile(context, credit);
      },
    );
  }

  Widget _creditTransferTile(BuildContext context, dynamic credit) {
    return FillContainer(
      margin: EdgeInsets.only(bottom: getWidth(20)!),
      child: Row(
        mainAxisAlignment: MainAxisAlignment.spaceBetween,
        children: [
          AppText.heading2(
            credit['name'],
            fontWeight: FontWeight.w700,
            getfontSize: 17,
            color: bgColor,
          ),
          const Spacer(),
          AppText.paragraph(
            "QAR ${credit['wallet_balance']}",
            fontWeight: FontWeight.w700,
            color: bgColor,
          ),
        ],
      ),
    );
  }

  Widget gridCard(String title, String icon,
      {required Color color, required void Function() onTap}) {
    return Bouncing(
      onTap: onTap,
      child: Container(
        decoration: BoxDecoration(
          color: color,
          borderRadius: BorderRadius.circular(16),
        ),
        child: Center(
          child: Column(
            mainAxisSize: MainAxisSize.min,
            children: [
              Image.asset(
                icon,
                height: getWidth(70),
                width: getWidth(70),
                fit: BoxFit.cover,
              ),
              Gap(getWidth(12)!),
              AppText.paragraph(
                title,
                getfontSize: 18,
                color: bgColor,
                fontWeight: FontWeight.w700,
              )
            ],
          ),
        ),
      ),
    );
  }

  void showMaintenanceDialog(BuildContext context) {
    showDialog(
      context: context,
      barrierDismissible: false,
      builder: (BuildContext context) {
        return AlertDialog(
          shape:
              RoundedRectangleBorder(borderRadius: BorderRadius.circular(16)),
          title: const Center(
            child: Text(
              'Feature Not Available',
              style: TextStyle(fontWeight: FontWeight.bold, fontSize: 20),
            ),
          ),
          content: const Text(
            'This feature is currently under maintenance.'
            'We’re working hard to bring it to you soon.'
            'Please check back later.',
            style: TextStyle(fontSize: 16),
            textAlign: TextAlign.center,
          ),
          actions: [
            TextButton(
              onPressed: () => Navigator.of(context).pop(),
              child: const Text("OK"),
            ),
          ],
        );
      },
    );
  }
}

class CustomGridLayout extends StatelessWidget {
  final List<Widget> items; // Your dashboard items as widgets

  const CustomGridLayout({super.key, required this.items});

  @override
  Widget build(BuildContext context) {
    return MasonryGridView.count(
      shrinkWrap: true,
      physics: const NeverScrollableScrollPhysics(),
      crossAxisCount: 2, // 2 columns
      mainAxisSpacing: getWidth(16)!,
      crossAxisSpacing: getWidth(16)!,
      itemCount: items.length,
      itemBuilder: (context, index) {
        // Determine size based on position

        double height = getHeightByPattern(index);
        return Container(
          height: height,
          child: items[index],
        );
      },
    );
  }

  double getHeightByPattern(int index) {
    const squareIndexes = {
      1,
      3,
      5,
    };
    const verticalIndexes = {
      0,
      2,
      4,
    };

    int patternIndex = index % 16;

    if (squareIndexes.contains(patternIndex)) {
      return 140;
    } else if (verticalIndexes.contains(patternIndex)) {
      return 180;
    } else {
      return 120; // fallback
    }
  }
}
