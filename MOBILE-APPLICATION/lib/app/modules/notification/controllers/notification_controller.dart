import 'dart:async';
import 'dart:developer';

import 'package:flutter/material.dart';
import 'package:get/get.dart';

import '../../../dio_api/dio_api.dart';
import '../../../utils/constant_vars.dart';

class NotificationLogic extends GetxController {
  @override
  void onInit() {
    getProductListAPI();
    super.onInit();
  }

  bool isLoading = false;
  Map<String, dynamic>? notiRes;
  getProductListAPI() {
    isLoading = true;
    update();
    API().post("/show-notifaction").then((value) async {
      Map<String, dynamic>? res = value.data;
      if (res != null) {
        if (res['status'] ?? false) {
          notiRes = res;
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
