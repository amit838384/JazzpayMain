import 'package:get/get.dart';

import '../controllers/top_up_controller.dart';

class TopUpBinding extends Bindings {
  @override
  void dependencies() {
    Get.lazyPut<TopUpLogic>(
      () => TopUpLogic(),
    );
  }
}
