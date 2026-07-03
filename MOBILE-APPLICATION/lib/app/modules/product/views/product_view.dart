import 'dart:io';

import 'package:carousel_slider/carousel_slider.dart';
import 'package:dotted_border/dotted_border.dart';
import 'package:jazz_smart_pay/app/custom_widget/app_divider.dart';
import 'package:jazz_smart_pay/app/custom_widget/app_text.dart';
import 'package:jazz_smart_pay/app/custom_widget/bouncing_button.dart';
import 'package:jazz_smart_pay/app/custom_widget/cached_image.dart';
import 'package:jazz_smart_pay/app/custom_widget/my_app_bar.dart';
import 'package:jazz_smart_pay/app/custom_widget/primary_button.dart';
import 'package:jazz_smart_pay/app/custom_widget/profile_image_circle.dart';
import 'package:jazz_smart_pay/app/modules/product/sub_module/buy_now_sheet/buy_now_view.dart';
import 'package:jazz_smart_pay/app/utils/app_const_colors.dart';
import 'package:jazz_smart_pay/app/utils/app_size.dart';
import 'package:jazz_smart_pay/app/utils/constant_vars.dart';
import 'package:jazz_smart_pay/app/utils/image_path.dart';
import 'package:jazz_smart_pay/exports.dart';
import 'package:smooth_page_indicator/smooth_page_indicator.dart';
import '../../../custom_widget/loading_circular_component.dart';
import '../../../custom_widget/read_more.dart';
import '../../../utils/currency_util.dart';
import '../controllers/product_controller.dart';
import '../sub_module/item_images_viewer/view/item_images_viewer.dart';

