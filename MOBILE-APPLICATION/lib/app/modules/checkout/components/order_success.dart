import 'package:flutter_localization/flutter_localization.dart';
import 'package:jazz_smart_pay/app/custom_widget/app_text.dart';
import 'package:jazz_smart_pay/app/custom_widget/primary_button.dart';
import 'package:jazz_smart_pay/app/utils/app_const_colors.dart';
import 'package:jazz_smart_pay/app/utils/app_size.dart';
import 'package:jazz_smart_pay/app/utils/image_path.dart';

import '../../../../exports.dart';
import '../../../custom_widget/cached_image.dart';
import '../../../custom_widget/fill_container.dart';
import '../../../utils/constant_vars.dart';

class OrderSuccess extends StatelessWidget {
  final dynamic order;
  const OrderSuccess({super.key, required this.order});

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: textColor,
      body: WillPopScope(
        onWillPop: () {
          return Future.value(false);
        },
        child: SafeArea(
          child: SingleChildScrollView(
            padding: EdgeInsets.all(getWidth(20)!),
            child: Column(
              mainAxisAlignment: MainAxisAlignment.center,
              children: [
                Gap(getWidth(20)!),
                Center(
                  child: Image.asset(
                    ImagePath.success,
                    height: getWidth(100),
                    width: getWidth(100),
                    color: bgColor,
                  ),
                ),
                Gap(getWidth(24)!),
                Center(
                  child: AppText.heading3(
                    "order_placed".getString(context),
                    color: bgColor,
                    getfontSize: 24,
                    fontWeight: FontWeight.w700,
                  ),
                ),
                Gap(getWidth(6)!),
                Center(
                  child: AppText.paragraph(
                    "order_placed_text".getString(context),
                    textAlign: TextAlign.center,
                    fontWeight: FontWeight.w600,
                    color: buttonColor,
                  ),
                ),
                Gap(getWidth(40)!),
                if (Constants.isIpad) _gridView(),
                if (!Constants.isIpad)
                  if (order['data'].isNotEmpty)
                    ...List.generate(
                      order?['data'].length,
                      (index) {
                        dynamic data = order?['data'][index];
                        return _productWidget(context, data, index);
                      },
                    ),
                Gap(getWidth(40)!),
              ],
            ),
          ),
        ),
      ),
      bottomNavigationBar: Padding(
        padding: EdgeInsets.all(getWidth(20)!),
        child: Column(
          mainAxisSize: MainAxisSize.min,
          children: [
            PrimaryButton(
              text: "crave_more".getString(context),
              onTap: () {
                Get.offAllNamed(Routes.BASE_PAGE);
              },
            ),
            Gap(getWidth(20)!),
          ],
        ),
      ),
    );
  }

  _gridView() {
    return GridView.builder(
      itemCount: order['data'].length,
      gridDelegate: const SliverGridDelegateWithFixedCrossAxisCount(
        crossAxisCount: 2, // Number of columns in grid
        crossAxisSpacing: 10,
        mainAxisSpacing: 10,
        childAspectRatio: 4, // Adjust for card shape
      ),
      shrinkWrap: true,
      physics:
          const NeverScrollableScrollPhysics(), // Prevents nested scrolling
      itemBuilder: (context, index) {
        dynamic data = order?['data'][index];
        return _productWidget(context, data, index);
      },
    );
  }

  _productWidget(BuildContext context, dynamic data, int index) {
    return FillContainer(
      padding: EdgeInsets.zero,
      margin: EdgeInsets.only(bottom: getWidth(20)!),
      child: Row(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          ClipRRect(
            borderRadius: BorderRadius.circular(getWidth(12)!),
            child: CacheImage(
              path:
                  "https://images.pexels.com/photos/376464/pexels-photo-376464.jpeg?cs=srgb&dl=pexels-ash-craig-122861-376464.jpg&fm=jpg",
              height: getWidth(95),
              fit: BoxFit.cover,
              width: getWidth(100),
            ),
          ),
          Gap(getWidth(20)!),
          Expanded(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Gap(getWidth(8)!),
                AppText.paragraph(data['dish_name'],
                    fontWeight: FontWeight.w700,
                    maxLines: 1,
                    overflow: TextOverflow.ellipsis),
                Gap(getWidth(4)!),
                AppText.paragraph(
                  "${data['student_name']}",
                  fontWeight: FontWeight.w500,
                ),
                Gap(getWidth(4)!),
                Row(
                  children: [
                    AppText.paragraph(
                      "QAR ${data['total_price']}",
                      fontWeight: FontWeight.w700,
                      color: buttonColor,
                    ),
                    const Spacer(),
                    AppText.paragraph(
                      "${data['date']}",
                      fontWeight: FontWeight.w600,
                      getfontSize: 14,
                    ),
                  ],
                ),
                Gap(getWidth(4)!),
              ],
            ),
          ),
          Gap(getWidth(20)!),
        ],
      ),
    );
  }
}
