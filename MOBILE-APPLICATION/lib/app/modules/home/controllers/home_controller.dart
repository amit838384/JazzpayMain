import '../../../../exports.dart';
import '../../../dio_api/dio_api.dart';
import '../../../models/profile_response.dart';
import '../../../utils/constant_vars.dart';

class HomeController extends GetxController {
  @override
  void onInit() {
    profileAPI();
    getCreditTransferData();
    super.onInit();
  }

  bool isStudentLoading = false;
  Map<String, dynamic>? creditRes;
  getCreditTransferData() {
    isStudentLoading = true;
    update();
    API().get("/get-wallet-balance").then((value) async {
      Map<String, dynamic>? res = value.data;
      if (res != null) {
        creditRes = res;
        if (res['status'] ?? false) {
          Constants.walletBalance = creditRes!['parent wallet'];
        } else {
          Constants.errorDialog(message: res['message']);
        }
      } else {
        Constants.errorDialog();
      }

      isStudentLoading = false;
      update();
    });
  }

  final List<DashboardItem> items = [
    DashboardItem(
        title: "Family",
        icon: Icons.family_restroom,
        color: Colors.pink.shade50),
    DashboardItem(
        title: "Pre Order",
        icon: Icons.history_edu,
        color: Colors.blue.shade50),
    DashboardItem(
        title: "Credit Transfer",
        icon: Icons.credit_card,
        color: Colors.grey.shade200),
    DashboardItem(
        title: "Top up",
        icon: Icons.account_balance_wallet,
        color: Colors.yellow.shade100),
    // Add more items dynamically if needed
  ];
  bool isLoading = false;

  Map<String, dynamic>? resdashboard;
  dashboardAPI() {
    isLoading = true;
    update();
    API().get("/dashboardlist").then((value) async {
      Map<String, dynamic>? res = value.data;
      if (res != null) {
        if (res['status'] == 1) {
          resdashboard = res['data'];
          isLoading = false;
          update();
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

  profileAPI() {
    isLoading = true;
    update();
    API().get("/parent-profile").then((value) async {
      Map<String, dynamic>? res = value.data;
      if (res != null) {
        if (res['status'] ?? false) {
          Constants.profileRes = ProfileResponse.fromJson(res['data']);
          isLoading = false;
          update();
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
}

class DashboardItem {
  final String title;
  final IconData icon;
  final Color color;

  DashboardItem({required this.title, required this.icon, required this.color});
}
