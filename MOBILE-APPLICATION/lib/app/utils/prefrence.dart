// Package imports:
import 'package:get_storage/get_storage.dart';

class Prefs {
  late GetStorage storage;

  static const String USER_TOKEN = 'token';
  static const String IS_GRID = 'is_grid';
  static const String USER_EMAIL = 'email';
  static const String USER_PASS = 'pass';
  static const String IS_FIRST = 'first';
  static const String IS_PRO_FIRST = 'pro_first';
  static const String RELEASE_NOTE = 'releaseNote';
  static const String COUNTRY_CODE = 'countryCode';
  static const String REVIEW_TIME = 'reviewTime';
  static const String UPDATE_SHOW = 'updateShow';
  static const String APP_OPEN_COUNT = 'appOpenCount';
  static const String SHOW_REFEREARN_BANNER = 'showReferEarnBanner';
  static const String RESTARTED = 'isRestarted';
  static const String PROFILEDIA = 'profileDia';
  static const String SHORTFEEDBACK = 'shortFeedback';
  static const String REFEREARN = 'referEarn';
  static const String SHOW_ONBOARD = 'showOnboad';

  Prefs() {
    storage = GetStorage();
  }

  void setProfileDia(String profileDia) {
    storage.write(PROFILEDIA, profileDia);
  }

  String? getProfileDia() {
    return storage.read(PROFILEDIA);
  }

  void setReferEarn(String referEarn) {
    storage.write(REFEREARN, referEarn);
  }

  String? getReferEarn() {
    return storage.read(REFEREARN);
  }

  void setReviewTime(DateTime isReviewTime) {
    storage.write(REVIEW_TIME, isReviewTime);
  }

  DateTime? getReviewTime() {
    return storage.read(REVIEW_TIME);
  }

  void setIsFirst(String isFirst) {
    storage.write(IS_FIRST, isFirst);
  }

  String? getIsFirst() {
    return storage.read(IS_FIRST);
  }

  void setIsGrid(String isGrid) {
    storage.write(IS_GRID, isGrid);
  }

  String? getIsGrid() {
    return storage.read(IS_GRID);
  }

  void setOnboard(String isOnBoard) {
    storage.write(SHOW_ONBOARD, isOnBoard);
  }

  String? getOnboard() {
    return storage.read(SHOW_ONBOARD);
  }

  void setReferEarnBanner(String isReferEarnBanner) {
    storage.write(SHOW_REFEREARN_BANNER, isReferEarnBanner);
  }

  String? getReferEarnBanner() {
    return storage.read(SHOW_REFEREARN_BANNER);
  }

  void removeReferEarnBanner() {
    storage.remove(SHOW_REFEREARN_BANNER);
  }

  void setIsUpdateShow(String isUpdateShow) {
    storage.write(UPDATE_SHOW, isUpdateShow);
  }

  String? getIsUpdateShow() {
    return storage.read(UPDATE_SHOW);
  }

  void setIsProFirst(String isProFirst) {
    storage.write(IS_PRO_FIRST, isProFirst);
  }

  String? getIsProFirst() {
    return storage.read(IS_PRO_FIRST);
  }

  void setReleaseNote(String isFirst) {
    storage.write(RELEASE_NOTE, isFirst);
  }

  String? getReleaseNote() {
    return storage.read(RELEASE_NOTE);
  }

  Future<void> setToken(String token) async {
    await storage.write(USER_TOKEN, token);
  }

  String? getToken() {
    return storage.read(USER_TOKEN);
  }

  Future<void> setCountry(String country) async {
    storage.write(COUNTRY_CODE, country);
  }

  String? getCountry() {
    return storage.read(COUNTRY_CODE);
  }

  int? getAppOpenCount() {
    return storage.read<int>(APP_OPEN_COUNT);
  }

  Future<void> setAppOpenCount(int count) async {
    storage.write(APP_OPEN_COUNT, count);
  }

  void setRememberEmail(String email) {
    storage.write(USER_EMAIL, email);
  }

  String? getRememberEmail() {
    return storage.read(USER_EMAIL);
  }

  void setRememberPassword(String passwqord) {
    storage.write(USER_PASS, passwqord);
  }

  String? getRememberPassword() {
    return storage.read(USER_PASS);
  }

  Future<void> removeToken() async {
    await storage.remove(USER_TOKEN);
  }

  void removeRememberCredientials() {
    storage.remove(USER_EMAIL);
    storage.remove(USER_PASS);
  }

  Future<void> setShortFeedback(int likeFlag) async {
    storage.write(SHORTFEEDBACK, likeFlag);
  }

  int? getShortFeedback() {
    return storage.read(SHORTFEEDBACK);
  }

  Future<void> saveUsers(SavedUser user) async {
    List<dynamic> users = storage.read<List<dynamic>>('users') ?? [];

    int userIndex = users.indexWhere((u) => u['userToken'] == user.userToken);
    if (userIndex != -1) {
      users[userIndex] = user.toJson();
    } else {
      users.add(user.toJson());
    }
    await storage.write('users', users);
  }

  List<SavedUser> getUsers() {
    List<dynamic> jsonList = storage.read<List>('users') ?? [];
    return jsonList.map((json) => SavedUser.fromJson(json)).toList();
  }

  Future<void> removeUserByToken(String? token) async {
    List<dynamic> users = storage.read('users') ?? [];
    users.removeWhere((user) => user['token'] == token);
    await storage.write('users', users);
  }

  // Future<void> removeUserByUserToken(String? token) async {
  //   List<dynamic> users = storage.read('users') ?? [];
  //   users.removeWhere((user) => user['userToken'] == token);
  //   await storage.write('users', users);
  // }

  Future<void> removeAllUsers() async {
    await storage.remove('users');
  }

  Future<void> updateUserList(List<SavedUser> users) async {
    List<Map<String, dynamic>> userList = users.map((user) => user.toJson()).toList();
    await storage.write('users', userList);
  }

  Future<void> updateUserDetails(SavedUser updatedUser) async {
    final currentToken = getToken();
    final users = getUsers();
    final userIndex = users.indexWhere((user) => user.token == currentToken);
    if (userIndex != -1) {
      users[userIndex] = updatedUser;
      await updateUserList(users);
    }
  }

  String get getCurrentProfilePhoto {
    final currentToken = getToken();
    final users = getUsers();
    final userIndex = users.indexWhere((user) => user.token == currentToken);
    if (userIndex != -1) {
      return users[userIndex].photo;
    } else {
      return '';
    }
  }
}

class SavedUser {
  String email;
  String fullName;
  String username;
  String photo;
  bool isVerified;
  String token;
  String userToken;

  SavedUser({
    required this.email,
    required this.fullName,
    required this.username,
    required this.photo,
    required this.isVerified,
    required this.token,
    required this.userToken,
  });

  // Convert a User into a Map. The keys must correspond to the names of the fields.
  Map<String, dynamic> toJson() => {
        'email': email,
        'full_name': fullName,
        'username': username,
        'photo': photo,
        'isVerified': isVerified,
        'token': token,
        'userToken': userToken,
      };

  // Convert a Map into a User.
  factory SavedUser.fromJson(Map<String, dynamic> json) => SavedUser(
        email: json['email'],
        fullName: json['full_name'],
        username: json['username'],
        photo: json['photo'],
        isVerified: json['isVerified'] ?? false,
        token: json['token'],
        userToken: json['userToken'],
      );
}
