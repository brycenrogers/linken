<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Meta -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="x-ua-compatible" content="ie=edge">

    <!-- CSS -->
    <!-- build:css /assets/dist/css/linken.min.css -->
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/base.css') }}">
    <!-- /build -->

    <title>Linken @yield('title')</title>
</head>
<body>
<div id="add-fader"></div>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="container-main">
                <div class="container-header">
                    <!-- Header -->
                    <div class="linken-logo"></div>
                </div>
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
                    <div class="container">
                        <div class="row">
                            <div class="col-md-6 col-md-offset-3">
                                <div class="footer">
                                    &copy; Linken 2015
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- build:js /assets/js/linken.min.js -->
<script src="{{ asset('assets/js/libs/jquery.min.js') }}"></script>
<script src="{{ asset('assets/js/libs/velocity.min.js') }}"></script>
<script src="{{ asset('assets/js/libs/bootstrap.min.js') }}"></script>
<script src="{{ asset('assets/js/libs/select2.min.js') }}"></script>
<script src="{{ asset('assets/js/libs/autosize.min.js') }}"></script>
<script src="{{ asset('assets/js/libs/spin.min.js') }}"></script>
<script src="{{ asset('assets/js/add-pane.js') }}"></script>
<script src="{{ asset('assets/js/control-pane.js') }}"></script>
<!-- /build -->
</body>

</html>
