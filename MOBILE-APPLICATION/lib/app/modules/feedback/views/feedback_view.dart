import 'package:flutter/material.dart';
import 'package:jazz_smart_pay/app/custom_widget/app_text.dart';
import 'package:jazz_smart_pay/app/custom_widget/my_app_bar.dart';
import 'package:jazz_smart_pay/app/custom_widget/primary_button.dart';
import 'package:jazz_smart_pay/app/custom_widget/tablet_padding_widget.dart';
import 'package:jazz_smart_pay/app/utils/app_const_colors.dart';
import 'package:jazz_smart_pay/app/utils/app_size.dart';
import 'package:gap/gap.dart';
import 'package:get/get.dart';
import '../../../custom_widget/app_text_area_revised.dart';
import '../../../utils/form_validation.dart';
import '../controllers/feedback_controller.dart';

class FeedbackView extends GetView<FeedbackController> {
  const FeedbackView({super.key});
  @override
  Widget build(BuildContext context) {
    return GetBuilder<FeedbackController>(builder: (logic) {
      return Scaffold(
        backgroundColor: bgColor,
        appBar: myAppBar(title: "Feedback"),
        body: TabPadding(
          child: SingleChildScrollView(
            padding: EdgeInsets.all(getWidth(20)!),
            child: Form(
              key: logic.feedbackFormKey,
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  AppText.heading2(
                    "We Value Your Feedback",
                    color: textColor,
                    getfontSize: 24,
                    fontWeight: FontWeight.w700,
                  ),
                  Gap(getWidth(10)!),
                  AppText.paragraph(
                    "Help us improve your experience by sharing your feedback.\nYour input helps us serve you better.",
                    color: textColor,
                  ),
                  Gap(getWidth(60)!),
                  AppNewTextAreaRevised(
                    hintText: "Tell us more about experience",
                    controller: logic.feedback,
                    maxLines: 9,
                    hintColor: hintColor,
                    validator: (value) =>
                        FormValidation.notEmptyValidator(value),
                    onChanged: (p0) {
                      logic.update();
                    },
                    textCapitalization: TextCapitalization.sentences,
                  ),
                  Gap(getWidth(60)!),
                  PrimaryButton(
                    text: "Submit Feedback",
                    isLoading: logic.isLoading,
                    onTap: () {
                      logic.feedbackSubmit();
                    },
                  ),
                ],
              ),
            ),
          ),
        ),
      );
    });
  }
}
