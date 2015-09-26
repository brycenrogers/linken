@extends('layouts.layout')

@section('title', ' - Signup')

@section('addPane')
    <div class="external-header">
        Signup for Linken
    </div>
@endsection

@section('pageContent')
    <div class="col-md-offset-3 col-md-6">
        <div class="panel panel-default">
            <div class="panel-body">
                @if (count($errors) > 0)
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <form class="form-horizontal" method="post" name="registerForm" action="/auth/register">
                    <div class="form-group">
                        <label for="inputName" class="col-sm-2 control-label">Name</label>

                        <div class="col-sm-10">
                            <input name="name" type="text" class="form-control" id="inputName" placeholder="Name">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputEmail" class="col-sm-2 control-label">Email</label>

                        <div class="col-sm-10">
                            <input name="email" type="email" class="form-control" id="inputEmail" placeholder="Email">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputPassword" class="col-sm-2 control-label">Password</label>

                        <div class="col-sm-6">
                            <input name="password" type="password" class="form-control" id="inputPassword" placeholder="Password">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                            <button type="submit" class="btn btn-primary">Signup</button>
                        </div>
                    </div>
                    {!! csrf_field() !!}
                </form>
            </div>
        </div>
    </div>
@endsection