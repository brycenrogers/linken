@extends((( App::environment() == 'dev') ? 'layouts.layout' : 'layouts.layoutDist' ))

@section('title', ' - Save and share links')

@section('featurePane')
        <div class="feature-container">
            <div class="col-md-12">
                <div class="blurb-container">
                    Linken helps you keep track of stuff
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
                @if (Session::get('error'))
                    <div class="alert alert-danger" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        {{ Session::get('error') }}
                    </div>
                @endif
                <div class="action-pane">
                    <button type="button"
                            aria-hidden="true"
                            class="btn btn-lg btn-success login-button"
                            data-toggle="modal"
                            data-target="#login-modal">
                        <span class="fa fa-sign-in"></span>&nbsp;
                        Login or Sign Up
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('pageContent')
    <div class="welcome-header">
        <span class="blue-header">All your links in one place. Notes too.</span>
    </div>
    <div class="col-md-12">
        @include('panes.welcomeAddPane')
        <div class="welcome-container">
            Linken makes it easy to quickly save and share links and notes from any page.<br>
            Click the blue bar above to try out the Linken interface.
        </div>
    </div>
    <div class="welcome-header">
        <span class="purple-header">Organized stream of consciousness</span>
    </div>
    <div class="col-md-12">
        <div class="welcome-container">
            Built with simplicity in mind, Linken makes bookmarking and idea organization a breeze.<br>
            <div class="sub-feature-container-header">Here are some ways Linken can help</div>
            <div class="sub-feature-container">
                <div class="sub-feature purple">
                    <div class="icon">
                        <span class="glyphicon glyphicon-globe"></span>
                    </div>
                    <div class="header">Browsing</div>
                    Save interesting articles for later
                </div>
                <div class="sub-feature green">
                    <div class="icon">
                        <span class="glyphicon glyphicon-road"></span>
                    </div>
                    <div class="header">Discovery</div>
                    Find neat stuff shared by other users
                </div>
                <div class="sub-feature orange">
                    <div class="icon">
                        <span class="glyphicon glyphicon-plane"></span>
                    </div>
                    <div class="header">Vacation Planning</div>
                    A place for all your points of interest
                </div>
                <div class="sub-feature blue">
                    <div class="icon">
                        <span class="glyphicon glyphicon-book"></span>
                    </div>
                    <div class="header">Research</div>
                    Reference relevant search hits in the future
                </div>
            </div>
            Tagging and a powerful search engine make it easy to find your stuff later.
        </div>
    </div>
    <div class="welcome-header">
        <span class="green-header">Discover and Share</span>
    </div>
    <div class="col-md-12">
        <div class="welcome-container">
            As you add links, Linken will find new stuff for you to discover based on your tags.<br>
            You can also choose whether your items are visible to other users, attributed or anonymously.<br>
            Quickly share via email or social media.
        </div>
    </div>
    <div class="welcome-header">
        <span class="orange-header">Free and Open Source</span>
    </div>
    <div class="col-md-12">
        <div class="welcome-container">
            Linken is designed, built and maintained by <a href="https://brycenrogers.com" target="_blank">Brycen Rogers</a>,
            a <a href="http://github.com/brycenrogers" target="_blank">software engineer</a> in Denver, CO. <br>The source code is <a href="http://github.com/brycenrogers/linken" target="_blank">available on Github</a>, supported by an
            <a href="https://github.com/brycenrogers/linken/blob/master/LICENSE.txt" target="_blank">Apache 2.0 license</a>, and running on
            <a href="https://laravel.com/" target="_blank">Laravel 5.2</a>.<br>
            Want to help? Donating or <a href="https://github.com/brycenrogers/linken/pulls" target="_blank">Contributing</a> helps keep Linken running.
        </div>
    </div>
    <!-- Login Modal -->
    <div class="modal fade" id="login-modal" tabindex="-1" role="dialog" aria-labelledby="login-modal-label">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form class="form-horizontal" method="post" action="/auth/login">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="item-settings-modal-label">
                            <span class="fa fa-sign-in"></span>&nbsp;
                            Linken Login
                        </h4>
                    </div>
                    <div class="modal-body">
                        <div class="">
                            <a class="btn btn-block btn-social btn-google" href="/oauth/google/auth">
                                <span class="fa fa-google"></span>Sign In with Google
                            </a>
                            <a class="btn btn-block btn-social btn-github" href="/oauth/github/auth">
                                <span class="fa fa-github"></span>Sign In with Github
                            </a>
                            <a type="button" class="btn btn-block btn-social btn-email"
                                data-toggle="modal"
                                data-target="#signup-modal"
                                data-dismiss="modal">
                                <span class="fa fa-envelope-o"></span>Sign Up with Email
                            </a>
                        </div>
                        <hr>
                        <div id="login-email-group" class="form-group">
                            <label for="login-email" class="col-sm-2 control-label">Email</label>
                            <div class="col-sm-10">
                                <input name="email" type="email" class="form-control" id="email" placeholder="Email" value="">
                            </div>
                        </div>
                        <div id="signup-password-group" class="form-group">
                            <label for="signup-password" class="col-sm-2 control-label">Password</label>
                            <div class="col-sm-8">
                                <input name="password" type="password" class="form-control" id="login-password" placeholder="Password"><br>
                                <a href="/password/email"
                                   id="forgot-password-button"
                                   type="button"
                                   title="Forgot Password?"
                                   data-target="#password-reset-modal">
                                   Forgot password?
                                </a>
                            </div>
                        </div>
                        {!! csrf_field() !!}
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Login</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Signup Modal -->
    <div class="modal fade" id="signup-modal" tabindex="-1" role="dialog" aria-labelledby="signup-modal-label">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="item-settings-modal-label">
                        <span class="fa fa-user-plus"></span>&nbsp;
                        Sign Up for Linken
                    </h4>
                </div>
                <div class="modal-body">
                    <form id="signup-form" class="form-horizontal" method="post" name="registerForm" action="/auth/register">
                        <div id="signup-name-group" class="form-group">
                            <label for="signup-name" class="col-sm-2 control-label">Name</label>
                            <div class="col-sm-10">
                                <input name="name" type="text" class="form-control" id="signup-name" placeholder="Name">
                            </div>
                        </div>
                        <div id="signup-email-group" class="form-group">
                            <label for="signup-email" class="col-sm-2 control-label">Email</label>
                            <div class="col-sm-10">
                                <input name="email" type="email" class="form-control" id="signup-email" placeholder="Email">
                            </div>
                        </div>
                        <div id="signup-password-group" class="form-group">
                            <label for="signup-password" class="col-sm-2 control-label">Password</label>
                            <div class="col-sm-8">
                                <input name="password" type="password" class="form-control" id="signup-password" placeholder="Password">
                            </div>
                        </div>
                        <div id="signup-password-confirm-group" class="form-group">
                            <label for="signup-password-confirm" class="col-sm-2 control-label">Confirm</label>
                            <div class="col-sm-8">
                                <input name="passwordConfirm" type="password" class="form-control" id="signup-password-confirm" placeholder="Confirm Password">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-10">
                                <div id="recaptcha" class="g-recaptcha" data-sitekey="6LdhShsTAAAAACN7gFIUOzaR0rJPDrJdeBj_MOWB"></div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-10">
                                By signing up you accept the <a href="#">Terms of Use</a>
                            </div>
                        </div>
                        <div id="signup-errors" class="alert alert-danger">
                            <button id="signup-errors-close" type="button" class="close" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            <div id="signup-errors-body"></div>
                        </div>
                        <input id="csrf_token" type="hidden" value="{!! csrf_token() !!}">
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button id="signup-submit-button" class="btn btn-success">Signup</button>
                </div>
            </div>
        </div>
    </div>
@endsection