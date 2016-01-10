 <div class="container-control-pane">
        <nav class="navbar navbar-default">
            <div class="container-fluid">

                <!-- Brand and Hamburger Menu -->
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#control-pane-navbar-collapse" aria-expanded="false">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <div class="navbar-brand">
                        {{ $title }}
                    </div>
                </div>

                <div class="collapse navbar-collapse" id="control-pane-navbar-collapse">

                    <!-- Menu Options -->
                    <ul class="nav navbar-nav">
                        <li class="<?php if($title == 'List') echo " active"; ?>">
                            <a href="/" type="button" class="" title="List">
                                <span class="glyphicon glyphicon-list" aria-hidden="true"></span>
                                <span class="">&nbsp;List</span>
                            </a>
                        </li>
                        <li class="<?php if($title == 'Discover') echo " active"; ?>">
                            <a href="/discover" type="button" class="<?php if($title == 'Discover') { echo " active"; } ?>" title="Discover">
                                <span class="glyphicon glyphicon-road" aria-hidden="true"></span>
                                <span class="">&nbsp;Discover</span>
                            </a>
                        </li>
                        <li id="tags-dropdown-container" class="dropdown">
                            <a href="#" id="tags-dropdown" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                                <span class="glyphicon glyphicon-tags" aria-hidden="true"></span>
                                <span class="">&nbsp;Tags</span>
                                &nbsp;<span class="caret"></span>
                            </a>
                            <ul class="dropdown-menu">
                                <li>
                                    <div id="tags-dropdown-pane">
                                        <div id="tags-pane-spinner"></div>
                                    </div>
                                </li>
                            </ul>
                        </li>
                        <li class="<?php if($title == 'Help') echo " active"; ?>">
                            <a href="/help" type="button" class="<?php if($title == 'Help') { echo " active"; } ?>" title="Help">
                                <span class="glyphicon glyphicon-question-sign" aria-hidden="true"></span>
                                <span class="">&nbsp;Help</span>
                            </a>
                        </li>
                    </ul>

                    <!-- Account Options -->
                    <ul class="nav navbar-nav navbar-right">
                        <li id="account-dropdown-container" class="dropdown">
                            <a href="#" type="button" title="Logged in as {{ $user->name }}" class="account-button dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="user-photo" style="background-image: url('{!! asset('/assets/images/uploads/' . Auth::user()->user_photo) !!}');"></span>
                                <span class="visible-xs username">
                                    Account
                                    &nbsp;<span class="caret"></span>
                                </span>
                            </a>
                            <ul class="dropdown-menu">
                                <li>
                                    <span class="menu-username">{{ $user->name }}</span>
                                </li>
                                <li class="divider" role="separator"></li>
                                <li>
                                    <a href="#userSettingsModal" data-toggle="modal" aria-hidden="true">
                                        <span class="glyphicon glyphicon-cog" aria-hidden="true"></span> Settings
                                    </a>
                                </li>
                                <li>
                                    <a href="#changePasswordModal" data-toggle="modal" aria-hidden="true">
                                        <span class="glyphicon glyphicon-lock" aria-hidden="true"></span> Change Password
                                    </a>
                                </li>
                                <li>
                                    <a href="#updatePhotoModal" data-toggle="modal" aria-hidden="true">
                                        <span class="glyphicon glyphicon-camera" aria-hidden="true"></span> Update Photo
                                    </a>
                                </li>
                                <li role="separator" class="divider"></li>
                                <li>
                                    <a href="/auth/logout">
                                        <span class="glyphicon glyphicon-log-out" aria-hidden="true"></span>
                                        Logout
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>

                    <!-- Search Form -->
                    <form action="/search" method="get" class="navbar-form navbar-right" role="search">
                        <div class="input-group">
                            <input type="text" class="form-control" id="searchField" placeholder="Search" name="q" value="{{ (isset($q)) ? $q : null }}">
                            <span class="input-group-btn">
                                <button type="submit" class="btn btn-default"><span class="glyphicon glyphicon-search" aria-hidden="true"></span></button>
                            </span>
                        </div>
                    </form>

                </div>
            </div>
        </nav>
    <!-- User Settings Modal -->
    <div class="modal fade" id="userSettingsModal" tabindex="-1" role="dialog" aria-labelledby="userSettingsModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="userSettingsModalLabel">User Settings</h4>
                </div>
                <div class="modal-body">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Change Password Modal -->
    <div class="modal fade" id="changePasswordModal" tabindex="-1" role="dialog" aria-labelledby="changePasswordModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="form-horizontal">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="changePasswordModalLabel">Change Password</h4>
                    </div>
                    <div class="modal-body">
                            <div id="changePasswordError" class="alert alert-danger"></div>
                            {!! csrf_field() !!}
                            <div class="form-group">
                                <label for="email" class="col-sm-4 control-label">Current Password</label>
                                <div class="col-sm-8">
                                    <input name="old" type="password" class="form-control" id="oldPassword" placeholder="Current Password" value="{{ old('old') }}">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="email" class="col-sm-4 control-label">New Password</label>
                                <div class="col-sm-8">
                                    <input name="password" type="password" class="form-control" id="newPassword" placeholder="New Password" value="{{ old('password') }}">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="email" class="col-sm-4 control-label">Confirm New Password</label>
                                <div class="col-sm-8">
                                    <input name="password_confirmation" type="password" class="form-control" id="newPasswordConfirmation" placeholder="Confirm New Password" value="{{ old('password_confirmation') }}">
                                </div>
                            </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" id="changePasswordSubmit">Save New Password</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Update Photo Modal -->
    <div class="modal fade" id="updatePhotoModal" tabindex="-1" role="dialog" aria-labelledby="updatePhotoModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="updatePhotoModalLabel">Update Photo</h4>
                </div>
                <div class="modal-body" style="position: relative;">
                    <form action="/user/photo/upload" method="post" id="uploadPhotoForm">
                        <div id="image-cropper">
                            <div class="cropit-image-preview" data-clicked="false"></div>
                            <br>
                            <input type="range" class="cropit-image-zoom-input" />
                            <input type="file" class="cropit-image-input" name="file" />
                            <input type="hidden" id="photoDataURI" name="photoDataURI" value="" />
                            <input type="hidden" name="redirectTo" value="{{ $requestPath }}" />
                        </div>
                        {!! csrf_field() !!}
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success select-image-btn pull-left" style="margin: 0 auto;">
                        <span class="glyphicon glyphicon-picture white"></span>
                        Choose Image
                    </button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="updatePhotoSubmit">Save changes</button>
                </div>
            </div>
        </div>
    </div>
</div>