import 'dart:async';
import 'dart:io';
import 'package:flutter_localization/flutter_localization.dart';
import 'package:jazz_smart_pay/app/custom_widget/app_divider.dart';
import 'package:jazz_smart_pay/app/custom_widget/primary_button.dart';
import 'package:jazz_smart_pay/app/modules/home/controllers/home_controller.dart';
import 'package:sadad_qa_payments/sadad_qa_payments.dart';
import 'package:jazz_smart_pay/app/utils/app_const_colors.dart';
import '../../../../exports.dart';
import '../../../custom_widget/app_text.dart';
import '../../../custom_widget/app_text_field.dart';
import '../../../dio_api/dio_api.dart';
import '../../../utils/app_size.dart';
import '../../../utils/constant_vars.dart';
import '../../../utils/form_validation.dart';

class TopUpLogic extends GetxController {
  late TextEditingController amountController;
  final List<String> amounts = ['100', '150', '200', '300', '400', '500'];
  String selectedAmount = '';
  @override
  void onInit() {
    amountController = TextEditingController();
    getTopUpData();
    super.onInit();
  }

  bool isLoading = false;
  Map<String, dynamic>? topRes;
  getTopUpData() {
    isLoading = true;
    update();
    API().get("/get-topup").then((value) async {
      Map<String, dynamic>? res = value.data;
      if (res != null) {
        topRes = res;
        if (res['status'] ?? false) {
          if (res['wallet_balance'] != null && res['wallet_balance'] != "") {
            Constants.walletBalance = res['wallet_balance'].toString();
          }
        } else {
          Constants.errorDialog(message: res['message']);
        }
      } else {
        Constants.errorDialog();
      }
      isLoading = false;
      isPayLoading = false;
      update();
    });
  }

  bool isPayLoading = false;
  topUpAPI() {
    Get.back();
    isPayLoading = true;
    update();
    API().post("/add-topup",
        data: {"amount": amountController.text.trim()}).then((value) async {
      Map<String, dynamic>? res = value.data;
      if (res != null) {
        if (res['status'] ?? false) {
          getTopUpData();
        } else {
          isPayLoading = false;
          update();
          Constants.errorDialog(message: res['message']);
        }
      } else {
        isPayLoading = false;
        update();
        Constants.errorDialog();
      }
    });
  }

  // 2 july, 10:10
  topUpAPINew() async {
    Get.back(); // close amount sheet
    isPayLoading = true;
    update();

    final value = await API().post(
      "/add-topup",
      data: {"amount": amountController.text.trim()},
    );
    Map<String, dynamic>? res = value.data;

    if (res == null || !(res['status'] ?? false)) {
      isPayLoading = false;
      update();
      Constants.errorDialog(message: res?['message']);
      return;
    }

    // Required fields for the SDK
    final orderId =
        "TEST_ORD_${DateTime.now().millisecondsSinceEpoch}"; // res['order_id']?.toString();
    final sdkToken = 'HSWkljl24ldj9D47lvN'; // res['sdk_token']?.toString();
    final merchantSadadId = '1523840'; // res['merchant_sadad_id']?.toString();

    if (orderId == null || sdkToken == null || merchantSadadId == null) {
      isPayLoading = false;
      update();
      Constants.errorDialog(message: 'invalid_sadad_config'.tr);
      return;
    }

    final amount = double.tryParse(amountController.text.trim()) ?? 0.0;

    isPayLoading = false;
    update();

    // Open SADAD SDK
    final sdkResult = await Get.to(() => PaymentScreen(
          orderId: orderId,
          productDetail: const [
            {"itemname": "Wallet Topup", "type": "topup"}
          ],
          // customerName: res['customer_name']?.toString() ?? '',
          customerName: "Test User",
          amount: amount,
          // email:  res['email']?.toString() ?? '',
          // mobile: res['mobile']?.toString() ?? '',
          email: "test@example.com",
          mobile: "97412311178",
          token:
              'nmlek57NyVG4qowpeipqiweljsldjfwoierrLKJnsjhtQdB4rkHBDYDyW4IPMScRDE',
          packageMode: PackageMode.debug, // PackageMode.release for prod
          isWalletEnabled: false,
          paymentTypes: const [
            PaymentType.creditCard,
            PaymentType.debitCard,
            PaymentType.sadadPay,
          ],
          image: Image.asset("assets/images/app_icon.jpg"), // your logo path
          titleText: "Jazz Smart Pay",
          paymentButtonColor: buttonColor,
          paymentButtonTextColor: whiteColor,
          themeColor: buttonColor,
          merchantSadadId: merchantSadadId,
          googleMerchantID: '',
          googleMerchantName: '',
        ));

    await _handleSadadResult(sdkResult, orderId);
  }

  Future<void> _handleSadadResult(dynamic sdkResult, String orderId) async {
    if (sdkResult == null) {
      // User dismissed the SDK without paying
      return;
    }

    final parsed = _parseSadadResult(sdkResult);
    final status = parsed['status'];
    final txnId = parsed['transaction_id'] ?? '';

    if (status != '3') {
      Constants.errorDialog(
        message: parsed['message'] ?? 'payment_failed'.tr,
      );
      return;
    }

    amountController.clear();
    selectedAmount = '';
    Constants.errorDialog(message: 'topup_success'.tr);
    return;

    // Verify with backend — never trust SDK status alone
    // isPayLoading = true;
    // update();

    // final verify = await API().post("/verify-topup", data: {
    //   "order_id": orderId,
    //   "transaction_id": txnId,
    //   "sdk_status": status,
    // });
    // Map<String, dynamic>? vRes = verify.data;
    // isPayLoading = false;

    // if (vRes != null && (vRes['status'] ?? false)) {
    //   if (vRes['wallet_balance'] != null) {
    //     Constants.walletBalance = vRes['wallet_balance'].toString();
    //   }
    //   amountController.clear();
    //   selectedAmount = '';
    //   getTopUpData();
    //   Constants.errorDialog(message: vRes['message'] ?? 'topup_success'.tr);
    // } else {
    //   Constants.errorDialog(message: vRes?['message'] ?? 'verify_failed'.tr);
    // }
    // update();
  }

