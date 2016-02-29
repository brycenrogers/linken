<div id="info-pane" class="row" data-loaded="false" data-url="" data-open="false">
    <div id="" class="image-container col-md-1"></div>
    <div id="" class="title-container col-md-11">
        <input title="Add Title" class="title" type="text" value="">
        <textarea class="title-decode" style="display: none;"></textarea>
    </div>
</div>
<div id="blue-hitbox-add-pane"
     class="@if (Session::has('success')) {{ "flash flash-success" }} @elseif (Session::has('error')) {{ "flash flash-error" }} @endif">
    <div class="container-input-add col-md-12">
        <div id="flash"
             class="flash-message @if (Session::has('success')) {{ "success" }} @elseif (Session::has('error')) {{ "error" }} @endif">
            @if (Session::has('success')) {{ Session::get('success') }} @elseif (Session::has('error')) {{ Session::get('error') }} @endif
        </div>
        <textarea id="add" tabindex="1" class="" placeholder="Add URL or Note" rows="1"></textarea>
    </div>
</div>
<div id="add-pane" class="container-item-pane" data-toggle="closed">
    <div id="add-pane-container" class="row">
        <div class="col-md-12">
            <input type="hidden" id="add-pane-height" value="" />
            <textarea autocomplete="off"
                      autocorrect="off"
                      autocapitalize="off"
                      spellcheck="false"
                      tabindex="2"
                      class="form-control input-lg"
                      id="add-description"
                      placeholder="Description"
                      rows="1"></textarea>
            <br>
        </div>
        <div class="col-md-10">
            <select multiple title="Add Tags" class="form-control input-lg select2" id="add-tags" style="width: 100%; padding: 10px;" aria-hidden="true"></select>
        </div>
        <div class="col-md-2">
            <button tabindex="6" class="btn btn-success btn-lg btn-block" id="add-button">
                Add
            </button>
        </div>
        <div class="col-md-12">
            <div class="link-options">
                <div id="discovery-options-container">
                    <label id="discoverable-label" title="Allows others to see this item on their Discover page">
                        <input id="discoverable-checkbox" type="checkbox" tabindex="4"@if ($discovery_setting != 'off') {{ " checked" }} @endif> Allow others to discover link
                    </label>
                    <span class="scope" data-toggle="modal" data-target="#discoverable-options-modal">
                        <input type="hidden" id="discovery-setting-default" value="{{ $discovery_setting }}">
                        <input type="hidden" id="discovery-setting" value="{{ $discovery_setting }}">
                        <span id="discoverable-attributed-display"
                              class="@if ($discovery_setting == 'attributed') {{ "show" }} @else {{ "hide" }} @endif">with my name and photo &nbsp;<div class="glyphicon glyphicon-eye-open"></div></span>
                        <span id="discoverable-anonymous-display"
                              class="@if ($discovery_setting == 'anonymous') {{ "show" }} @else {{ "hide" }} @endif">anonymously &nbsp;<div class="glyphicon glyphicon-eye-close"></div></span>
                        <span id="discoverable-off-display"
                              class="@if ($discovery_setting == 'off') {{ "show" }} @else {{ "hide" }} @endif">This link will be private</span>
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>
<input id="csrf_token" type="hidden" value="{!! csrf_token() !!}" />

<!-- Discover Options Modal -->
<div class="modal fade" id="discoverable-options-modal" tabindex="-1" role="dialog" aria-labelledby="discoverable-options-modal-label">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="updatePhotoModalLabel">Discovery Options</h4>
            </div>
            <div class="modal-body" style="position: relative;">
                <div class="radio">
                    <label>
                        <input type="radio"
                               name="discoverable-setting"
                               id="discoverable-attributed"
                               class="discoverable-option"
                               value="attributed" @if ($discovery_setting == 'attributed') {{ "checked" }} @endif>
                        <strong>Show my Name and Photo</strong>
                        <br>
                        <span>Link may be shown on the Discover page with your name and photo</span>
                    </label>
                </div>
                <div class="radio">
                    <label>
                        <input type="radio"
                               name="discoverable-setting"
                               id="discoverable-anonymous"
                               class="discoverable-option"
                               value="anonymous" @if ($discovery_setting == 'anonymous') {{ "checked" }} @endif>
                        <strong>Anonymous</strong>
                        <br>
                        <span>Link may be shown on the Discover page, but without your name or photo</span>
                    </label>
                </div>
                <hr>
                You can change your default Discovery selection in the <a href="#" id="show-settings-modal-link">Settings</a> area
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>