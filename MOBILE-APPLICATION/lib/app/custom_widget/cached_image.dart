import 'package:cached_network_image/cached_network_image.dart';
import 'package:flutter/material.dart';
import 'package:jazz_smart_pay/app/utils/app_const_colors.dart';
import '../utils/constant_vars.dart';

class CacheImage extends StatelessWidget {
  const CacheImage({
    super.key,
    this.path,
    this.fit = BoxFit.contain,
    this.errorWidget,
    this.height,
    this.width,
    this.backgroundColor,
    this.errorWidgetBuilder,
    this.alignment,
  });
  final String? path;
  final BoxFit fit;
  final Widget? errorWidget;
  final double? height;
  final double? width;
  final Color? backgroundColor;
  final Widget Function(BuildContext, String, dynamic)? errorWidgetBuilder;
  final AlignmentGeometry? alignment;

  Widget customNetworkImage(String? path, {BoxFit fit = BoxFit.contain}) {
    return CachedNetworkImage(
      fit: fit,
      imageUrl: path != null && path != "" ? path : Constants.noImage,
      imageBuilder: (context, imageProvider) => Container(
        height: height,
        width: width,
        decoration: BoxDecoration(
          image: DecorationImage(
            image: imageProvider,
            fit: fit,
            alignment: alignment ?? Alignment.center,
          ),
        ),
      ),
      placeholderFadeInDuration: const Duration(milliseconds: 500),
      placeholder: (context, url) => Container(
        height: height,
        width: width,
        color: fillColor,
      ),
      errorWidget: errorWidgetBuilder ??
          (context, url, error) =>
              errorWidget ??
              Container(
                height: height,
                width: width,
                color: backgroundColor ?? const Color(0xffeeeeee),
                child: Image.network(Constants.noImage, fit: BoxFit.cover),
              ),
      memCacheHeight: height?.toInt(),
      memCacheWidth: width?.toInt(),
    );
  }

  @override
  Widget build(BuildContext context) {
    return customNetworkImage(path, fit: fit);
  }
}
