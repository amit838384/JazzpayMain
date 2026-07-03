import 'package:get/get.dart';
import '../controllers/sub_cat_controller.dart';

class SubCatBinding extends Bindings {
  @override
  void dependencies() {
    Get.lazyPut<SubCatController>(
      () => SubCatController(),
    );
  }
}
