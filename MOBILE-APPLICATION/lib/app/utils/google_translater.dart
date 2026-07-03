import 'dart:convert';
import 'package:http/http.dart' as http;
import 'package:jazz_smart_pay/app/utils/constant_vars.dart';

class TranslationService {
  final String apiKey = Constants.googleMapKey;

  /// Method to translate text
  Future<String> translateText(String text, String targetLanguage) async {
    final String url =
        'https://translation.googleapis.com/language/translate/v2?key=$apiKey';

    final response = await http.post(
      Uri.parse(url),
      headers: {'Content-Type': 'application/json'},
      body: jsonEncode({
        'q': text,
        'target': targetLanguage, // 'en' for English, 'hi' for Hindi, etc.
      }),
    );

    if (response.statusCode == 200) {
      final data = jsonDecode(response.body);
      return data['data']['translations'][0]['translatedText'];
    } else {
      throw Exception('Translation failed: ${response.body}');
    }
  }

  /// Recursively translate dynamic JSON response
  Future<Map<String, dynamic>> translateJson(
      Map<String, dynamic> jsonData, String targetLanguage) async {
    Map<String, dynamic> translatedJson = {};

    for (var key in jsonData.keys) {
      var value = jsonData[key];

      if (value is String) {
        translatedJson[key] = await translateText(value, targetLanguage);
      } else if (value is Map<String, dynamic>) {
        translatedJson[key] = await translateJson(value, targetLanguage);
      } else if (value is List) {
        translatedJson[key] = await _translateList(value, targetLanguage);
      } else {
        translatedJson[key] =
            value; // Keep numbers, booleans, and null unchanged
      }
    }
    return translatedJson;
  }

  /// Helper function to translate lists
  Future<List<dynamic>> _translateList(
      List<dynamic> list, String targetLanguage) async {
    List<dynamic> translatedList = [];

    for (var item in list) {
      if (item is String) {
        translatedList.add(await translateText(item, targetLanguage));
      } else if (item is Map<String, dynamic>) {
        translatedList.add(await translateJson(item, targetLanguage));
      } else {
        translatedList.add(item);
      }
    }
    return translatedList;
  }
}
