class BmiRecord {
  final int id;
  final int userId;
  final double height;
  final double weight;
  final double bmi;

  BmiRecord({
    required this.id,
    required this.userId,
    required this.height,
    required this.weight,
    required this.bmi,
  });

  factory BmiRecord.fromJson(Map<String, dynamic> json) {
    return BmiRecord(
      id: json['id'],
      userId: json['user_id'],
      height: json['height'].toDouble(),
      weight: json['weight'].toDouble(),
      bmi: json['bmi'].toDouble(),
    );
  }
}