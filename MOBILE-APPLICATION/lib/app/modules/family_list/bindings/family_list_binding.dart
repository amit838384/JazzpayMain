import 'package:get/get.dart';
import '../controllers/family_list_controller.dart';

class FamilyListBinding extends Bindings {
  @override
  void dependencies() {
    Get.lazyPut<FamilyListLogic>(
      () => FamilyListLogic(),
    );
  }
}
