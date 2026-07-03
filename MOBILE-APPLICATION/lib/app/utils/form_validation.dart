class FormValidation {
  //**************************************Not Empty Validator*********************************//
  static String? notEmptyValidator(value, {bool isMandatory = true}) {
    if (value.length == 0) {
      return isMandatory ? 'Required' : null;
    }
    return null;
  }

  static String? deleteValidator(String? value) {
    if (value == null || value.trim().isEmpty) {
      return 'Required';
    }

    if (value.trim() != 'Delete') {
      return 'Please type “Delete” to confirm';
    }

    return null;
  }

  static String? ifscValidator(String? value) {
    if (value == null || value.trim().isEmpty) {
      return "IFSC code is required";
    } else if (!RegExp(r'^[A-Z]{4}0[A-Z0-9]{6}$')
        .hasMatch(value.trim().toUpperCase())) {
      return 'Enter a valid IFSC code';
    }
    return null;
  }

  //**************************************Email Validator*********************************//
  static String? emailValidator(String? value) {
    if (value == null || value.isEmpty) {
      return "Email is required";
    } else if (!RegExp(
            r"^[a-zA-Z0-9.a-zA-Z0-9.!#$%&'*+-/=?^_`{|}~]+@[a-zA-Z0-9]+\.[a-zA-Z]+")
        .hasMatch(value)) {
      return 'Enter a valid email!';
    }
    return null;
  }

  static String? gstValidator(String? value) {
    if (value == null || value.isEmpty) {
      return "GST number is required";
    } else if (!RegExp(
            r'^[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z]{1}[1-9A-Z]{1}Z[0-9A-Z]{1}$')
        .hasMatch(value)) {
      return 'Enter a valid GST number';
    }
    return null;
  }

  static String? pinCodeValidator(value, {bool isMandatory = true}) {
    if (value.length == 0) {
      return isMandatory ? 'pin code is required' : null;
    } else if (value.length < 6 || value.length > 6) {
      return isMandatory ? 'Please enter valid pin code' : null;
    }
    return null;
  }

//**************************************OTP Validator*********************************//
  static String? otpValidator(String? value) {
    if (value == null || value.isEmpty) {
      return "OTP is required";
    } else if (!RegExp(r"^\d{6}$").hasMatch(value)) {
      return "Enter a valid 6-digit OTP";
    }
    return null;
  }

  //**************************************Phone Number Validator*********************************//

  static String? phoneValidator(value, {bool isMandatory = true}) {
    String pattern = r'(^(?:[+0]9)?[0-9]{10}$)';
    RegExp regExp = RegExp(pattern);
    if (value.length == 0) {
      return isMandatory ? 'Phone number is required' : null;
    } else if (!regExp.hasMatch(value)) {
      return 'Please enter valid mobile number';
    }
    return null;
  }

  static String? confirmPasswordValidator(value, passValue) {
    if (value!.isEmpty) {
      return 'Password is required';
    } else if (value != passValue) {
      return 'Uh oh! Looks like the passwords didn\'t match.';
    }
    return null;
  }
}
