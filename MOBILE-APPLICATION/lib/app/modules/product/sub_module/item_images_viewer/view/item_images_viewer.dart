import 'package:flutter/material.dart';
import 'package:carousel_slider/carousel_slider.dart';
import 'package:flutter_animate/flutter_animate.dart';
import 'package:jazz_smart_pay/app/utils/app_const_colors.dart';
import 'package:get/get.dart';
import 'package:photo_view/photo_view.dart';
import 'package:photo_view/photo_view_gallery.dart';
import '../../../../../custom_widget/bouncing_button.dart';
import '../../../../../custom_widget/loading_circular_component.dart';
import '../../../../../custom_widget/my_app_bar.dart';
import '../../../../../utils/app_size.dart';
import '../../../../../utils/constant_vars.dart';
import '../logic/item_images_viewer_logic.dart';

class ItemImagesViewer extends StatelessWidget {
  const ItemImagesViewer({super.key});

  @override
  Widget build(BuildContext context) {
    return Scaffold(
        appBar: myAppBar(),
        backgroundColor: bgColor,
        body: GetBuilder<ItemImagesViewerLogic>(
          init: ItemImagesViewerLogic(),
          builder: (logic) {
            return logic.isLoading
                ? const Center(child: LoadingCircularComponent())
                : SafeArea(
                    child: Column(
                      children: [
                        Expanded(
                          flex: 7,
                          child: CarouselSlider.builder(
                            carouselController: logic.carouselController,
                            itemCount: logic.images.isNotEmpty
                                ? logic.images.length
                                : 1,
                            itemBuilder: (context, index, realIndex) {
                              return GestureDetector(
                                onDoubleTapDown: logic.handleDoubleTapDown,
                                onDoubleTap: logic.handleDoubleTap,
                                child: PhotoViewGallery(
                                  pageOptions: [
                                    PhotoViewGalleryPageOptions(
                                      imageProvider:
                                          NetworkImage(logic.images[index]!),
                                      minScale:
                                          PhotoViewComputedScale.contained,
                                      maxScale:
                                          PhotoViewComputedScale.covered * 2,
                                    )
                                  ],
                                  scrollPhysics: const BouncingScrollPhysics(),
                                  loadingBuilder: (context, event) =>
                                      const LoadingCircularComponent(),
                                  backgroundDecoration: BoxDecoration(
                                    color: bgColor,
                                  ),
                                ),
                              );
                            },
                            options: CarouselOptions(
                              height: height(),
                              enlargeCenterPage: false,
                              viewportFraction: 1,
                              enableInfiniteScroll: true,
                              onPageChanged: (index, reason) {
                                logic.viewIndex = index;
                                logic.transformationController.value =
                                    Matrix4.identity();
                                logic.update();
                              },
                              initialPage: 0,
                            ),
                          ),
                        ),
                        Expanded(
                          flex: 1,
                          child: ListView.builder(
                            scrollDirection: Axis.horizontal,
                            shrinkWrap: true,
                            itemCount: logic.images.length,
                            itemBuilder: (_, index) {
                              return Column(
                                children: [
                                  Bouncing(
                                    onTap: () {
                                      logic.carouselController
                                          .animateToPage(index);
                                    },
                                    child: Container(
                                      height: getWidth(80),
                                      width: getWidth(80),
                                      margin: EdgeInsets.symmetric(
                                          horizontal: getWidth(8)!),
                                      decoration: BoxDecoration(
                                        borderRadius:
                                            BorderRadius.circular(getWidth(8)!),
                                        // border: Border.all(color: blackColor),
                                      ),
                                      child: ClipRRect(
                                        borderRadius:
                                            BorderRadius.circular(getWidth(7)!),
                                        child: Image.network(
                                          logic.images[index] ??
                                              Constants.noImage,
                                          fit: BoxFit.fitWidth,
                                          filterQuality: FilterQuality.low,
                                          gaplessPlayback: true,
                                          loadingBuilder:
                                              (_, child, loadingProgress) {
                                            if (loadingProgress == null) {
                                              return child;
                                            }
                                            return LoadingCircularComponent(
                                                getSize: getWidth(10));
                                          },
                                        ),
                                      ),
                                    ),
                                  ),
                                  if (logic.viewIndex == index)
                                    Container(
                                      margin:
                                          EdgeInsets.only(top: getWidth(10)!),
                                      height: getWidth(4),
                                      width: getWidth(70),
                                      decoration: BoxDecoration(
                                        borderRadius: BorderRadius.circular(
                                            getWidth(10)!),
                                        color: whiteColor,
                                      ),
                                    ).animate().shakeX(),
                                ],
                              );
                            },
                          ),
                        ),
                      ],
                    ),
                  );
          },
        ));
  }
}
