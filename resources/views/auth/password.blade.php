@extends((( App::environment() == 'dev') ? 'layouts.layout' : 'layouts.layoutDist' ))

@section('title', ' - Forgot Password')

@section('pageContent')
    <div class="welcome-header">
        <span class="blue-header">Forgot Password?</span>
    </div>
    <div class="col-md-12">
        @if (count($errors) > 0)
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <div class="alert alert-info text-center">
            Enter the email address you registered with and we'll send you a link to reset your password
        </div>
        <form method="POST" action="/password/email" class="form-horizontal">
            {!! csrf_field() !!}
            <div class="form-group">
                <label for="email" class="col-sm-2 control-label">Email</label>
                <div class="col-sm-10">
                    <input name="email" type="email" class="form-control" id="email" placeholder="Email" value="{{ old('email') }}">
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                    <button type="submit" class="btn btn-primary">
                        Send Password Reset Link
                    </button>
                </div>
            </div>
        </form>
    </div>
@endsection