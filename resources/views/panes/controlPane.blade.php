<div class="container-control-pane">
    @if(Session::has('success'))
        <div class="alert alert-success col-md-12">
            {{ Session::get('success') }}
        </div>
    @elseif (Session::has('error'))
        <div class="alert alert-danger col-md-12">
            {{ Session::get('error') }}
        </div>
    @endif
    <div class="col-md-7">
        <form action="/search" method="get" class="form-inline pull-left" style="margin-bottom: 0;">
            <div class="form-group">
                <input type="input" class="form-control" id="searchField" placeholder="Search" name="q">
                <button type="submit" class="btn btn-default">Search</button>
            </div>
        </form>
    </div>
    <div class="container-account-pane col-md-5">
        <div class="dropdown pull-right">
            <button type="button" title="Logged in as {{ $user->name }}" class="user-photo" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="background-image: url({{ file_exists('uploads/' . $user->id . '.png') ? 'uploads/' . $user->id . '.png?' . time() : '/assets/images/default-user.png' }});">
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
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="changePasswordModalLabel">Change Password</h4>
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

<!-- Update Photo Modal -->
<div class="modal fade" id="updatePhotoModal" tabindex="-1" role="dialog" aria-labelledby="updatePhotoModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="updatePhotoModalLabel">Update Photo</h4>
                </div>
                <div class="modal-body" style="position: relative;">
                    <form action="/photo/upload" method="post" id="uploadPhotoForm">
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