import 'dart:developer';
import 'package:get/get.dart';
import '../../../dio_api/dio_api.dart';
import '../../../utils/constant_vars.dart';

class AddressController extends GetxController {
  String primaryAddressId = "";
  String primaryApiAddressId = "";
  @override
  void onInit() {
    primaryAddressId = Get.arguments;
    getAddressAPI();
    super.onInit();
  }

  bool isLoading = false;
  Map<String, dynamic>? resAddress;
  getAddressAPI() {
    isLoading = true;
    update();
    API().get("/getaddress").then((value) async {
      Map<String, dynamic>? res = value.data;
      if (res != null) {
        if (res['status'] == 1) {
          resAddress = res;
          for (var address in res['data']) {
            if (address['status'] == 1) {
              primaryApiAddressId = address['id'].toString();
            }
          }
          log("Response  :$res");
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

  deleteAddressAPI(String id) {
    isLoading = true;
    update();
    API().post("/deleteaddress/$id").then((value) async {
      Map<String, dynamic>? res = value.data;
      if (res != null) {
        if (res['status'] == 1) {
          resAddress!['data']
              .removeWhere((address) => address['id'].toString() == id);
          Constants.successDialog(
            message: res['message'],
            onTap: () {
              Get.back();
            },
          );
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

  setPrimaryAddressAPI(String id) {
    isLoading = true;
    update();
    API().post("/setActiveAddress/$id").then((value) async {
      Map<String, dynamic>? res = value.data;
      if (res != null) {
        if (res['status'] == 1) {
          getAddressAPI();
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
