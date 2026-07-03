import 'package:jazz_smart_pay/app/utils/app_const_colors.dart';
import 'package:jazz_smart_pay/exports.dart';
import '../utils/app_size.dart';

Divider appDivider({
  double? getIndent,
  double? endIndent,
  double? thickness,
  double? height,
  Color? color,
}) {
  return Divider(
    height: height,
    thickness: getWidth(thickness ?? 1),
    indent: getWidth(getIndent ?? 0),
    endIndent: endIndent,
    color: color ?? fillColor,
  );
}

class ThickDivider extends StatelessWidget {
  const ThickDivider({super.key});

  @override
  Widget build(BuildContext context) {
    return Column(
      children: [
        SizedBox(height: getWidth(20)),
        Divider(
          thickness: getWidth(12),
          color: fillColor,
        ),
        SizedBox(height: getWidth(20)),
      ],
    );
  }
}
