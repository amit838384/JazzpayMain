// Flutter imports:
import 'package:flutter/material.dart';

class Bouncing extends StatefulWidget {
  final VoidCallback? onTap;
  final VoidCallback? onLongPress;
  final Widget? child;
  final Duration? duration;
  final bool? enableFeedback;

  const Bouncing({
    super.key,
    @required this.child,
    this.duration,
    @required this.onTap,
    this.onLongPress,
    this.enableFeedback,
  });

  @override
  BouncingState createState() => BouncingState();
}

class BouncingState extends State<Bouncing> with SingleTickerProviderStateMixin {
  late double _scale;
  late AnimationController _animate;

  VoidCallback? get onTap => widget.onTap;
  VoidCallback? get _onLongPress => widget.onLongPress;
  bool? get enableFeedback => widget.enableFeedback;

  Duration get userDuration => widget.duration ?? const Duration(milliseconds: 100);

  @override
  void initState() {
    //defining the controller
    _animate = AnimationController(
      vsync: this,
      duration: const Duration(milliseconds: 200),
      lowerBound: 0.0,
      upperBound: 0.1,
    )..addListener(() {
        setState(() {});
      });
    super.initState();
  }

  @override
  void dispose() {
    _animate.dispose();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    _scale = 1 - _animate.value;
    return InkWell(
        enableFeedback: enableFeedback ?? true,
        splashColor: Colors.transparent,
        highlightColor: Colors.transparent,
        onTap: _onTap,
        onLongPress: _onLongPress,
        child: Transform.scale(
          scale: _scale,
          child: widget.child,
        ));
  }

  void _onTap() {
    if (onTap != null) {
      _animate.forward();

      Future.delayed(userDuration, () {
        _animate.reverse();
        onTap?.call();
      });
    }
  }
}
