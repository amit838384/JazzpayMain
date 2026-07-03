import 'package:get/get.dart';
import 'package:jazz_smart_pay/app/utils/constant_vars.dart';

import '../../../dio_api/dio_api.dart';

class OrderDetailsController extends GetxController {
  String orderId = "";
  @override
  void onInit() {
    orderId = Get.arguments;
    orderDetailsAPI();
    super.onInit();
  }

  bool isLoading = false;
  Map<String, dynamic>? orderRes;
  Map<String, dynamic>? address;
  orderDetailsAPI() {
    isLoading = true;
    update();
    API().get("/ordervendordetailproductlist/$orderId").then((value) async {
      Map<String, dynamic>? res = value.data;
      if (res != null) {
        if (res['status'] == 1) {
          orderRes = res['data'];
          address = res['data']['address'];
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
}
