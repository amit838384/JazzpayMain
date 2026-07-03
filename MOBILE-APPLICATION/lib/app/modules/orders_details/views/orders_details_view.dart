import 'package:flutter/material.dart';
import 'package:jazz_smart_pay/app/utils/app_const_colors.dart';
import 'package:jazz_smart_pay/app/utils/app_size.dart';
import 'package:gap/gap.dart';
import 'package:get/get.dart';
import '../../../custom_widget/app_text.dart';
import '../../../custom_widget/cached_image.dart';
import '../../../custom_widget/fill_container.dart';
import '../../../custom_widget/loading_circular_component.dart';
import '../../../custom_widget/my_app_bar.dart';
import '../../../routes/app_pages.dart';
import '../../../utils/constant_vars.dart';
import '../../../utils/currency_util.dart';
import '../controllers/orders_details_controller.dart';

class OrderDetailsView extends GetView<OrderDetailsController> {
  const OrderDetailsView({super.key});
  @override
  Widget build(BuildContext context) {
    return GetBuilder<OrderDetailsController>(
        init: OrderDetailsController(),
        builder: (logic) {
          return Scaffold(
            appBar: myAppBar(
              title: "My Orders",
              backTap: () {
                if (Constants.orderSuccess) {
                  Get.offAllNamed(Routes.BASE_PAGE);
                } else {
                  Get.back();
                }
              },
            ),
            backgroundColor: bgColor,
            body: logic.isLoading
                ? const Center(child: LoadingCircularComponent())
                : WillPopScope(
                    onWillPop: () {
                      if (Constants.orderSuccess) {
                        Get.offAllNamed(Routes.BASE_PAGE);
                      } else {
                        Get.back();
                      }
                      return Future.value(false);
                    },
                    child: SingleChildScrollView(
                      padding: EdgeInsets.symmetric(
                          vertical: getWidth(30)!, horizontal: getWidth(20)!),
                      child: Column(
                        children: [
                          FillContainer(
                            child: Column(
                              children: [
                                _rowItemWidget(
                                  key: "Order number",
                                  value:
                                      "#${logic.orderRes?['order_random_id']}",
                                ),
                                Gap(getWidth(12)!),
                                _rowItemWidget(
                                  key: "Order Status",
                                  value: "${logic.orderRes?['order_status']}",
                                ),
                                Gap(getWidth(12)!),
                                _rowItemWidget(
                                  key: "Order Date",
                                  value: "${logic.orderRes?['order_date']}",
                                ),
                                Gap(getWidth(12)!),
                                _rowItemWidget(
                                    key: "Payment Mode", value: "Cash"),
                              ],
                            ),
                          ),
                          Gap(getWidth(20)!),
                          FillContainer(
                            child: Column(
                              crossAxisAlignment: CrossAxisAlignment.start,
                              children: [
                                AppText.paragraph(
                                  "Delivery Address",
                                  fontWeight: FontWeight.w700,
                                  getfontSize: 18,
                                ),
                                Gap(getWidth(8)!),
                                Padding(
                                  padding:
                                      EdgeInsets.only(right: getWidth(120)!),
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
                          Gap(getWidth(20)!),
                          ...List.generate(
                            logic.orderRes?['order_details'].length,
                            (index) {
                              dynamic data =
                                  logic.orderRes?['order_details'][index];
                              return _productWidget(context, logic, data);
                            },
                          ),
                          FillContainer(
                            child: Column(
                              children: [
                                _rowItemWidget(
                                  key: "Items(3)",
                                  value: currency(double.parse(logic
                                      .orderRes!['product_val']
                                      .toString())),
                                ),
                                Gap(getWidth(12)!),
                                _rowItemWidget(
                                  key: "Shipping",
                                  value: currency(double.parse(logic
                                      .orderRes!['shipping_charge']
                                      .toString())),
                                ),
                                Gap(getWidth(12)!),
                                _rowItemWidget(
                                  key: "Platform charges",
                                  value: currency(double.parse(logic
                                      .orderRes!['totalcommission']
                                      .toString())),
                                ),
                                Gap(getWidth(12)!),
                                _rowItemWidget(
                                  key: logic.orderRes!['coupon_code'] != ""
                                      ? "Promo Code (Applied ${logic.orderRes!['coupon_code']})"
                                      : "Promo Code",
                                  value: currency(double.parse(logic
                                      .orderRes!['coupon_value']
                                      .toString())),
                                ),
                                Gap(getWidth(30)!),
                                _rowItemWidget(
                                  key: "Total",
                                  value: currency(double.parse(logic
                                      .orderRes!['total_price']
                                      .toString())),
                                ),
                              ],
                            ),
                          ),
                          Gap(getWidth(80)!),
                        ],
                      ),
                    ),
                  ),
          );
        });
  }

  _rowItemWidget({required String key, required String value}) {
    return Row(
      mainAxisAlignment: MainAxisAlignment.spaceBetween,
      children: [
        AppText.paragraph(
          key,
          fontWeight: FontWeight.w500,
          color: blackColor,
          getfontSize: 18,
        ),
        AppText.paragraph(
          value,
          fontWeight: FontWeight.w500,
          color: primaryColor,
          getfontSize: 18,
        ),
      ],
    );
  }

  _productWidget(
      BuildContext context, OrderDetailsController logic, dynamic data) {
    return FillContainer(
      margin: EdgeInsets.only(bottom: getWidth(20)!),
      child: Row(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          ClipRRect(
            borderRadius: BorderRadius.circular(getWidth(12)!),
            child: CacheImage(
              path: data['image_name'],
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
                      currency(double.tryParse(data['price'].toString())!),
                      fontWeight: FontWeight.w700,
                      color: hintColor,
                      getfontSize: 18,
                      decoration: TextDecoration.lineThrough,
                    ),
                    Gap(getWidth(12)!),
                    AppText.paragraph(
                      currency(double.tryParse(data['discount'].toString())!),
                      fontWeight: FontWeight.w700,
                      color: primaryColor,
                      getfontSize: 20,
                    ),
                  ],
                ),
                Gap(getWidth(20)!),
                AppText.heading2(
                  'Quantity : ${data['quantity']}',
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
