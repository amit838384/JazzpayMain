import 'dart:developer';
import 'dart:io';
import 'package:flutter_localization/flutter_localization.dart';
import 'package:jazz_smart_pay/app/custom_widget/app_divider.dart';
import 'package:jazz_smart_pay/app/custom_widget/primary_button.dart';
import 'package:jazz_smart_pay/app/utils/app_const_colors.dart';
import '../../../../exports.dart';
import '../../../custom_widget/app_text.dart';
import '../../../custom_widget/app_text_field.dart';
import '../../../dio_api/dio_api.dart';
import '../../../utils/app_size.dart';
import '../../../utils/constant_vars.dart';
import '../../../utils/form_validation.dart';

class CreditTransferLogic extends GetxController {
  late TextEditingController fromController;
  late TextEditingController toController;
  late TextEditingController amountController;
  late TextEditingController amountCreditController;
  final List<String> amounts = ['100', '150', '200', '300', '400', '500'];
  String selectedAmount = '';
  @override
  void onInit() {
    fromController = TextEditingController();
    toController = TextEditingController();
    amountController = TextEditingController();
    amountCreditController = TextEditingController();
    getCreditTransferData();
    super.onInit();
  }

  bool isLoading = false;
  Map<String, dynamic>? creditRes;
  final List<CreditStudent> students = [];
  getCreditTransferData() {
    isLoading = true;
    update();
    API().get("/get-wallet-balance").then((value) async {
      Map<String, dynamic>? res = value.data;
      if (res != null) {
        creditRes = res;
        if (res['status'] ?? false) {
          Constants.walletBalance = creditRes!['parent wallet'];
          students.clear();
          for (var credit in res['data']) {
            students.add(
              CreditStudent(
                id: credit['id'],
                name: credit['name'],
                walletBalance: credit['wallet_balance'].toString(),
              ),
            );
          }
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

  transferBackToWalletBottomSheet(BuildContext context,
      {required String amount, required String id}) {
    Constants.bottomSheetWithHandle(context, builder: (context) {
      return GetBuilder<CreditTransferLogic>(
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
              child: SingleChildScrollView(
                child: Padding(
                  padding: EdgeInsets.symmetric(horizontal: getWidth(20)!),
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
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
                      Row(
                        mainAxisAlignment: MainAxisAlignment.spaceBetween,
                        children: [
                          AppText.heading2(
                              "transfer_back_to_wallet".getString(context),
                              color: textColor,
                              fontWeight: FontWeight.w700),
                          AppText.heading2("QAR $amount",
                              color: yelloColor, fontWeight: FontWeight.w700),
                        ],
                      ),
                      Gap(getWidth(12)!),
                      appDivider(color: textColor),
                      Gap(getWidth(12)!),
                      AppText.paragraph(
                          "transfer_back_to_wallet_text".getString(context),
                          fontWeight: FontWeight.w500,
                          color: textColor),
                      Gap(getWidth(24)!),
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
                      Gap(getWidth(32)!),
                      PrimaryButton(
                        text: "transfer".getString(context),
                        onTap: () {
                          logic.backToWalletAPI(id);
                        },
                        isDisabled:
                            logic.amountController.text.trim().isEmpty ||
                                logic.diableButton(
                                    amount, logic.amountController.text.trim()),
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

  bool diableButton(String amount, String controllerAmount) {
    bool value = false;
    final inputAmount = double.tryParse(amount);
    final controllerText = double.tryParse(controllerAmount);
    if (inputAmount! <= 0) {
      value = true;
    }
    if (controllerText! <= 0) {
      value = true;
    }
    if (controllerText > inputAmount) {
      value = true;
    }
    return value;
  }

  backToWalletAPI(String id) {
    Get.back();
    isLoading = true;
    update();
    API().post("/student-to-parent-transfer", data: {
      "studentID": id,
      "balance": amountController.text.trim()
    }).then((value) async {
      Map<String, dynamic>? res = value.data;
      if (res != null) {
        if (res['status'] ?? false) {
          getCreditTransferData();
        } else {
          isLoading = false;
          update();
          Constants.errorDialog(message: res['message']);
        }
      } else {
        isLoading = false;
        update();
        Constants.errorDialog();
      }
    });
  }

  transferAPI(String id) {
    Get.back();
    isLoading = true;
    update();
    API().post("/add-wallet-balance", data: {
      "studentID": id,
      "balance": amountCreditController.text.trim()
    }).then((value) async {
      Map<String, dynamic>? res = value.data;
      if (res != null) {
        if (res['status'] ?? false) {
          getCreditTransferData();
        } else {
          isLoading = false;
          update();
          Constants.errorDialog(message: res['message']);
        }
      } else {
        isLoading = false;
        update();
        Constants.errorDialog();
      }
    });
  }

  transferCreditBottomSheet(BuildContext context, {required String id}) {
    Constants.bottomSheetWithHandle(context, builder: (context) {
      return GetBuilder<CreditTransferLogic>(
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
              child: SingleChildScrollView(
                child: Padding(
                  padding: EdgeInsets.symmetric(horizontal: getWidth(20)!),
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
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
                      Row(
                        mainAxisAlignment: MainAxisAlignment.spaceBetween,
                        children: [
                          AppText.heading2("transfer_credit".getString(context),
                              color: textColor, fontWeight: FontWeight.w700),
                          AppText.heading2(
                              "QAR ${logic.creditRes!['parent wallet']}",
                              color: yelloColor,
                              fontWeight: FontWeight.w700),
                        ],
                      ),
                      Gap(getWidth(12)!),
                      appDivider(color: textColor),
                      Gap(getWidth(12)!),
                      AppText.paragraph(
                          "transfer_credit_text".getString(context),
                          fontWeight: FontWeight.w500,
                          color: textColor),
                      Gap(getWidth(24)!),
                      AppTextField(
                        controller: logic.amountCreditController,
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
                      Gap(getWidth(32)!),
                      PrimaryButton(
                        text: "add".getString(context),
                        onTap: () {
                          logic.transferAPI(id);
                        },
                        isDisabled:
                            logic.amountCreditController.text.trim().isEmpty ||
                                logic.diableButton(
                                    logic.creditRes!['parent wallet'],
                                    logic.amountCreditController.text.trim()),
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

  transferOneToOneBottomSheet(BuildContext context) {
    Constants.bottomSheetWithHandle(context, builder: (context) {
      return GetBuilder<CreditTransferLogic>(
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
              child: SingleChildScrollView(
                child: Padding(
                  padding: EdgeInsets.symmetric(horizontal: getWidth(20)!),
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
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
                      Row(
                        mainAxisAlignment: MainAxisAlignment.spaceBetween,
                        children: [
                          AppText.heading2("transfer_credit".getString(context),
                              color: textColor, fontWeight: FontWeight.w700),
                          if (logic.fromStudent != null)
                            AppText.heading2(
                                "QAR ${logic.fromStudent?.walletBalance}",
                                color: yelloColor,
                                fontWeight: FontWeight.w700),
                        ],
                      ),
                      Gap(getWidth(12)!),
                      appDivider(color: textColor),
                      Gap(getWidth(12)!),
                      Column(
                        children: [
                          TransferAutocomplete(
                            label: "transfer_from".getString(context),
                            hint: "select".getString(context),
                            textColor: textColor,
                            hintColor: hintColor,
                            buttonColor: buttonColor,
                            initialText: logic.fromController.text,
                            selectedStudent: logic.fromStudent,
                            excludeStudent: logic.toStudent,
                            filter: (q, {exclude}) =>
                                logic.filter(q, exclude: exclude),
                            onSelected: (s) {
                              logic.pickFrom(s);
                              logic.update();
                            },
                            onClearSelected: () {
                              logic.fromStudent = null;
                              // keep the controller text as user typed; only clear the model
                              logic.update();
                            },
                            getWidth: getWidth,
                          ),
                          Gap(getWidth(20)!),
                          TransferAutocomplete(
                            label: "transfer_to".getString(context),
                            hint: "select".getString(context),
                            textColor: textColor,
                            hintColor: hintColor,
                            buttonColor: buttonColor,
                            initialText: logic.toController.text,
                            selectedStudent: logic.toStudent,
                            excludeStudent: logic.fromStudent,
                            filter: (q, {exclude}) =>
                                logic.filter(q, exclude: exclude),
                            onSelected: (s) {
                              logic.pickTo(s);
                              logic.update();
                            },
                            onClearSelected: () {
                              logic.toStudent = null;
                              logic.update();
                            },
                            getWidth: getWidth,
                          ),
                        ],
                      ),
                      Gap(getWidth(20)!),
                      AppText.paragraph(
                        "enter_amount".getString(context),
                        color: textColor,
                        getfontSize: 18,
                        fontWeight: FontWeight.w600,
                      ),
                      Gap(getWidth(12)!),
                      AppTextField(
                        controller: logic.amountCreditController,
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
                      Gap(getWidth(32)!),
                      PrimaryButton(
                        text: "transfer".getString(context),
                        onTap: () {
                          logic.validateAndSubmit(context);
                        },
                        isDisabled: logic.disableTransfer(),
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

  CreditStudent? fromStudent;
  CreditStudent? toStudent;
  Iterable<CreditStudent> filter(String q, {CreditStudent? exclude}) {
    final query = q.trim().toLowerCase();
    return students.where((s) {
      final okQuery = query.isEmpty || s.name.toLowerCase().contains(query);
      final okExclude = exclude == null || s.id != exclude.id;
      return okQuery && okExclude;
    });
  }

  void pickFrom(CreditStudent s) {
    fromStudent = s;
    fromController.text = s.name;
    update();
  }

  void pickTo(CreditStudent s) {
    toStudent = s;
    toController.text = s.name;
    update();
  }

// Optional: clear both in one go
  void resetBoth() {
    fromStudent = null;
    toStudent = null;
    fromController.clear();
    toController.clear();
    update();
  }

  bool disableTransfer() {
    final textAmount = amountCreditController.text.trim();
    final amt = double.tryParse(textAmount) ?? 0;
    // if no sender selected, or amount is invalid, or amount > sender's wallet
    if (fromStudent == null) return true;
    return amt <= 0 || amt > double.parse(fromStudent!.walletBalance);
  }

  void validateAndSubmit(BuildContext context) {
    // required selections
    if (fromStudent == null || toStudent == null) {
      Constants.errorDialog(
          message: 'Please select both From and To students.');
      return;
    }
    if (fromStudent!.id == toStudent!.id) {
      Constants.errorDialog(message: 'From and To cannot be the same student.');
      return;
    }
    final textAmount = amountCreditController.text.trim();
    final amt = double.tryParse(textAmount) ?? 0;
    if (amt <= 0) {
      log("Amount. :  $amt");
      Constants.errorDialog(message: 'Enter a valid amount.');
      return;
    }
    if (amt > double.parse(fromStudent!.walletBalance)) {
      Constants.errorDialog(
          message:
              'Amount exceeds ${fromStudent!.name}\'s wallet (QAR ${fromStudent!.walletBalance}).');
      return;
    }
    transferObneToOneAPI();
  }

  transferObneToOneAPI() {
    Get.back();
    isLoading = true;
    update();
    API().post("/child-money-transfer", data: {
      "student_senderID": fromStudent!.id.toString(),
      "student_reciverID": toStudent!.id.toString(),
      "money": amountCreditController.text.trim(),
    }).then((value) async {
      Map<String, dynamic>? res = value.data;
      if (res != null) {
        if (res['status'] ?? false) {
          getCreditTransferData();
        } else {
          isLoading = false;
          update();
          Constants.errorDialog(message: res['message']);
        }
      } else {
        isLoading = false;
        update();
        Constants.errorDialog();
      }
    });
  }
}

class CreditStudent {
  final int id;
  final String name;
  final String walletBalance;
  CreditStudent(
      {required this.id, required this.name, required this.walletBalance});
}

class AppSnackBar {
  static void success(BuildContext context, String message) {
    _showBanner(context, message, Colors.green);
  }

  static void error(BuildContext context, String message) {
    _showBanner(context, message, buttonColor);
  }

  // Top-of-page "snackbar" using MaterialBanner
  static void _showBanner(BuildContext context, String message, Color color) {
    final messenger = ScaffoldMessenger.of(context);

    // Remove any existing banner/snackbar first
    messenger.hideCurrentMaterialBanner();
    messenger.hideCurrentSnackBar();

    final banner = MaterialBanner(
      content: Text(
        message,
        style:
            const TextStyle(color: Colors.white, fontWeight: FontWeight.w600),
      ),
      backgroundColor: color,
      leadingPadding: const EdgeInsets.only(right: 12),
      padding: EdgeInsets.only(
        top: MediaQuery.of(context).padding.top + 8, // below status bar
        left: 12,
        right: 8,
        bottom: 8,
      ),
      leading: const Icon(Icons.info, color: Colors.white),
      actions: [
        TextButton(
          onPressed: () => messenger.hideCurrentMaterialBanner(),
          child: const Text('DISMISS', style: TextStyle(color: Colors.white)),
        ),
      ],
    );

    messenger.showMaterialBanner(banner);

    // Auto dismiss after 2s to mimic SnackBar behavior
    Future.delayed(const Duration(seconds: 2), () {
      if (messenger.mounted) messenger.hideCurrentMaterialBanner();
    });
  }
}

// Reusable widget for your transfer fields
class TransferAutocomplete extends StatelessWidget {
  const TransferAutocomplete({
    super.key,
    required this.label,
    required this.hint,
    required this.textColor,
    required this.hintColor,
    required this.buttonColor,
    required this.initialText,
    required this.selectedStudent,
    required this.excludeStudent,
    required this.onSelected,
    required this.onClearSelected,
    required this.filter,
    required this.getWidth,
  });

  final String label;
  final String hint;
  final Color textColor;
  final Color hintColor;
  final Color buttonColor;

  // mirrors your GetX controller text (fromController / toController)
  final String initialText;
  final CreditStudent? selectedStudent;
  final CreditStudent? excludeStudent;

  final ValueChanged<CreditStudent> onSelected;
  final VoidCallback onClearSelected;

  // filter(query, exclude: student)
  final Iterable<CreditStudent> Function(String, {CreditStudent? exclude})
      filter;

  final double? Function(double) getWidth;

  @override
  Widget build(BuildContext context) {
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        AppText.paragraph(
          label,
          color: textColor,
          getfontSize: 18,
          fontWeight: FontWeight.w600,
        ),
        Gap(getWidth(12)!),
        Autocomplete<CreditStudent>(
          displayStringForOption: (s) => s.name,
          optionsBuilder: (TextEditingValue tv) =>
              filter(tv.text, exclude: excludeStudent),
          onSelected: onSelected,
          initialValue: TextEditingValue(text: initialText),
          fieldViewBuilder:
              (context, textController, focusNode, onFieldSubmitted) {
            // keep UI + logic in sync without attaching listeners every rebuild
            if (textController.text != initialText) {
              textController.value = TextEditingValue(
                text: initialText,
                selection: TextSelection.collapsed(offset: initialText.length),
              );
            }

            return AppTextField(
              controller: textController,
              focusNode: focusNode,
              hintText: hint,
              validator: FormValidation.notEmptyValidator,
              keyboardType: TextInputType.text,
              borderColor: textColor,
              hintTextColor: hintColor,
              textStyleColor: textColor,
              onChanged: (v) {
                // If user types away from the chosen option, clear selection once
                if (selectedStudent != null &&
                    v.trim() != selectedStudent!.name) {
                  onClearSelected();
                }
                // Call your GetX update if you need live rebuilds
                // (Keep this here instead of a listener)
                // logic.update();  // call from parent via onClearSelected if you prefer
              },
            );
          },
          optionsViewBuilder: (context, onOptionSelected, options) {
            final list = options.toList();
            return Align(
              alignment: Alignment.topLeft,
              child: Material(
                color: Colors.transparent,
                child: Container(
                  margin: EdgeInsets.only(
                    top: getWidth(10)!,
                    left: getWidth(10)!,
                    right: getWidth(50)!,
                  ),
                  decoration: BoxDecoration(
                    color: textColor,
                    borderRadius: BorderRadius.circular(12),
                  ),
                  child: ListView.builder(
                    padding: const EdgeInsets.symmetric(vertical: 4),
                    shrinkWrap: true,
                    physics: const NeverScrollableScrollPhysics(),
                    itemCount: list.length,
                    itemBuilder: (_, i) {
                      final s = list[i];
                      return ListTile(
                        title: AppText.paragraph(
                          s.name,
                          fontWeight: FontWeight.w600,
                          getfontSize: 17,
                          color: buttonColor,
                        ),
                        subtitle: AppText.paragraph(
                          'Wallet: QAR ${s.walletBalance}',
                          fontWeight: FontWeight.w500,
                          color: buttonColor.withValues(alpha: .70),
                        ),
                        onTap: () => onOptionSelected(s),
                        shape: RoundedRectangleBorder(
                          borderRadius: BorderRadius.circular(8),
                        ),
                      );
                    },
                  ),
                ),
              ),
            );
          },
        ),
      ],
    );
  }
}
