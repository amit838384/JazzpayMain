import 'package:flutter/material.dart';
import 'package:gap/gap.dart';
import 'package:get/get.dart';
import 'package:jazz_smart_pay/app/custom_widget/app_text.dart';
import 'package:jazz_smart_pay/app/custom_widget/fill_container.dart';
import 'package:jazz_smart_pay/app/custom_widget/my_app_bar.dart';
import 'package:jazz_smart_pay/app/utils/app_const_colors.dart';
import 'package:jazz_smart_pay/app/utils/index.dart';
import '../../../custom_widget/loading_circular_component.dart';
import '../controllers/notification_controller.dart';

class NotificationView extends GetView<NotificationLogic> {
  const NotificationView({super.key});
  @override
  Widget build(BuildContext context) {
    return GetBuilder<NotificationLogic>(
        init: NotificationLogic(),
        builder: (logic) {
          return GestureDetector(
            onTap: () => FocusScope.of(context).unfocus(),
            child: Scaffold(
                appBar: myAppBar(title: "Notifications"),
                backgroundColor: bgColor,
                body: logic.isLoading
                    ? const Center(child: LoadingCircularComponent())
                    : SingleChildScrollView(
                        padding: EdgeInsets.all(getWidth(12)!),
                        child: Column(
                          children: [
                            if (logic.notiRes?['data'].isEmpty)
                              Center(
                                child: Column(
                                  children: [
                                    Gap(getWidth(300)!),
                                    Image.asset(
                                      ImagePath.bellPlace,
                                      scale: 2.5,
                                    ),
                                    Gap(getWidth(20)!),
                                    AppText.heading2("No Notification yet!")
                                  ],
                                ),
                              ),
                            if (logic.notiRes?['data'].isNotEmpty)
                              ...List.generate(
                                logic.notiRes?['data'].length,
                                (index) {
                                  var noti = logic.notiRes?['data'][index];
                                  return FillContainer(
                                    margin:
                                        EdgeInsets.only(bottom: getWidth(20)!),
                                    child: Row(
                                      children: [
                                        Stack(
                                          children: [
                                            SizedBox.square(
                                              dimension: getWidth(48),
                                              child: Image.asset(
                                                ImagePath.notiItem,
                                              ),
                                            ),
                                            if (noti['read_status'] == "0")
                                              Positioned(
                                                  right: getWidth(8),
                                                  top: getWidth(4),
                                                  child: Container(
                                                    height: getWidth(10),
                                                    width: getWidth(10),
                                                    decoration: BoxDecoration(
                                                      color: redColor,
                                                      shape: BoxShape.circle,
                                                    ),
                                                  ))
                                          ],
                                        ),
                                        Gap(getWidth(24)!),
                                        Expanded(
                                          child: Column(
                                            crossAxisAlignment:
                                                CrossAxisAlignment.start,
                                            children: [
                                              AppText.paragraph(
                                                noti['message'],
                                                fontWeight: FontWeight.w600,
                                              ),
                                              Gap(getWidth(6)!),
                                              AppText.paragraph(
                                                noti['created_at'],
                                                fontWeight: FontWeight.w600,
                                              ),
                                            ],
                                          ),
                                        )
                                      ],
                                    ),
                                  );
                                },
                              ),
                            Gap(getWidth(50)!),
                          ],
                        ),
                      )),
          );
        });
  }
}
