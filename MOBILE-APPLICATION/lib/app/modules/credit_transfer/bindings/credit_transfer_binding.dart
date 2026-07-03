import 'package:get/get.dart';

import '../controllers/credit_transfer_controller.dart';

class CreditTransferBinding extends Bindings {
  @override
  void dependencies() {
    Get.lazyPut<CreditTransferLogic>(
      () => CreditTransferLogic(),
    );
  }
}
