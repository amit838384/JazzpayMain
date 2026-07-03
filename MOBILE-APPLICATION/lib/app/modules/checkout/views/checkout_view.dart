import 'package:flutter/material.dart';
import 'package:jazz_smart_pay/app/custom_widget/app_text.dart';
import 'package:jazz_smart_pay/app/custom_widget/my_app_bar.dart';
import 'package:jazz_smart_pay/app/utils/app_const_colors.dart';
import 'package:jazz_smart_pay/app/utils/app_size.dart';
import 'package:gap/gap.dart';
import 'package:get/get.dart';
import '../../../custom_widget/cached_image.dart';
import '../../../custom_widget/divider.dart';
import '../../../custom_widget/loading_circular_component.dart';
import '../../../custom_widget/primary_button.dart';
import '../../../routes/app_pages.dart';
import '../../../utils/currency_util.dart';
import '../controllers/checkout_controller.dart';

class CheckoutView extends GetView<CheckoutController> {
  const CheckoutView({super.key});
  @override
  Widget build(BuildContext context) {
    return GetBuilder<CheckoutController>(
        init: CheckoutController(),
        builder: (logic) {
          return Scaffold(
            backgroundColor: bgColor,
            appBar: myAppBar(title: "Order Summary"),
            body: logic.isLoading
                ? const Center(child: LoadingCircularComponent())
                : SingleChildScrollView(
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        ...List.generate(
                          logic.checkoutRes?['data'].length,
                          (index) {
                            dynamic data = logic.checkoutRes?['data'][index];
                            return Column(
                              children: [
                                _productWidget(context, logic, data),
                                thikDivider()
                              ],
                            );
                          },
                        ),
                        Padding(
                          padding: EdgeInsets.all(getWidth(20)!),
                          child: Column(
                            crossAxisAlignment: CrossAxisAlignment.start,
                            children: [
                              Row(
                                mainAxisAlignment:
                                    MainAxisAlignment.spaceBetween,
                                children: [
                                  AppText.paragraph(
                                    "Delivery Address",
                                    fontWeight: FontWeight.w700,
                                    color: primaryColor,
                                    getfontSize: 20,
                                  ),
                                  GestureDetector(
                                    onTap: () async {
                                      final value = await Get.toNamed(
                                        Routes.ADDRESS,
                                        arguments:
                                            logic.address?['id'].toString(),
                                      );
                                      if (value != null && value) {
                                        logic.checkoutAPI();
                                      }
                                    },
                                    child: AppText.paragraph(
                                      "Change Address",
                                      fontWeight: FontWeight.w700,
                                      color: primaryColor,
                                      getfontSize: 16,
                                    ),
                                  ),
                                ],
                              ),
                              Gap(getWidth(12)!),
                              Padding(
                                padding: EdgeInsets.only(right: getWidth(120)!),
                                child: AppText.heading2(
                                  "${logic.address?['name']}\n${logic.address?['address1']}, ${logic.address?['address2']}, ${logic.address?['city']}, ${logic.address?['state']}, ${logic.address?['pincode']}, \n${logic.address?['mobileno']}",
                                  color: blackColor,
                                  getfontSize: 16,
                                  fontWeight: FontWeight.w500,
                                ),
                              ),
                            ],
                          ),
                        ),
                        thikDivider(height: 2),
                        Padding(
                          padding: EdgeInsets.all(getWidth(20)!),
                          child: Column(
                            children: [
                              Row(
                                mainAxisAlignment:
                                    MainAxisAlignment.spaceBetween,
                                children: [
                                  AppText.heading2(
                                    "Items (${logic.checkoutRes?['data'].length})",
                                    color: blackColor,
                                    getfontSize: 18,
                                    fontWeight: FontWeight.w500,
                                  ),
                                  AppText.heading2(
                                    currency(double.parse(logic
                                        .checkoutRes!['product_val']
                                        .toString())),
                                    color: primaryColor,
                                    getfontSize: 18,
                                    fontWeight: FontWeight.w500,
                                  ),
                                ],
                              ),
                              Gap(getWidth(20)!),
                              Row(
                                mainAxisAlignment:
                                    MainAxisAlignment.spaceBetween,
                                children: [
                                  AppText.heading2(
                                    "Shipping",
                                    color: blackColor,
                                    getfontSize: 18,
                                    fontWeight: FontWeight.w500,
                                  ),
                                  AppText.heading2(
                                    currency(double.parse(logic
                                        .checkoutRes!['shippingcharge']
                                        .toString())),
                                    color: primaryColor,
                                    getfontSize: 18,
                                    fontWeight: FontWeight.w500,
                                  ),
                                ],
                              ),
                              Gap(getWidth(20)!),
                              Row(
                                mainAxisAlignment:
                                    MainAxisAlignment.spaceBetween,
                                children: [
                                  AppText.heading2(
                                    "Platform charges",
                                    color: blackColor,
                                    getfontSize: 18,
                                    fontWeight: FontWeight.w500,
                                  ),
                                  AppText.heading2(
                                    currency(double.parse(logic.checkoutRes![
                                            'platform_of_bording_fees']
                                        .toString())),
                                    color: primaryColor,
                                    getfontSize: 18,
                                    fontWeight: FontWeight.w500,
                                  ),
                                ],
                              ),
                              Gap(getWidth(20)!),
                              Row(
                                crossAxisAlignment: CrossAxisAlignment.start,
                                mainAxisAlignment:
                                    MainAxisAlignment.spaceBetween,
                                children: [
                                  (logic.couponId == "" &&
                                          logic.couponAmount <= 0)
                                      ? AppText.heading2(
                                          "Promo Code",
                                          color: blackColor,
                                          getfontSize: 18,
                                          fontWeight: FontWeight.w500,
                                        )
                                      : Column(
                                          crossAxisAlignment:
                                              CrossAxisAlignment.start,
                                          children: [
                                            AppText.heading2(
                                              "Promo Code (Applied ${logic.couponName})",
                                              color: blackColor,
                                              getfontSize: 18,
                                              fontWeight: FontWeight.w500,
                                            ),
                                            if ((logic.couponId != "" &&
                                                logic.couponAmount > 0))
                                              GestureDetector(
                                                onTap: () {
                                                  logic.couponId = "";
                                                  logic.couponAmount = 0;
                                                  logic.couponName = "";
                                                  logic.update();
                                                },
                                                child: AppText.heading2(
                                                  "Remove Code",
                                                  color: primaryColor,
                                                  getfontSize: 18,
                                                  fontWeight: FontWeight.w500,
                                                ),
                                              ),
                                          ],
                                        ),
                                  (logic.couponId == "" &&
                                          logic.couponAmount <= 0)
                                      ? PrimaryButton(
                                          width: getWidth(100),
                                          verticalPaddingGet: 6,
                                          textSize: 14,
                                          text: "Apply",
                                          onTap: () async {
                                            var coupon = await Get.toNamed(
                                              Routes.COUPON_CODE,
                                              arguments: logic
                                                  .checkoutRes!['total']
                                                  .toString(),
                                            );
                                            if (coupon != null) {
                                              logic.couponAmount = double.parse(
                                                  coupon['coupon_value']
                                                      .toString());
                                              logic.couponId =
                                                  coupon['id'].toString();
                                              logic.couponName =
                                                  coupon['code'].toString();
                                              logic.update();
                                            }
                                          },
                                        )
                                      : AppText.heading2(
                                          "- ${currency(logic.couponAmount)}",
                                          color: primaryColor,
                                          getfontSize: 18,
                                          fontWeight: FontWeight.w500,
                                        ),
                                ],
                              ),
                              Gap(getWidth(20)!),
                              Row(
                                mainAxisAlignment:
                                    MainAxisAlignment.spaceBetween,
                                children: [
                                  AppText.heading2(
                                    "Paynment Mode",
                                    color: blackColor,
                                    getfontSize: 18,
                                    fontWeight: FontWeight.w500,
                                  ),
                                  AppText.heading2(
                                    "Cash",
                                    color: primaryColor,
                                    getfontSize: 18,
                                    fontWeight: FontWeight.w500,
                                  ),
                                ],
                              ),
                              Gap(getWidth(20)!),
                            ],
                          ),
                        ),
                      ],
                    ),
                  ),
            bottomNavigationBar: logic.isLoading
                ? const SizedBox.shrink()
                : Container(
                    color: fillColor,
                    padding: EdgeInsets.symmetric(
                        horizontal: getWidth(20)!, vertical: getWidth(30)!),
                    child: Row(
                      children: [
                        AppText.heading2(
                          currency(logic.totalAmount - logic.couponAmount),
                          color: primaryColor,
                          getfontSize: 20,
                          fontWeight: FontWeight.w800,
                        ),
                        Gap(getWidth(100)!),
                        Expanded(
                          child: PrimaryButton(
                            verticalPaddingGet: 14,
                            text: "Place Order",
                            onTap: () {
                              logic.placeOrderAPI();
                            },
                          ),
                        ),
                      ],
                    ),
                  ),
          );
        });
  }

  _productWidget(BuildContext context, CheckoutController logic, dynamic data) {
    return Padding(
      padding: EdgeInsets.all(getWidth(20)!),
      child: Row(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          ClipRRect(
            borderRadius: BorderRadius.circular(getWidth(12)!),
            child: CacheImage(
              path: data['image_url'],
              height: getWidth(170),
              width: getWidth(120),
              fit: BoxFit.cover,
            ),
          ),
          Gap(getWidth(20)!),
          Expanded(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Gap(getWidth(4)!),
                AppText.heading2(
                  data['product_name'],
                  color: blackColor,
                  getfontSize: 18,
                  fontWeight: FontWeight.w500,
                  maxLines: 1,
                ),
                Gap(getWidth(8)!),
                if (data['variants'] != null && data['variants'].isNotEmpty)
                  Container(
                    margin: EdgeInsets.only(bottom: getWidth(8)!),
                    height: getWidth(34),
                    padding: EdgeInsets.symmetric(
                        horizontal: getWidth(16)!, vertical: getWidth(6)!),
                    decoration: BoxDecoration(
                      borderRadius: BorderRadius.circular(
                        getWidth(8)!,
                      ),
                      color: lightGray,
                    ),
                    child: AppText.paragraph(
                      data['variants'],
                      color: blackColor,
                      fontWeight: FontWeight.w500,
                    ),
                  ),
                Row(
                  children: [
                    AppText.paragraph(
                      currency(
                          double.tryParse(data['product_price'].toString())!),
                      fontWeight: FontWeight.w700,
                      color: hintColor,
                      getfontSize: 18,
                      decoration: TextDecoration.lineThrough,
                    ),
                    Gap(getWidth(12)!),
                    AppText.paragraph(
                      currency(
                          double.tryParse(data['discountprice'].toString())!),
                      fontWeight: FontWeight.w700,
                      color: primaryColor,
                      getfontSize: 20,
                    ),
                  ],
                ),
                Gap(getWidth(20)!),
                AppText.heading2(
                  'Quantity : ${data['cart_quantity']}',
                  fontWeight: FontWeight.w600,
                ),
              ],
            ),
          )
        ],
      ),
    );
  }
}
