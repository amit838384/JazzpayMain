import 'dart:developer';

import 'package:flutter_localization/flutter_localization.dart';
import 'package:jazz_smart_pay/app/custom_widget/bouncing_button.dart';
import 'package:jazz_smart_pay/app/custom_widget/cached_image.dart';
import 'package:jazz_smart_pay/app/custom_widget/my_app_bar.dart';
import 'package:jazz_smart_pay/app/custom_widget/primary_button.dart';
import 'package:jazz_smart_pay/app/utils/app_const_colors.dart';
import 'package:jazz_smart_pay/app/utils/constant_vars.dart';
import '../../../../exports.dart';
import '../../../custom_widget/app_text.dart';
import '../../../custom_widget/fill_container.dart';
import '../../../custom_widget/loading_circular_component.dart';
import '../../../utils/app_size.dart';
import '../../../utils/image_path.dart';
import '../controllers/pre_order_controller.dart';

class PreOrderView extends GetView<PreOrderLogic> {
  const PreOrderView({super.key});
  @override
  Widget build(BuildContext context) {
    return GetBuilder<PreOrderLogic>(
        init: PreOrderLogic(),
        builder: (logic) {
          return GestureDetector(
            onTap: () => FocusScope.of(context).unfocus(),
            child: Scaffold(
              appBar: myAppBar(
                  title: "pre_order".getString(context),
                  color: textColor,
                  contentColor: bgColor,
                  actions: [
                    // TextButton(
                    //   onPressed: () {
                    //     Get.toNamed(Routes.PDF_VIEW);
                    //   },
                    //   child: const Text(
                    //     "PDF Menu",
                    //     style: TextStyle(
                    //       color: Color(0xFF9B203E),
                    //       fontWeight: FontWeight.w600,
                    //       fontSize: 16,
                    //     ),
                    //   ),
                    // ),
                    Padding(
                      padding: const EdgeInsets.only(right: 12),
                      child: GestureDetector(
                        onTap: () {
                          Get.toNamed(Routes.PDF_VIEW);
                        },
                        child: Image.asset(
                          "assets/images/pdf_menu.jpeg",
                          height: 24,
                          width: 24,
                        ),
                      ),
                    ),
                  ]
              ),
              backgroundColor: textColor,
              body: logic.isLoading
                  ? const Center(
                      child:
                          LoadingCircularComponent(indicatorColor: buttonColor))
                  : SingleChildScrollView(
                      padding: EdgeInsets.symmetric(horizontal: getWidth(20)!),
                      child: Column(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          Row(
                            children: [
                              AppText.paragraph(
                                "${"name".getString(context)} : ${logic.students[logic.selectedStudent]['name']}",
                                color: bgColor,
                                fontWeight: FontWeight.w600,
                                getfontSize: 17,
                              ),
                              const Spacer(),
                              if (logic.students.length > 1)
                                GestureDetector(
                                  behavior: HitTestBehavior.translucent,
                                  onTap: () {
                                    logic.studentSelectionBottomSheet(context);
                                  },
                                  child: AppText.paragraph(
                                    "change".getString(context),
                                    color: bgColor,
                                    fontWeight: FontWeight.w600,
                                    getfontSize: 17,
                                  ),
                                ),
                            ],
                          ),
                          Gap(getWidth(20)!),
                          Row(
                            children: [
                              buildSelectableBox("date".getString(context),
                                  logic.selectedDateValue, onTap: () {
                                logic.selectDate(context);
                              }),
                              Gap(getWidth(20)!),
                              buildSelectableBox(
                                  "category".getString(context),
                                  logic.catRes!['data'][logic.selectedCategory]
                                      ['name'], onTap: () {
                                logic.transferCreditBottomSheet(context);
                              }),
                            ],
                          ),
                          Gap(getWidth(20)!),
                          if (Constants.isIpad) _gridViewTab(logic),
                          if (!Constants.isIpad) _gridView(logic),
                        ],
                      ),
                    ),
              bottomNavigationBar: logic.isLoading
                  // || logic.cartQuantity == 0
                  ? null
                  : SafeArea(
                      child: Padding(
                        padding: EdgeInsets.all(getWidth(20)!),
                        child: Column(
                          mainAxisSize: MainAxisSize.min,
                          children: [
                            PrimaryButton(
                              text:
                                  "${"view_cart".getString(context)} (${logic.cartQuantity})",
                              onTap: () {
                                Get.toNamed(Routes.CART);
                              },
                            ),
                          ],
                        ),
                      ),
                    ),
            ),
          );
        });
  }

  Widget buildSelectableBox(String titleHead, String title,
      {required VoidCallback onTap}) {
    return Expanded(
      flex: 2,
      child: GestureDetector(
        behavior: HitTestBehavior.translucent,
        onTap: onTap,
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            AppText.paragraph(titleHead,
                fontWeight: FontWeight.w600, getfontSize: 16, color: bgColor),
            Container(
              margin: EdgeInsets.only(top: getWidth(10)!),
              alignment: Alignment.center,
              padding: EdgeInsets.symmetric(
                vertical: getWidth(12)!,
              ),
              decoration: BoxDecoration(
                border: Border.all(
                  width: getWidth(1)!,
                  color: buttonColor,
                ),
                borderRadius: BorderRadius.circular(getWidth(12)!),
              ),
              child: AppText.paragraph(title,
                  fontWeight: FontWeight.w600, color: buttonColor),
            ),
          ],
        ),
      ),
    );
  }

  _gridView(PreOrderLogic logic) {
    return GridView.builder(
      itemCount: logic.dataRes!['data'].length,
      gridDelegate: const SliverGridDelegateWithFixedCrossAxisCount(
        crossAxisCount: 2, // Number of columns in grid
        crossAxisSpacing: 10,
        mainAxisSpacing: 10,
        childAspectRatio: .97, // Adjust for card shape
      ),
      shrinkWrap: true,
      physics:
          const NeverScrollableScrollPhysics(), // Prevents nested scrolling
      itemBuilder: (context, index) {
        var food = logic.dataRes!['data'][index];
        return _foodItemTile(logic, context, food);
      },
    );
  }

  _gridViewTab(PreOrderLogic logic) {
    return GridView.builder(
      itemCount: logic.dataRes!['data'].length,
      gridDelegate: const SliverGridDelegateWithFixedCrossAxisCount(
        crossAxisCount: 4, // Number of columns in grid
        crossAxisSpacing: 10,
        mainAxisSpacing: 10,
        childAspectRatio: .95, // Adjust for card shape
      ),
      shrinkWrap: true,
      physics:
          const NeverScrollableScrollPhysics(), // Prevents nested scrolling
      itemBuilder: (context, index) {
        var food = logic.dataRes!['data'][index];
        return _foodItemTile(logic, context, food);
      },
    );
  }

  Widget _foodItemTile(
      PreOrderLogic logic, BuildContext context, dynamic food) {
    return FillContainer(
      borderRadius: 12,
      margin: EdgeInsets.only(bottom: getWidth(20)!),
      padding: EdgeInsets.all(getWidth(0)!),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          ClipRRect(
            borderRadius: BorderRadius.circular(getWidth(12)!),
            child: CacheImage(
              path: food['image'],
              height: getWidth(120),
              fit: BoxFit.fitWidth,
            ),
          ),
          Gap(getWidth(12)!),
          Padding(
            padding: EdgeInsets.symmetric(horizontal: getWidth(12)!),
            child: AppText.paragraph(
              food['name'],
              fontWeight: FontWeight.w700,
              maxLines: 2,
              overflow: TextOverflow.ellipsis,
            ),
          ),
          const Spacer(),
          Padding(
            padding: EdgeInsets.symmetric(horizontal: getWidth(12)!),
            child: Row(
              children: [
                AppText.smallParagraph(
                  "QAR ${food['price']}",
                  fontWeight: FontWeight.w700,
                  color: buttonColor,
                ),
                const Spacer(),
                Row(
                  children: [
                    Bouncing(
                      onTap: () async {
                        if (food['qty'] > 0) {
                          logic.preOrderDecreaseAPI(data: food);
                        }
                      },
                      child: Container(
                        padding: EdgeInsets.all(getWidth(6)!),
                        decoration: BoxDecoration(
                            borderRadius: BorderRadius.circular(getWidth(6)!),
                            color: buttonColor.withValues(
                              alpha: .2,
                            )),
                        child: Image.asset(
                          ImagePath.minus,
                          height: getWidth(20),
                          width: getWidth(20),
                          color: buttonColor,
                        ),
                      ),
                    ),
                    Padding(
                      padding: EdgeInsets.symmetric(horizontal: getWidth(14)!),
                      child: AppText.paragraph(food['qty'].toString(),
                          fontWeight: FontWeight.w600, color: buttonColor),
                    ),
                    Bouncing(
                      onTap: () {
                        if (food['addons'].isNotEmpty) {
                          logic.multiSelectBottomSheet(context,
                              items: food['addons'], food: food);
                        } else {
                          logic.preOrderAPI(data: food);
                        }
                      },
                      child: Container(
                        padding: EdgeInsets.all(getWidth(6)!),
                        decoration: BoxDecoration(
                            borderRadius: BorderRadius.circular(getWidth(6)!),
                            color: buttonColor.withValues(
                              alpha: .2,
                            )),
                        child: Image.asset(
                          ImagePath.plus,
                          height: getWidth(20),
                          width: getWidth(20),
                          color: buttonColor,
                        ),
                      ),
                    ),
                  ],
                ),
              ],
            ),
          ),
          Gap(getWidth(8)!)
        ],
      ),
    );
  }
}
