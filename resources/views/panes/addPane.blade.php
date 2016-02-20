<div id="info-pane" class="row" data-loaded="false" data-url="" data-open="false">
    <div id="" class="image-container col-md-1"></div>
    <div id="" class="title-container col-md-11">
        <input class="title" type="text" value="">
        <textarea class="title-decode" style="display: none;"></textarea>
    </div>
</div>
<div id="blue-hitbox-add-pane" class="<?php
            if (Session::has('success')) {
                echo "flash flash-success";
            } else if (Session::has('error')) {
                echo "flash flash-error";
            } ?>">
    <div class="container-input-add col-md-12">
        <div id="flash" class="flash-message<?php
            if (Session::has('success')) {
                echo " success";
            } else if (Session::has('error')) {
                echo " error";
            } ?>">
            <?php
            if (Session::has('success')) {
                echo Session::get('success');
            } else if (Session::has('error')) {
                echo Session::get('error');
            }
            ?>
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
            <select multiple class="form-control input-lg select2" id="add-tags" style="width: 100%; padding: 10px;" aria-hidden="true"></select>
        </div>
        <div class="col-md-2">
            <button tabindex="6" class="btn btn-success btn-lg btn-block" id="add-button">
                Add
            </button>
        </div>
        <div class="col-md-12">
            <div class="link-options">
                <label id="discoverable-label" title="Allows others to see this item on their Discover page">
                    <input type="checkbox" tabindex="4"@if ($discovery_setting != 'off') {{ " checked" }} @endif> Allow others to discover -
                </label>
                <span class="scope" data-toggle="modal" data-target="#discoverable-options-modal">
                    @if ($discovery_setting == 'attributed')
                        show my name and photo
                    @elseif ($discovery_setting == 'anonymous')
                        anonymously
                    @else
                        off
                    @endif
                </span>
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
                               data-display="show my name and photo"
                               value="attributed" checked>
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
                               data-display="anonymously"
                               value="anonymous">
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