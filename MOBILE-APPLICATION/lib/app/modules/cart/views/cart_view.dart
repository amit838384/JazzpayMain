import 'dart:io';
import 'package:flutter/material.dart';
import 'package:flutter_localization/flutter_localization.dart';
import 'package:jazz_smart_pay/app/custom_widget/fill_container.dart';
import 'package:jazz_smart_pay/app/custom_widget/my_app_bar.dart';
import 'package:jazz_smart_pay/app/custom_widget/primary_button.dart';
import 'package:jazz_smart_pay/app/utils/app_const_colors.dart';
import 'package:jazz_smart_pay/app/utils/app_size.dart';
import 'package:gap/gap.dart';
import 'package:get/get.dart';
import 'package:jazz_smart_pay/app/utils/constant_vars.dart';
import '../../../custom_widget/app_text.dart';
import '../../../custom_widget/bouncing_button.dart';
import '../../../custom_widget/cached_image.dart';
import '../../../custom_widget/loading_circular_component.dart';
import '../../../utils/image_path.dart';
import '../controllers/cart_controller.dart';

class CartView extends GetView<CartController> {
  final bool showBack;
  const CartView({super.key, required this.showBack});
  @override
  Widget build(BuildContext context) {
    return GetBuilder<CartController>(
        init: CartController(),
        builder: (logic) {
          return Scaffold(
            appBar: myAppBar(
                title: "cart".getString(context),
                color: textColor,
                contentColor: bgColor),
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
                        if (logic.cartRes?['data'].isNotEmpty &&
                            !Constants.isIpad)
                          ...List.generate(
                            logic.cartRes?['data'].length,
                            (index) {
                              dynamic data = logic.cartRes?['data'][index];
                              return _productWidget(
                                  context, logic, data, index);
                            },
                          ),
                        if (Constants.isIpad) _gridView(logic),
                        if (logic.cartRes?['data'].isEmpty)
                          Center(
                            child: Column(
                              children: [
                                Gap(getWidth(300)!),
                                Icon(
                                  Icons.remove_shopping_cart_outlined,
                                  color: bgColor,
                                  size: getWidth(120),
                                ),
                                Gap(getWidth(12)!),
                                AppText.paragraph(
                                    "empty_cart".getString(context),
                                    getfontSize: 24,
                                    fontWeight: FontWeight.w700,
                                    color: bgColor),
                                Gap(getWidth(12)!),
                                AppText.paragraph(
                                  "empty_cart_text".getString(context),
                                  getfontSize: 18,
                                  fontWeight: FontWeight.w500,
                                ),
                              ],
                            ),
                          ),
                        Gap(getWidth(60)!),
                      ],
                    ),
                  ),
            bottomNavigationBar:
                logic.isLoading || logic.cartRes?['data'].isEmpty
                    ? null
                    : FillContainer(
                        borderRadius: 0,
                        child: Column(
                          mainAxisSize: MainAxisSize.min,
                          crossAxisAlignment: CrossAxisAlignment.start,
                          children: [
                            Row(
                              mainAxisAlignment: MainAxisAlignment.spaceBetween,
                              children: [
                                AppText.paragraph(
                                  "total_amount".getString(context),
                                  fontWeight: FontWeight.w700,
                                  color: buttonColor,
                                ),
                                AppText.paragraph(
                                  "QAR ${logic.totalAmount}",
                                  fontWeight: FontWeight.w700,
                                  color: buttonColor,
                                  getfontSize: 16,
                                ),
                              ],
                            ),
                            Gap(getWidth(12)!),
                            Row(
                              children: [
                                Expanded(
                                  child: PrimaryButton(
                                    verticalPaddingGet: 12,
                                    textSize: 15,
                                    text: "add_items".getString(context),
                                    onTap: () {
                                      Get.back();
                                    },
                                  ),
                                ),
                                Gap(getWidth(20)!),
                                Expanded(
                                  child: PrimaryButton(
                                    verticalPaddingGet: 12,
                                    textSize: 15,
                                    text: "checkout".getString(context),
                                    onTap: () {
                                      if (logic.payByWallet) {
                                        logic.checkoutAPI();
                                      } else {
                                        Constants.errorDialog(
                                          message:
                                              "Your wallet balance is low.\nYou can place the order using online payment.",
                                        );
                                      }
                                    },
                                  ),
                                ),
                              ],
                            ),
                            Gap(getWidth(12)!),
                            if (Platform.isAndroid) Gap(getWidth(40)!),
                          ],
                        ),
                      ),
          );
        });
  }

  _gridView(CartController logic) {
    return GridView.builder(
      itemCount: logic.cartRes?['data'].length,
      gridDelegate: const SliverGridDelegateWithFixedCrossAxisCount(
        crossAxisCount: 2, // Number of columns in grid
        crossAxisSpacing: 10,
        mainAxisSpacing: 10,
        childAspectRatio: 3.22, // Adjust for card shape
      ),
      shrinkWrap: true,
      physics:
          const NeverScrollableScrollPhysics(), // Prevents nested scrolling
      itemBuilder: (context, index) {
        dynamic data = logic.cartRes?['data'][index];
        return _productWidget(context, logic, data, index);
      },
    );
  }

  _productWidget(
      BuildContext context, CartController logic, dynamic data, int index) {
    return FillContainer(
      padding: EdgeInsets.zero,
      margin: EdgeInsets.only(bottom: getWidth(20)!),
      child: Row(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          ClipRRect(
            borderRadius: BorderRadius.circular(getWidth(12)!),
            child: CacheImage(
              path:
                  "https://images.pexels.com/photos/376464/pexels-photo-376464.jpeg?cs=srgb&dl=pexels-ash-craig-122861-376464.jpg&fm=jpg",
              height: getWidth(120),
              fit: BoxFit.cover,
              width: getWidth(140),
            ),
          ),
          Gap(getWidth(20)!),
          Expanded(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Gap(getWidth(12)!),
                AppText.paragraph(data['dish_name'],
                    fontWeight: FontWeight.w700,
                    maxLines: 1,
                    overflow: TextOverflow.ellipsis),
                Gap(getWidth(4)!),
                AppText.paragraph(
                  "QAR ${data['total_price']}",
                  fontWeight: FontWeight.w700,
                  color: buttonColor,
                ),
                Gap(getWidth(4)!),
                AppText.paragraph(
                  "${data['student_name']}",
                  fontWeight: FontWeight.w500,
                ),
                Gap(getWidth(4)!),
                Row(
                  children: [
                    AppText.paragraph(
                      "${data['date']}",
                      fontWeight: FontWeight.w500,
                      getfontSize: 14,
                    ),
                    const Spacer(),
                    Row(
                      children: [
                        Bouncing(
                          onTap: () async {
                            if (data['qty'] > 0) {
                              logic.preOrderDecreaseAPI(index: index);
                            }
                          },
                          child: Container(
                            padding: EdgeInsets.all(getWidth(6)!),
                            decoration: BoxDecoration(
                                borderRadius:
                                    BorderRadius.circular(getWidth(6)!),
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
                          padding:
                              EdgeInsets.symmetric(horizontal: getWidth(14)!),
                          child: AppText.paragraph(data['qty'].toString(),
                              fontWeight: FontWeight.w600, color: buttonColor),
                        ),
                        Bouncing(
                          onTap: () {
                            logic.preOrderAPI(data: data);
                          },
                          child: Container(
                            padding: EdgeInsets.all(getWidth(6)!),
                            decoration: BoxDecoration(
                                borderRadius:
                                    BorderRadius.circular(getWidth(6)!),
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
                if (data['selected_addons'] != null &&
                    data['selected_addons'].isNotEmpty) ...[
                  Gap(getWidth(12)!),
                  Row(
                    children: [
                      Expanded(
                        child: AppText.paragraph(
                            "Addons : ${data['selected_addons']}",
                            fontWeight: FontWeight.w500),
                      ),
                      Gap(getWidth(12)!),
                      Bouncing(
                        onTap: () {
                          logic.multiSelectBottomSheet(context,
                              items: data['addons'], food: data);
                        },
                        child: Container(
                          padding: EdgeInsets.all(getWidth(5)!),
                          decoration: BoxDecoration(
                              color: bgColor.withValues(alpha: .15),
                              borderRadius:
                                  BorderRadius.circular(getWidth(6)!)),
                          child: Image.asset(
                            ImagePath.edit,
                            height: getWidth(20),
                            width: getWidth(20),
                            color: bgColor,
                          ),
                        ),
                      )
                    ],
                  ),
                  Gap(getWidth(12)!)
                ],
              ],
            ),
          ),
          Gap(getWidth(20)!),
        ],
      ),
    );
  }
}
