import 'dart:developer';
import 'package:flutter/material.dart';
import 'package:jazz_smart_pay/app/custom_widget/app_divider.dart';
import 'package:jazz_smart_pay/app/custom_widget/app_text.dart';
import 'package:jazz_smart_pay/app/custom_widget/loading_circular_component.dart';
import 'package:jazz_smart_pay/app/custom_widget/my_app_bar.dart';
import 'package:jazz_smart_pay/app/custom_widget/primary_button.dart';
import 'package:jazz_smart_pay/app/routes/app_pages.dart';
import 'package:jazz_smart_pay/app/utils/app_const_colors.dart';
import 'package:jazz_smart_pay/app/utils/app_size.dart';
import 'package:gap/gap.dart';
import 'package:get/get.dart';
import 'package:jazz_smart_pay/app/utils/constant_vars.dart';
import '../controllers/address_controller.dart';

class AddressView extends GetView<AddressController> {
  const AddressView({super.key});
  @override
  Widget build(BuildContext context) {
    return GetBuilder<AddressController>(builder: (logic) {
      return Scaffold(
        backgroundColor: bgColor,
        appBar: myAppBar(
          title: "Choose Delivery Address",
          backTap: () {
            if (logic.isLoading == false) {
              if (logic.primaryAddressId != logic.primaryApiAddressId) {
                Get.back(result: true);
              } else {
                Get.back(result: false);
              }
            }
          },
        ),
        body: logic.isLoading
            ? const Center(child: LoadingCircularComponent())
            : WillPopScope(
                onWillPop: () {
                  if (logic.primaryAddressId != logic.primaryApiAddressId) {
                    Get.back(result: true);
                  } else {
                    Get.back(result: false);
                  }
                  return Future.value(false);
                },
                child: SingleChildScrollView(
                  padding: EdgeInsets.symmetric(vertical: getWidth(20)!),
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      if (logic.resAddress != null)
                        ...List.generate(
                          logic.resAddress!['data'].length,
                          (index) {
                            return Column(
                              children: [
                                _addressWidget(
                                  logic,
                                  address: logic.resAddress!['data'][index],
                                  isSelected: logic.resAddress!['data'][index]
                                          ['status'] ==
                                      1,
                                ),
                                if (logic.resAddress!['data'].length - 1 !=
                                    index)
                                  const ThickDivider(),
                              ],
                            );
                          },
                        ),
                    ],
                  ),
                ),
              ),
        bottomNavigationBar: logic.isLoading
            ? null
            : Padding(
                padding: EdgeInsets.all(getWidth(30)!),
                child: PrimaryButton(
                  text: "Add New address",
                  onTap: () {
                    Get.toNamed(Routes.ADD_ADDRESS,
                        arguments: {"type": "0", "address": null})?.then(
                      (value) {
                        if (value == true) {
                          logic.getAddressAPI();
                        }
                      },
                    );
                  },
                ),
              ),
      );
    });
  }

  _addressWidget(AddressController logic,
      {bool isSelected = false, required Map address}) {
    return GestureDetector(
      onTap: () {
        if (address['status'] != 1) {
          Constants.yesNoDialogRevise(
            Get.context!,
            confirmText: "Yes",
            cancelText: "Cancel",
            contentText: "Set this address to your primary address",
            titleText: "Primary Address",
            onTapYes: () {
              Get.close(1);
              logic.setPrimaryAddressAPI(address['id'].toString());
            },
          );
        }
      },
      child: Container(
        margin: EdgeInsets.only(
            bottom: getWidth(20)!, right: getWidth(20)!, left: getWidth(20)!),
        padding: EdgeInsets.all(getWidth(20)!),
        decoration: BoxDecoration(
            border: Border.all(
              width: getWidth(1.5)!,
              color: isSelected ? primaryColor : whiteColor,
            ),
            borderRadius: BorderRadius.circular(
              getWidth(20)!,
            )),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            AppText.heading1(address['name'],
                color: primaryColor,
                fontWeight: FontWeight.w800,
                getfontSize: 20),
            Gap(getWidth(12)!),
            Padding(
              padding: EdgeInsets.only(right: getWidth(100)!),
              child: AppText.paragraph(
                "${address['address1']} ${address['address2']} ${address['state']} ${address['city']} ${address['pincode']}",
                color: hintColor,
              ),
            ),
            Gap(getWidth(6)!),
            Row(
              mainAxisAlignment: MainAxisAlignment.spaceBetween,
              children: [
                AppText.paragraph(
                  address['mobileno'],
                  color: hintColor,
                ),
                AppText.paragraph(
                  address['email'],
                  color: hintColor,
                ),
              ],
            ),
            Gap(getWidth(12)!),
            Row(
              children: [
                PrimaryButton(
                  verticalPaddingGet: 12,
                  width: getWidth(150),
                  text: "Edit",
                  onTap: () {
                    Get.toNamed(Routes.ADD_ADDRESS,
                        arguments: {"type": "1", "address": address})?.then(
                      (value) {
                        if (value == true) {
                          logic.getAddressAPI();
                        }
                      },
                    );
                  },
                ),
                Gap(getWidth(12)!),
                GestureDetector(
                  onTap: () {
                    Constants.yesNoDialogRevise(
                      Get.context!,
                      confirmText: "Delete",
                      cancelText: "Cancel",
                      contentText:
                          "Are you sure you want to delete this address?",
                      titleText: "Delete Address",
                      onTapYes: () {
                        Get.close(1);
                        log("Check");
                        logic.deleteAddressAPI(address['id'].toString());
                      },
                    );
                  },
                  child: Icon(
                    Icons.delete_outline_outlined,
                    color: redColor,
                    size: getWidth(40),
                  ),
                )
              ],
            ),
          ],
        ),
      ),
    );
  }
}
