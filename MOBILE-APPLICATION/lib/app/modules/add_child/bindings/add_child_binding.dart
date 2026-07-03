import 'package:get/get.dart';
import '../controllers/add_child_controller.dart';

class AddChildBinding extends Bindings {
  @override
  void dependencies() {
    Get.lazyPut<AddChildLogic>(
      () => AddChildLogic(),
    );
  }
}
