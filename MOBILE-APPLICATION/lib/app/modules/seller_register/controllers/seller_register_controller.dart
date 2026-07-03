import 'dart:convert';
import 'dart:io';
import 'dart:convert' as convert;
import 'package:image_picker/image_picker.dart';
import 'package:jazz_smart_pay/app/utils/app_bottom_sheet.dart';
import 'package:jazz_smart_pay/app/utils/app_const_colors.dart';
import 'package:jazz_smart_pay/app/utils/search_places_jay/place_model.dart';
import 'package:jazz_smart_pay/app/utils/search_places_jay/search_places.dart';
import 'package:jazz_smart_pay/app/utils/search_places_jay/search_places_widget.dart';
import '../../../../exports.dart';
import '../../../dio_api/dio_api.dart';
import '../../../utils/constant_vars.dart';
import '../../../utils/index.dart';
import '../../../utils/uuid_generator.dart';

class SellerRegisterController extends GetxController {
  late TextEditingController email;
  late TextEditingController mobile;
  late TextEditingController password;
  late TextEditingController personName;
  late TextEditingController companyName;
  late TextEditingController confirmPassword;
  late TextEditingController registerOtp;
  late TextEditingController addressLine1;
  late TextEditingController pincode;
  late TextEditingController gstNumber;
  late TextEditingController accNumber;
  late TextEditingController bankName;
  late TextEditingController ifsCode;
  late TextEditingController holderName;
  late TextEditingController branchName;
  late TextEditingController branchState;
  late TextEditingController branchCity;

  bool showOtp = false;

  @override
  void onInit() {
    email = TextEditingController();
    password = TextEditingController();
    mobile = TextEditingController();
    personName = TextEditingController();
    companyName = TextEditingController();
    confirmPassword = TextEditingController();
    registerOtp = TextEditingController();
    addressLine1 = TextEditingController();
    pincode = TextEditingController();
    gstNumber = TextEditingController();
    accNumber = TextEditingController();
    ifsCode = TextEditingController();
    bankName = TextEditingController();
    holderName = TextEditingController();
    branchName = TextEditingController();
    branchState = TextEditingController();
    branchCity = TextEditingController();
    super.onInit();
  }

  // Helper to count selected options
  bool frenchise = true;
  bool quick = false;
  bool multivendor = false;
  int get selectedCount =>
      (frenchise ? 1 : 0) + (quick ? 1 : 0) + (multivendor ? 1 : 0);

  void showCustomSnackbar(String message) {
    ScaffoldMessenger.of(Get.context!)
        .clearSnackBars(); // Optional: clear previous
    ScaffoldMessenger.of(Get.context!).showSnackBar(
      SnackBar(
        elevation: 0,
        backgroundColor: Colors.transparent, // So we can use a custom container
        behavior: SnackBarBehavior.floating,
        margin: const EdgeInsets.fromLTRB(16, 0, 16, 10), // Padding at bottom
        content: Container(
          padding: const EdgeInsets.all(12),
          decoration: BoxDecoration(
            color: Colors.white,
            borderRadius: BorderRadius.circular(12),
            border: Border.all(color: blackColor),
          ),
          child: Row(
            children: [
              Icon(Icons.error_outline, color: blackColor),
              const SizedBox(width: 10),
              Expanded(
                child: Text(
                  message,
                  style: const TextStyle(
                    color: Colors.black87,
                    fontSize: 14,
                    fontWeight: FontWeight.w500,
                  ),
                ),
              ),
            ],
          ),
        ),
        duration: const Duration(seconds: 1),
      ),
    );
  }

  void frenchiseTap() {
    if (frenchise && selectedCount == 1) {
      showCustomSnackbar("At least one option must be selected.");
      return;
    }
    frenchise = !frenchise;
    update();
  }

  void quickTap() {
    if (quick && selectedCount == 1) {
      showCustomSnackbar("At least one option must be selected.");
      return;
    }
    quick = !quick;
    update();
  }

  void multivendorTap() {
    if (multivendor && selectedCount == 1) {
      showCustomSnackbar("At least one option must be selected.");
      return;
    }
    multivendor = !multivendor;
    update();
  }

