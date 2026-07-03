import '../../exports.dart';

class TabPadding extends StatelessWidget {
  const TabPadding({
    super.key,
    required this.child,
    this.paddingPercent,
  });
  final Widget child;

  ///  The percent of the screen width to use as tab padding on each side.
  ///
  /// Which is from 0 to 1
  final double? paddingPercent;

  @override
  Widget build(BuildContext context) {
    final shortestSide = context.mediaQueryShortestSide;
    return Padding(
      padding: shortestSide > 600
          ? EdgeInsets.symmetric(
              horizontal: context.width * (paddingPercent ?? 0.18),
            )
          : EdgeInsets.zero,
      child: child,
    );
  }
}

class TabPaddingEnd extends StatelessWidget {
  const TabPaddingEnd({
    super.key,
    required this.child,
    this.paddingPercent,
  });
  final Widget child;

  ///  The percent of the screen width to use as tab padding on each side.
  ///
  /// Which is from 0 to 1
  final double? paddingPercent;

  @override
  Widget build(BuildContext context) {
    final shortestSide = context.mediaQueryShortestSide;
    return Padding(
      padding: shortestSide > 600
          ? EdgeInsets.only(
              right: context.width * (paddingPercent ?? 0.36),
            )
          : EdgeInsets.zero,
      child: child,
    );
  }
}

class TabPaddingStart extends StatelessWidget {
  const TabPaddingStart({
    super.key,
    required this.child,
    this.paddingPercent,
  });
  final Widget child;

  ///  The percent of the screen width to use as tab padding on each side.
  ///
  /// Which is from 0 to 1
  final double? paddingPercent;

  @override
  Widget build(BuildContext context) {
    final shortestSide = context.mediaQueryShortestSide;
    return Padding(
      padding: shortestSide > 600
          ? EdgeInsets.symmetric(
              horizontal: context.width * (paddingPercent ?? 0.18),
            )
          : EdgeInsets.zero,
      child: child,
    );
  }
}
