import 'package:carousel_slider/carousel_slider.dart';
import 'package:jazz_smart_pay/app/utils/app_const_colors.dart';
import '../../../../exports.dart';
import '../../../custom_widget/app_text.dart';
import '../../../custom_widget/cached_image.dart';
import '../../../dio_api/dio_api.dart';
import '../../../utils/constant_vars.dart';
import '../../../utils/currency_util.dart';
import '../../../utils/index.dart';

class ProductController extends GetxController {
  final carouselController = CarouselSliderController();

  int selctedImageIndex = 0;

  late Map arg;
  @override
  void onInit() {
    arg = Get.arguments;
    getProductAPI();
    super.onInit();
  }

  RxBool isLoadingObs = false.obs;
  bool isLoading = false;
  Map<String, dynamic>? productRes;
  getProductAPI() {
    isLoading = true;
    isLoadingObs.value = true;
    update();
    API().post("/product-detail-data", data: {"proid": arg['id']}).then(
        (value) async {
      Map<String, dynamic>? res = value.data;
      if (res != null) {
        if (res['status'] == 1) {
          productRes = res['data'];
          isLoading = false;
          isLoadingObs.value = false;
          update();
        } else {
          Constants.errorDialog(message: res['message']);
          isLoading = false;
          isLoadingObs.value = false;
          update();
        }
      } else {
        Constants.errorDialog();
        isLoading = false;
        isLoadingObs.value = false;
        update();
      }
    });
  }

  List<dynamic> attributes = [];
  getProductAttributeAPI(String attributeId) {
    isLoading = true;
    isLoadingObs.value = true;
    update();
    API().post("/productalldata", data: {
      "pid": arg['id'],
      "sub_attribute_id": attributeId,
    }).then((value) async {
      Map<String, dynamic>? res = value.data;
      if (res != null) {
        if (res['status'] == 1) {
          attributes.clear();
          attributes.addAll(res['data']['attributes']);
          if (attributes.isNotEmpty) {
            productRes!['data']['images'] = attributes[0]['attribute_images'];
            productRes!['data']['price'] = attributes[0]['attribute_price'];
          }
        } else {
          Constants.errorDialog(message: res['message']);
        }
      } else {
        Constants.errorDialog();
      }

      isLoading = false;
      isLoadingObs.value = false;
      update();
    });
  }

  bool isWishlistLoad = false;
  addToWishlistAPI() {
    isWishlistLoad = true;
    update();
    API().post("/addtowishlist", data: {"pid": arg['id']}).then((value) async {
      Map<String, dynamic>? res = value.data;
      if (res != null) {
        if (res['status'] == 1) {
          productRes!['wishlist'] = 1;
        } else {
          Constants.errorDialog(message: res['message']);
        }
      } else {
        Constants.errorDialog();
      }
      isWishlistLoad = false;
      update();
    });
  }

  deleteToWishlistAPI() {
    isWishlistLoad = true;
    update();
    API().post("/delwishlis", data: {"id": arg['id']}).then((value) async {
      Map<String, dynamic>? res = value.data;
      if (res != null) {
        if (res['status'] == 1) {
          productRes!['wishlist'] = 0;
        } else {
          Constants.errorDialog(message: res['message']);
        }
      } else {
        Constants.errorDialog();
      }
      isWishlistLoad = false;
      update();
    });
  }

  int activeCarouselIndex = 0;

  changeIndicator(int index) {
    activeCarouselIndex = index;
    update();
  }

  int selectedAttribute = 0;

  changeSelectedAttribute(int index) {
    if (selectedAttribute == index) {
    } else {
      selectedAttribute = index;
      productRes!['data']['images'] =
          attributes[selectedAttribute]['attribute_images'];
      productRes!['data']['price'] =
          attributes[selectedAttribute]['attribute_price'];
    }

    update();
  }

  int selectedSize = 0;

