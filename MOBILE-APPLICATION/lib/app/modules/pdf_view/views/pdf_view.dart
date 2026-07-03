import 'package:flutter/cupertino.dart';
import 'package:flutter/material.dart';
import 'package:flutter/src/widgets/framework.dart';
import 'package:flutter_localization/flutter_localization.dart';
import 'package:flutter_pdfview/flutter_pdfview.dart';
import 'package:get/get_state_manager/src/simple/get_state.dart';
import 'package:get/get_state_manager/src/simple/get_view.dart';
import 'package:jazz_smart_pay/app/custom_widget/loading_circular_component.dart';
import 'package:jazz_smart_pay/app/custom_widget/my_app_bar.dart';
import 'package:jazz_smart_pay/app/modules/pdf_view/controllers/pdf_view_controller.dart';

import '../../../utils/app_const_colors.dart';

class PdfView extends GetView<PdfViewLogic> {
  const PdfView({super.key});

  @override
  Widget build(BuildContext context) {
    return GetBuilder<PdfViewLogic>(
      init: PdfViewLogic(),
        builder: (logic) {
          return GestureDetector(
            child: Scaffold(
              appBar: myAppBar(
                  title: "pre_order".getString(context),
                  color: textColor,
                  contentColor: bgColor,
                  centerTitle: true
              ),
              body: logic.isLoading
                  ? const Center(
                      child: LoadingCircularComponent(
                          indicatorColor: buttonColor
                      ),
                    )
                  : logic.localPath == null
                  ? Center(
                      child: Text(
                        "Menu not available.",
                        style: TextStyle(
                          color: redColor,
                          fontSize: 18,
                          fontWeight: FontWeight.w600,
                        ),
                    ),
                  )
                  : PDFView(
                filePath: logic.localPath!,
              ),
            ),
          );
        }
    );
  }
}