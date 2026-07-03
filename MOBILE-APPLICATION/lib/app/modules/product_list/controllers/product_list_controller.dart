import 'dart:developer';

import 'package:get/get.dart';
import '../../../dio_api/dio_api.dart';
import '../../../utils/constant_vars.dart';

class ProductListController extends GetxController {
  late Map arg;
  @override
  void onInit() {
    arg = Get.arguments;
    getProductListAPI();
    super.onInit();
  }

  bool isLoading = false;
  Map<String, dynamic>? listRes;
  getProductListAPI() {
    isLoading = true;
    update();
    API().get("/${arg['apiName']}").then((value) async {
      Map<String, dynamic>? res = value.data;
      if (res != null) {
        if (res['status'] == 1) {
          listRes = res;
          log("Response  :  $res");
        } else {
          Constants.errorDialog(message: res['message']);
        }
      } else {
        Constants.errorDialog();
      }

      isLoading = false;
      update();
    });
  }
}
