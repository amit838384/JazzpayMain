import 'package:get/get.dart';
import 'dart:convert';
import 'package:flutter/services.dart' show rootBundle;

class AppTranslations extends Translations {
  static Map<String, Map<String, String>> translations = {};

  @override
  Map<String, Map<String, String>> get keys => translations;

  /// Loads JSON files dynamically
  static Future<void> loadLanguages() async {
    List<String> languages = ['en', 'fr']; // Add more languages if needed
    for (var lang in languages) {
      String jsonString = await rootBundle.loadString('assets/lang/$lang.json');
      translations[lang] = Map<String, String>.from(json.decode(jsonString));
    }
  }
}
