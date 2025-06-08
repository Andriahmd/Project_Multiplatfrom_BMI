import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import 'package:bmi_app/controllers/auth_controller.dart';
import 'register_view.dart';

class LoginView extends StatefulWidget {
  @override
  _LoginViewState createState() => _LoginViewState();
}

class _LoginViewState extends State<LoginView> {
  final _formKey = GlobalKey<FormState>();
  String _email = '', _password = '';

  void _submit() async {
    if (_formKey.currentState!.validate()) {
      _formKey.currentState!.save();
      try {
        await Provider.of<AuthController>(context, listen: false).login(_email, _password);
        final user = Provider.of<AuthController>(context, listen: false).user;
        if (user != null) {
          Navigator.pushReplacementNamed(context, '/home');
        } else {
          ScaffoldMessenger.of(context).showSnackBar(SnackBar(content: Text('Login failed, user not found')));
        }
      } catch (e) {
        print('Login Error: $e');
        ScaffoldMessenger.of(context).showSnackBar(SnackBar(content: Text('Error: $e')));
      }
    }
  }

  @override
  Widget build(BuildContext context) {
    final isLoading = Provider.of<AuthController>(context).isLoading;

    return Scaffold(
      appBar: AppBar(title: Text('Login')),
      body: Padding(
        padding: EdgeInsets.all(16.0),
        child: Form(
          key: _formKey,
          child: Column(
            children: [
              TextFormField(
                decoration: InputDecoration(labelText: 'Email'),
                validator: (value) => !value!.contains('@') ? 'Enter valid email' : null,
                onSaved: (value) => _email = value!,
              ),
              TextFormField(
                decoration: InputDecoration(labelText: 'Password'),
                obscureText: true,
                validator: (value) => value!.length < 6 ? 'Password too short' : null,
                onSaved: (value) => _password = value!,
              ),
              SizedBox(height: 16),
              isLoading
                  ? CircularProgressIndicator()
                  : ElevatedButton(onPressed: _submit, child: Text('Login')),
              TextButton(
                onPressed: () => Navigator.push(
                  context,
                  MaterialPageRoute(builder: (_) => RegisterView()),
                ),
                child: Text('Switch to Register'),
              ),
            ],
          ),
        ),
      ),
    );
  }
}
