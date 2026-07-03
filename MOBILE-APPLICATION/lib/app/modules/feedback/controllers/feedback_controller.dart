import 'dart:developer';

import 'package:flutter/material.dart';
import 'package:get/get.dart';
import '../../../dio_api/dio_api.dart';
import '../../../utils/app_methods.dart';
import '../../../utils/constant_vars.dart';

class FeedbackController extends GetxController {
  late TextEditingController feedback;
  @override
  void onInit() {
    feedback = TextEditingController();
    super.onInit();
  }

  //*******************************************************************//
  //********************** Send Feedback APi **************************//
  //*******************************************************************//
  bool isLoading = false;
  feedbackAPI() {
    isLoading = true;
    update();
    API().post("/feedback", data: {
      'message': feedback.text.trim(),
    }).then((value) async {
      Map<String, dynamic>? res = value.data;
      if (res != null) {
        if (res['status'] ?? false) {
          Get.back();
          AppMethods.showCustomSnackbar(
              "Thank you! Your feedback has been submitted successfully.");
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

  var feedbackFormKey = GlobalKey<FormState>();
  void feedbackSubmit() {
    final isValid = feedbackFormKey.currentState!.validate();
    if (!isValid) {
      return;
    } else {
      feedbackAPI();
    }
    feedbackFormKey.currentState!.save();
  }
}
