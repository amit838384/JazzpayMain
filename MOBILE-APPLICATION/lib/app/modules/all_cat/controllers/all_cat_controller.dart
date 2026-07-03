import 'dart:developer';

import 'package:get/get.dart';
import '../../../dio_api/dio_api.dart';
import '../../../utils/constant_vars.dart';

class AllCatController extends GetxController {
  @override
  void onInit() {
    getAllCategoriesAPI();
    super.onInit();
  }

  bool isLoading = false;
  Map<String, dynamic>? listRes;

  getAllCategoriesAPI() {
    isLoading = true;
    update();
    API().get("/all-shop-category").then((value) async {
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
