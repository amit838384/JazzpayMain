import 'dart:ui';
///// Jazz Pay

const Color bgColor = Color(0xFF9B203E);
Color lightBgColor = const Color(0xFF9B203E).withValues(alpha: .1);
const Color buttonColor = Color(0xFF78001D);
const Color textColor = Color(0xFFF8F8FF);
const Color yelloColor = Color(0xFFF2D441);

//////
const Color whiteColor = Color(0xFFFFFFFF);
Color blackColor = const Color(0xFF000000);
Color blueColor = const Color(0xFF136DC1);

// const Color bgColor = Color(0xFF2E1E66);
const Color hintColor = Color(0xFFBDBDBD);
// const Color buttonColor = Color(0xFFE91E63);
const Color textFieldBorderColor = Color(0xFF9575CD);
const Color cardColor = Color(0xFF3E2B7A);
Color primaryColor = const Color(0xFFFF3101);
Color greenColor = const Color(0xFF61B604);
Color greenSuccessColor = const Color(0xFF2AA952);
Color greenLightColor = const Color(0xFFA8C08E);
Color processingColor = const Color(0xFFFAE100);
Color redColor = const Color(0xFFDB0B0B);
Color disPriceColor = const Color(0xFF666666);
Color fillColor = const Color(0xFFF2F2F2);
Color borderLineColor = const Color(0xFFE1E2E7);
Color thickBorderColor = const Color(0xFF191919);
Color greyColor = const Color(0xFF565656);
// Color buttonColor = const Color(0xFF3E3B3B);
Color orangeColor = const Color(0xFFFFA439);
Color lightGray = const Color(0xFFEBEBEB);

Color hexToColor(String hex) {
  hex = hex.replaceAll('#', '');
  if (hex.length == 6) {
    hex = 'FF$hex';
  }
  return Color(int.parse(hex, radix: 16));
}
