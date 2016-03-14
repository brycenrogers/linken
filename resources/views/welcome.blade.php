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
                    <div class="alert alert-danger" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        @foreach ($errors->all() as $errorKey => $error)
                            @if ($errorKey != 0) &amp; @endif
                            {{ $error }}
                        @endforeach
                    </div>
                @endif
                <div class="login-pane">
                    <form class="form-horizontal" method="post" action="/auth/login">
                        <div class="col-md-5">
                            <input name="email" type="email" class="form-control" id="email" placeholder="Email" value="{{ old('email') }}">
                        </div>
                        <div class="col-md-5">
                            <div class="input-group">
                                <input name="password" type="password" class="form-control" id="password" placeholder="Password">
                                <div class="input-group-btn">
                                    <a href="/password/email" id="forgot-password-button" class="btn btn-default" type="button" title="Forgot Password?" data-toggle="tooltip" data-target="#password-reset-modal">
                                        <span class="glyphicon glyphicon-question-sign" aria-hidden="true"></span>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary btn-block">Login</button>
                        </div>
                        {{--<a href="/auth/register" type="button" class="btn btn-default">--}}
                            {{--Signup--}}
                        {{--</a>--}}
                        {!! csrf_field() !!}
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('pageContent')
    <div class="welcome-header">
        <span class="blue-header">All your stuff in one place</span>
    </div>
    <div class="col-md-12">
        <div class="welcome-container">
            Linken makes it easy to quickly save and share links and notes. After you enter a link, Linken checks the
            site for a title and image to represent the link automatically. Click the suggested image to cycle through
            them, or change the title to something that suits you better. Quick tagging also makes it easy to categorize
            your content. Tagging also allows Linken to find you other links that may interest you.
        </div>
    </div>
    <div class="welcome-header">
        <span class="orange-header">Organized stream of consciousness</span>
    </div>
    <div class="col-md-12">
        <div class="welcome-container">
            The main List page on Linken shows your most recent content for quick reference. Use Linken to save news
            articles for a current event, for researching new topics, planning a vacation, or to save your favorite cat
            videos. Tagging and a powerful search engine make it easy to find your stuff later.
        </div>
    </div>
    <div class="welcome-header">
        <span class="green-header">Discover and Share</span>
    </div>
    <div class="col-md-12">
        <div class="welcome-container">
            As you add links to your list, Linken will find new links for you based on your tags. The Discover page
            shows a list of your top tags and links that other Linken users have added. You can also choose whether or
            not your links are visible to other users, attributed or anonymously. Also share anything with family or
            friends using the Share options.
        </div>
    </div>
    <div class="welcome-header">
        <span class="purple-header">Free and Open Source</span>
    </div>
    <div class="col-md-12">
        <div class="welcome-container">
            Linken is designed, built and maintained by Brycen Rogers, a software engineer in Denver, CO. The source
            code is available on Github, supported by an Apache 2.0 license, and running on Laravel 5.2 and Laravel
            Forge. Please consider contributing to help keep Linken up and running, or contact Brycen to say hi.
        </div>
    </div>
@endsection