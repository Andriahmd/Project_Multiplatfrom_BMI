class Article {
  final int id;
  final int userId;
  final String title;
  final String content;

  Article({
    required this.id,
    required this.userId,
    required this.title,
    required this.content,
  });

  factory Article.fromJson(Map<String, dynamic> json) {
    return Article(
      id: json['id'],
      userId: json['user_id'],
      title: json['title'],
      content: json['content'],
    );
  }
}