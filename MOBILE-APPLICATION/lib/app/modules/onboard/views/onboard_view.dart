import 'package:jazz_smart_pay/app/custom_widget/app_text.dart';
import 'package:jazz_smart_pay/app/utils/app_const_colors.dart';
import 'package:jazz_smart_pay/app/utils/index.dart';
import 'package:jazz_smart_pay/exports.dart';
import 'package:smooth_page_indicator/smooth_page_indicator.dart';

import '../controllers/onboard_controller.dart';

class OnboardView extends GetView<OnboardController> {
  const OnboardView({super.key});

  @override
  Widget build(BuildContext context) {
    return WillPopScope(
      onWillPop: () async => false,
      child: Scaffold(
        body: SafeArea(
          child: Column(
            children: [
              Expanded(
                child: PageView.builder(
                  controller: controller.pageController,
                  itemCount: controller.imageList.length,
                  onPageChanged: controller.updateCurrentPage,
                  itemBuilder: (context, index) {
                    return Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        AppText.heading1(controller.pageCount[index]),
                        const Spacer(),
                        Center(
                          child: SizedBox(
                            height: getWidth(400),
                            width: context.width * 0.89,
                            child: Image.asset(
                              controller.imageList[index],
                              fit: BoxFit.contain,
                            ),
                          ),
                        ),
                        Center(
                          child: AppText.heading2(
                            controller.titleList[index],
                            getfontSize: 28,
                            fontWeight: FontWeight.w800,
                            textAlign: TextAlign.center,
                          ),
                        ),
                        const Gap(16),
                        Padding(
                          padding:
                              EdgeInsets.symmetric(horizontal: getWidth(16)!),
                          child: AppText.heading2(
                            controller.subTitleList[index],
                            getfontSize: 20,
                            textAlign: TextAlign.center,
                          ),
                        ),
                        const Spacer(),
                      ],
                    );
                  },
                ),
              ),
              const Gap(32),
              Padding(
                padding: EdgeInsets.symmetric(horizontal: getWidth(16)!),
                child: Obx(() => Row(
                      children: [
                        if (controller.currentPage.value > 0)
                          Expanded(
                            child: TextButton(
                              onPressed: controller.onPagePrev,
                              child: AppText.heading3('Prev'),
                            ),
                          )
                        else
                          const Spacer(),
                        const Gap(20),
                        SmoothPageIndicator(
                          controller: controller.pageController,
                          count: controller.imageList.length,
                          effect: const ExpandingDotsEffect(
                            activeDotColor: whiteColor,
                            dotHeight: 12,
                            dotWidth: 10,
                          ),
                        ),
                        const Gap(20),
                        Expanded(
                          child: TextButton(
                            onPressed: () {
                              if (controller.isLastPage) {
                                Get.offAllNamed(Routes.LOGIN);
                              } else {
                                controller.onPageNext();
                              }
                            },
                            child: AppText.heading3(
                              controller.isLastPage ? 'Get Started' : 'Next',
                            ),
                          ),
                        ),
                      ],
                    )),
              ),
            ],
          ),
        ),
      ),
    );
  }
}
