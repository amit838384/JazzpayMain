// Flutter imports:
import 'package:flutter/material.dart';

// Package imports:
import 'package:carousel_slider/carousel_controller.dart';
import 'package:get/get.dart';

class ItemImagesViewerLogic extends GetxController
    with GetSingleTickerProviderStateMixin {
  bool isLoading = false;

  late List<dynamic> images;
  int viewIndex = 0;
  final carouselController = CarouselSliderController();

  late AnimationController _animationController;
  late Animation<Matrix4> _animation;
  final transformationController = TransformationController();
  TapDownDetails? _doubleTapDetails;

  @override
  void onInit() {
    images = Get.arguments;

    _animationController = AnimationController(
      vsync: this,
      duration: const Duration(milliseconds: 400),
    )..addListener(() {
        transformationController.value = _animation.value;
      });
    super.onInit();
  }

  @override
  void onClose() {
    transformationController.dispose();
    _animationController.dispose();
    super.onClose();
  }

  void handleDoubleTapDown(TapDownDetails details) {
    _doubleTapDetails = details;
    update();
  }

  void handleDoubleTap() {
    Matrix4 endMatrix;
    Offset position = _doubleTapDetails!.localPosition;

    if (transformationController.value != Matrix4.identity()) {
      endMatrix = Matrix4.identity();
    } else {
      endMatrix = Matrix4.identity()
        ..translate(-position.dx * 2, -position.dy * 2)
        ..scale(3.0);
    }

    _animation = Matrix4Tween(
      begin: transformationController.value,
      end: endMatrix,
    ).animate(
      CurveTween(curve: Curves.easeOut).animate(_animationController),
    );
    _animationController.forward(from: 0);
  }
}
