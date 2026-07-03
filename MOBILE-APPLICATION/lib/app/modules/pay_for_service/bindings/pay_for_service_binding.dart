import 'package:get/get.dart';
import '../controllers/pay_for_service_controller.dart';

class PayForServiceBinding extends Bindings {
  @override
  void dependencies() {
    Get.lazyPut<PayForServiceLogic>(
      () => PayForServiceLogic(),
    );
  }
}
