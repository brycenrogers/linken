<div class="container-control-pane">
    <div class="row no-margin">
        <div class="col-md-4">
            <div class="page-title">
                {{ $title }}
            </div>
        </div>
        <div class="col-md-7">
            <div class="btn-group" role="group" aria-label="...">
                <a href="/" type="button" class="btn btn-default<?php if($title == 'List') { echo " active"; } ?>" title="List">
                    <span class="glyphicon glyphicon-list" aria-hidden="true"></span>
                    &nbsp;List
                </a>
                <a href="/discover" type="button" class="btn btn-default<?php if($title == 'Discover') { echo " active"; } ?>" title="Discover">
                    <span class="glyphicon glyphicon-road" aria-hidden="true"></span>
                    &nbsp;Discover
                </a>
                <div class="btn-group">
                    <button title="Tags" id="tags-dropdown" type="button" class="btn btn-default dropdown-toggle<?php if($title == 'Tags') { echo " active"; } ?>" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <span class="glyphicon glyphicon-tags" aria-hidden="true"></span>
                        &nbsp;Tags
                        &nbsp;&nbsp;<span class="caret"></span>
                    </button>
                    <div class="dropdown-menu">
                        <div id="tags-dropdown-pane"></div>
                    </div>
                </div>
                <a href="/help" type="button" class="btn btn-default<?php if($title == 'Help') { echo " active"; } ?>" title="Help">
                    <span class="glyphicon glyphicon-question-sign" aria-hidden="true"></span>
                    &nbsp;Help
                </a>
            </div>
            <div class="pull-right">
                <form action="/search" method="get" class="form-inline" style="margin-bottom: 0;">
                    <div class="form-group">
                        <input type="input" class="form-control" id="searchField" placeholder="Search" name="q" value="{{ (isset($q)) ? $q : null }}">
                        <button type="submit" class="btn btn-default"><span class="glyphicon glyphicon-search" aria-hidden="true"></span></button>
                    </div>
                </form>
            </div>
        </div>
        <div class="container-account-pane col-md-1">
            <div class="dropdown pull-right">
                <button type="button" title="Logged in as {{ $user->name }}" class="user-photo" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="background-image: url('{!! asset('/assets/images/uploads/' . Auth::user()->user_photo) !!}');">
                </button>
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
                        <a href="/auth/logout">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
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
                <form method="POST" action="/settings/changePassword" class="form-horizontal">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="changePasswordModalLabel">Change Password</h4>
                    </div>
                    <div class="modal-body">
                        @if (count($errors) > 0)
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
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
                        <button type="submit" class="btn btn-primary">Save New Password</button>
                    </div>
                </form>
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