import 'package:bmi_app/models/bmi_record.dart';
import 'package:bmi_app/models/article.dart';
import 'package:bmi_app/models/recommendation.dart';
import 'package:bmi_app/models/user_profile.dart';
import 'package:bmi_app/services/api_service.dart';
import 'package:flutter/material.dart';

class DataController with ChangeNotifier {
  List<BmiRecord> _bmiRecords = [];
  List<Article> _articles = [];
  List<Recommendation> _recommendations = [];
  List<UserProfile> _userProfiles = [];
  bool _isLoading = false;

  List<BmiRecord> get bmiRecords => _bmiRecords;
  List<Article> get articles => _articles;
  List<Recommendation> get recommendations => _recommendations;
  List<UserProfile> get userProfiles => _userProfiles;
  bool get isLoading => _isLoading;

  Future<void> fetchBmiRecords() async {
    _isLoading = true;
    notifyListeners();
    try {
      _bmiRecords = await ApiService.getBmiRecords();
    } catch (e) {
      _bmiRecords = [];
      throw e;
    } finally {
      _isLoading = false;
      notifyListeners();
    }
  }

  Future<void> fetchArticles() async {
    _isLoading = true;
    notifyListeners();
    try {
      _articles = await ApiService.getArticles();
    } catch (e) {
      _articles = [];
      throw e;
    } finally {
      _isLoading = false;
      notifyListeners();
    }
  }

  Future<void> fetchRecommendations() async {
    _isLoading = true;
    notifyListeners();
    try {
      _recommendations = await ApiService.getRecommendations();
    } catch (e) {
      _recommendations = [];
      throw e;
    } finally {
      _isLoading = false;
      notifyListeners();
    }
  }

  Future<void> fetchUserProfiles() async {
    _isLoading = true;
    notifyListeners();
    try {
      _userProfiles = await ApiService.getUserProfiles();
    } catch (e) {
      _userProfiles = [];
      throw e;
    } finally {
      _isLoading = false;
      notifyListeners();
    }
  }
}