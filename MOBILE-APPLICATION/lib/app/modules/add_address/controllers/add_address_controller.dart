import 'package:flutter/material.dart';
import 'package:get/get.dart';
import 'package:jazz_smart_pay/app/utils/search_places_jay/place_model.dart';
import 'package:jazz_smart_pay/app/utils/search_places_jay/search_places.dart';
import 'package:jazz_smart_pay/app/utils/search_places_jay/search_places_widget.dart';
import 'package:jazz_smart_pay/app/utils/uuid_generator.dart';

import '../../../dio_api/dio_api.dart';
import '../../../utils/constant_vars.dart';

class AddAddressController extends GetxController {
  late Map arguments;
  late TextEditingController name;
  late TextEditingController email;
  late TextEditingController phone;
  late TextEditingController streat;
  late TextEditingController streatTwo;
  late TextEditingController city;
  late TextEditingController state;
  late TextEditingController zipCode;
  late TextEditingController country;
  @override
  void onInit() {
    arguments = Get.arguments;
    name = TextEditingController();
    phone = TextEditingController();
    email = TextEditingController();
    streat = TextEditingController();
    streatTwo = TextEditingController();
    city = TextEditingController();
    state = TextEditingController();
    zipCode = TextEditingController();
    country = TextEditingController();
    if (arguments['type'] == "1") {
      addDataToFields();
    }
    super.onInit();
  }

  addDataToFields() {
    var data = arguments['address'];
    name.text = data['name'];
    email.text = data['email'];
    phone.text = data['mobileno'];
    streat.text = data['address1'];
    streatTwo.text = data['address2'];
    state.text = data['state'];
    city.text = data['city'];
    zipCode.text = data['pincode'];
    addLat = data['lat'];
    addLng = data['lng'];
  }

  bool isLoading = false;
  addAddressAPI() {
    isLoading = true;
    update();
    API().post("/addaddress", data: {
      "name": name.text.trim(),
      "email": email.text.trim(),
      "mobileno": phone.text.trim(),
      "address1": streat.text.trim(),
      "address2": streatTwo.text.trim(),
      "state": state.text.trim(),
      "city": city.text.trim(),
      "pincode": zipCode.text.trim(),
      "status": 1,
      "view": 0,
      "addresstype": "1",
    }).then((value) async {
      Map<String, dynamic>? res = value.data;
      if (res != null) {
        if (res['status'] == 1) {
          Constants.successDialog(
            message: res['message'],
            onTap: () {
              Get.back();
              Get.back(result: true);
            },
          );
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

  updateAddressAPI() {
    isLoading = true;
    update();
    API().post("/updateaddress/${arguments['address']['id']}", data: {
      "name": name.text.trim(),
      "email": email.text.trim(),
      "mobileno": phone.text.trim(),
      "address1": streat.text.trim(),
      "address2": streatTwo.text.trim(),
      "state": state.text.trim(),
      "city": city.text.trim(),
      "pincode": zipCode.text.trim(),
      "addresstype": "1",
      "country": "india",
      "latitude": addLat,
      "longitude": addLng
    }).then((value) async {
      Map<String, dynamic>? res = value.data;
      if (res != null) {
        if (res['status'] == 1) {
          Constants.successDialog(
            message: res['message'],
            onTap: () {
              Get.back();
              Get.back(result: true);
            },
          );
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

  var addressFormKey = GlobalKey<FormState>();
  void addressSubmit() {
    final isValid = addressFormKey.currentState!.validate();
    if (!isValid) {
      return;
    } else {
      if (arguments['type'] == "1") {
        updateAddressAPI();
      } else {
        addAddressAPI();
      }
    }
    addressFormKey.currentState!.save();
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
      streat.text = result.lineOne ?? '';
      streatTwo.text = result.lineTwo ?? '';
      zipCode.text = result.zipCode ?? '';
      city.text = result.city ?? '';
      state.text = result.state ?? '';
    }
    update();
  }
}
