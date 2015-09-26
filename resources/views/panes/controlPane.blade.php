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
                <span class="glyphicon glyphicon-user" aria-hidden="true"></span> {{ $user->name }}
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
                    <a href="/auth/logout">Logout</a>
                </li>
            </ul>
        </div>
    </div>
</div>