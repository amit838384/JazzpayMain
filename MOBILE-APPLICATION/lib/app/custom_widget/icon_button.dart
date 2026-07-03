import 'package:jazz_smart_pay/app/custom_widget/bouncing_button.dart';

import '../../exports.dart';
import '../utils/app_const_colors.dart';
import '../utils/app_size.dart';
import 'fill_container.dart';

class CustomIconButton extends StatelessWidget {
  final void Function() onTap;
  final String icon;
  const CustomIconButton({super.key, required this.onTap, required this.icon});

  @override
  Widget build(BuildContext context) {
    return Bouncing(
      onTap: onTap,
      child: FillContainer(
        padding: EdgeInsets.all(getWidth(6)!),
        backgroundColor: textColor,
        child: Image.asset(
          icon,
          color: bgColor,
          height: getWidth(36),
          width: getWidth(36),
        ),
      ),
    );
  }
}
