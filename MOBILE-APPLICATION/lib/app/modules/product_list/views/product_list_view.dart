import 'package:flutter/material.dart';
import 'package:jazz_smart_pay/app/custom_widget/app_text.dart';
import 'package:jazz_smart_pay/app/custom_widget/cached_image.dart';
import 'package:jazz_smart_pay/app/custom_widget/fill_container.dart';
import 'package:jazz_smart_pay/app/custom_widget/loading_circular_component.dart';
import 'package:jazz_smart_pay/app/custom_widget/my_app_bar.dart';
import 'package:jazz_smart_pay/app/utils/app_const_colors.dart';
import 'package:jazz_smart_pay/app/utils/app_size.dart';
import 'package:gap/gap.dart';
import 'package:get/get.dart';
import 'package:jazz_smart_pay/app/utils/currency_util.dart';
import '../controllers/product_list_controller.dart';

class ProductListView extends GetView<ProductListController> {
  const ProductListView({super.key});
  @override
  Widget build(BuildContext context) {
    return GetBuilder<ProductListController>(builder: (logic) {
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
                      ListView.separated(
                        padding: EdgeInsets.all(getWidth(16)!),
                        shrinkWrap: true,
                        physics: const NeverScrollableScrollPhysics(),
                        separatorBuilder: (context, index) {
                          return Gap(getWidth(20)!);
                        },
                        itemCount: logic.listRes!['data'].length,
                        itemBuilder: (context, index) {
                          var product = logic.listRes!['data'][index];
                          return _productWidget(logic, product);
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

  _productWidget(ProductListController logic, Map product) {
    return FillContainer(
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          AppText.paragraph(
            "Product Title : ${product['title'].toString().toUpperCase()}",
            color: blackColor,
            getfontSize: 20,
            fontWeight: FontWeight.w500,
            maxLines: 1,
            overflow: TextOverflow.ellipsis,
          ),
          Gap(getWidth(6)!),
          AppText.paragraph(
            "HSNCODE : ${product['hsncode']}",
            fontWeight: FontWeight.w500,
            getfontSize: 16,
          ),
          AppText.paragraph(
            "PRODUCT WEIGHT : ${product['product_weight']}",
            fontWeight: FontWeight.w500,
            getfontSize: 16,
          ),
          AppText.paragraph(
            "COUNTRY OF ORIGIN : ${product['countryoforgin'].toString().toUpperCase()}",
            fontWeight: FontWeight.w500,
            getfontSize: 16,
          ),
          AppText.paragraph(
            "TAX CODE : ${product['taxcode']}",
            fontWeight: FontWeight.w500,
            getfontSize: 16,
          ),
          AppText.paragraph(
            "CATEGOYY : ${product['main_category']['title'].toString().toUpperCase()}",
            fontWeight: FontWeight.w500,
            getfontSize: 16,
          ),
          AppText.paragraph(
            "SUB-CATEGOYY : ${product['sub_category']['title'].toString().toUpperCase()}",
            fontWeight: FontWeight.w500,
            getfontSize: 16,
          ),
          AppText.paragraph(
            "SUB-SUB-CATEGOYY : ${product['subsub_category']['title'].toString().toUpperCase()}",
            fontWeight: FontWeight.w500,
            getfontSize: 16,
          ),
          Gap(getWidth(12)!),
          Row(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              ClipRRect(
                borderRadius: BorderRadius.circular(getWidth(12)!),
                child: CacheImage(
                  // path: product['image'],
                  path:
                      "https://assets.myntassets.com/w_412,q_60,dpr_2,fl_progressive/assets/images/13273394/2023/9/22/6517c071-3f49-4ec2-a0ee-65e0b5e2907f1695351807671-Campus-Men-Black-Mesh-Running-Shoes-5551695351807538-7.jpg",
                  height: getWidth(120),
                  width: getWidth(120),
                  fit: BoxFit.cover,
                ),
              ),
              Gap(getWidth(16)!),
              Expanded(
                  child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Gap(getWidth(6)!),
                  Row(
                    children: [
                      AppText.paragraph(
                        currency(double.parse(product['prices'])),
                        fontWeight: FontWeight.w500,
                        color: disPriceColor,
                        decorationColor: disPriceColor,
                        getfontSize: 18,
                        decoration: TextDecoration.lineThrough,
                      ),
                      Gap(getWidth(12)!),
                      AppText.paragraph(
                        currency(double.parse(product['discount'])),
                        fontWeight: FontWeight.w600,
                        color: primaryColor,
                        getfontSize: 22,
                      ),
                    ],
                  ),
                  AppText.paragraph(
                    "BRAND : ${product['brand'].toString().toUpperCase()}",
                    fontWeight: FontWeight.w500,
                    getfontSize: 16,
                    maxLines: 1,
                    overflow: TextOverflow.ellipsis,
                  ),
                  AppText.paragraph(
                    "STOCK QUANTITY : ${product['stockfirst']}",
                    fontWeight: FontWeight.w500,
                    getfontSize: 16,
                  ),
                  AppText.paragraph(
                    "SKU : ${product['sku']}",
                    fontWeight: FontWeight.w500,
                    getfontSize: 16,
                  ),
                ],
              )),
            ],
          ),
          Gap(getWidth(12)!),
          AppText.paragraph("Description : ${product['description']}"),
        ],
      ),
    );
  }
}
