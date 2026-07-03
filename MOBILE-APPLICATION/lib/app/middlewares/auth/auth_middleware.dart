import 'package:jazz_smart_pay/app/utils/prefrence.dart';
import 'package:jazz_smart_pay/exports.dart';

class AuthMiddleware extends GetMiddleware {
  @override
  RouteSettings? redirect(String? route) {
    final prefs = Prefs();
    final token = prefs.getToken();

    if (token == null || token.isEmpty) {
      return const RouteSettings(name: Routes.LOGIN);
    }
    return null;
  }
}
