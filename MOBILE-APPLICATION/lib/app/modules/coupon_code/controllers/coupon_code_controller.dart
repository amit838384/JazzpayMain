import 'package:get/get.dart';

import '../../../dio_api/dio_api.dart';
import '../../../utils/constant_vars.dart';

class CouponCodeController extends GetxController {
  //TODO: Implement OnboardController
  String totalAmount = "";

  @override
  void onInit() {
    totalAmount = Get.arguments;
    getCouponCodesAPI();
    super.onInit();
  }

  bool isLoading = false;
  Map<String, dynamic>? couponRes;
  getCouponCodesAPI() {
    isLoading = true;
    update();
    API().post("/shownewcoupondata", data: {"total_price": totalAmount}).then(
        (value) async {
      Map<String, dynamic>? res = value.data;
      if (res != null) {
        if (res['status'] == 1) {
          couponRes = res;
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
