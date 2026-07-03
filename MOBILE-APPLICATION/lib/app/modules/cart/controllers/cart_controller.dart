import 'package:get/get.dart';
import '../../../../exports.dart';
import '../../../custom_widget/app_divider.dart';
import '../../../custom_widget/app_text.dart';
import '../../../custom_widget/bouncing_button.dart';
import '../../../custom_widget/dialog_loader.dart';
import '../../../custom_widget/primary_button.dart';
import '../../../dio_api/dio_api.dart';
import '../../../utils/app_const_colors.dart';
import '../../../utils/app_size.dart';
import '../../../utils/constant_vars.dart';
import '../../checkout/components/order_success.dart';

class CartController extends GetxController {
  @override
  void onInit() {
    cartAPI();
    super.onInit();
  }

  bool payByWallet = true;
  String totalAmount = "0";
  bool isLoading = false;
  Map<String, dynamic>? cartRes;
  cartAPI() {
    isLoading = true;
    update();
    API().get("/pre-order-cart-details").then((value) async {
      Map<String, dynamic>? res = value.data;
      if (res != null) {
        if (res['status'] ?? false) {
          cartRes = res;
          totalAmount = res['total_amount'];
          payByWallet = res['pay_by_wallet'] == "1";
        } else {
          Constants.errorDialog(message: res['message']);
        }
      } else {
        Constants.errorDialog();
      }
      isLoading = false;
      update();
    });
  }

  preOrderAPI({required dynamic data}) {
    LoadingBuilder.showLoadingIndicator();
    API().post(
      "/pre-order",
      data: {
        "student_id": data['student_id'].toString(),
        "dish_id": data['dish_id'].toString(),
        "date": data['date'],
        "qty": "1",
      },
    ).then((value) async {
      Map<String, dynamic>? res = value.data;
      if (res != null) {
        if (res['status'] ?? false) {
          totalAmount = res['total_amount'];
          payByWallet = res['pay_by_wallet'] == "1";
          data['qty'] = data['qty'] + 1;
          LoadingBuilder.hideOpenDialog();
        } else {
          LoadingBuilder.hideOpenDialog();
          Constants.errorDialog(message: res['message']);
        }
      } else {
        LoadingBuilder.hideOpenDialog();
        Constants.errorDialog();
      }
      update();
    });
  }

  updateAddonsAPI({required dynamic data}) {
    LoadingBuilder.showLoadingIndicator();
    API().post(
      "/addons-updates",
      data: {
        "student_id": data['student_id'].toString(),
        "dish_id": data['dish_id'].toString(),
        "date": data['date'],
        "addons": selectedItemsString,
      },
    ).then((value) async {
      Map<String, dynamic>? res = value.data;
      if (res != null) {
        if (res['status'] ?? false) {
          totalAmount = res['total_amount'];
          payByWallet = res['pay_by_wallet'] == "1";
          data['selected_addons'] = selectedItemsString;
          selectedItemsString = "";
          LoadingBuilder.hideOpenDialog();
        } else {
          LoadingBuilder.hideOpenDialog();
          Constants.errorDialog(message: res['message']);
        }
      } else {
        LoadingBuilder.hideOpenDialog();
        Constants.errorDialog();
      }
      update();
    });
  }

  preOrderDecreaseAPI({required int index}) {
    var item = cartRes!['data'][index];
    LoadingBuilder.showLoadingIndicator();
    API().post(
      "/dish-decrease",
      data: {
        "student_id": item['student_id'].toString(),
        "dish_id": item['dish_id'].toString(),
        "date": item['date'],
      },
    ).then((value) async {
      Map<String, dynamic>? res = value.data;
      if (res != null) {
        if (res['status'] ?? false) {
          totalAmount = res['total_amount'];
          payByWallet = res['pay_by_wallet'] == "1";
          item['qty'] = item['qty'] - 1;
          if (item['qty'] == 0) {
            cartRes!['data'].removeAt(index);
          }
          update();
          LoadingBuilder.hideOpenDialog();
        } else {
          LoadingBuilder.hideOpenDialog();
          Constants.errorDialog(message: res['message']);
        }
      } else {
        LoadingBuilder.hideOpenDialog();
        Constants.errorDialog();
      }
      update();
    });
  }

