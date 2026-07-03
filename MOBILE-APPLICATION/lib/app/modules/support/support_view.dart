import 'package:jazz_smart_pay/app/custom_widget/app_text.dart';
import 'package:jazz_smart_pay/app/custom_widget/my_app_bar.dart';
import 'package:jazz_smart_pay/app/utils/app_const_colors.dart';
import 'package:jazz_smart_pay/app/utils/index.dart';

import '../../../exports.dart';

class SupportView extends StatelessWidget {
  const SupportView({super.key});

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: bgColor,
      appBar: myAppBar(title: "Contact Us"),
      body: Padding(
        padding: EdgeInsets.all(getWidth(30)!),
        child: Center(
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.center,
            children: [
              AppText.heading1("App support", color: yelloColor),
              Gap(getWidth(10)!),
              AppText.heading2("WhatsApp : +974-5560-8088", color: textColor),
              AppText.heading2(
                "Email : jeronimo@jazzgroup.com.qa",
                color: textColor,
              ),
              Gap(getWidth(40)!),
              AppText.heading1("Cafeteria queries", color: yelloColor),
              Gap(getWidth(10)!),
              AppText.heading2("WhatsApp : +974 3357 0999", color: textColor),
              AppText.heading2(
                "Email : info@jazzgroup.com.qa",
                color: textColor,
              ),
            ],
          ),
        ),
      ),
    );
  }
}
