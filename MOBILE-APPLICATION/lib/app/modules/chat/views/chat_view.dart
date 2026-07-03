import 'package:flutter/material.dart';
import 'package:gap/gap.dart';
import 'package:get/get.dart';
import 'package:jazz_smart_pay/app/custom_widget/app_text.dart';
import 'package:jazz_smart_pay/app/custom_widget/my_app_bar.dart';
import 'package:jazz_smart_pay/app/utils/app_const_colors.dart';
import 'package:jazz_smart_pay/app/utils/index.dart';
import '../../../custom_widget/loading_circular_component.dart';
import '../controllers/chat_controller.dart';

class ChatView extends GetView<ChatController> {
  final bool showBack;
  const ChatView({super.key, this.showBack = true});
  @override
  Widget build(BuildContext context) {
    return GetBuilder<ChatController>(
        init: ChatController(),
        builder: (logic) {
          return Scaffold(
            backgroundColor: bgColor,
            appBar: logic.isLoading
                ? null
                : myAppBar(
                    title: "Chat Support",
                    visibleBack: showBack,
                    actions: [
                        GestureDetector(
                          onTap: () {
                            logic.getChat();
                          },
                          child: Icon(
                            Icons.replay_circle_filled_rounded,
                            color: whiteColor,
                            size: getWidth(32),
                          ),
                        ),
                        Gap(getWidth(20)!),
                      ]),
            body: logic.isLoading
                ? const Center(child: LoadingCircularComponent())
                : Column(
                    children: [
                      (logic.chatList.isNotEmpty)
                          ? Expanded(
                              child: ListView.builder(
                                reverse: false,
                                padding: EdgeInsets.symmetric(
                                    vertical: getWidth(10)!,
                                    horizontal: getWidth(16)!),
                                itemCount: logic.chatList.length,
                                itemBuilder: (context, index) {
                                  var msg = logic.chatList[index];
                                  return _buildMessage(msg);
                                },
                              ),
                            )
                          : const Expanded(child: SizedBox()),
                      Gap(getWidth(20)!),
                      Padding(
                        padding: EdgeInsets.all(getWidth(12)!),
                        child: Row(
                          children: [
                            Expanded(
                              child: Container(
                                padding: EdgeInsets.all(getWidth(12)!),
                                decoration: BoxDecoration(
                                    borderRadius:
                                        BorderRadius.circular(getWidth(12)!),
                                    border: Border.all(
                                      width: getWidth(1)!,
                                      color: primaryColor,
                                    )),
                                child: TextField(
                                  controller: logic.message,
                                  textInputAction: TextInputAction.send,
                                  textCapitalization:
                                      TextCapitalization.sentences,
                                  decoration: InputDecoration.collapsed(
                                      hintText: "Type a message",
                                      hintStyle: TextStyle(
                                        fontSize: getFontSize(18),
                                      )),
                                  onSubmitted: logic.sendMessage,
                                ),
                              ),
                            ),
                            Gap(getWidth(12)!),
                            ElevatedButton(
                              onPressed: () {
                                logic.sendMessage(logic.message.text.trim());
                              },
                              child: const Icon(Icons.send),
                            ),
                          ],
                        ),
                      ),
                      if (showBack) Gap(getWidth(40)!),
                    ],
                  ),
          );
        });
  }

  Widget _buildMessage(dynamic msg) {
    return Align(
      alignment: msg['sender'] == "user"
          ? Alignment.centerRight
          : Alignment.centerLeft,
      child: Container(
        margin: EdgeInsets.only(
          bottom: getWidth(12)!,
          left: msg['sender'] == "user" ? getWidth(100)! : 0,
          right: msg['sender'] != "user" ? getWidth(100)! : 0,
        ),
        padding: EdgeInsets.symmetric(
            horizontal: getWidth(15)!, vertical: getWidth(12)!),
        decoration: BoxDecoration(
            color: msg['sender'] == "user" ? blueColor : primaryColor,
            borderRadius: BorderRadius.circular(getWidth(12)!)),
        child: Column(
          crossAxisAlignment: msg['sender'] != "user"
              ? CrossAxisAlignment.start
              : CrossAxisAlignment.end,
          children: [
            AppText.paragraph(
              msg['message'],
              color: whiteColor,
              fontWeight: FontWeight.w500,
              getfontSize: 17,
            ),
            Gap(getWidth(6)!),
            AppText.smallParagraph(
              msg['created_at'],
              color: whiteColor.withValues(
                alpha: .8,
              ),
              fontWeight: FontWeight.w600,
            )
          ],
        ),
      ),
    );
  }
}