  String convertListToCommaString(List<String> items) {
    return items.join(', ');
  }

  checkoutAPI() {
    isLoading = true;
    update();
    API().post(
      "/checkout",
      data: {
        "total_amount": totalAmount,
        "payment_type": "wallet",
      },
    ).then((value) async {
      Map<String, dynamic>? res = value.data;
      if (res != null) {
        if (res['status'] ?? false) {
          Get.to(() => OrderSuccess(order: cartRes!));
        } else {
          Constants.errorDialog(message: res['message']);
          isLoading = false;
          update();
        }
      } else {
        Constants.errorDialog();
        isLoading = false;
        update();
      }
    });
  }

  List<String>? selectedItems = [];
  String selectedItemsString = "";
  void multiSelectBottomSheet(
    BuildContext context, {
    required List<dynamic> items,
    required dynamic food,
  }) {
    // ❗ Clear previous selections every time
    selectedItems = [];
    selectedItemsString = "";

    Constants.bottomSheetWithHandle(context, builder: (context) {
      return GetBuilder<CartController>(
        builder: (logic) {
          return AnimatedContainer(
            duration: 100.milliseconds,
            padding: EdgeInsets.only(
              bottom: MediaQuery.of(context).viewInsets.bottom,
            ),
            decoration: const BoxDecoration(
              color: bgColor,
              borderRadius: BorderRadius.vertical(top: Radius.circular(24)),
              boxShadow: [BoxShadow(blurRadius: 8, color: Colors.black26)],
            ),
            child: SafeArea(
              child: Column(
                mainAxisSize: MainAxisSize.min,
                children: [
                  /// Handle
                  Center(
                    child: Container(
                      height: 4,
                      width: 40,
                      margin: const EdgeInsets.symmetric(vertical: 24),
                      decoration: BoxDecoration(
                        color: textColor,
                        borderRadius: BorderRadius.circular(40),
                      ),
                    ),
                  ),

                  /// List
                  Flexible(
                    child: SingleChildScrollView(
                      child: Padding(
                        padding:
                            EdgeInsets.symmetric(horizontal: getWidth(20)!),
                        child: Column(
                          children: List.generate(items.length, (index) {
                            final name = items[index];
                            final isSelected = selectedItems!.contains(name);

                            return Column(
                              children: [
                                Bouncing(
                                  onTap: () {
                                    if (isSelected) {
                                      selectedItems!.remove(name);
                                    } else {
                                      selectedItems!.add(name);
                                    }
                                    update();
                                  },
                                  child: Row(
                                    children: [
                                      AppText.paragraph(
                                        name,
                                        fontWeight: FontWeight.w600,
                                        color: textColor,
                                      ),
                                      const Spacer(),
                                      Checkbox(
                                        value: isSelected,
                                        checkColor: whiteColor,
                                        activeColor: buttonColor,
                                        onChanged: (v) {
                                          if (v == true) {
                                            selectedItems!.add(name);
                                          } else {
                                            selectedItems!.remove(name);
                                          }
                                          update();
                                        },
                                      ),
                                    ],
                                  ),
                                ),
                                if (index != items.length - 1) ...[
                                  Gap(getWidth(12)!),
                                  appDivider(),
                                  Gap(getWidth(12)!),
                                ],
                              ],
                            );
                          }),
                        ),
                      ),
                    ),
                  ),

                  /// Add Button
                  Padding(
                      padding: EdgeInsets.symmetric(
                        horizontal: getWidth(20)!,
                        vertical: getWidth(14)!,
                      ),
                      child: PrimaryButton(
                        text: "Add",
                        onTap: () {
                          selectedItemsString = selectedItems!.join(", ");
                          update();
                          Get.back();
                          logic.updateAddonsAPI(data: food);
                        },
                      )),
                ],
              ),
            ),
          );
        },
      );
    });
  }
}
