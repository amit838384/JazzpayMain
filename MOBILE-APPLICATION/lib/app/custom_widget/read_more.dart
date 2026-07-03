import 'package:flutter/material.dart';

import 'expandable_text/expandable_text.dart';

class ReadMoreText extends StatelessWidget {
  final String text;
  final int? trimLines;
  final String? trimCollapsedText;
  final String? trimExpandedText;
  final Color? colorClickableText;
  final TextStyle? style;
  const ReadMoreText(
    this.text, {
    super.key,
    this.trimLines,
    this.trimCollapsedText,
    this.trimExpandedText,
    this.colorClickableText,
    this.style,
  });

  @override
  Widget build(BuildContext context) {
    return ExpandableText(
      text,
      expandText: trimCollapsedText ?? "show more",
      collapseText: trimExpandedText,
      maxLines: trimLines ?? 4,
      linkColor: colorClickableText,
      style: style,
      animation: true,
    );
  }
}
