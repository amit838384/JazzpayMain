import 'package:flutter/material.dart';
import 'package:gap/gap.dart';
import 'package:get/get.dart';

import 'package:flutter/services.dart';
import 'package:jazz_smart_pay/app/custom_widget/fill_container.dart';
import 'package:jazz_smart_pay/app/custom_widget/loading_circular_component.dart';
import 'package:jazz_smart_pay/app/custom_widget/my_app_bar.dart';
import 'package:jazz_smart_pay/app/utils/app_const_colors.dart';

import '../../../custom_widget/app_text.dart';
import '../../../custom_widget/cached_image.dart';
import '../../../routes/app_pages.dart';
import '../../../utils/app_size.dart';
import '../../../utils/currency_util.dart';
import '../controllers/search_controller.dart';

class SearchView extends GetView<SearchLogic> {
  const SearchView({super.key});
  @override
  Widget build(BuildContext context) {
    return GetBuilder<SearchLogic>(
        init: SearchLogic(),
        builder: (logic) {
          return GestureDetector(
            onTap: () => FocusScope.of(context).unfocus(),
            child: Scaffold(
                appBar: myAppBar(title: "Search"),
                backgroundColor: bgColor,
                body: SingleChildScrollView(
                  padding: EdgeInsets.all(getWidth(12)!),
                  child: Column(
                    children: [
                      CommentsSearchField(
                        controller: logic.searchController,
                        hintText: "Search by products name",
                        onChanged: logic.onSearchTextChanged,
                        autofocus: true,
                      ),
                      Gap(getWidth(24)!),
                      if (logic.isLoading == true)
                        Column(
                          children: [
                            Gap(getWidth(300)!),
                            const LoadingCircularComponent(),
                          ],
                        ),
                      if (logic.isLoading == false)
                        if (logic.searchRes != null)
                          ...List.generate(
                            logic.searchRes!['data']['products'].length,
                            (index) {
                              var product =
                                  logic.searchRes!['data']['products'][index];
                              return GestureDetector(
                                onTap: () {
                                  Get.offNamed(
                                    Routes.PRODUCT,
                                    arguments: {
                                      "id": product['id'],
                                      "name": product['productname']
                                    },
                                  );
                                },
                                child: FillContainer(
                                  margin:
                                      EdgeInsets.only(bottom: getWidth(20)!),
                                  padding: EdgeInsets.all(getWidth(12)!),
                                  child: Row(
                                    children: [
                                      ClipRRect(
                                        borderRadius: BorderRadius.circular(
                                            getWidth(12)!),
                                        child: CacheImage(
                                          path: product['frontimage'],
                                          height: getWidth(100),
                                          width: getWidth(100),
                                          fit: BoxFit.cover,
                                          alignment: Alignment.topCenter,
                                        ),
                                      ),
                                      Gap(getWidth(20)!),
                                      Expanded(
                                        child: Column(
                                          crossAxisAlignment:
                                              CrossAxisAlignment.start,
                                          mainAxisSize: MainAxisSize.min,
                                          children: [
                                            AppText.heading3(
                                              product['title'],
                                              getfontSize: 20,
                                              maxLines: 2,
                                            ),
                                            Row(
                                              children: [
                                                AppText.paragraph(
                                                  currency(double.parse(
                                                      product['prices']
                                                          .toString())),
                                                  fontWeight: FontWeight.w700,
                                                  color: hintColor,
                                                  getfontSize: 18,
                                                  decoration: TextDecoration
                                                      .lineThrough,
                                                ),
                                                Gap(getWidth(12)!),
                                                AppText.paragraph(
                                                  currency(double.parse(
                                                      product['discount']
                                                          .toString())),
                                                  fontWeight: FontWeight.w700,
                                                  color: primaryColor,
                                                  getfontSize: 20,
                                                ),
                                              ],
                                            ),
                                            Gap(getWidth(10)!),
                                          ],
                                        ),
                                      ),
                                    ],
                                  ),
                                ),
                              );
                            },
                          ),
                      Gap(getWidth(50)!),
                    ],
                  ),
                )),
          );
        });
  }
}