  changeSize(int index) {
    if (selectedSize != index) {
      getProductAttributeAPI(
          productRes!['data']['sizes'][index]['sub_attribute_id'].toString());
    }
    selectedSize = index;

    update();
  }

  List<String> sizeList = [
    "XS",
    "S",
    "M",
    "L",
    "XL",
  ];

  List<Specification> specification = [
    Specification(key: "Sleeve Length", value: "Long Sleeves"),
    Specification(key: "Type", value: "Denim Jacket"),
    Specification(key: "Print or Pattern Type", value: "Washed"),
    Specification(key: "Collor", value: "Spread collor"),
    Specification(key: "Length", value: "Regular"),
    Specification(key: "Closure", value: "Button"),
    Specification(key: "Lining Fabric", value: "Unlined"),
    Specification(key: "Number of Pockets", value: "4"),
    Specification(key: "Hemline", value: "Straight"),
    Specification(key: "Occasion", value: "Casual"),
  ];

  // Future<T?> bottomSheetWithHandle<T>(BuildContext context,
  //     {Widget? body,
  //     bool isScrollControlled = false,
  //     bool isDismissible = true,
  //     bool useSafeArea = false,
  //     bool showHandleBar = true,
  //     bool enableDrag = true,
  //     Color? background}) {
  //   return showModalBottomSheet<T>(
  //     context: context,
  //     elevation: 10,
  //     isScrollControlled: isScrollControlled,
  //     backgroundColor: redColor,
  //     useSafeArea: useSafeArea,
  //     isDismissible: isDismissible,
  //     enableDrag: enableDrag,
  //     builder: (context) => AnimatedContainer(
  //       decoration: BoxDecoration(
  //         color: background ?? bgColor,
  //         borderRadius: const BorderRadius.vertical(
  //           top: Radius.circular(24),
  //         ),
  //         boxShadow: const [
  //           BoxShadow(
  //             blurRadius: 8,
  //             color: Colors.black26,
  //           )
  //         ],
  //       ),
  //       duration: 300.milliseconds,
  //       child: Column(
  //         crossAxisAlignment: CrossAxisAlignment.start,
  //         mainAxisSize: MainAxisSize.min,
  //         children: [
  //           if (showHandleBar)
  //             Center(
  //               child: Container(
  //                 height: 4,
  //                 width: 40,
  //                 margin: const EdgeInsets.symmetric(vertical: 24),
  //                 decoration: BoxDecoration(
  //                   color: const Color(0xFFACB3BC),
  //                   borderRadius: BorderRadius.circular(40),
  //                 ),
  //               ),
  //             ),
  //           if (body != null) body,
  //         ],
  //       ),
  //     ),
  //   );
  // }

  // ******************************************************************* //
  // ************************ Add To cart logic ************************ //
  // ******************************************************************* //

  bool toCart = false;
  Map<String, dynamic>? productCartRes;
  selectToCartAPI() {
    toCart = true;
    update();
    API().post("/addclickcart", data: {"proid": arg['id']}).then((value) async {
      Map<String, dynamic>? res = value.data;
      if (res != null) {
        if (res['status'] == 1) {
          productCartRes = res['data'][0];
          onBottomHSheetTap();
        } else {
          Constants.errorDialog(message: res['message']);
        }
      } else {
        Constants.errorDialog();
      }
      toCart = false;
      update();
    });
  }

  // ******************************************************************* //
  // ************************ Add To cart logic ************************ //
  // ******************************************************************* //

  bool buyNow = false;
  Map<String, dynamic>? buyNowRes;
  buyNowAPI() {
    buyNow = true;
    update();
    API().post("/product-buynow-data", data: {"proid": arg['id']}).then(
        (value) async {
      Map<String, dynamic>? res = value.data;
      if (res != null) {
        if (res['status'] == 1) {
          buyNowRes = res['data'];

          bottomSheetWithHandle(Get.context!);
        } else {
          Constants.errorDialog(message: res['message']);
        }
      } else {
        Constants.errorDialog();
      }
      buyNow = false;
      update();
    });
  }

