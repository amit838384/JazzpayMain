import 'dart:async';
import 'dart:developer';

import 'package:flutter/material.dart';
import 'package:get/get.dart';

import '../../../dio_api/dio_api.dart';
import '../../../utils/constant_vars.dart';

class SearchLogic extends GetxController {
  late TextEditingController searchController;
  @override
  void onInit() {
    searchController = TextEditingController();
    super.onInit();
  }

  bool isLoading = false;
  Map<String, dynamic>? searchRes;
  getProductListAPI() {
    isLoading = true;
    update();
    API().post("/searchproduct",
        data: {"name": searchController.text.trim()}).then((value) async {
      Map<String, dynamic>? res = value.data;
      if (res != null) {
        if (res['status'] == 1) {
          searchRes = res;
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

  Timer? _debounce;

  void onSearchTextChanged(String text) {
    if (_debounce?.isActive ?? false) _debounce!.cancel();

    if (text.trim().isEmpty) {
      searchRes = null;
      update();
      return;
    }
    _debounce = Timer(const Duration(milliseconds: 500), () {
      getProductListAPI();
    });
  }

  @override
  void dispose() {
    _debounce!.cancel();
    super.dispose();
  }
}
