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
        <div class="text-center">
            Enter the email address you registered with and we'll send you a link to reset your password
        </div>
        <br>
        <form method="POST" action="/password/email" class="form-horizontal">
            {!! csrf_field() !!}
            <div class="form-group">
                <div class="col-sm-offset-3 col-sm-6">
                    <input name="email" type="email" class="form-control" id="email" placeholder="Email" value="{{ old('email') }}">
                    <br>
                    <div class="text-center">
                        <button type="submit" class="btn btn-primary">
                            Send Password Reset Link
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection