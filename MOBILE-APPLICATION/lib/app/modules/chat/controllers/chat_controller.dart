import 'package:get/get.dart';
import 'package:jazz_smart_pay/exports.dart';

import '../../../dio_api/dio_api.dart';
import '../../../utils/constant_vars.dart';

class ChatController extends GetxController {
  late TextEditingController message;
  @override
  void onInit() {
    message = TextEditingController();
    getChat();
    super.onInit();
  }

  List<dynamic> chatList = [];
  bool isLoading = false;
  getChat() {
    isLoading = true;
    update();
    API().get("/showchat").then((value) async {
      Map<String, dynamic>? res = value.data;
      if (res != null) {
        if (res['status'] == 1) {
          chatList = res['data'];
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

  sendMessageAPI(String msg) {
    isLoading = true;
    update();
    API().post("/addchat", data: {"message": msg}).then((value) async {
      Map<String, dynamic>? res = value.data;
      if (res != null) {
        if (res['status'] == 1) {
          getChat();
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

  void sendMessage(String text) {
    if (text.trim().isEmpty) return;
    sendMessageAPI(text);
    message.clear();
  }
}
