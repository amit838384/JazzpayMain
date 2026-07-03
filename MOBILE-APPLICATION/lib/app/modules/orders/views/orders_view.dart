import 'package:flutter/material.dart';
import 'package:jazz_smart_pay/app/routes/app_pages.dart';
import 'package:jazz_smart_pay/app/utils/app_const_colors.dart';
import 'package:jazz_smart_pay/app/utils/app_size.dart';
import 'package:gap/gap.dart';
import 'package:get/get.dart';
import 'package:jazz_smart_pay/app/utils/currency_util.dart';
import '../../../custom_widget/app_text.dart';
import '../../../custom_widget/loading_circular_component.dart';
import '../../../custom_widget/my_app_bar.dart';
import '../controllers/orders_controller.dart';

class OrdersView extends GetView<OrdersController> {
  const OrdersView({super.key});
  @override
  Widget build(BuildContext context) {
    return GetBuilder<OrdersController>(
        init: OrdersController(),
        builder: (logic) {
          return Scaffold(
            appBar: myAppBar(title: "My Orders"),
            backgroundColor: bgColor,
            body: logic.isLoading
                ? const Center(child: LoadingCircularComponent())
                : SingleChildScrollView(
                    padding: EdgeInsets.symmetric(vertical: getWidth(30)!),
                    child: Column(
                      children: List.generate(
                        logic.orders!['data'].length,
                        (index) {
                          var order = logic.orders!['data'][index];
                          return _orderWidget(logic, order);
                        },
                      ),
                    ),
                  ),
          );
        });
  }

  _orderWidget(OrdersController logic, dynamic order) {
    return GestureDetector(
      onTap: () {
        Get.toNamed(
          Routes.ORDER_DETAILS,
          arguments: order['order_id'].toString(),
        );
      },
      child: Container(
        margin: EdgeInsets.only(
            bottom: getWidth(24)!, left: getWidth(20)!, right: getWidth(20)!),
        padding: EdgeInsets.all(getWidth(20)!),
        decoration: BoxDecoration(
          borderRadius: BorderRadius.circular(getWidth(16)!),
          color: fillColor,
        ),
        child: Column(
          children: [
            Row(
              mainAxisAlignment: MainAxisAlignment.spaceBetween,
              children: [
                AppText.heading1Sub(
                  "Order No : ${order['order_random_id']}",
                  color: blackColor,
                  getfontSize: 16,
                ),
                AppText.heading1Sub(
                  order['order_date'],
                  color: hintColor,
                  getfontSize: 14,
                ),
              ],
            ),
            Gap(getWidth(12)!),
            Row(
              mainAxisAlignment: MainAxisAlignment.spaceBetween,
              children: [
                Row(
                  children: [
                    AppText.heading2(
                      "Quantity : ",
                      color: hintColor,
                      getfontSize: 16,
                    ),
                    AppText.heading2(
                      order['quantity'].toString(),
                      color: blackColor,
                      getfontSize: 16,
                    ),
                  ],
                ),
                Row(
                  children: [
                    AppText.heading2(
                      "Total Amount : ",
                      color: hintColor,
                      getfontSize: 16,
                    ),
                    AppText.heading2(
                      currency(double.parse(order['total_price'].toString())),
                      color: blackColor,
                      getfontSize: 16,
                    ),
                  ],
                ),
              ],
            ),
            Gap(getWidth(20)!),
            Row(
              mainAxisAlignment: MainAxisAlignment.spaceBetween,
              children: [
                Container(
                  padding: EdgeInsets.symmetric(
                      horizontal: getWidth(30)!, vertical: getWidth(10)!),
                  decoration: BoxDecoration(
                      borderRadius: BorderRadius.circular(getWidth(100)!),
                      border: Border.all(
                        width: getWidth(2)!,
                        color: primaryColor,
                      )),
                  child: AppText.heading1(
                    "Details",
                    color: primaryColor,
                    getfontSize: 16,
                    fontWeight: FontWeight.w600,
                  ),
                ),
                // if (statusMap.containsKey(orderType))
                AppText.heading1(
                  // statusMap[orderType]!.$1,
                  order['order_status'], // color: statusMap[orderType]!.$2,
                  color: blackColor,
                  getfontSize: 18,
                  fontWeight: FontWeight.w700,
                ),
              ],
            )
          ],
        ),
      ),
    );
  }
}
