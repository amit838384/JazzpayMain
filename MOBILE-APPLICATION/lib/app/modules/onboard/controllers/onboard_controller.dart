import 'package:jazz_smart_pay/app/utils/index.dart';
import 'package:jazz_smart_pay/exports.dart';

class OnboardController extends GetxController {
  final imageList = [
    ImagePath.onBoardOne,
    ImagePath.onBoardTwo,
    ImagePath.onBoardThree
  ];
  final titleList = [
    'Choose Products',
    'Generate Your Logo ',
    'Order Your Products with Brand Identity'
  ];
  final pageCount = ['1/3', '2/3 ', '3/3'];
  final subTitleList = [
    'Shop curated collections of top-rated items, perfect for any occasion or need, all at great prices.',
    'AI logo generator that allows you to input your brand values and design preferences to create a professional logo.',
    'Order now to showcase your iconic logo on premium products designed for the bold, stylish, and forward-thinking.',
  ];
  late PageController pageController;
  final RxInt currentPage = 0.obs;

  bool get isLastPage => currentPage.value == imageList.length - 1;

  @override
  void onInit() {
    pageController = PageController(viewportFraction: 1, keepPage: true);
    super.onInit();
  }

  @override
  void onClose() {
    pageController.dispose();
    super.onClose();
  }

  void onPageNext() {
    if (!isLastPage) {
      pageController.nextPage(
        duration: 300.milliseconds,
        curve: Curves.ease,
      );
    }
  }

  void onPagePrev() {
    if (currentPage.value > 0) {
      pageController.previousPage(
        duration: 300.milliseconds,
        curve: Curves.ease,
      );
    }
  }

  void updateCurrentPage(int index) {
    currentPage.value = index;
  }
}
