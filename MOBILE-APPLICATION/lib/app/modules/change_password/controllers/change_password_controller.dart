import '../../../../exports.dart';
import '../../../dio_api/dio_api.dart';
import '../../../utils/constant_vars.dart';

class ChangePasswordController extends GetxController {
  late TextEditingController oldPassController;
  late TextEditingController passController;
  late TextEditingController confirmPassController;

  @override
  void onInit() {
    oldPassController = TextEditingController();
    passController = TextEditingController();
    confirmPassController = TextEditingController();
    super.onInit();
  }

  bool isLoading = false;
  changePasswordAPI() {
    isLoading = true;
    update();
    API().post(
      "/changepassword",
      data: {
        "old_password": oldPassController.text.trim(),
        "new_password": passController.text.trim()
      },
    ).then((value) async {
      Map<String, dynamic>? res = value.data;
      if (res != null) {
        if (res['status'] == 1) {
          Constants.successDialog(
            message: res['message'],
            onTap: () {
              Get.close(2);
            },
          );
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

  var changeFormKey = GlobalKey<FormState>();
  void changePasswordSubmit() {
    final isValid = changeFormKey.currentState!.validate();
    if (!isValid) {
      return;
    } else {
      changePasswordAPI();
    }
    changeFormKey.currentState!.save();
  }
}