  Future<T?> bottomSheetWithHandle<T>(
    BuildContext context, {
    Widget? body,
    bool isScrollControlled = false,
    bool isDismissible = true,
    bool useSafeArea = false,
    bool showHandleBar = true,
    bool enableDrag = true,
    Color? background,
  }) {
    return showModalBottomSheet<T>(
      context: context,
      elevation: 10,
      isScrollControlled: isScrollControlled,
      backgroundColor: redColor,
      useSafeArea: useSafeArea,
      isDismissible: isDismissible,
      enableDrag: enableDrag,
      builder: (context) {
        return GetBuilder<ProductController>(
          builder: (logic) {
            return AnimatedContainer(
              duration: 300.milliseconds,
              decoration: BoxDecoration(
                color: background ?? bgColor,
                borderRadius:
                    const BorderRadius.vertical(top: Radius.circular(24)),
                boxShadow: const [
                  BoxShadow(
                    blurRadius: 8,
                    color: Colors.black26,
                  )
                ],
              ),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                mainAxisSize: MainAxisSize.min,
                children: [
                  if (showHandleBar)
                    Center(
                      child: Container(
                        height: 4,
                        width: 40,
                        margin: const EdgeInsets.symmetric(vertical: 24),
                        decoration: BoxDecoration(
                          color: const Color(0xFFACB3BC),
                          borderRadius: BorderRadius.circular(40),
                        ),
                      ),
                    ),
                  Padding(
                    padding: EdgeInsets.symmetric(horizontal: getWidth(20)!),
                    child: Column(
                      children: [],
                    ),
                  ),
                ],
              ),
            );
          },
        );
      },
    );
  }

  Widget selectionBottomSheet(ProductController logic) {
    return SingleChildScrollView(
      padding: EdgeInsets.all(getWidth(20)!),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        mainAxisSize: MainAxisSize.min,
        children: <Widget>[
          Column(
            children: [
              Row(
                children: [
                  ClipRRect(
                    borderRadius: BorderRadius.circular(getWidth(12)!),
                    child: CacheImage(
                      path: productCartRes!['productattributeimage'],
                      height: getWidth(160),
                      width: getWidth(120),
                      fit: BoxFit.cover,
                    ),
                  ),
                  Gap(getWidth(20)!),
                  Expanded(
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        AppText.paragraph(productCartRes!['name'],
                            fontWeight: FontWeight.w600, getfontSize: 20),
                        Gap(getWidth(8)!),
                        Row(
                          children: [
                            AppText.paragraph(
                              currency(double.parse(productCartRes!['price'])),
                              fontWeight: FontWeight.w700,
                              color: hintColor,
                              getfontSize: 16,
                              decoration: TextDecoration.lineThrough,
                            ),
                            Gap(getWidth(12)!),
                            AppText.paragraph(
                              currency(double.parse(
                                  productCartRes!['discountprice'])),
                              fontWeight: FontWeight.w700,
                              color: primaryColor,
                              getfontSize: 18,
                            ),
                          ],
                        ),
                        Gap(getWidth(8)!),
                        AppText.paragraph("Stock Qty :  1200",
                            fontWeight: FontWeight.w500, getfontSize: 16),
                        Gap(getWidth(20)!),
                      ],
                    ),
                  )
                ],
              ),
              Gap(getWidth(20)!),
            ],
          ),
        ],
      ),
    );
  }

  onBottomHSheetTap() {
    showModalBottomSheet(
      context: Get.context!,
      isScrollControlled: true,
      enableDrag: true,
      isDismissible: true,
      builder: (context) => Padding(
        padding: EdgeInsets.only(
          bottom: MediaQuery.of(context).viewInsets.bottom,
        ),
        child: GetBuilder<ProductController>(
          builder: (logic) {
            return selectionBottomSheet(logic);
          },
        ),
      ),
    );
  }
}

class Specification {
  String? key;
  String? value;

  Specification({this.key, this.value});
}
