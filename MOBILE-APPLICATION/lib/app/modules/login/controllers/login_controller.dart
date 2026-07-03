import '../../../../exports.dart';
import '../../../dio_api/dio_api.dart';
import '../../../utils/constant_vars.dart';
import '../../../utils/index.dart';

class LoginController extends GetxController {
  late TextEditingController loginPhone;
  late TextEditingController loginPassword;
  bool showPsss = false;
  bool loginWithPhone = false;

  @override
  void onInit() {
    // loginPhone = TextEditingController();
    // loginPassword = TextEditingController();
    loginPhone = TextEditingController(text: "7983153131");
    loginPassword = TextEditingController(text: "123456");
    // Constants.getCurrentLocation();
    super.onInit();
  }

  //*******************************************************************//
  //************************* Login User APi **************************//
  //*******************************************************************//
  bool isLoading = false;
  loginAPI() {
    isLoading = true;
    update();
    API().post("/login", data: {
      'phone': loginPhone.text.trim(),
      'password': loginPassword.text.trim(),
      // "fcmtoken": Constants.fcmToken,
    }).then((value) async {
      Map<String, dynamic>? res = value.data;
      if (res != null) {
        if (res['status'] ?? false) {
          Prefs().setToken(res['token']);
          Get.offAllNamed(Routes.BASE_PAGE);
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

  var loginFormKey = GlobalKey<FormState>();
  void loginSubmit() {
    final isValid = loginFormKey.currentState!.validate();
    if (!isValid) {
      return;
    } else {
      loginAPI();
    }
    loginFormKey.currentState!.save();
  }
}
