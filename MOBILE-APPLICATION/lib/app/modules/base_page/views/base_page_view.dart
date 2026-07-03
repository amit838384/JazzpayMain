import 'package:flutter_localization/flutter_localization.dart';
import 'package:jazz_smart_pay/app/custom_widget/loading_circular_component.dart';
import 'package:jazz_smart_pay/app/utils/app_const_colors.dart';
import 'package:jazz_smart_pay/app/utils/index.dart';
import 'package:jazz_smart_pay/exports.dart';
import '../controllers/base_page_controller.dart';

class BasePageView extends GetView<BasePageController> {
  const BasePageView({super.key});
  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: textColor,
      body: Obx(
        () => controller.isLoading.value == true
            ? const Center(
                child: LoadingCircularComponent(indicatorColor: bgColor))
            : Center(
                child: controller.widgetOptions
                    .elementAt(controller.selectedIndex.value),
              ),
      ),
      bottomNavigationBar: Obx(
        () => Container(
          decoration: BoxDecoration(
            color: bgColor,
            boxShadow: [
              BoxShadow(
                color: lightBgColor,
                blurRadius: getWidth(8)!,
                offset: const Offset(0, -2), // upward shadow
              ),
            ],
          ),
          child: BottomNavigationBar(
            type: BottomNavigationBarType.fixed,
            selectedItemColor: bgColor,
            unselectedItemColor: bgColor.withValues(alpha: .5),
            selectedLabelStyle: TextStyle(
              fontSize: getFontSize(16),
              fontWeight: FontWeight.w600,
            ),
            unselectedLabelStyle: TextStyle(
              fontSize: getFontSize(16),
              fontWeight: FontWeight.w600,
            ),
            backgroundColor: textColor,
            elevation: getWidth(10),
            items: <BottomNavigationBarItem>[
              BottomNavigationBarItem(
                icon: SizedBox(
                  height: getWidth(30),
                  width: getWidth(30),
                  child: Image.asset(
                    ImagePath.home,
                    color: bgColor.withValues(alpha: .5),
                  ),
                ),
                activeIcon: SizedBox(
                  height: getWidth(30),
                  width: getWidth(30),
                  child: Image.asset(ImagePath.home, color: bgColor),
                ),
                label: "home".getString(context),
              ),
              BottomNavigationBarItem(
                icon: SizedBox(
                  height: getWidth(30),
                  width: getWidth(30),
                  child: Image.asset(ImagePath.user,
                      color: bgColor.withValues(alpha: .5)),
                ),
                activeIcon: SizedBox(
                  height: getWidth(30),
                  width: getWidth(30),
                  child: Image.asset(ImagePath.user, color: bgColor),
                ),
                label: "profile".getString(context),
              ),
            ],
            currentIndex: controller.selectedIndex.value,
            onTap: controller.onItemTapped,
            showUnselectedLabels: true,
            showSelectedLabels: true,
            mouseCursor: SystemMouseCursors.none,
          ),
        ),
      ),
    );
  }
}
