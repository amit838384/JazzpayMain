import 'dart:io';

import 'package:flutter/services.dart';
import 'package:get/get_state_manager/src/simple/get_controllers.dart';
import 'package:path_provider/path_provider.dart';

class PdfViewLogic extends GetxController {
  String? localPath;
  bool isLoading = true;

  @override
  void onInit() {
    super.onInit();
    loadPdf();
  }

  Future<void> loadPdf() async {
    // const assetPath = 'assets/pdf/acs_menu.pdf';
    const assetPath = 'assets/pdf/acs_july_menu.pdf';

    try {
      final bytes = await rootBundle.load(assetPath);
      final dir = await getTemporaryDirectory();
      final file = File("${dir.path}/temp.pdf");

      await file.writeAsBytes(bytes.buffer.asUint8List());

      localPath = file.path;
      isLoading = false;
      update();
    } catch (e) {
      isLoading = false;
      update();
    }
  }
}