  //*******************************************************************//
  //************************* Login User APi **************************//
  //*******************************************************************//
  bool isLoading = false;
  registerAPI() {
    isLoading = true;
    update();
    API().post(
      "/vendor-register",
      data: {
        "username": personName.text.trim(),
        "phone": mobile.text.trim(),
        "email": email.text.trim(),
        "password": password.text.trim(),
        "address": "okhla new delhi",
        "pincode": "110302",
        "latitude": addLat,
        "longitude": addLng,
        "gstno": gstNumber.text.trim(),
        "bankaccountno": accNumber.text.trim(),
        "bankname": bankName.text.trim(),
        "ifscno": ifsCode.text.trim(),
        "companyname": companyName.text.trim(),
        "accountholdername": holderName.text.trim(),
        "bankbranch": branchName.text.trim(),
        "bankcity": branchCity.text.trim(),
        "bankstate": branchState.text.trim(),
        "quickcommerce": quick ? "1" : "0",
        "ecommerce": multivendor ? "1" : "0",
        "frinches": frenchise ? "1" : "0",
        // "gst_document": base64ImageGST,
        // "pan_card_document": base64ImagePAN,
        "view": "0",
        "status": "0",
      },
    ).then((value) async {
      Map<String, dynamic>? res = value.data;
      if (res != null) {
        if (res['status'] == 1) {
          showOtp = true;
          update();
        } else {
          Constants.errorDialog(message: res['message']);
        }
      } else {
        Constants.errorDialog();
      }
      isLoading = false;
      update();
    });
  }

  bool iOtpLoading = false;
  verifyRegisterAPI() {
    iOtpLoading = true;
    update();
    API().post(
      "/emailverificationvendor",
      data: {
        'otp': registerOtp.text.trim(),
        'email': email.text.trim(),
        "fcmtoken": "sdfdsfsdf"
      },
    ).then((value) async {
      Map<String, dynamic>? res = value.data;
      if (res != null) {
        if (res['status'] == 1) {
          Prefs().setToken(res['data']['access_token']);
          Get.offAllNamed(Routes.BASE_PAGE);
        } else {
          Constants.errorDialog(message: res['message']);
        }
      } else {
        Constants.errorDialog();
      }
      iOtpLoading = false;
      update();
    });
  }

  var registerFormKey = GlobalKey<FormState>();
  void registerSubmit() {
    final isValid = registerFormKey.currentState!.validate();
    if (!isValid) {
      return;
    } else {
      registerAPI();
    }
    registerFormKey.currentState!.save();
  }

  // ************************************************************************ //
  // ************************ Search Field Delegates ************************ //
  // ************************************************************************ //

  String? addLat;
  String? addLng;
  pickAddress(BuildContext context) async {
    final sessionToken = Uuid().generateV4();
    final result = await showPlacesSearch<PlaceJay?>(
      context: context,
      delegate: AddressSearch(sessionToken),
    );
    if (result != null) {
      addLat = result.lat?.toString();
      addLng = result.lng?.toString();
      final parts = [
        result.lineOne,
        result.lineTwo,
        result.city,
        result.state,
      ]
          .where((part) => part != null && part.toString().trim().isNotEmpty)
          .toList();
      addressLine1.text = parts.join(', ');
      pincode.text = result.zipCode ?? '';
    }
    update();
  }

  uploadGSTDoc(BuildContext context) {
    AppBottomSheet.imagePickerBottomSheet(
      context,
      onCameraTap: () async {
        Get.close(1);
        File? img = await Constants.pickImage();
        await _processPickedImage(img);
      },
      onGalleryTap: () async {
        Get.close(1);
        File? img = await Constants.pickImage(source: ImageSource.gallery);
        await _processPickedImage(img);
      },
    );
  }

  uploadPANDoc(BuildContext context) {
    AppBottomSheet.imagePickerBottomSheet(
      context,
      onCameraTap: () async {
        Get.close(1);
        File? img = await Constants.pickImage();
        await _processPickedImagePAN(img);
      },
      onGalleryTap: () async {
        Get.close(1);
        File? img = await Constants.pickImage(source: ImageSource.gallery);
        await _processPickedImagePAN(img);
      },
    );
  }

  String? base64ImageGST;
  File? selectedGST;

  Future<void> _processPickedImage(File? img) async {
    if (img == null) return;
    selectedGST = img;
    base64ImageGST = convertFileToBase64(img);
    update();
  }

  String? base64ImagePAN;
  File? selectedPAN;

  Future<void> _processPickedImagePAN(File? img) async {
    if (img == null) return;
    selectedPAN = img;
    base64ImagePAN = convertFileToBase64(img);
    update();
  }

  String convertFileToBase64(File file) {
    final bytes = File(file.path).readAsBytesSync();
    String base64 = convert.base64Encode(bytes);
    return base64;
  }
}
