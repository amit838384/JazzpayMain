import '../../exports.dart';
import '../utils/app_const_colors.dart';
import '../utils/app_size.dart';
import 'app_text.dart';

class StatusButton extends StatelessWidget {
  final String text;
  final Color? color;
  final Color? txtColor;
  const StatusButton(
      {super.key, required this.text, this.color, this.txtColor});

  @override
  Widget build(BuildContext context) {
    return Container(
      padding: EdgeInsets.symmetric(
          horizontal: getWidth(16)!, vertical: getWidth(6)!),
      decoration: BoxDecoration(
        color: color ?? greenSuccessColor,
        borderRadius: BorderRadius.circular(100),
      ),
      child: AppText.smallParagraph(text,
          fontWeight: FontWeight.w500, color: txtColor ?? textColor),
    );
  }
}
