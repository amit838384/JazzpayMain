import 'package:get/get.dart';
import '../controllers/orders_details_controller.dart';

class OrderDetailsBinding extends Bindings {
  @override
  void dependencies() {
    Get.lazyPut<OrderDetailsController>(
      () => OrderDetailsController(),
    );
  }
}