// Flutter imports:

// Project imports:

class CommentsSearchField extends StatelessWidget {
  final void Function()? suffixOnTap;
  final bool showSuffixIcon;
  final String hintText;
  final void Function(String)? onChanged;
  final void Function(String)? onFieldSubmitted;
  final TextEditingController? controller;
  final double? hintFontSize;
  final double? textFontSize;
  final IconData? suffixIconData;
  final bool readOnly;
  final void Function()? onTap;
  final void Function()? onFieldTap;
  final String? Function(String?)? validator;
  final TextInputType? keyboardType;
  final bool isPassword;
  final List<TextInputFormatter>? inputFormatters;
  final String? initialValue;
  final String? prefixText;
  final String? suffixText;
  final MaxLengthEnforcement? maxLengthEnforcement;
  final int? maxLength;
  final Color? borderColor;
  final TextCapitalization textCapitalization;
  final TextAlign textAlign;
  final bool autofocus;
  final FocusNode? focusNode;
  final void Function()? onEditingComplete;
  const CommentsSearchField({
    super.key,
    this.suffixOnTap,
    this.showSuffixIcon = true,
    this.hintText = "Search products",
    this.onChanged,
    this.onFieldSubmitted,
    this.controller,
    this.hintFontSize,
    this.suffixIconData,
    this.readOnly = false,
    this.onTap,
    this.validator,
    this.keyboardType,
    this.isPassword = false,
    this.inputFormatters,
    this.initialValue,
    this.prefixText,
    this.suffixText,
    this.maxLengthEnforcement,
    this.maxLength,
    this.borderColor,
    this.textFontSize,
    this.textCapitalization = TextCapitalization.none,
    this.textAlign = TextAlign.start,
    this.onFieldTap,
    this.autofocus = false,
    this.focusNode,
    this.onEditingComplete,
  });

  @override
  Widget build(BuildContext context) {
    final double borderRadius = getWidth(20)!;
    final outlineBorder = OutlineInputBorder(
      borderRadius: BorderRadius.circular(borderRadius),
      borderSide: BorderSide(
        color: Get.isDarkMode ? hintColor! : Colors.transparent,
        width: getWidth(1)!,
      ),
    );
    final errorBorder = OutlineInputBorder(
      borderRadius: BorderRadius.circular(borderRadius),
      borderSide: BorderSide(
        color: Theme.of(context).colorScheme.error,
        width: getWidth(1)!,
      ),
    );
    final errorFocusedBorder = OutlineInputBorder(
      borderRadius: BorderRadius.circular(borderRadius),
      borderSide: BorderSide(
        color: Theme.of(context).colorScheme.error,
        width: getWidth(1)!,
      ),
    );
    return TextFormField(
      focusNode: focusNode,
      autofocus: autofocus,
      controller: controller,
      cursorColor: primaryColor,
      textAlign: textAlign,
      textInputAction: TextInputAction.search,
      textCapitalization: textCapitalization,
      maxLengthEnforcement: maxLengthEnforcement,
      maxLength: maxLength,
      initialValue: initialValue,
      obscureText: isPassword,
      readOnly: readOnly,
      onTap: onFieldTap,
      onEditingComplete: onEditingComplete,
      style: TextStyle(
        fontSize: getFontSize(17),
        fontWeight: FontWeight.w600,
      ),
      decoration: InputDecoration(
        contentPadding: EdgeInsets.symmetric(
          vertical: getWidth(10)!,
          horizontal: getWidth(16)!,
        ),
        hintText: hintText,
        hintStyle: TextStyle(
          fontSize: getFontSize(17),
          color: hintColor,
          fontWeight: FontWeight.w600,
        ),
        border: outlineBorder,
        enabledBorder: outlineBorder,
        focusedBorder: outlineBorder,
        disabledBorder: outlineBorder,
        errorBorder: errorBorder,
        suffixIcon: Icon(Icons.search, color: hintColor),
        fillColor: fillColor,
        filled: true,
      ),
      onChanged: onChanged,
      onFieldSubmitted: onFieldSubmitted,
    );
  }
}
