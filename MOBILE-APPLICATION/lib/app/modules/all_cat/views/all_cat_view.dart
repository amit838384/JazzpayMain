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
import '../controllers/all_cat_controller.dart';

class AllCatView extends GetView<AllCatController> {
  final bool showBack;
  const AllCatView({super.key, this.showBack = true});
  @override
  Widget build(BuildContext context) {
    return GetBuilder<AllCatController>(
        init: AllCatController(),
        builder: (logic) {
          return Scaffold(
            backgroundColor: bgColor,
            appBar: myAppBar(title: "All categories", visibleBack: showBack),
            body: logic.isLoading
                ? const Center(child: LoadingCircularComponent())
                : SingleChildScrollView(
                    padding: EdgeInsets.symmetric(horizontal: getWidth(12)!),
                    child: Column(
                      children: [
                        Gap(getWidth(24)!),
                        if (logic.listRes!['data'].isNotEmpty)
                          Center(
                            child: Wrap(
                              spacing: getWidth(16)!,
                              runSpacing: getWidth(8)!,
                              children: List.generate(
                                logic.listRes!['data'].length,
                                (index) {
                                  var category = logic.listRes!['data'][index];
                                  return GestureDetector(
                                    onTap: () {
                                      Get.toNamed(
                                        Routes.SUB_CAT,
                                        arguments: {
                                          "id": category['main_cat_id'],
                                          "name": category['main_cat_name']
                                        },
                                      );
                                    },
                                    child: SizedBox(
                                      width: getWidth(140),
                                      child: Column(
                                        crossAxisAlignment:
                                            CrossAxisAlignment.center,
                                        mainAxisSize: MainAxisSize.min,
                                        children: [
                                          Container(
                                            height: getWidth(120),
                                            width: getWidth(140),
                                            padding:
                                                EdgeInsets.all(getWidth(6)!),
                                            decoration: BoxDecoration(
                                              color: whiteColor,
                                              borderRadius:
                                                  BorderRadius.circular(
                                                      getWidth(16)!),
                                            ),
                                            child: ClipRRect(
                                              borderRadius:
                                                  BorderRadius.circular(
                                                      getWidth(12)!),
                                              child: CacheImage(
                                                path: category['image'],
                                                fit: BoxFit.cover,
                                                alignment: Alignment.topCenter,
                                              ),
                                            ),
                                          ),
                                          Gap(getWidth(10)!),
                                          Flexible(
                                            child: AppText.heading3(
                                              category['main_cat_name'],
                                              getfontSize: 18,
                                              maxLines: 2,
                                              textAlign: TextAlign.center,
                                            ),
                                          ),
                                          Gap(getWidth(10)!),
                                        ],
                                      ),
                                    ),
                                  );
                                },
                              ),
                            ),
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

  _subCatWidget(AllCatController logic, Map subCat) {
    return GestureDetector(
      behavior: HitTestBehavior.translucent,
      onTap: () {},
      child: Padding(
        padding: EdgeInsets.only(bottom: getWidth(30)!),
        child: Row(
          children: [
            ClipRRect(
              borderRadius: BorderRadius.circular(getWidth(8)!),
              child: CacheImage(
                path: subCat['main_cat_name'],
                height: getWidth(60),
                width: getWidth(60),
                fit: BoxFit.cover,
              ),
            ),
            Gap(getWidth(12)!),
            Expanded(
              child: AppText.paragraph(
                subCat['main_cat_name'],
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
