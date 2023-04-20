@extends('layout.app')
@section("content")
    <div class="container custom-login">
        <div class="row">
            <div class="col-sm-4 col-sm-offset-4">
                <div class="form-group">
                    <label for="exampleInputEmail1">Email address</label>
                    <input type="email" name="email" class="form-control email" id="exampleInputEmail1" placeholder="Email">
                </div>
                <div class="form-group">
                    <label for="exampleInputPassword1">Password</label>
                    <input type="password" name="password" class="form-control password" id="exampleInputPassword1" placeholder="Password">
                </div>
                <button class="btn btn-default login" onclick="login()">Login</button>
            </div>
        </div>
    </div>
@endsection
