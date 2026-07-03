import 'package:flutter/material.dart';
import 'package:jazz_smart_pay/app/custom_widget/app_text.dart';
import 'package:jazz_smart_pay/app/custom_widget/cached_image.dart';
import 'package:jazz_smart_pay/app/custom_widget/loading_circular_component.dart';
import 'package:jazz_smart_pay/app/custom_widget/my_app_bar.dart';
import 'package:jazz_smart_pay/app/routes/app_pages.dart';
import 'package:jazz_smart_pay/app/utils/app_const_colors.dart';
import 'package:jazz_smart_pay/app/utils/app_size.dart';
import 'package:gap/gap.dart';
import 'package:get/get.dart';
import '../controllers/sub_cat_controller.dart';

class SubCatView extends GetView<SubCatController> {
  const SubCatView({super.key});
  @override
  Widget build(BuildContext context) {
    return GetBuilder<SubCatController>(builder: (logic) {
      return Scaffold(
        backgroundColor: bgColor,
        appBar: myAppBar(title: logic.arg['name']),
        body: logic.isLoading
            ? const Center(child: LoadingCircularComponent())
            : SingleChildScrollView(
                child: Column(
                  children: [
                    Gap(getWidth(20)!),
                    if (logic.listRes!['data'].isNotEmpty)
                      ListView.builder(
                        padding:
                            EdgeInsets.symmetric(horizontal: getWidth(20)!),
                        shrinkWrap: true,
                        physics: const NeverScrollableScrollPhysics(),
                        itemCount: logic.listRes!['data'].length,
                        itemBuilder: (context, index) {
                          var product = logic.listRes!['data'][index];
                          return _subCatWidget(logic, product);
                        },
                      ),
                    if (logic.listRes!['data'].isEmpty)
                      Column(
                        children: [
                          Gap(getWidth(400)!),
                          Center(
                            child: AppText.heading2(
                              logic.listRes!['message'],
                              textAlign: TextAlign.center,
                              fontWeight: FontWeight.w500,
                              getfontSize: 20,
                            ),
                          ),
                        ],
                      )
                  ],
                ),
              ),
      );
    });
  }

  _subCatWidget(SubCatController logic, Map subCat) {
    return GestureDetector(
      behavior: HitTestBehavior.translucent,
      onTap: () {
        if (subCat['subsubcat'] == "no") {
          Get.toNamed(
            Routes.PRODUCT_LIST,
            arguments: {
              "id": subCat['subcatid'],
              "name": subCat['name'],
              "check": "no",
            },
          );
        } else {
          Get.toNamed(
            Routes.SUB_SUB_CAT,
            arguments: {"id": subCat['subcatid'], "name": subCat['name']},
          );
        }
      },
      child: Padding(
        padding: EdgeInsets.only(bottom: getWidth(30)!),
        child: Row(
          children: [
            ClipRRect(
              borderRadius: BorderRadius.circular(getWidth(8)!),
              child: CacheImage(
                path: subCat['image'],
                height: getWidth(60),
                width: getWidth(60),
                fit: BoxFit.cover,
              ),
            ),
            Gap(getWidth(12)!),
            Expanded(
              child: AppText.paragraph(
                subCat['name'],
                color: blackColor,
                getfontSize: 18,
                height: 1,
                fontWeight: FontWeight.w600,
                maxLines: 2,
                overflow: TextOverflow.ellipsis,
              ),
            ),
          ],
        ),
      ),
    );
  }
}
