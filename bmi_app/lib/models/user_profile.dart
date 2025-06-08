class UserProfile {
  final int id;
  final int userId;
  final String name;
  final int age;
  final String bio;

  UserProfile({
    required this.id,
    required this.userId,
    required this.name,
    required this.age,
    required this.bio,
  });

  factory UserProfile.fromJson(Map<String, dynamic> json) {
    return UserProfile(
      id: json['id'],
      userId: json['user_id'],
      name: json['name'],
      age: json['age'],
      bio: json['bio'],
    );
  }
}