<div id="info-pane" data-loaded="false" data-url="">
    <div id="info-image-container" class=""></div>
    <div id="info-title-container"></div>
</div>
<div id="blue-hitbox-add-pane">
    <div class="container-input-add col-md-12">
        <textarea id="add" tabindex="1" class="" placeholder="Add URL or Note" rows="1"></textarea>
    </div>
</div>
<div id="add-pane" class="container-link-pane" data-toggle="closed">
    <div id="add-pane-container" class="row">
        <div class="col-md-12">
            <input type="hidden" id="add-pane-height" value="" />
            <textarea tabindex="2" class="form-control input-lg" id="add-description" placeholder="Description" rows="1"></textarea>
            <br>
        </div>
        <div class="col-md-10">
            <select multiple tabindex="3" class="form-control input-lg select2" id="add-tags" style="width: 100%; padding: 10px;" aria-hidden="true"></select>
        </div>
        <div class="col-md-2">
            <button tabindex="4" class="btn btn-success btn-lg btn-block" id="add-button">
                Add
            </button>
        </div>
    </div>
</div>
<input id="csrf_token" type="hidden" value="{!! csrf_token() !!}" />