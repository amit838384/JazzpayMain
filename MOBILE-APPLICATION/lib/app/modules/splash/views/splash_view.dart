import 'package:flutter_animate/flutter_animate.dart';
import 'package:jazz_smart_pay/app/utils/app_const_colors.dart';
import 'package:jazz_smart_pay/app/utils/constant_vars.dart';
import 'package:jazz_smart_pay/app/utils/index.dart';
import 'package:jazz_smart_pay/exports.dart';

import '../../../calling.dart';

// jazzpay splash screen
class SplashView extends StatefulWidget {
  const SplashView({super.key});
  @override
  State<SplashView> createState() => _SplashViewState();
}

class _SplashViewState extends State<SplashView> {
  @override
  void initState() {
    super.initState();
    navigateToHome();
  }

  void detectDeviceType(BuildContext context) {
    final shortestSide = MediaQuery.of(context).size.shortestSide;
    Constants.isIpad = shortestSide > 600;
  }

  Future<void> navigateToHome() async {
    // Constants.getCurrentLocation();
    if (Prefs().getToken() != null) {
      final minimumWait = Future.delayed(const Duration(seconds: 3));
      await minimumWait;
      Get.offAllNamed(Routes.BASE_PAGE);
    } else {
      await Future.delayed(const Duration(seconds: 3), () {
        Get.offNamed(Routes.LOGIN);
      });
    }
  }

  @override
  Widget build(BuildContext context) {
    detectDeviceType(context);
    return Scaffold(
      backgroundColor: bgColor,
      body: Container(
        alignment: Alignment.center,
        height: height(),
        width: width(),
        decoration: const BoxDecoration(color: bgColor),
        child: Stack(
          children: [
            ClipRRect(
              borderRadius: BorderRadius.circular(getWidth(20)!),
              child: Image.asset(
                ImagePath.jazzPayLogo,
                scale: 3.8,
              )
                  .animate()
                  .scaleXY(
                    begin: 0.8,
                    end: 1,
                  )
                  .blurXY(
                    duration: const Duration(seconds: 1),
                    begin: 15,
                    end: 0,
                  ),
            ),
          ],
        ),
      ),
    );
  }
}


// smartseat splash screen
/*
class SplashView extends StatefulWidget {
  const SplashView({super.key});
  @override
  State<SplashView> createState() => _SplashViewState();
}

class _SplashViewState extends State<SplashView> {
  @override
  void initState() {
    super.initState();
    navigateToHome();
  }

  void detectDeviceType(BuildContext context) {
    final shortestSide = MediaQuery.of(context).size.shortestSide;
    Constants.isIpad = shortestSide > 600;
  }

  Future<void> navigateToHome() async {
    // Constants.getCurrentLocation();
    if (Prefs().getToken() != null) {
      final minimumWait = Future.delayed(const Duration(seconds: 3));
      await minimumWait;
      Get.offAllNamed(Routes.BASE_PAGE);
    } else {
      await Future.delayed(const Duration(seconds: 3), () {
        Get.offNamed(Routes.LOGIN);
      });
    }
  }

  @override
  Widget build(BuildContext context) {
    detectDeviceType(context);
    return Scaffold(
      backgroundColor: bgColor,
      body: Container(
        alignment: Alignment.center,
        height: height(),
        width: width(),
        decoration: const BoxDecoration(color: bgColor),
        child: Stack(
          alignment: Alignment.center,
          children: [
            Center(
              child: Text(
                'Smart Eats',
                style: TextStyle(
                  fontSize: getWidth(40),
                  fontWeight: FontWeight.bold,
                  color: Colors.white,
                  letterSpacing: 1.2,
                ),
              )
                  .animate()
                  .scaleXY(
                begin: 0.8,
                end: 1,
              )
                  .blurXY(
                duration: const Duration(seconds: 1),
                begin: 15,
                end: 0,
              ),
            ),
          ],
        ),
      ),
    );
  }
}
*/