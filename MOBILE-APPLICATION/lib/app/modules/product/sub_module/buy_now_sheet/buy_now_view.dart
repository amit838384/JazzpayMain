import 'package:jazz_smart_pay/app/custom_widget/cached_image.dart';
import 'package:jazz_smart_pay/app/custom_widget/loading_circular_component.dart';
import 'package:jazz_smart_pay/app/custom_widget/primary_button.dart';
import 'package:jazz_smart_pay/app/modules/product/sub_module/buy_now_sheet/buy_now_logic.dart';
import 'package:jazz_smart_pay/app/utils/app_const_colors.dart';
import '../../../../../exports.dart';
import '../../../../custom_widget/app_text.dart';
import '../../../../utils/app_size.dart';
import '../../../../utils/currency_util.dart';

class BuyNowView extends StatelessWidget {
  const BuyNowView({super.key});

  @override
  Widget build(BuildContext context) {
    return GetBuilder<BuyNowLogic>(
      init: BuyNowLogic(),
      builder: (logic) {
        return Container(
          padding: EdgeInsets.only(top: getWidth(20)!),
          height: height()! * .75,
          decoration: BoxDecoration(
            borderRadius: BorderRadius.all(Radius.circular(getWidth(20)!)),
            border: Border.all(
                width: 1, color: const Color(0xFF324158).withValues(alpha: .5)),
            color: whiteColor,
          ),
          child: logic.isLoading || logic.isCheckLoading
              ? const Center(child: LoadingCircularComponent())
              : SingleChildScrollView(
                  padding: EdgeInsets.symmetric(horizontal: getWidth(20)!),
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      Gap(getWidth(20)!),
                      Row(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          GestureDetector(
                            onTap: () {
                              Get.close(1);
                            },
                            child: ClipRRect(
                              borderRadius:
                                  BorderRadius.circular(getWidth(12)!),
                              child: CacheImage(
                                path: logic.buyNowRes?['images'] ?? "",
                                height: getWidth(160),
                                width: getWidth(120),
                                fit: BoxFit.cover,
                              ),
                            ),
                          ),
                          Gap(getWidth(20)!),
                          Expanded(
                            child: Column(
                              crossAxisAlignment: CrossAxisAlignment.start,
                              children: [
                                Gap(getWidth(12)!),
                                AppText.heading2(
                                  logic.buyNowRes?['name'] ?? "",
                                  maxLines: 2,
                                  overflow: TextOverflow.ellipsis,
                                ),
                                Gap(getWidth(8)!),
                                Row(
                                  children: [
                                    AppText.paragraph(
                                      currency(double.tryParse(logic.price)),
                                      fontWeight: FontWeight.w700,
                                      color: hintColor,
                                      getfontSize: 18,
                                      decoration: TextDecoration.lineThrough,
                                    ),
                                    Gap(getWidth(12)!),
                                    AppText.paragraph(
                                      currency(double.tryParse(logic.disPrice)),
                                      fontWeight: FontWeight.w700,
                                      color: primaryColor,
                                      getfontSize: 20,
                                    ),
                                  ],
                                ),
                                Gap(getWidth(8)!),
                                AppText.paragraph(
                                  "Stock Qty : ${logic.stockQuantity}",
                                  fontWeight: FontWeight.w700,
                                  getfontSize: 18,
                                ),
                              ],
                            ),
                          ),
                        ],
                      ),
                      Gap(getWidth(30)!),
                      ...List.generate(
                        logic.buyNowRes?['attributes']?.length ?? 0,
                        (index) {
                          var attribute = logic.buyNowRes?['attributes'][index];

                          return Padding(
                            padding: EdgeInsets.only(bottom: getWidth(24)!),
                            child: Column(
                              crossAxisAlignment: CrossAxisAlignment.start,
                              children: [
                                AppText.heading2(attribute['name']),
                                Gap(getWidth(12)!),
                                SingleChildScrollView(
                                  scrollDirection: Axis.horizontal,
                                  child: Row(
                                    children: List.generate(
                                      attribute['values'].length,
                                      (innerIndex) {
                                        var valText =
                                            attribute['values'][innerIndex];
                                        bool isSelected =
                                            logic.selectedValues[index] ==
                                                valText;

                                        return GestureDetector(
                                          onTap: () {
                                            logic.selectValue(index, valText);
                                            if (logic.isAllSelected) {
                                              logic.checkVariantAPI();
                                            }
                                          },
                                          child: Container(
                                            margin: EdgeInsets.only(
                                                right: getWidth(16)!),
                                            padding: EdgeInsets.symmetric(
                                              vertical: getWidth(6)!,
                                              horizontal: getWidth(12)!,
                                            ),
                                            decoration: BoxDecoration(
                                              borderRadius:
                                                  BorderRadius.circular(
                                                      getWidth(8)!),
                                              border: Border.all(
                                                width: 1,
                                                color: isSelected
                                                    ? redColor
                                                    : blackColor,
                                              ),
                                              color:
                                                  isSelected ? redColor : null,
                                            ),
                                            child: AppText.paragraph(
                                              valText,
                                              getfontSize: 16,
                                              fontWeight: FontWeight.w700,
                                              color: isSelected
                                                  ? whiteColor
                                                  : blackColor,
                                            ),
                                          ),
                                        );
                                      },
                                    ),
                                  ),
                                ),
                              ],
                            ),
                          );
                        },
                      ),
                      if (int.parse(logic.stockQuantity) > 0)
                        Row(
                          children: [
                            AppText.heading2(
                              'Quantity',
                              fontWeight: FontWeight.w600,
                            ),
                            const Spacer(),
                            Container(
                              padding: EdgeInsets.symmetric(
                                  horizontal: getWidth(12)!,
                                  vertical: getWidth(8)!),
                              decoration: BoxDecoration(
                                border: Border.all(color: Colors.grey.shade400),
                                borderRadius: BorderRadius.circular(12),
                              ),
                              child: Row(
                                children: [
                                  GestureDetector(
                                    onTap: logic.decrement,
                                    child: Icon(
                                      Icons.remove,
                                      color: logic.quantity > 1
                                          ? Colors.black
                                          : Colors.grey,
                                    ),
                                  ),
                                  Gap(getWidth(24)!),
                                  Text(
                                    '${logic.quantity}',
                                    style: const TextStyle(
                                        fontSize: 16,
                                        fontWeight: FontWeight.w500),
                                  ),
                                  Gap(getWidth(24)!),
                                  GestureDetector(
                                    onTap: logic.increment,
                                    child: const Icon(Icons.add),
                                  ),
                                ],
                              ),
                            ),
                          ],
                        ),
                      Gap(getWidth(20)!),
                      int.parse(logic.stockQuantity) > 0
                          ? PrimaryButton(
                              color: redColor,
                              text: "Add to Cart",
                              isDisabled:
                                  logic.buyNowRes?['attributes']?.isNotEmpty &&
                                      !logic.isValid,
                              isLoading: logic.isCartLoading,
                              onTap: () {
                                logic.addToCartAPI();
                              },
                            )
                          : Center(
                              child: AppText.heading2(
                                  "This combination is out of stock",
                                  fontWeight: FontWeight.w600,
                                  color: primaryColor),
                            ),
                      Gap(getWidth(20)!),
                      PrimaryButton(
                        isOutlined: true,
                        text: "Cancel",
                        textColor: redColor,
                        outlineColor: redColor,
                        onTap: () {
                          Get.close(1);
                        },
                      )
                    ],
                  ),
                ),
        );
      },
    );
  }
}
