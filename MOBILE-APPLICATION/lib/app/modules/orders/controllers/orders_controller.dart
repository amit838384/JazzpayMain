import 'package:get/get.dart';
import 'package:jazz_smart_pay/app/utils/constant_vars.dart';
import '../../../dio_api/dio_api.dart';

class OrdersController extends GetxController {
  @override
  void onInit() {
    orderDetailsAPI();
    super.onInit();
  }

  bool isLoading = false;
  Map<String, dynamic>? orders;
  orderDetailsAPI() {
    isLoading = true;
    update();
    API().get("/ordervendorproductlist").then((value) async {
      Map<String, dynamic>? res = value.data;
      if (res != null) {
        if (res['status'] == 1) {
          orders = res;
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