class ProductView extends GetView<ProductController> {
  const ProductView({super.key});
  @override
  Widget build(BuildContext context) {
    return GetBuilder<ProductController>(builder: (logic) {
      return Scaffold(
        backgroundColor: bgColor,
        appBar: myAppBar(
          title: logic.arg['name'],
          actions: logic.isLoading || logic.isWishlistLoad
              ? []
              : [
                  GestureDetector(
                    onTap: () {
                      if (logic.productRes!['wishlist'].toString() == "1") {
                        logic.deleteToWishlistAPI();
                      } else {
                        logic.addToWishlistAPI();
                      }
                    },
                    child: Icon(
                      logic.productRes!['wishlist'].toString() == "1"
                          ? Icons.favorite
                          : Icons.favorite_border,
                      color: whiteColor,
                    ),
                  ),
                  Gap(getWidth(12)!),
                  GestureDetector(
                    onTap: () {
                      Get.toNamed(Routes.CART);
                    },
                    child: Image.asset(
                      ImagePath.cart,
                      height: getWidth(36),
                      width: getWidth(36),
                    ),
                  ),
                  Gap(getWidth(20)!),
                ],
        ),
        body: logic.isLoading || logic.isWishlistLoad
            ? const Center(child: LoadingCircularComponent())
            : SingleChildScrollView(
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    CacheImage(
                      path: logic.productRes!['media'][logic.selctedImageIndex]
                          ['mediaPath'],
                      fit: BoxFit.cover,
                      height: getWidth(400),
                    ),
                    Gap(getWidth(12)!),
                    SingleChildScrollView(
                      scrollDirection: Axis.horizontal,
                      child: Row(
                        children: [
                          Gap(getWidth(12)!),
                          Row(
                            children: List.generate(
                              logic.productRes!['media'].length,
                              (index) {
                                var media = logic.productRes!['media'][index];
                                return Padding(
                                  padding:
                                      EdgeInsets.only(right: getWidth(12)!),
                                  child: GestureDetector(
                                    onTap: () {
                                      logic.selctedImageIndex = index;
                                      logic.update();
                                    },
                                    child: Container(
                                      decoration: BoxDecoration(
                                        border: logic.selctedImageIndex == index
                                            ? Border.all(
                                                width: getWidth(1)!,
                                                color: primaryColor,
                                              )
                                            : null,
                                        borderRadius:
                                            BorderRadius.circular(getWidth(9)!),
                                      ),
                                      child: ClipRRect(
                                        borderRadius:
                                            BorderRadius.circular(getWidth(8)!),
                                        child: CacheImage(
                                          path: media['mediaPath'],
                                          fit: BoxFit.cover,
                                          height: getWidth(100),
                                          width: getWidth(80),
                                        ),
                                      ),
                                    ),
                                  ),
                                );
                              },
                            ),
                          ),
                        ],
                      ),
                    ),
                    Padding(
                      padding: EdgeInsets.all(getWidth(20)!),
                      child: Column(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          Row(
                            mainAxisAlignment: MainAxisAlignment.spaceBetween,
                            children: [
                              Row(
                                children: [
                                  AppText.paragraph(
                                    currency(double.parse(
                                        logic.productRes!['price'])),
                                    fontWeight: FontWeight.w500,
                                    color: disPriceColor,
                                    decorationColor: disPriceColor,
                                    getfontSize: 18,
                                    decoration: TextDecoration.lineThrough,
                                  ),
                                  Gap(getWidth(12)!),
                                  AppText.paragraph(
                                    currency(double.parse(
                                        logic.productRes!['discountprice'])),
                                    fontWeight: FontWeight.w600,
                                    color: primaryColor,
                                    getfontSize: 22,
                                  ),
                                ],
                              ),
                              Row(
                                children: [
                                  AppText.paragraph(
                                      "${logic.productRes!['formattedSoldby']} Sold",
                                      fontWeight: FontWeight.w400,
                                      getfontSize: 20),
                                  Gap(getWidth(8)!),
                                  GestureDetector(
                                    onTap: () {
                                      if (logic.productRes!['wishlist']
                                              .toString() ==
                                          "1") {
                                        logic.deleteToWishlistAPI();
                                      } else {
                                        logic.addToWishlistAPI();
                                      }
                                    },
                                    child: Icon(
                                      logic.productRes!['wishlist']
                                                  .toString() ==
                                              "1"
                                          ? Icons.favorite
                                          : Icons.favorite_border,
                                      color: primaryColor,
                                    ),
                                  ),
                                ],
                              )
                            ],
                          ),
                          Gap(getWidth(6)!),
                          AppText.paragraph(
                            logic.productRes!['brands'],
                            fontWeight: FontWeight.w500,
                            color: const Color(0xFF8F8F8F),
                            getfontSize: 15,
                          ),
                          Gap(getWidth(8)!),
                          AppText.paragraph(
                            logic.productRes!['name'],
                            fontWeight: FontWeight.w400,
                            color: blackColor,
                            getfontSize: 20,
                          ),
                          Gap(getWidth(12)!),
                          DottedBorder(
                            options: CustomPathDottedBorderOptions(
                              color: const Color(0xFFA3A3A3),
                              strokeWidth: getWidth(1)!,
                              dashPattern: [10, 5],
                              customPath: (size) => Path()
                                ..moveTo(0, size.height)
                                ..relativeLineTo(size.width, 0),
                            ),
                            child: Container(width: width()),
                          ),
                          Gap(getWidth(16)!),
                          AppText.paragraph(
                            "Description:",
                            fontWeight: FontWeight.w500,
                            color: blackColor,
                            getfontSize: 18,
                          ),
                          Gap(getWidth(8)!),
                          ReadMoreText(
                            logic.productRes!['description'],
                            style: TextStyle(
                              fontSize: getFontSize(16),
                              fontWeight: FontWeight.w400,
                              color: disPriceColor,
                            ),
                            trimCollapsedText: "See more",
                            trimExpandedText: "See less",
                            colorClickableText: primaryColor,
                          ),
                          Gap(getWidth(16)!),
                          AppText.paragraph(
                            "Product Reviews",
                            fontWeight: FontWeight.w500,
                            color: blackColor,
                            getfontSize: 18,
                          ),
                          Gap(getWidth(16)!),
                          _ratingWidget(context, logic),
                          if (logic.productRes!['reviews'].isNotEmpty) ...[
                            Gap(getWidth(16)!),
                            AppText.paragraph(
                              "Review Lists",
                              fontWeight: FontWeight.w500,
                              color: blackColor,
                              getfontSize: 18,
                            ),
                            Gap(getWidth(16)!),
                            _productReviews(logic)
                          ],
                          Gap(getWidth(16)!),
                          if (logic
                              .productRes!['related_products'].isNotEmpty) ...[
                            Gap(getWidth(16)!),
                            Row(
                              mainAxisAlignment: MainAxisAlignment.spaceBetween,
                              children: [
                                AppText.paragraph(
                                  "Related Product",
                                  fontWeight: FontWeight.w500,
                                  color: blackColor,
                                  getfontSize: 18,
                                ),
                                if (logic.productRes?['see_all']['status'] ==
                                    "1")
                                  AppText.paragraph(
                                    "View All",
                                    fontWeight: FontWeight.w400,
                                    color: blackColor,
                                    getfontSize: 15,
                                    isUnderline: true,
                                    decorationColor: blackColor,
                                  ),
                              ],
                            ),
                            _relatedProducts(logic),
                            Gap(getWidth(16)!),
                          ],
                        ],
                      ),
                    ),
                    Gap(getWidth(60)!)
                  ],
                ),
              ),
        bottomNavigationBar: logic.isLoading || logic.isWishlistLoad
            ? null
            : Padding(
                padding: EdgeInsets.symmetric(vertical: getWidth(20)!),
                child: Column(
                  mainAxisSize: MainAxisSize.min,
                  children: [
                    Row(
                      children: [
                        Expanded(
                          child: Bouncing(
                            onTap: () {
                              Get.toNamed(Routes.CHAT);
                            },
                            child: Container(
                              height: getWidth(70),
                              margin: EdgeInsets.only(right: getWidth(4)!),
                              color: const Color(0xFF07B463),
                              child: Column(
                                mainAxisAlignment: MainAxisAlignment.center,
                                children: [
                                  Image.asset(
                                    ImagePath.chat,
                                    height: getWidth(24),
                                    width: getWidth(24),
                                    color: whiteColor,
                                  ),
                                  AppText.paragraph(
                                    "Chat with us",
                                    fontWeight: FontWeight.w400,
                                    getfontSize: 18,
                                    color: whiteColor,
                                  )
                                ],
                              ),
                            ),
                          ),
                        ),
                        Expanded(
                          child: Bouncing(
                            onTap: () {
                              Constants.productBuyId =
                                  logic.arg['id'].toString();
                              showModalBottomSheet(
                                context: Get.context!,
                                isScrollControlled: true,
                                enableDrag: false,
                                isDismissible: false,
                                builder: (context) => Padding(
                                  padding: EdgeInsets.only(
                                      bottom: MediaQuery.of(context)
                                          .viewInsets
                                          .bottom),
                                  child: const SingleChildScrollView(
                                    child: Column(
                                      crossAxisAlignment:
                                          CrossAxisAlignment.start,
                                      mainAxisSize: MainAxisSize.min,
                                      children: <Widget>[BuyNowView()],
                                    ),
                                  ),
                                ),
                              );
                            },
                            child: Container(
                              margin: EdgeInsets.only(right: getWidth(4)!),
                              height: getWidth(70),
                              color: const Color(0xFF07B463),
                              child: Column(
                                mainAxisAlignment: MainAxisAlignment.center,
                                children: [
                                  Image.asset(
                                    ImagePath.cart,
                                    height: getWidth(24),
                                    width: getWidth(24),
                                    color: whiteColor,
                                  ),
                                  AppText.paragraph(
                                    "Add to Cart",
                                    fontWeight: FontWeight.w400,
                                    getfontSize: 18,
                                    color: whiteColor,
                                  )
                                ],
                              ),
                            ),
                          ),
                        ),
                        Expanded(
                          child: Bouncing(
                            onTap: () {
                              Constants.productBuyId =
                                  logic.arg['id'].toString();
                              showModalBottomSheet(
                                context: Get.context!,
                                isScrollControlled: true,
                                enableDrag: false,
                                isDismissible: false,
                                builder: (context) => Padding(
                                  padding: EdgeInsets.only(
                                      bottom: MediaQuery.of(context)
                                          .viewInsets
                                          .bottom),
                                  child: const SingleChildScrollView(
                                    child: Column(
                                      crossAxisAlignment:
                                          CrossAxisAlignment.start,
                                      mainAxisSize: MainAxisSize.min,
                                      children: <Widget>[BuyNowView()],
                                    ),
                                  ),
                                ),
                              );
                            },
                            child: Container(
                              alignment: Alignment.center,
                              color: primaryColor,
                              height: getWidth(70),
                              child: AppText.paragraph(
                                "Buy Now",
                                fontWeight: FontWeight.w400,
                                getfontSize: 18,
                                color: whiteColor,
                              ),
                            ),
                          ),
                        ),
                      ],
                    ),
                    Gap(getWidth(10)!),
                    if (Platform.isAndroid) Gap(getWidth(40)!),
                  ],
                ),
              ),
      );
    });
  }

  Widget _ratingWidget(BuildContext context, ProductController logic) {
    return DottedBorder(
      options: RoundedRectDottedBorderOptions(
        dashPattern: [10, 5],
        strokeWidth: getWidth(1)!,
        radius: Radius.circular(getWidth(16)!),
        color: const Color(0xFFA3A3A3),
        padding: EdgeInsets.all(getWidth(16)!),
      ),
      child: Padding(
        padding: EdgeInsets.symmetric(
            horizontal: getWidth(16)!, vertical: getWidth(8)!),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Row(
              children: [
                Stack(
                  alignment: Alignment.center,
                  children: [
                    SizedBox(
                      width: 60,
                      height: 60,
                      child: CircularProgressIndicator(
                        value: logic.productRes!['average_rating'] / 5,
                        strokeWidth: 6,
                        valueColor: AlwaysStoppedAnimation(orangeColor),
                        backgroundColor: Colors.grey.shade200,
                      ),
                    ),
                    AppText.heading2(
                      logic.productRes!['average_rating'].toStringAsFixed(1),
                      fontWeight: FontWeight.w800,
                    ),
                  ],
                ),
                Gap(getWidth(20)!),
                Column(
                  children: [
                    Row(
                      children: List.generate(5, (index) {
                        return Padding(
                          padding: EdgeInsets.only(right: getWidth(4)!),
                          child: Image.asset("assets/icons/star.png",
                              height: getWidth(20), width: getWidth(20)),
                        );
                      }),
                    ),
                    Gap(getWidth(5)!),
                    AppText.paragraph(
                      'from ${logic.productRes!['total_reviews']} reviews',
                      fontWeight: FontWeight.w600,
                      color: blackColor,
                    ),
                  ],
                ),
              ],
            ),
            Gap(getWidth(20)!),
            Padding(
              padding: EdgeInsets.only(right: getWidth(50)!),
              child: Column(
                children: (logic.productRes!['rating_counts'] as List)
                    .map<Widget>((rating) {
                  double percentage = (rating['percentage'] ?? 0) / 100;
                  return Padding(
                    padding: const EdgeInsets.symmetric(vertical: 4),
                    child: Row(
                      children: [
                        Text(rating['name'].toString()),
                        Gap(getWidth(5)!),
                        Image.asset("assets/icons/star.png",
                            height: getWidth(20), width: getWidth(20)),
                        Gap(getWidth(10)!),
                        Expanded(
                          child: LinearProgressIndicator(
                            value: percentage,
                            backgroundColor: Colors.grey.shade200,
                            minHeight: getWidth(8),
                            valueColor:
                                const AlwaysStoppedAnimation(Colors.black87),
                          ),
                        ),
                        Gap(getWidth(10)!),
                        Text(rating['count'].toString()),
                      ],
                    ),
                  );
                }).toList(),
              ),
            ),
          ],
        ),
      ),
    );
  }

  _productReviews(ProductController logic) {
    return Column(
      children: List.generate(
        logic.productRes!['reviews'].length,
        (index) {
          var review = logic.productRes!['reviews'][index];
          int rating = review['rating']?.toInt() ?? 0; // default to 0 if null
          return Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Row(
                children: List.generate(5, (starIndex) {
                  return Padding(
                    padding: EdgeInsets.only(right: getWidth(4)!),
                    child: Image.asset(
                      "assets/icons/star_line.png",
                      height: getWidth(28),
                      width: getWidth(28),
                      color: starIndex < rating ? orangeColor : null,
                    ),
                  );

                  // starIndex < rating ? Icons.star : Icons.star_border,
                }),
              ),
              Padding(
                padding: EdgeInsets.only(left: getWidth(6)!),
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Gap(getWidth(16)!),
                    AppText.paragraph(
                      review['description'],
                      fontWeight: FontWeight.w400,
                      getfontSize: 16,
                    ),
                    Gap(getWidth(8)!),
                    AppText.paragraph(
                      review['date'],
                      color: const Color(0xFF818B9C),
                      fontWeight: FontWeight.w400,
                      getfontSize: 14,
                    ),
                    Gap(getWidth(16)!),
                    Row(
                      children: [
                        profileImageCircle(
                          imageUrl: review['image'],
                          name: review['name'],
                          circleSize: 36,
                        ),
                        Gap(getWidth(8)!),
                        Expanded(
                          child: AppText.paragraph(
                            review['name'],
                            fontWeight: FontWeight.w600,
                            getfontSize: 18,
                          ),
                        )
                      ],
                    ),
                  ],
                ),
              ),
              if (index != logic.productRes!['reviews'].length - 1) ...[
                Gap(getWidth(8)!),
                appDivider(),
                Gap(getWidth(8)!)
              ],
            ],
          );
        },
      ),
    );
  }

  _relatedProducts(ProductController logic) {
    return Column(
      children: [
        Wrap(
          spacing: 16,
          runSpacing: 16,
          children: List.generate(
            logic.productRes!['related_products'].length,
            (index) {
              var best = logic.productRes!['related_products'][index];
              return GestureDetector(
                onTap: () {
                  Get.delete<ProductController>();
                  Get.toNamed(
                    Routes.PRODUCT,
                    arguments: {"id": best['id'], "name": best['name']},
                    preventDuplicates: false,
                  );
                },
                child: SizedBox(
                  width: getWidth(200),
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    mainAxisSize: MainAxisSize.min,
                    children: [
                      Container(
                        height: getWidth(230),
                        width: getWidth(200),
                        padding: EdgeInsets.all(getWidth(6)!),
                        decoration: BoxDecoration(
                          color: whiteColor,
                          borderRadius: BorderRadius.circular(getWidth(16)!),
                        ),
                        child: ClipRRect(
                          borderRadius: BorderRadius.circular(getWidth(12)!),
                          child: CacheImage(
                            path: best['image'],
                            fit: BoxFit.cover,
                            alignment: Alignment.topCenter,
                          ),
                        ),
                      ),
                      Gap(getWidth(10)!),
                      AppText.heading3(
                        best['brands'] ?? "",
                        getfontSize: 18,
                        maxLines: 1,
                        fontWeight: FontWeight.w600,
                        overflow: TextOverflow.ellipsis,
                      ),
                      Gap(getWidth(6)!),
                      Row(
                        children: [
                          AppText.paragraph(
                            currency(double.parse(logic.productRes!['price'])),
                            fontWeight: FontWeight.w700,
                            color: hintColor,
                            getfontSize: 15,
                            decoration: TextDecoration.lineThrough,
                          ),
                          Gap(getWidth(12)!),
                          AppText.paragraph(
                            currency(double.parse(
                                logic.productRes!['discountprice'])),
                            fontWeight: FontWeight.w700,
                            color: primaryColor,
                            getfontSize: 17,
                          ),
                        ],
                      ),
                      Gap(getWidth(6)!),
                      AppText.heading3(
                        best['name'],
                        getfontSize: 17,
                        color: const Color(0xFF7A7A7A),
                        maxLines: 3,
                        overflow: TextOverflow.ellipsis,
                      ),
                      Gap(getWidth(10)!),
                      Row(
                        children: [
                          Image.asset(
                            "assets/icons/star.png",
                            height: getWidth(20),
                            width: getWidth(20),
                          ),
                          AppText.paragraph(
                            " ${best['average_rating']}",
                          ),
                          Gap(getWidth(20)!),
                          AppText.paragraph(
                            "${best['formattedSoldby']} Sold",
                          ),
                        ],
                      ),
                    ],
                  ),
                ),
              );
            },
          ),
        )
      ],
    );
  }

  sizeContainer({
    bool isSelected = false,
    required String size,
    required void Function() onTap,
  }) {
    return GestureDetector(
      onTap: onTap,
      child: Container(
        alignment: Alignment.center,
        height: getWidth(60),
        width: getWidth(60),
        margin: EdgeInsets.only(right: getWidth(12)!),
        decoration: BoxDecoration(
            borderRadius: BorderRadius.circular(getWidth(8)!),
            color: isSelected ? greenColor : greyColor),
        child: AppText.heading1(size,
            color: isSelected ? whiteColor : blackColor,
            getfontSize: 20,
            fontWeight: FontWeight.w700),
      ),
    );
  }

  _imageWidget(ProductController logic) {
    return Stack(
      clipBehavior: Clip.none,
      children: [
        CarouselSlider.builder(
          itemCount: logic.productRes!['media'].length,
          itemBuilder: (context, index, realIndex) {
            var media = logic.productRes!['media'][index];
            return GestureDetector(
              onTap: () {
                Get.to(
                  () => const ItemImagesViewer(),
                  arguments: media['mediaPath'],
                );
              },
              child: CacheImage(
                path: media['mediaPath'],
                fit: BoxFit.cover,
              ),
            );
          },
          carouselController: logic.carouselController,
          options: CarouselOptions(
            aspectRatio: 1,
            viewportFraction: 1,
            onPageChanged: (index, reason) {
              logic.changeIndicator(index);
            },
            initialPage: 0,
          ),
        ),
        Positioned(
          bottom: getWidth(10),
          right: 0,
          left: 0,
          child: Align(
            alignment: Alignment.center,
            child: Container(
              padding: const EdgeInsets.symmetric(
                horizontal: 6,
                vertical: 4,
              ),
              decoration: BoxDecoration(
                  color: blackColor.withOpacity(0.5),
                  borderRadius: BorderRadius.circular(50)),
              child: AnimatedSmoothIndicator(
                activeIndex: logic.activeCarouselIndex,
                count: logic.productRes!['media'].length,
                effect: SlideEffect(
                  activeDotColor: primaryColor,
                  dotHeight: getWidth(10)!,
                  dotWidth: getWidth(10)!,
                ),
              ),
            ),
          ),
        ),
      ],
    );
  }
}
