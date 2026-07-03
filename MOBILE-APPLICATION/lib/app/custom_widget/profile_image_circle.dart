// Flutter imports:
import 'package:cached_network_image/cached_network_image.dart';
import 'package:flutter/cupertino.dart';
import 'package:flutter/material.dart';
// Package imports:
import '../utils/app_const_colors.dart';
import '../utils/app_size.dart';
import '../utils/constant_vars.dart';
import 'gradient_letter.dart';

Widget profileImageCircle({
  required String? imageUrl,
  required String? name,
  bool showCameraIcon = false,
  bool showBorder = true,
  Color? borderColor,
  double? circleSize,
  double borderWidth = 1.0,
  void Function()? onTap,
  Object? heroTag,
  double? getLoadingSize,
}) {
  double? size = getWidth(circleSize ?? 120);
  return imageUrl != "" && imageUrl != null
      ? Container(
          height: size,
          width: size,
          decoration: const BoxDecoration(
            shape: BoxShape.circle,
            color: whiteColor,
          ),
          child: GestureDetector(
            onTap: onTap,
            child: Stack(
              clipBehavior: Clip.none,
              alignment: Alignment.center,
              children: [
                CachedNetworkImage(
                  imageUrl: imageUrl,
                  imageBuilder: (context, imageProvider) {
                    return Container(
                      decoration: BoxDecoration(
                        image: DecorationImage(
                          image: imageProvider,
                          fit: BoxFit.cover,
                        ),
                        color: whiteColor,
                        shape: BoxShape.circle,
                      ),
                    );
                  },
                  placeholder: (context, url) => Container(
                    height: size,
                    width: size,
                    decoration: const BoxDecoration(
                      color: Color(0xffeeeeee),
                      shape: BoxShape.circle,
                    ),
                  ),
                  errorWidget: (context, value, error) {
                    return name != "" && name != null
                        ? GestureDetector(
                            onTap: onTap,
                            child: GradientLetterPlaceholder(
                              name: name,
                              size: size,
                              fontSize: size! / 1.8,
                            ),
                          )
                        : Container(
                            decoration: const BoxDecoration(
                              image: DecorationImage(
                                // image: NetworkImage(Constants.noImage),
                                image: NetworkImage(""),
                                fit: BoxFit.cover,
                              ),
                              color: whiteColor,
                              shape: BoxShape.circle,
                            ),
                          );
                  },
                ),
                Visibility(
                  visible: showCameraIcon,
                  child: CircleAvatar(
                    radius: getWidth(20),
                    backgroundColor: blackColor.withOpacity(0.5),
                    child: Icon(
                      CupertinoIcons.camera,
                      size: getWidth(18),
                      color: whiteColor,
                    ),
                  ),
                ),
              ],
            ),
          ),
        )
      : name != "" && name != null
          ? GestureDetector(
              onTap: onTap,
              child: GradientLetterPlaceholder(
                name: name,
                size: size,
                fontSize: size! / 1.8,
              ),
            )
          : Icon(
              CupertinoIcons.person_crop_circle,
              color: whiteColor,
              size: size,
            );
}
