import 'package:jazz_smart_pay/app/modules/checkout/controllers/checkout_controller.dart';
import 'package:get/get.dart';

import '../controllers/coupon_code_controller.dart';

class CouponCodeBinding extends Bindings {
  @override
  void dependencies() {
    Get.lazyPut<CouponCodeController>(
      () => CouponCodeController(),
    );
  }
}
