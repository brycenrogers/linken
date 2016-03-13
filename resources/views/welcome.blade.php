@extends((( App::environment() == 'dev') ? 'layouts.layout' : 'layouts.layoutDist' ))

@section('title', ' - Save and share links')

@section('featurePane')
        <div class="feature-container">
            <div class="col-md-12">
                <div class="blurb-container">
                    Linken helps you remember things
                </div>
            </div>
            <div class="col-md-offset-2 col-md-8">
                @if (count($errors) > 0)
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <div class="login-pane">
                    <form class="form-horizontal" method="post" action="/auth/login">
                        <div class="col-md-5">
                            <input name="email" type="email" class="form-control" id="email" placeholder="Email" value="{{ old('email') }}">
                        </div>
                        <div class="col-md-5">
                            <input name="password" type="password" class="form-control" id="password" placeholder="Password">
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary">Login</button>
                        </div>
                        {{--<a href="/auth/register" type="button" class="btn btn-default">--}}
                            {{--Signup--}}
                        {{--</a>--}}
                        {{--<a href="/password/email" type="button" class="btn btn-default">--}}
                            {{--Reset Password--}}
                        {{--</a>--}}
                        {!! csrf_field() !!}
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('addPane')
@endsection

@section('pageContent')
    <div class="col-md-12">

    </div>
    <div class="col-md-7">
        What is this all about...
    </div>
@endsection