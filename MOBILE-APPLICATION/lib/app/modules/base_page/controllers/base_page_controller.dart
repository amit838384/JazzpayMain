import 'package:jazz_smart_pay/app/modules/home/views/home_view.dart';
import 'package:jazz_smart_pay/app/modules/profile/views/profile_view.dart';
import 'package:jazz_smart_pay/exports.dart';
import '../../../dio_api/dio_api.dart';
import '../../../utils/constant_vars.dart';

class BasePageController extends GetxController {
  RxInt selectedIndex = 0.obs;
  static const TextStyle optionStyle =
      TextStyle(fontSize: 30, fontWeight: FontWeight.bold);

  List<Widget> widgetOptions = <Widget>[const HomeView(), const ProfileView()];
  @override
  void onInit() {
    // Constants.getCurrentLocation();
    // getProfile();
    super.onInit();
  }

  void onItemTapped(int index) {
    selectedIndex.value = index;
  }

  RxBool isLoading = false.obs;
  getProfile() {
    isLoading.value = true;
    update();
    API().get("/profile").then((value) async {
      Map<String, dynamic>? res = value.data;
      if (res != null) {
        if (res['status'] == 1) {
          Constants.profile = res['data'];
        } else {
          Constants.errorDialog(message: res['message']);
        }
      } else {
        Constants.errorDialog();
      }
      isLoading.value = false;
      update();
    });
  }
}
