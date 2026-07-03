import 'package:jazz_smart_pay/app/utils/search_places_jay/place_model.dart';
import 'package:jazz_smart_pay/app/utils/search_places_jay/search_places.dart';
import 'package:jazz_smart_pay/app/utils/search_places_jay/search_places_widget.dart';
import '../../../../exports.dart';
import '../../../dio_api/dio_api.dart';
import '../../../utils/constant_vars.dart';
import '../../../utils/index.dart';
import '../../../utils/uuid_generator.dart';

class RegisterController extends GetxController {
  late TextEditingController inviteCode;
  late TextEditingController registerPhone;
  late TextEditingController registerPassword;
  late TextEditingController registerName;
  bool showPsss = false;

  @override
  void onInit() {
    inviteCode = TextEditingController();
    registerPassword = TextEditingController();
    registerPhone = TextEditingController();
    registerName = TextEditingController();
    super.onInit();
  }

  //*******************************************************************//
  //************************* Login User APi **************************//
  //*******************************************************************//
  bool isLoading = false;
  registerAPI() {
    isLoading = true;
    update();
    API().postFormData(
      "/signup",
      data: {
        'name': registerName.text.trim(),
        'invite_code': inviteCode.text.trim(),
        'phone': registerPhone.text.trim(),
        'password': registerPassword.text.trim(),
      },
    ).then((value) async {
      Map<String, dynamic>? res = value.data;
      if (res != null) {
        if (res['status'] ?? false) {
          Prefs().setToken(res['token']);
          Get.offAllNamed(Routes.BASE_PAGE);
        } else {
          isLoading = false;
          Constants.errorDialog(message: res['message']);
        }
      } else {
        isLoading = false;
        Constants.errorDialog();
      }

      update();
    });
  }

  var registerFormKey = GlobalKey<FormState>();
  void registerSubmit() {
    final isValid = registerFormKey.currentState!.validate();
    if (!isValid) {
      return;
    } else {
      registerAPI();
    }
    registerFormKey.currentState!.save();
  }

  // ************************************************************************ //
  // ************************ Search Field Delegates ************************ //
  // ************************************************************************ //

  String? addLat;
  String? addLng;
  pickAddress(BuildContext context) async {
    final sessionToken = Uuid().generateV4();
    final result = await showPlacesSearch<PlaceJay?>(
      context: context,
      delegate: AddressSearch(sessionToken),
    );
    if (result != null) {
      addLat = result.lat?.toString();
      addLng = result.lng?.toString();
    }
    update();
  }
}
