import 'package:get/get.dart';
import 'package:jazz_smart_pay/app/modules/pdf_view/controllers/pdf_view_controller.dart';

class PdfViewBinding extends Bindings {
  @override
  void dependencies() {
    Get.lazyPut<PdfViewLogic>(
          () => PdfViewLogic(),
    );
  }
}