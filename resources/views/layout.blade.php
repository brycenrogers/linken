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
                            <div id="info-pane">

                            </div>
                            <div id="blue-hitbox-add-pane">
                                <div class="container-input-add col-md-12">
                                    <textarea id="add" tabindex="1" class="" placeholder="Add URL or Note" rows="1"></textarea>
                                </div>
                            </div>
                            <div id="add-pane" class="container-link-pane" data-toggle="closed">
                                <div id="add-pane-container">
                                    <input type="hidden" id="add-pane-height" value="" />
                                    <textarea tabindex="2" class="form-control input-lg" id="add-description" placeholder="Description" rows="1"></textarea>
                                    <br>
                                    <select multiple tabindex="3" class="form-control input-lg select2" id="add-tags" style="width: 100%; padding: 10px;" aria-hidden="true"></select>
                                    <br><br>
                                    <button tabindex="4" class="btn btn-success btn-lg" id="add-button">
                                        &nbsp;&nbsp;&nbsp;Add&nbsp;&nbsp;&nbsp;
                                    </button>
                                </div>
                            </div>
                            <!-- Control Pane -->
                            <div class="container-control-pane">
                                <div class="container-tags-pane col-md-7">
                                    <div class="btn-group" role="group" aria-label="..." style="width: 100%">
                                        <button type="button" class="btn btn-default active">All</button>
                                        <button type="button" class="btn btn-default">Programming</button>
                                        <button type="button" class="btn btn-default">Funny</button>
                                        <button type="button" class="btn btn-default">...</button>
                                    </div>
                                </div>
                                <div class="container-account-pane col-md-5">
                                    <form class="form-inline pull-left">
                                        <div class="form-group">
                                            <input type="input" class="form-control" id="searchField" placeholder="Search">
                                        </div>
                                        <button type="submit" class="btn btn-default">Search</button>
                                    </form>
                                    <div class="btn-group pull-right">
                                        <button type="button" class="btn btn-default">
                                            <span class="glyphicon glyphicon-user" aria-hidden="true"></span> Brycen Rogers
                                        </button>
                                        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <span class="caret"></span>
                                            <span class="sr-only">Toggle Dropdown</span>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li>
                                                <a href="#">
                                                    <span class="glyphicon glyphicon-cog" aria-hidden="true"></span> Settings
                                                </a>
                                            </li>
                                            <li>
                                                <a href="#">
                                                    <span class="glyphicon glyphicon-lock" aria-hidden="true"></span> Change Password
                                                </a>
                                            </li>
                                            <li>
                                                <a href="#">
                                                    <span class="glyphicon glyphicon-camera" aria-hidden="true"></span> Update Photo
                                                </a>
                                            </li>
                                            <li role="separator" class="divider"></li>
                                            <li>
                                                <a href="#">Logout</a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="container-link-pane container">
                                <div class="col-md-12">
                                    <div class="media link-padding">
                                        <div class="media-left">
                                            <a href="#">
                                                <img class="media-object" src="" alt="">
                                            </a>
                                        </div>
                                        <div class="media-body">
                                            <h4 class="media-heading">Middle aligned media</h4>
                                            Cras sit amet nibh libero, in gravida nulla. Nulla vel metus scelerisque ante sollicitudin commodo.
                                            Cras purus odio, vestibulum in vulputate at, tempus viverra turpis. Fusce condimentum nunc ac nisi vulputate fringilla.
                                            Donec lacinia congue felis in faucibus. Donec sed odio dui. Nullam quis risus eget urna mollis ornare vel eu leo.
                                            Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus.
                                            <div class="media-url">
                                                google.com/test/stuff
                                            </div>
                                            <div class="media-tags">
                                                programming, apple, stuff
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="container-link-pane">
                                <div class="col-md-12">
                                    <div class="media link-padding">
                                        <div class="media-left">
                                            <a href="#">
                                                <img class="media-object" src="" alt="">
                                            </a>
                                        </div>
                                        <div class="media-body">
                                            <h4 class="media-heading">Middle aligned media</h4>
                                            Cras sit amet nibh libero, in gravida nulla. Nulla vel metus scelerisque ante sollicitudin commodo.
                                            Cras purus odio, vestibulum in vulputate at, tempus viverra turpis. Fusce condimentum nunc ac nisi vulputate fringilla.
                                            Donec lacinia congue felis in faucibus. Donec sed odio dui. Nullam quis risus eget urna mollis ornare vel eu leo.
                                            Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus.
                                            <div class="media-url">
                                                google.com/test/stuff
                                            </div>
                                            <div class="media-tags">
                                                programming, apple, stuff
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="container-link-pane">
                                <div class="col-md-12">
                                    <div class="media link-padding">
                                        <div class="media-left">
                                            <a href="#">
                                                <img class="media-object" src="" alt="">
                                            </a>
                                        </div>
                                        <div class="media-body">
                                            <h4 class="media-heading">Middle aligned media</h4>
                                            Cras sit amet nibh libero, in gravida nulla. Nulla vel metus scelerisque ante sollicitudin commodo.
                                            Cras purus odio, vestibulum in vulputate at, tempus viverra turpis. Fusce condimentum nunc ac nisi vulputate fringilla.
                                            Donec lacinia congue felis in faucibus. Donec sed odio dui. Nullam quis risus eget urna mollis ornare vel eu leo.
                                            Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus.
                                            <div class="media-url">
                                                google.com/test/stuff
                                            </div>
                                            <div class="media-tags">
                                                programming, apple, stuff
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="container-link-pane">
                                <div class="col-md-12">
                                    <div class="media link-padding">
                                        <div class="media-left">
                                            <a href="#">
                                                <img class="media-object" src="" alt="">
                                            </a>
                                        </div>
                                        <div class="media-body">
                                            <h4 class="media-heading">Middle aligned media</h4>
                                            Cras sit amet nibh libero, in gravida nulla. Nulla vel metus scelerisque ante sollicitudin commodo.
                                            Cras purus odio, vestibulum in vulputate at, tempus viverra turpis. Fusce condimentum nunc ac nisi vulputate fringilla.
                                            Donec lacinia congue felis in faucibus. Donec sed odio dui. Nullam quis risus eget urna mollis ornare vel eu leo.
                                            Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus.
                                            <div class="media-tags">
                                                programming, apple, stuff
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
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
<script src="{{ asset('assets/js/add-pane.js') }}"></script>
<!-- /build -->
</body>

</html>
