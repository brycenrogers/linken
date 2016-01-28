<div id="info-pane" class="row" data-loaded="false" data-url="" data-open="false">
    <div id="info-image-container" class="col-md-1"></div>
    <div id="info-title-container" class="col-md-11">
        <input id="info-title" type="text" value="">
        <textarea id="info-title-decode" style="display: none;"></textarea>
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
            <textarea tabindex="2" class="form-control input-lg" id="add-description" placeholder="Description" rows="1"></textarea>
            <br>
        </div>
        <div class="col-md-10">
            <select multiple class="form-control input-lg select2" id="add-tags" style="width: 100%; padding: 10px;" aria-hidden="true"></select>
        </div>
        <div class="col-md-2">
            <button tabindex="4" class="btn btn-success btn-lg btn-block" id="add-button">
                Add
            </button>
        </div>
        <div class="col-md-12">
            <div class="link-options">
                <label id="reminder-label">
                    <input type="checkbox"> Reminder
                    <span class="glyphicon glyphicon-question-sign"></span>
                </label>
                <label id="discoverable-label">
                    <input type="checkbox" checked> Discoverable
                    <span class="glyphicon glyphicon-question-sign"></span>
                </label>
            </div>
        </div>
    </div>
</div>
<input id="csrf_token" type="hidden" value="{!! csrf_token() !!}" />