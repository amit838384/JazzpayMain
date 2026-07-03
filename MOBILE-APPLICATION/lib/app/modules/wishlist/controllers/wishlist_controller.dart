import 'dart:developer';

import 'package:get/get.dart';

import '../../../dio_api/dio_api.dart';
import '../../../utils/constant_vars.dart';

class WishlistController extends GetxController {
  @override
  void onInit() {
    getWishlistProductsAPI();
    super.onInit();
  }

  bool isLoading = false;
  Map<String, dynamic>? productRes;
  getWishlistProductsAPI() {
    isLoading = true;
    update();
    API().get("/getwishlist").then((value) async {
      Map<String, dynamic>? res = value.data;
      if (res != null) {
        if (res['status'] == 1) {
          productRes = res;
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

  deleteToWishlistAPI(String id) {
    isLoading = true;
    update();
    API().post("/delwishlis", data: {"id": id}).then((value) async {
      Map<String, dynamic>? res = value.data;
      if (res != null) {
        if (res['status'] == 1) {
          getWishlistProductsAPI();
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
