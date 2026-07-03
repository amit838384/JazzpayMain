import 'dart:io';

import 'package:flutter/material.dart';
import 'package:get/get.dart';
import 'package:image_picker/image_picker.dart';
import 'package:intl/intl.dart';
import 'package:jazz_smart_pay/app/models/student_response.dart';
import 'package:omni_datetime_picker/omni_datetime_picker.dart';
import '../../../dio_api/dio_api.dart';
import '../../../utils/app_bottom_sheet.dart';
import '../../../utils/constant_vars.dart';

import 'package:image_cropper/image_cropper.dart' as crop;

class AddChildLogic extends GetxController {
  StudentResponse? student;
  late TextEditingController nameController;
  late TextEditingController addmissionController;
  late TextEditingController limitController;
  late TextEditingController dobController;
  List<String> gender = ['Male', 'Female', 'Other'];
  List<String> grades = [];
  String selectedGender = "Male";
  String? selectedGrade;
  String? studentId;
  String? studentImage;
  @override
  void onInit() {
    student = Get.arguments;
    nameController = TextEditingController();
    addmissionController = TextEditingController();
    limitController = TextEditingController();
    dobController = TextEditingController();
    fetchData();
    super.onInit();
  }

  fetchData() async {
    await gradesAPI();
    if (student != null) {
      studentId = student!.id ?? "";
      nameController.text = student!.name ?? "";
      addmissionController.text = student!.admissionNo ?? "";
      limitController.text = student!.dailySpendLimit ?? "";
      dobController.text = student!.dob ?? "";
      selectedGender = student!.gender ?? "";
      selectedGrade = student!.grade ?? "";
      studentImage = student!.image ?? "";
    }
  }

  Future<void> selectDate(BuildContext context) async {
    DateTime? selectedDate = await showOmniDateTimePicker(
      context: context,
      is24HourMode: false,
      isShowSeconds: false,
      isForce2Digits: true,
      borderRadius: const BorderRadius.all(Radius.circular(16)),
      constraints: const BoxConstraints(
        maxWidth: 350,
        maxHeight: 650,
      ),
      type: OmniDateTimePickerType.date,
      firstDate: DateTime(1900),
      lastDate: DateTime.now(),
      initialDate: DateTime(2000, 1, 1),
    );

    if (selectedDate != null) {
      String formattedDate = DateFormat('MM/dd/yyyy').format(selectedDate);
      dobController.text = formattedDate;
    }
  }

  bool isGrades = false;
  gradesAPI() {
    isGrades = true;
    update();
    API().get("/grade-all").then((value) async {
      Map<String, dynamic>? res = value.data;
      if (res != null) {
        if (res['status'] ?? false) {
          for (String grade in res['data']) {
            grades.add(grade);
          }
        } else {
          Constants.errorDialog(message: res['message']);
        }
      } else {
        Constants.errorDialog();
      }
      isGrades = false;
      update();
    });
  }

  bool isLoading = false;
  addStudent() {
    isLoading = true;
    update();
    API().uploadSingleFile(
      "/add-student",
      file: selectedFile,
      data: {
        "id": studentId,
        "name": nameController.text.trim(),
        "grade": selectedGrade,
        "admission_no": addmissionController.text.trim(),
        "daily_spend_limit": limitController.text.trim(),
        "dob": dobController.text.trim(),
        "gender": selectedGender,
      },
    ).then((value) async {
      Map<String, dynamic>? res = value.data;
      if (res != null) {
        if (res['status'] ?? false) {
          Get.back(result: true);
        } else {
          isLoading = false;
          update();
          Constants.errorDialog(message: res['message']);
        }
      } else {
        isLoading = false;
        update();
        Constants.errorDialog();
      }
    });
  }

  File? selectedFile;
  uploadProfilePicture(BuildContext context) {
    AppBottomSheet.imagePickerBottomSheet(
      context,
      onCameraTap: () async {
        Get.back();
        File? img = await Constants.pickImage();
        selectedFile = await imageCropper(img!);
        update();
      },
      onGalleryTap: () async {
        Get.back();
        File? img = await Constants.pickImage(source: ImageSource.gallery);
        selectedFile = await imageCropper(img!);
        update();
      },
    );
  }

  Future imageCropper(
    File imageFile, {
    bool showCircle = true,
    bool showCropOption = false,
  }) async {
    crop.CroppedFile? croppedFile = await crop.ImageCropper().cropImage(
      sourcePath: imageFile.path,
      aspectRatio: showCropOption == false
          ? const crop.CropAspectRatio(ratioX: 1, ratioY: 1)
          : null,
      uiSettings: [
        showCropOption == true
            ? crop.IOSUiSettings(
                aspectRatioPresets: [
                  crop.CropAspectRatioPreset.square,
                  crop.CropAspectRatioPreset.ratio3x2,
                  crop.CropAspectRatioPreset.original,
                  crop.CropAspectRatioPreset.ratio4x3,
                  crop.CropAspectRatioPreset.ratio16x9,
                ],
              )
            : crop.IOSUiSettings(
                aspectRatioPickerButtonHidden: true,
                resetAspectRatioEnabled: false,
                resetButtonHidden: true,
                hidesNavigationBar: true,
                showCancelConfirmationDialog: true,
                cropStyle: showCircle
                    ? crop.CropStyle.circle
                    : crop.CropStyle.rectangle,
              ),
      ],
    );
    if (croppedFile == null) return;
    return File(croppedFile.path);
  }

  var addFormKey = GlobalKey<FormState>();
  void loginSubmit() {
    final isValid = addFormKey.currentState!.validate();
    if (!isValid) {
      return;
    } else {
      addStudent();
    }
    addFormKey.currentState!.save();
  }
}
