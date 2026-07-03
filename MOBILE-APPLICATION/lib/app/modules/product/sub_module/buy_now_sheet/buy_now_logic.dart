import 'dart:developer';

import 'package:jazz_smart_pay/exports.dart';

import '../../../../dio_api/dio_api.dart';
import '../../../../utils/constant_vars.dart';

class BuyNowLogic extends GetxController {
  @override
  void onInit() {
    buyNowAPI();
    super.onInit();
  }

  String price = "0";
  String disPrice = "0";
  String stockQuantity = "0";
  bool isLoading = false;
  Map<String, dynamic>? buyNowRes;
  buyNowAPI() {
    isLoading = true;
    update();
    API().post("/product-buynow-data",
        data: {"proid": Constants.productBuyId}).then((value) async {
      Map<String, dynamic>? res = value.data;
      if (res != null) {
        if (res['status'] == 1) {
          buyNowRes = res['data'];
          price = buyNowRes?['price'];
          disPrice = buyNowRes?['discountprice'];
          stockQuantity = buyNowRes?['quentity'] ?? "0";
          totalAttributes = buyNowRes?['attributes']?.length ?? 0;
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

  bool isCheckLoading = false;
  bool isValid = false;
  checkVariantAPI() {
    quantity = 1;
    isValid = false;
    isCheckLoading = true;
    update();
    API().post(
      "/varientchange",
      data: {
        "product_id": Constants.productBuyId,
        // "quantity": quantity,
        "attributes": [selectedData],
      },
    ).then((value) async {
      Map<String, dynamic>? res = value.data;
      if (res != null) {
        if (res['status'] == 1) {
          price = res['data']['price'];
          disPrice = res['data']['discount_price'];
          stockQuantity = res['data']['quantity'] ?? "0";
          isValid = true;
        } else {
          Constants.errorDialog(message: res['message']);
        }
      } else {
        Constants.errorDialog();
      }
      isCheckLoading = false;
      update();
    });
  }

  bool isCartLoading = false;

  addToCartAPI() {
    isCartLoading = true;
    update();
    API().post("/addcart", data: {
      "proid": Constants.productBuyId,
      "quantity": quantity,
      "selectedAttribute": selectedData,
    }).then((value) async {
      Map<String, dynamic>? res = value.data;
      if (res != null) {
        if (res['status'] == 1) {
          Get.close(1);
          Get.toNamed(Routes.CART);
        } else {
          Constants.errorDialog(message: res['message']);
          isCartLoading = false;
          update();
        }
      } else {
        Constants.errorDialog();
        isCartLoading = false;
        update();
      }
    });
  }

  Map<int, String> selectedValues = {};
  List<String> selectedData = [];
  int totalAttributes = 0;

  void selectValue(int attributeIndex, String value) {
    selectedValues[attributeIndex] = value;

    while (selectedData.length <= attributeIndex) {
      selectedData.add('');
    }

    selectedData[attributeIndex] = value;

    log("Updated selectedData: $selectedData");
    update();
  }

  bool get isAllSelected {
    if (totalAttributes == 0) return false;

    for (int i = 0; i < totalAttributes; i++) {
      if (!selectedValues.containsKey(i)) return false;
      final val = selectedValues[i];
      if (val == null || val.trim().isEmpty) return false;
    }
    return true;
  }

  int quantity = 1;

  void increment() {
    if (quantity < int.parse(stockQuantity)) {
      quantity++;
      update();
    }
  }

  void decrement() {
    if (quantity > 1) {
      quantity--;
      update();
    }
  }
}
