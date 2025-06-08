import 'dart:convert';
import 'package:http/http.dart' as http;
import 'package:bmi_app/models/user.dart';
import 'package:bmi_app/models/bmi_record.dart';
import 'package:bmi_app/models/article.dart';
import 'package:bmi_app/models/recommendation.dart';
import 'package:bmi_app/models/user_profile.dart';

class ApiService {
  static const String baseUrl = 'http://192.168.19.160:8000/api';
  static String? _token;

  static setToken(String token) {
    _token = token;
  }

  static Future<User> register(String name, String email, String password) async {
    final response = await http.post(
      Uri.parse('$baseUrl/register'),
      headers: {'Content-Type': 'application/json'},
      body: jsonEncode({'name': name, 'email': email, 'password': password}),
    );

    if (response.statusCode == 201) {
      final data = jsonDecode(response.body);
      _token = data['token'];
      return User.fromJson(data['user']);
    } else {
      throw Exception('Failed to register');
    }
  }

  static Future<User> login(String email, String password) async {
    final response = await http.post(
      Uri.parse('$baseUrl/login'),
      headers: {'Content-Type': 'application/json'},
      body: jsonEncode({'email': email, 'password': password}),
    );

    if (response.statusCode == 200) {
      final data = jsonDecode(response.body);
      _token = data['token'];
      return User.fromJson(data['user']);
    } else {
      throw Exception('Failed to login');
    }
  }

  static Future<List<BmiRecord>> getBmiRecords() async {
    final response = await http.get(
      Uri.parse('$baseUrl/bmi-records'),
      headers: {'Authorization': 'Bearer $_token'},
    );

    if (response.statusCode == 200) {
      final List<dynamic> data = jsonDecode(response.body);
      return data.map((json) => BmiRecord.fromJson(json)).toList();
    } else {
      throw Exception('Failed to load BMI records');
    }
  }

  static Future<List<Article>> getArticles() async {
    final response = await http.get(
      Uri.parse('$baseUrl/articles'),
      headers: {'Authorization': 'Bearer $_token'},
    );
    if (response.statusCode == 200) {
      final List<dynamic> data = jsonDecode(response.body);
      return data.map((json) => Article.fromJson(json)).toList();
    } else {
      throw Exception('Failed to load articles');
    }
  }

  static Future<List<Recommendation>> getRecommendations() async {
    final response = await http.get(
      Uri.parse('$baseUrl/recommendations'),
      headers: {'Authorization': 'Bearer $_token'},
    );
    if (response.statusCode == 200) {
      final List<dynamic> data = jsonDecode(response.body);
      return data.map((json) => Recommendation.fromJson(json)).toList();
    } else {
      throw Exception('Failed to load recommendations');
    }
  }

  static Future<List<UserProfile>> getUserProfiles() async {
    final response = await http.get(
      Uri.parse('$baseUrl/user-profiles'),
      headers: {'Authorization': 'Bearer $_token'},
    );
    if (response.statusCode == 200) {
      final List<dynamic> data = jsonDecode(response.body);
      return data.map((json) => UserProfile.fromJson(json)).toList();
    } else {
      throw Exception('Failed to load user profiles');
    }
  }
}