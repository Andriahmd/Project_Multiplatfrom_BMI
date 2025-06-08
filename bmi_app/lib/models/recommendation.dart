class Recommendation {
  final int id;
  final int userId;
  final String recommendationText;

  Recommendation({
    required this.id,
    required this.userId,
    required this.recommendationText,
  });

  factory Recommendation.fromJson(Map<String, dynamic> json) {
    return Recommendation(
      id: json['id'],
      userId: json['user_id'],
      recommendationText: json['recommendation_text'],
    );
  }
}