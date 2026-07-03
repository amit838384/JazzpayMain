import 'package:jazz_smart_pay/app/modules/add_address/controllers/add_address_controller.dart';
import 'package:get/get.dart';

class AddAddressBinding extends Bindings {
  @override
  void dependencies() {
    Get.lazyPut<AddAddressController>(
      () => AddAddressController(),
    );
  }
}
