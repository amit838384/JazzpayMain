import 'package:get/get.dart';
import '../controllers/sub_sub_cat_controller.dart';

class SubSubCatBinding extends Bindings {
  @override
  void dependencies() {
    Get.lazyPut<SubSubCatController>(
      () => SubSubCatController(),
    );
  }
}
