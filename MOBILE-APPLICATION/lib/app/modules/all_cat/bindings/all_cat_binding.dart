import 'package:get/get.dart';
import '../controllers/all_cat_controller.dart';

class AllCatBinding extends Bindings {
  @override
  void dependencies() {
    Get.lazyPut<AllCatController>(
      () => AllCatController(),
    );
  }
}
