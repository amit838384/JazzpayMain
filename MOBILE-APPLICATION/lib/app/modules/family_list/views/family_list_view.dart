import 'package:flutter_localization/flutter_localization.dart';
import 'package:jazz_smart_pay/app/custom_widget/app_text.dart';
import 'package:jazz_smart_pay/app/custom_widget/bouncing_button.dart';
import 'package:jazz_smart_pay/app/custom_widget/cached_image.dart';
import 'package:jazz_smart_pay/app/custom_widget/fill_container.dart';
import 'package:jazz_smart_pay/app/custom_widget/my_app_bar.dart';
import 'package:jazz_smart_pay/app/utils/app_const_colors.dart';
import 'package:jazz_smart_pay/app/utils/constant_vars.dart';
import 'package:jazz_smart_pay/app/utils/index.dart';
import 'package:jazz_smart_pay/exports.dart';
import '../../../custom_widget/loading_circular_component.dart';
import '../../../models/student_response.dart';
import '../controllers/family_list_controller.dart';

class FamilyListView extends GetView<FamilyListLogic> {
  const FamilyListView({super.key});
  @override
  Widget build(BuildContext context) {
    return GetBuilder<FamilyListLogic>(
        init: FamilyListLogic(),
        builder: (logic) {
          return GestureDetector(
            onTap: () => FocusScope.of(context).unfocus(),
            child: Scaffold(
              appBar: myAppBar(
                title: 'family_members'.getString(context),
                color: textColor,
                contentColor: bgColor,
              ),
              backgroundColor: textColor,
              body: logic.isLoading || logic.isDetailsLoading || logic.isFoods
                  ? const Center(
                      child:
                          LoadingCircularComponent(indicatorColor: buttonColor))
                  : SingleChildScrollView(
                      padding: EdgeInsets.symmetric(horizontal: getWidth(20)!),
                      child: Column(
                        children: [
                          if (Constants.isIpad) _gridView(context, logic),
                          if (!Constants.isIpad)
                            Column(
                              children: List.generate(
                                logic.students.length,
                                (index) {
                                  var student = logic.students[index];
                                  return _familyMember(context, student, logic);
                                },
                              ),
                            ),
                        ],
                      ),
                    ),
              floatingActionButton: logic.isLoading || logic.isDetailsLoading
                  ? null
                  : FloatingActionButton(
                      onPressed: () async {
                        logic.student = null;
                        final value = await Get.toNamed(Routes.ADD_CHILD);
                        if (value != null && value) {
                          logic.getStudents();
                        }
                      },
                      backgroundColor: bgColor, // your custom white
                      splashColor: Colors.transparent,
                      elevation: 6, // Optional shadow
                      child: Image.asset(
                        ImagePath.plus,
                        height: getWidth(32),
                        width: getWidth(32),
                        color: textColor,
                      )),
            ),
          );
        });
  }

  _gridView(BuildContext context, FamilyListLogic logic) {
    return GridView.builder(
      itemCount: logic.students.length,
      gridDelegate: const SliverGridDelegateWithFixedCrossAxisCount(
        crossAxisCount: 2, // Number of columns in grid
        crossAxisSpacing: 10,
        mainAxisSpacing: 10,
        childAspectRatio: 2.2, // Adjust for card shape
      ),
      shrinkWrap: true,
      physics:
          const NeverScrollableScrollPhysics(), // Prevents nested scrolling
      itemBuilder: (context, index) {
        var student = logic.students[index];
        return _familyMember(context, student, logic);
      },
    );
  }

  Widget _familyMember(
      BuildContext context, StudentResponse student, FamilyListLogic logic) {
    final double imageSize = getWidth(60)!;
    final double spacing = getWidth(16)!;
    final double pillPaddingV = getWidth(6)!;
    final double pillPaddingH = spacing;
    final double borderRadius = getWidth(100)!;

    return FillContainer(
      backgroundColor: lightBgColor,
      margin: EdgeInsets.only(bottom: getWidth(20)!),
      child: Row(
        crossAxisAlignment: Constants.isIpad
            ? CrossAxisAlignment.start
            : CrossAxisAlignment.center,
        children: [
          student.image != null && student.image!.isNotEmpty
              ? ClipRRect(
                  borderRadius: BorderRadiusGeometry.circular(100),
                  child: CacheImage(
                    path: student.image,
                    height: imageSize,
                    width: imageSize,
                    fit: BoxFit.cover,
                  ),
                )
              : Image.asset(ImagePath.student,
                  height: imageSize, width: imageSize),
          Gap(spacing),
          Expanded(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Row(
                  mainAxisAlignment: MainAxisAlignment.spaceBetween,
                  children: [
                    AppText.heading2(student.name ?? "",
                        fontWeight: FontWeight.w700, getfontSize: 18),
                    Container(
                      padding: EdgeInsets.symmetric(
                          horizontal: pillPaddingH, vertical: pillPaddingV),
                      decoration: BoxDecoration(
                        color: greenSuccessColor,
                        borderRadius: BorderRadius.circular(borderRadius),
                      ),
                      child: AppText.smallParagraph(
                          "verified".getString(context),
                          fontWeight: FontWeight.w500,
                          color: textColor),
                    ),
                  ],
                ),

                // Admission Info
                Row(
                  children: [
                    AppText.paragraph(
                      "${"admission_no".getString(context)} : ",
                    ),
                    AppText.paragraph(student.admissionNo ?? "",
                        fontWeight: FontWeight.w700),
                  ],
                ),
                Gap(getWidth(4)!),
                Row(
                  children: [
                    AppText.paragraph(
                      "${"grade".getString(context)} : ",
                    ),
                    AppText.paragraph(student.grade ?? "",
                        fontWeight: FontWeight.w700),
                  ],
                ),
                Gap(getWidth(6)!),
                Row(
                  mainAxisAlignment: MainAxisAlignment.spaceBetween,
                  children: [
                    GestureDetector(
                      behavior: HitTestBehavior.translucent,
                      onTap: () {
                        logic.moveToChildDetails(student);
                      },
                      child: AppText.paragraph(
                        "view_details".getString(context),
                        fontWeight: FontWeight.w700,
                        color: bgColor,
                        getfontSize: 16,
                      ),
                    ),
                    GestureDetector(
                      behavior: HitTestBehavior.translucent,
                      onTap: () {
                        Get.toNamed(
                          Routes.HISTORY,
                          arguments: student.id ?? "",
                        );
                      },
                      child: AppText.paragraph(
                        "view_reports".getString(context),
                        fontWeight: FontWeight.w700,
                        color: bgColor,
                        getfontSize: 16,
                      ),
                    ),
                  ],
                ),
              ],
            ),
          ),
        ],
      ),
    );
  }
}
