import 'package:flutter/material.dart';
import 'package:jazz_smart_pay/app/custom_widget/app_text.dart';
import 'package:jazz_smart_pay/app/custom_widget/bouncing_button.dart';
import 'package:jazz_smart_pay/app/custom_widget/fill_container.dart';
import 'package:jazz_smart_pay/app/custom_widget/my_app_bar.dart';
import 'package:jazz_smart_pay/app/utils/app_const_colors.dart';
import 'package:jazz_smart_pay/app/utils/app_size.dart';
import 'package:gap/gap.dart';
import 'package:get/get.dart';
import '../../../custom_widget/loading_circular_component.dart';
import '../controllers/coupon_code_controller.dart';

class CouponCodeView extends GetView<CouponCodeController> {
  const CouponCodeView({super.key});
  @override
  Widget build(BuildContext context) {
    return GetBuilder<CouponCodeController>(
        init: CouponCodeController(),
        builder: (logic) {
          return Scaffold(
            backgroundColor: bgColor,
            appBar: myAppBar(title: "Promo Codes"),
            body: logic.isLoading
                ? const Center(child: LoadingCircularComponent())
                : SingleChildScrollView(
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        if (logic.couponRes != null &&
                            logic.couponRes?['data'].isNotEmpty)
                          ...List.generate(
                            logic.couponRes?['data'].length,
                            (index) {
                              var coupon = logic.couponRes?['data'][index];
                              return Bouncing(
                                onTap: () {
                                  Get.back(result: coupon);
                                },
                                child: FillContainer(
                                    margin: EdgeInsets.all(getWidth(16)!),
                                    child: Column(
                                      crossAxisAlignment:
                                          CrossAxisAlignment.start,
                                      children: [
                                        Row(
                                          children: [
                                            AppText.heading2(
                                              "${coupon['coupon_name']} - ",
                                              fontWeight: FontWeight.w600,
                                              color: primaryColor,
                                            ),
                                            AppText.heading2(
                                              coupon['code'],
                                              fontWeight: FontWeight.w700,
                                              color: primaryColor,
                                            ),
                                          ],
                                        ),
                                        Gap(getWidth(4)!),
                                        AppText.paragraph(
                                          "By using this you can save ${coupon['coupon_value']}",
                                          fontWeight: FontWeight.w500,
                                          getfontSize: 18,
                                        ),
                                        Gap(getWidth(4)!),
                                        Align(
                                          alignment: Alignment.centerRight,
                                          child: AppText.paragraph(
                                            "Expiry ${coupon['coupon_expire_date']}",
                                            fontWeight: FontWeight.w500,
                                          ),
                                        )
                                      ],
                                    )),
                              );
                            },
                          ),
                        if (logic.couponRes == null ||
                            logic.couponRes?['data'].isEmpty)
                          _emptyPlaceHolder(),
                      ],
                    ),
                  ),
          );
        });
  }

  _emptyPlaceHolder() {
    return Center(
      child: Padding(
        padding: EdgeInsets.all(getWidth(20)!),
        child: Column(
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            Gap(getWidth(280)!),
            Icon(
              Icons.local_offer_outlined,
              size: getWidth(100),
              color: primaryColor,
            ),
            Gap(getWidth(16)!),
            AppText.heading2(
              "Sorry, No Promo Codes Available Yet.",
              textAlign: TextAlign.center,
              getfontSize: 24,
              fontWeight: FontWeight.w700,
              color: primaryColor,
            ),
            Gap(getWidth(10)!),
            AppText.paragraph(
              "Please check back later for exciting deals and discounts. We’re always updating with new offers just for you!",
              textAlign: TextAlign.center,
              fontWeight: FontWeight.w600,
            ),
          ],
        ),
      ),
    );
  }
}
