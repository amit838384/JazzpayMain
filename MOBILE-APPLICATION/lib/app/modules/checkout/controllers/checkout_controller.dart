import 'package:get/get.dart';

import '../../../dio_api/dio_api.dart';
import '../../../utils/constant_vars.dart';
import '../components/order_success.dart';

class CheckoutController extends GetxController {
  //TODO: Implement OnboardController

  @override
  void onInit() {
    checkoutAPI();
    super.onInit();
  }

  double couponAmount = 0;
  double totalAmount = 0;
  String couponId = "";
  String couponName = "";
  bool isLoading = false;
  Map<String, dynamic>? checkoutRes;
  Map<String, dynamic>? address;
  checkoutAPI() {
    isLoading = true;
    update();
    API().get("/checkoutpage").then((value) async {
      Map<String, dynamic>? res = value.data;
      if (res != null) {
        if (res['status'] == 1) {
          checkoutRes = res;
          address = checkoutRes?['address'];
          totalAmount = double.parse(checkoutRes!['total'].toString());
          isLoading = false;
          update();
        } else {
          Constants.errorDialog(message: res['message']);
          isLoading = false;
          update();
        }
      } else {
        Constants.errorDialog();
        isLoading = false;
        update();
      }
    });
  }

  placeOrderAPI() {
    isLoading = true;
    update();
    API().post(
      "/placeorder",
      data: {
        "address_id": address!['id'].toString(),
        "coupon_id": couponId,
        "shipping_charge": checkoutRes!['shippingcharge'].toString(),
        "total_amt": totalAmount - couponAmount,
        "commision": checkoutRes!['platform_of_bording_fees'].toString(),
        "payment_method": "cash",
        "currencyCode": "INR"
      },
    ).then((value) async {
      Map<String, dynamic>? res = value.data;
      if (res != null) {
        if (res['status'] == "success") {
          // Get.to(() => OrderSuccess(orderId: res['order_id'].toString()));
          update();
        } else {
          Constants.errorDialog(message: res['message']);
          isLoading = false;
          update();
        }
      } else {
        Constants.errorDialog();
        isLoading = false;
        update();
      }
    });
  }
}