  Map<String, String?> _parseSadadResult(dynamic raw) {
    // SDK may return a Map or a String. Normalize both.
    if (raw is Map) {
      return {
        'status': (raw['status'] ?? raw['Status'])?.toString(),
        'transaction_id': (raw['transactionid'] ??
                raw['transaction id'] ??
                raw['transactionId'])
            ?.toString(),
        'order_id': (raw['orderid'] ?? raw['orderId'])?.toString(),
        'amount': raw['amount']?.toString(),
        'payment_mode': (raw['payment mode'] ?? raw['paymentMode'])?.toString(),
        'message': raw['message']?.toString(),
      };
    }
    final s = raw.toString();
    String? grab(List<String> keys) {
      for (final k in keys) {
        final patterns = [
          RegExp('"$k"\\s*:\\s*"([^"]+)"'),
          RegExp('$k\\s*[:=]\\s*([^,\\n}]+)'),
        ];
        for (final p in patterns) {
          final m = p.firstMatch(s);
          if (m != null) return m.group(1)?.trim();
        }
      }
      return null;
    }

    return {
      'status': grab(['status', 'Status']),
      'transaction_id':
          grab(['transactionid', 'transaction id', 'transactionId']),
      'order_id': grab(['orderid', 'orderId']),
      'amount': grab(['amount']),
      'payment_mode': grab(['payment mode', 'paymentMode']),
      'message': grab(['message']),
    };
  }

  Future<T?> bottomSheetWithHandle<T>(
    BuildContext context, {
    Widget? body,
    bool isScrollControlled = true,
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
        backgroundColor: bgColor,
        useSafeArea: useSafeArea,
        isDismissible: isDismissible,
        enableDrag: enableDrag,
        builder: (context) {
          return GetBuilder<TopUpLogic>(
            builder: (logic) {
              return AnimatedContainer(
                duration: 100.milliseconds,
                padding: EdgeInsets.only(
                  bottom: MediaQuery.of(context).viewInsets.bottom,
                ),
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
                child: SafeArea(
                  child: SingleChildScrollView(
                    child: Padding(
                      padding: EdgeInsets.symmetric(horizontal: getWidth(20)!),
                      child: Column(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          if (showHandleBar)
                            Center(
                              child: Container(
                                height: 4,
                                width: 40,
                                margin:
                                    const EdgeInsets.symmetric(vertical: 24),
                                decoration: BoxDecoration(
                                  color: textColor,
                                  borderRadius: BorderRadius.circular(40),
                                ),
                              ),
                            ),
                          AppText.heading2("topup_wallet".getString(context),
                              color: textColor, fontWeight: FontWeight.w700),
                          Gap(getWidth(12)!),
                          appDivider(color: textColor),
                          Gap(getWidth(12)!),
                          AppText.heading2(
                              "topup_wallet_text".getString(context),
                              color: textColor,
                              getfontSize: 16),
                          Gap(getWidth(12)!),
                          amountSelector(
                            onAmountSelected: (amount) {
                              selectedAmount = amount;
                              amountController.text = amount;
                              update();
                            },
                          ),
                          Gap(getWidth(32)!),
                          AppTextField(
                            controller: logic.amountController,
                            hintText: "amount".getString(context),
                            onChanged: (p0) {
                              logic.update();
                            },
                            validator: (value) =>
                                FormValidation.notEmptyValidator(value),
                            keyboardType: TextInputType.number,
                            borderColor: textColor,
                            hintTextColor: hintColor,
                            textStyleColor: textColor,
                          ),
                          Gap(getWidth(12)!),
                          AppText.heading4(
                            "note_topup".getString(context),
                            color: textColor,
                            fontWeight: FontWeight.w700,
                            getfontSize: 14,
                          ),
                          Gap(getWidth(32)!),
                          PrimaryButton(
                            text: "continue_to_pay".getString(context),
                            onTap: () {
                              logic.topUpAPI();
                            },
                            isDisabled: amountController.text.trim().isEmpty,
                          ),
                          if (Platform.isAndroid) Gap(getWidth(20)!),
                        ],
                      ),
                    ),
                  ),
                ),
              );
            },
          );
        });
  }

  amountWidget(String amount, {bool isSelected = false}) {
    return Container(
      alignment: Alignment.center,
      padding: EdgeInsets.symmetric(vertical: getWidth(12)!),
      width: getWidth(130),
      decoration: BoxDecoration(
          color: !isSelected ? null : buttonColor,
          border: Border.all(
            color: isSelected ? Colors.transparent : whiteColor,
            width: getWidth(1)!,
          ),
          borderRadius: BorderRadius.circular(getWidth(14)!)),
      child: AppText.paragraph(
        amount,
        fontWeight: FontWeight.w700,
        color: isSelected ? whiteColor : whiteColor,
      ),
    );
  }

  Widget amountSelector({required void Function(String) onAmountSelected}) {
    return Center(
      child: Wrap(
        spacing: getWidth(getWidth(30)!)!,
        runSpacing: getWidth(12)!,
        children: amounts.map((amount) {
          final isSelected = selectedAmount == amount;
          return GestureDetector(
            onTap: () => onAmountSelected(amount),
            child: amountWidget(amount, isSelected: isSelected),
          );
        }).toList(),
      ),
    );
  }
}
