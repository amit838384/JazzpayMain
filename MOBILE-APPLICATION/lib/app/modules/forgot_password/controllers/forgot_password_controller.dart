import '../../../../exports.dart';
import '../../../dio_api/dio_api.dart';
import '../../../utils/constant_vars.dart';

class ForgotPasswordController extends GetxController {
  late TextEditingController emailController;
  late TextEditingController otpController;
  late TextEditingController passController;
  late TextEditingController confirmPassController;

  @override
  void onInit() {
    emailController = TextEditingController();
    // emailController = TextEditingController(text: "self.ashraf1416@gmail.com");
    otpController = TextEditingController();
    passController = TextEditingController();
    confirmPassController = TextEditingController();
    super.onInit();
  }

  bool showPsss = false;
  bool showConfirmPsss = false;
  String type = "0";

  bool isLoading = false;
  sendOtpAPI() {
    isLoading = true;
    update();
    API().post(
      "/parent-forgot-password",
      data: {
        'email': emailController.text.trim(),
      },
    ).then((value) async {
      Map<String, dynamic>? res = value.data;
      if (res != null) {
        if (res['status'] ?? false) {
          type = "1";
          update();
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

  bool verifyLoading = false;
  verifyEmailAPI() {
    verifyLoading = true;
    update();
    API().post(
      "/parent-change-password",
      data: {
        "otp": otpController.text.trim(),
        "password": passController.text.trim(),
      },
    ).then((value) async {
      Map<String, dynamic>? res = value.data;
      if (res != null) {
        if (res['status'] ?? false) {
          Constants.successDialog(
            message: res['message'],
            onTap: () {
              Get.offAllNamed(Routes.LOGIN);
            },
          );
        } else {
          Constants.errorDialog(message: res['message']);
        }
      } else {
        Constants.errorDialog();
      }
      verifyLoading = false;
      update();
    });
  }

  var forgotFormKey = GlobalKey<FormState>();
  void forgotPasswordSubmit() {
    final isValid = forgotFormKey.currentState!.validate();
    if (!isValid) {
      return;
    } else {
      if (type == "0") {
        sendOtpAPI();
      } else {
        verifyEmailAPI();
      }
    }
    forgotFormKey.currentState!.save();
  }
}
