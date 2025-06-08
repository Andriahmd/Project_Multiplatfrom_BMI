import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import 'package:bmi_app/controllers/auth_controller.dart';
import 'login_view.dart';

class RegisterView extends StatefulWidget {
  @override
  _RegisterViewState createState() => _RegisterViewState();
}

class _RegisterViewState extends State<RegisterView> {
  final _formKey = GlobalKey<FormState>();
  String _name = '', _email = '', _password = '';

  void _submit() async {
  if (_formKey.currentState!.validate()) {
    _formKey.currentState!.save();
    try {
      await Provider.of<AuthController>(context, listen: false).register(_name, _email, _password);
      if (Provider.of<AuthController>(context, listen: false).user != null) {
        Navigator.pushReplacement(context, MaterialPageRoute(builder: (_) => LoginView()));
      } else {
        ScaffoldMessenger.of(context).showSnackBar(SnackBar(content: Text('Registration failed, user not set')));
      }
    } catch (e) {
      print('Register Error: $e'); // Tambahkan logging
      ScaffoldMessenger.of(context).showSnackBar(SnackBar(content: Text('Error: $e')));
    }
  }
}

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: Text('Register')),
      body: Padding(
        padding: EdgeInsets.all(16.0),
        child: Form(
          key: _formKey,
          child: Column(
            children: [
              TextFormField(
                decoration: InputDecoration(labelText: 'Name'),
                validator: (value) => value!.isEmpty ? 'Enter name' : null,
                onSaved: (value) => _name = value!,
              ),
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
              ElevatedButton(onPressed: _submit, child: Text('Register')),
              TextButton(
                onPressed: () => Navigator.push(context, MaterialPageRoute(builder: (_) => LoginView())),
                child: Text('Switch to Login'),
              ),
            ],
          ),
        ),
      ),
    );
  }
}