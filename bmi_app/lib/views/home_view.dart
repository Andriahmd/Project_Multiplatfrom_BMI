import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import 'package:bmi_app/controllers/data_controller.dart';

class HomeView extends StatefulWidget {
  @override
  _HomeViewState createState() => _HomeViewState();
}

class _HomeViewState extends State<HomeView> {
  @override
  void initState() {
    super.initState();
    Provider.of<DataController>(context, listen: false).fetchBmiRecords();
    Provider.of<DataController>(context, listen: false).fetchArticles();
    Provider.of<DataController>(context, listen: false).fetchRecommendations();
    Provider.of<DataController>(context, listen: false).fetchUserProfiles();
  }

  @override
  Widget build(BuildContext context) {
    final dataController = Provider.of<DataController>(context);

    return Scaffold(
      appBar: AppBar(title: Text('Home')),
      body: dataController.isLoading
          ? Center(child: CircularProgressIndicator())
          : ListView(
              children: [
                ListTile(
                  title: Text('BMI Records'),
                  subtitle: Column(
                    children: dataController.bmiRecords.map((record) => ListTile(
                      title: Text('BMI: ${record.bmi}'),
                      subtitle: Text('Height: ${record.height}, Weight: ${record.weight}'),
                    )).toList(),
                  ),
                ),
                ListTile(
                  title: Text('Articles'),
                  subtitle: Column(
                    children: dataController.articles.map((article) => ListTile(
                      title: Text(article.title),
                      subtitle: Text(article.content),
                    )).toList(),
                  ),
                ),
                ListTile(
                  title: Text('Recommendations'),
                  subtitle: Column(
                    children: dataController.recommendations.map((rec) => ListTile(
                      title: Text(rec.recommendationText),
                    )).toList(),
                  ),
                ),
                ListTile(
                  title: Text('User Profiles'),
                  subtitle: Column(
                    children: dataController.userProfiles.map((profile) => ListTile(
                      title: Text(profile.name),
                      subtitle: Text('Age: ${profile.age}, Bio: ${profile.bio}'),
                    )).toList(),
                  ),
                ),
              ],
            ),
    );
  }
}