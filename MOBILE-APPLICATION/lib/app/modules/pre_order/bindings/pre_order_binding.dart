import 'package:get/get.dart';
import '../controllers/pre_order_controller.dart';

class PreOrderBinding extends Bindings {
  @override
  void dependencies() {
    Get.lazyPut<PreOrderLogic>(
      () => PreOrderLogic(),
    );
  }
}
