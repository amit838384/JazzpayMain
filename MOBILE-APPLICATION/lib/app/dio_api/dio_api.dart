import 'dart:convert';
import 'dart:developer';
import 'dart:io';
import 'package:http/http.dart' as http;
import 'package:dio/dio.dart';
import 'package:pretty_dio_logger/pretty_dio_logger.dart';

import '../utils/index.dart';

class API {
  API() : _dio = Dio() {
    _dio.options.baseUrl = _baseUrl;
    _dio.interceptors.add(PrettyDioLogger(compact: true));

    // Set Bearer token interceptor
    _dio.interceptors.add(
      InterceptorsWrapper(
        onRequest: (options, handler) {
          // Set your Bearer token here
          var token = Prefs().getToken() ?? "";
          options.headers['Authorization'] = 'Bearer $token';
          return handler.next(options);
        },
      ),
    );
  }
  // last changed: 17 jun, 13:24
  static const String _baseUrl = 'https://jazzpay.dkddev.com/api';
  // static const String _baseUrl = 'http://127.0.0.1:8000/api';
  // static const String _baseUrl = 'https://www.smarteatsonline.com/api';

  final Dio _dio;

  Future<Response<T>> get<T>(
    String path, {
    Map<String, dynamic>? queryParameters,
  }) async {
    try {
      final response =
          await _dio.get<T>(path, queryParameters: queryParameters);
      return response;
    } on DioException catch (e) {
      if (e.response != null) {
        throw Exception(
          'Request failed with status code ${e.response!.statusCode}',
        );
      } else {
        throw Exception('Failed to make the request: ${e.type}');
      }
    }
  }

  Future<Response<T>> post<T>(String path, {dynamic data}) async {
    try {
      log("Request  :   $data");
      final response = await _dio.post<T>(path, data: data);
      return response;
    } on DioException catch (e) {
      if (e.response != null) {
        throw Exception(
          'Request failed with status code ${e.response!.statusCode}',
        );
      } else {
        throw Exception('Failed to make the request: $e');
      }
    }
  }

  Future<Response<T>> postFormData<T>(
    String path, {
    required Map<String, dynamic> data,
  }) async {
    log("Request Data  :  $data");
    try {
      final formData = FormData.fromMap(data);
      final response = await _dio.post<T>(
        path,
        data: formData,
        options: Options(
          contentType: 'multipart/form-data',
        ),
      );
      return response;
    } on DioException catch (e) {
      if (e.response != null) {
        throw Exception(
          'Request failed with status code ${e.response!.statusCode}',
        );
      } else {
        throw Exception('Failed to make the request: $e');
      }
    }
  }

  Future<String?> uploadFile(String filePath) async {
    final request = http.MultipartRequest(
      'POST',
      Uri.parse('YOUR_API_ENDPOINT'), // Replace with your API endpoint
    );
    request.files.add(await http.MultipartFile.fromPath('file', filePath));

    final response = await request.send();
    if (response.statusCode == 200) {
      final responseData = await response.stream.toBytes();
      final String responseString = String.fromCharCodes(responseData);
      return jsonDecode(responseString);
    }
    return null;
  }

  Future<Response<T>> uploadSingleFile<T>(String path,
      {required Map<String, dynamic> data, File? file}) async {
    try {
      final formData = FormData();

      // Add all form fields safely
      data.forEach((key, value) {
        if (value != null) {
          formData.fields.add(MapEntry(key, value.toString()));
        }
      });

      // Add file only if it's non-null and exists
      if (file != null && await file.exists()) {
        formData.files
            .add(MapEntry('image', await MultipartFile.fromFile(file.path)));
      }

      final response = await _dio.post<T>(path, data: formData);
      return response;
    } on DioException catch (e) {
      if (e.response != null) {
        throw Exception(
            'Upload failed with status code ${e.response!.statusCode}');
      } else {
        throw Exception('Failed to upload files: ${e.message}');
      }
    } catch (e) {
      throw Exception('Unexpected error: $e');
    }
  }
}
