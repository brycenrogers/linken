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
                    <input type="checkbox" tabindex="4" checked> Discoverable
                </label>
                <span class="scope" data-toggle="modal" data-target="#discoverable-options-modal">attributed</span>
                <label id="reminder-label" title="Sends an email reminder about this item">
                    <input type="checkbox" tabindex="5"> Send email reminder
                </label>
                <span class="scope" data-toggle="modal" data-target="#reminder-options-modal">today</span>
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
                <h4 class="modal-title" id="updatePhotoModalLabel">Discoverable Options</h4>
            </div>
            <div class="modal-body" style="position: relative;">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Reminder Options Modal -->
<div class="modal fade" id="reminder-options-modal" tabindex="-1" role="dialog" aria-labelledby="reminder-options-modal-label">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="updatePhotoModalLabel">Reminder Options</h4>
            </div>
            <div class="modal-body" style="position: relative;">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>