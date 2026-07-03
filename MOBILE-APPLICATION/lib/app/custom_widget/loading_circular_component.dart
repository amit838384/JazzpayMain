// Flutter imports:
import 'package:flutter/cupertino.dart';
import 'package:jazz_smart_pay/app/utils/app_const_colors.dart';

// Project imports:

import '../utils/app_size.dart';

class LoadingCircularComponent extends StatelessWidget {
  final Color? indicatorColor;
  final double? getSize;
  const LoadingCircularComponent(
      {super.key, this.indicatorColor, this.getSize});

  @override
  Widget build(BuildContext context) {
    return Center(
      child: CupertinoActivityIndicator(
        color: indicatorColor ?? whiteColor,
        radius: getWidth(getSize ?? 14)!,
        animating: true,
      ),
    );
  }
}
