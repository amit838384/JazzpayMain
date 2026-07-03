import 'package:flutter/material.dart';
import 'package:jazz_smart_pay/app/custom_widget/app_text.dart';
import 'package:jazz_smart_pay/app/custom_widget/cached_image.dart';
import 'package:jazz_smart_pay/app/custom_widget/fill_container.dart';
import 'package:jazz_smart_pay/app/custom_widget/loading_circular_component.dart';
import 'package:jazz_smart_pay/app/custom_widget/my_app_bar.dart';
import 'package:jazz_smart_pay/app/routes/app_pages.dart';
import 'package:jazz_smart_pay/app/utils/app_const_colors.dart';
import 'package:jazz_smart_pay/app/utils/app_size.dart';
import 'package:jazz_smart_pay/app/utils/currency_util.dart';
import 'package:jazz_smart_pay/app/utils/image_path.dart';
import 'package:gap/gap.dart';
import 'package:get/get.dart';
import '../controllers/wishlist_controller.dart';

class WishlistView extends GetView<WishlistController> {
  const WishlistView({super.key});
  @override
  Widget build(BuildContext context) {
    return GetBuilder<WishlistController>(
        init: WishlistController(),
        builder: (logic) {
          return Scaffold(
            appBar: myAppBar(title: "Wishlist"),
            backgroundColor: bgColor,
            body: logic.isLoading
                ? const Center(child: LoadingCircularComponent())
                : SingleChildScrollView(
                    padding: EdgeInsets.all(getWidth(20)!),
                    child: Column(
                      children: [
                        if (logic.productRes!['data'].isNotEmpty)
                          ...List.generate(
                            logic.productRes!['data'].length,
                            (index) {
                              return _productWidget(
                                  logic, logic.productRes!['data'][index]);
                            },
                          )
                      ],
                    ),
                  ),
          );
        });
  }

  _productWidget(WishlistController logic, Map wishlist) {
    return Padding(
      padding: EdgeInsets.only(bottom: getWidth(40)!),
      child: GestureDetector(
        behavior: HitTestBehavior.translucent,
        onTap: () {
          Get.toNamed(
            Routes.PRODUCT,
            arguments: {"id": wishlist['id'], "name": wishlist['name']},
          );
        },
        child: IntrinsicHeight(
          child: FillContainer(
            padding: EdgeInsets.all(getWidth(12)!),
            child: Row(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                ClipRRect(
                  borderRadius: BorderRadius.circular(getWidth(12)!),
                  child: CacheImage(
                    path: wishlist['image'],
                    height: getWidth(120),
                    width: getWidth(120),
                    fit: BoxFit.cover,
                  ),
                ),
                Gap(getWidth(20)!),
                Expanded(
                  child: Row(
                    children: [
                      Expanded(
                        child: Column(
                          crossAxisAlignment: CrossAxisAlignment.start,
                          mainAxisAlignment: MainAxisAlignment.start,
                          children: [
                            Gap(getWidth(6)!),
                            AppText.heading2(
                              wishlist['name'],
                              color: blackColor,
                              getfontSize: 20,
                              fontWeight: FontWeight.w700,
                              maxLines: 2,
                            ),
                            Row(
                              children: [
                                AppText.paragraph(
                                  currency(double.parse(wishlist['price'])),
                                  fontWeight: FontWeight.w700,
                                  color: hintColor,
                                  getfontSize: 18,
                                  decoration: TextDecoration.lineThrough,
                                ),
                                Gap(getWidth(12)!),
                                AppText.paragraph(
                                  currency(double.parse(wishlist['discount'])),
                                  fontWeight: FontWeight.w700,
                                  color: primaryColor,
                                  getfontSize: 20,
                                ),
                              ],
                            ),
                            Gap(getWidth(12)!),
                            AppText.heading2(
                              wishlist['stockStatus'],
                              color: hintColor,
                              getfontSize: 16,
                              fontWeight: FontWeight.w500,
                              maxLines: 3,
                              overflow: TextOverflow.ellipsis,
                            ),
                          ],
                        ),
                      ),
                    ],
                  ),
                ),
                GestureDetector(
                  onTap: () {
                    logic.deleteToWishlistAPI(wishlist['id'].toString());
                  },
                  child: Padding(
                    padding: EdgeInsets.only(top: getWidth(8)!),
                    child: Icon(
                      Icons.favorite,
                      color: primaryColor,
                    ),
                  ),
                ),
              ],
            ),
          ),
        ),
      ),
    );
  }
}
