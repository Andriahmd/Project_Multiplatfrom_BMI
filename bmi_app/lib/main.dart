import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import 'package:bmi_app/controllers/auth_controller.dart';
import 'package:bmi_app/controllers/data_controller.dart';
import 'package:bmi_app/views/login_view.dart'; // Sudah ada
import 'package:bmi_app/views/register_view.dart'; // Tambahkan ini
import 'package:bmi_app/views/home_view.dart';

void main() {
  runApp(MyApp());
}

class MyApp extends StatelessWidget {
  final GlobalKey<NavigatorState> navigatorKey = GlobalKey<NavigatorState>();
  Widget build(BuildContext context) {
    return MultiProvider(
      providers: [
        ChangeNotifierProvider(create: (_) => AuthController()),
        ChangeNotifierProvider(create: (_) => DataController()),
      ],
      child: MaterialApp(
        navigatorKey: navigatorKey,
        title: 'BMI App',
        initialRoute: '/login',
        routes: {
          '/login': (context) => LoginView(),
          '/register': (context) => RegisterView(), 
          '/home': (context) => HomeView(),
        },
      ),
    );
  }
}