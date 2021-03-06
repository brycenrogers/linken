<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Meta -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="msapplication-TileImage" content="{{ asset('assets/images/linken-logo-144.png') }}"/>
    <meta name="msapplication-TileColor" content="#efefef"/>

    <!-- Icons -->
    <link rel="apple-touch-icon" href="{{ asset('assets/images/linken-logo-57.png') }}">
    <link rel="apple-touch-icon" sizes="72x72" href="{{ asset('assets/images/linken-logo-72.png') }}">
    <link rel="apple-touch-icon" sizes="114x114" href="{{ asset('assets/images/linken-logo-114.png') }}">

    <!-- CSS -->
    <!-- build:css /assets/dist/css/linken.min.css -->
    <link rel="stylesheet" href="{{ asset('assets/css/google-fonts-Montserrat.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/font-awesome.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap-social.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/base.css') }}">
    <!-- /build -->

    <!-- build:js /assets/dist/js/linken.min.js -->
    <script src="{{ asset('assets/js/libs/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/js/libs/velocity.min.js') }}"></script>
    <script src="{{ asset('assets/js/libs/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/js/libs/autosize.min.js') }}"></script>
    <script src="{{ asset('assets/js/libs/spin.min.js') }}"></script>
    <script src="{{ asset('assets/js/libs/jquery.cropit.js') }}"></script>
    <script src="{{ asset('assets/js/libs/add-to-any.min.js') }}"></script>
    <script src="{{ asset('assets/js/common.js') }}"></script>
    <script src="{{ asset('assets/js/add-pane.js') }}"></script>
    <script src="{{ asset('assets/js/control-pane.js') }}"></script>
    <script src="{{ asset('assets/js/item-pane.js') }}"></script>
    <script src="{{ asset('assets/js/settings.js') }}"></script>
    <script src="{{ asset('assets/js/discover.js') }}"></script>
    <script src="{{ asset('assets/js/welcome.js') }}"></script>
    <!-- /build -->

    <script src='https://www.google.com/recaptcha/api.js'></script>

    <title>Linken</title>
</head>
<body>
    <div id="add-fader"></div>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="container-main">
                    <div class="alert-pane"></div>
                    <div class="container-header">
                        <!-- Header -->
                        <a href="/" class="linken-logo"></a>
                    </div>
                    @yield('featurePane')
                    <div class="container-content">
                        <!-- Content -->
                        <div class="row">
                            <div class="col-md-12">
                                <!-- Add Pane -->
                                @yield('addPane')

                                <!-- Control Pane -->
                                @yield('controlPane')

                                <!-- Content -->
                                @yield('pageContent')
                            </div>
                        </div>
                    </div>
                    <div class="container-footer">
                        <!-- Footer -->
                        <div class="footer">
                            Linken &copy; {{ date("Y") }}
                            <br>
                            <div class="links">
                                <a href="/terms">Terms</a>
                                <a href="/help">Help</a>
                                <a href="/about">About</a>
                                <a href="/donate">Donate</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
