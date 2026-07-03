import 'dart:ui';
import 'package:flutter/material.dart';

import 'constant_vars.dart';

double? width() => Constants.screenSize!.width;

double? height() => Constants.screenSize!.height;

FlutterView _view = WidgetsBinding.instance.platformDispatcher.views.first;

double? getFontSize(double size) {
  var value = (_view.physicalSize.width) / (_view.devicePixelRatio);
  final resultFont = value * (size) / 500;
  if (Constants.screenSize!.width < 550) {
    return resultFont;
  } else {
    return size;
  }
}

double? getWidth(double size) {
  var value = _view.physicalSize.width / _view.devicePixelRatio;
  final resultWidth = value * (size) / 500;

  if (Constants.screenSize!.width < 550) {
    return resultWidth;
  } else {
    return size * 0.85;
  }
}

double? getHeight(double size) {
  var value = (_view.physicalSize.height) / (_view.devicePixelRatio);
  final resultHeight = value * (size) / 1000;
  if (Constants.screenSize!.width < 550) {
    return resultHeight;
  } else {
    return size * 0.85;
  }
}
