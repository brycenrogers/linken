<div class="col-md-offset-2 col-md-8">
    <div class="click-me">
        <span class="click-span">Click the blue bar</span>
        <br>
        <span class="glyphicon glyphicon-arrow-down"></span>
    </div>

    <div class="welcome-add-pane-container row-fluid">
        <div id="info-pane" class="" data-loaded="false" data-url="" data-open="false">
            <div id="" class="image-container col-md-1"></div>
            <div id="" class="title-container col-md-10">
                <input title="Add Title" class="title" type="text" value="">
                <textarea class="title-decode" style="display: none;"></textarea>
            </div>
        </div>
        <div id="welcome-blue-hitbox">
            <div class="container-input-add col-md-12">
                <div id="flash" class="flash-message"></div>
                <textarea id="add-welcome" tabindex="1" class="" placeholder="Add URL or Note" rows="1"></textarea>
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
                    <select multiple title="Add Tags" class="form-control input-lg select2" id="add-tags-welcome" style="width: 100%; padding: 10px;" aria-hidden="true"></select>
                </div>
                <div class="col-md-2">
                    <button tabindex="6" class="btn btn-success btn-lg btn-block" id="welcome-add-button">
                        Add
                    </button>
                </div>
                <div class="col-md-12">
                    <div class="link-options">
                        <div id="discovery-options-container">
                            <label id="discoverable-label" title="Allows others to see this item on their Discover page">
                                <input id="discoverable-checkbox" type="checkbox" tabindex="4"> Allow others to discover link
                            </label>
                            <span class="scope" data-toggle="modal" data-target="#discoverable-options-modal">
                                <input type="hidden" id="discovery-setting-default" value="">
                                <input type="hidden" id="discovery-setting" value="">
                                <span id="discoverable-attributed-display" class="">with my name and photo &nbsp;<div class="glyphicon glyphicon-eye-open"></div></span>
                                <span id="discoverable-anonymous-display" class="">anonymously &nbsp;<div class="glyphicon glyphicon-eye-close"></div></span>
                                <span id="discoverable-off-display" class="">This link will be private</span>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="control-pane-container-lg"></div>
        <div class="control-pane-container-sm">
            <div class="title"></div>
            <div class="menu"></div>
        </div>
    </div>
</div>