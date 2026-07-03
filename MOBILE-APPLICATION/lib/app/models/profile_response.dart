class ProfileResponse {
  String? schoolName;
  int? id;
  String? name;
  String? mobile;
  String? email;
  String? image;

  ProfileResponse(
      {this.schoolName,
      this.id,
      this.name,
      this.mobile,
      this.email,
      this.image});

  ProfileResponse.fromJson(Map<String, dynamic> json) {
    schoolName = json['school_name'];
    id = json['id'];
    name = json['name'];
    mobile = json['mobile'];
    email = json['email'];
    image = json['image'];
  }
}
