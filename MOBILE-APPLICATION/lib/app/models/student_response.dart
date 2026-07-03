class StudentResponse {
  String? id;
  String? name;
  String? admissionNo;
  String? grade;
  String? image;
  String? dob;
  String? gender;
  String? dailySpendLimit;
  List<String>? restrictedFood;

  StudentResponse({
    this.id,
    this.name,
    this.admissionNo,
    this.grade,
    this.image,
    this.dob,
    this.gender,
    this.dailySpendLimit,
    this.restrictedFood,
  });

  StudentResponse.fromJson(Map<String, dynamic> json) {
    id = json['id'].toString();
    name = json['name'];
    admissionNo = json['admission_no'];
    grade = json['grade'];
    image = json['image'];
    dob = json['dob'];
    gender = json['gender'];
    dailySpendLimit = json['daily_spend_limit'];
    if (json['restricted_food'] != null && json['restricted_food'] is List) {
      restrictedFood = List<String>.from(json['restricted_food']);
    } else {
      restrictedFood = [];
    }
  }
}
