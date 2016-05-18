@extends((( App::environment() == 'dev') ? 'layouts.layout' : 'layouts.layoutDist' ))

@section('title', $title)

@section('addPane')
    @include('panes.addPane')
@endsection

@section('controlPane')
    @include('panes.controlPane')
@endsection

@section('pageContent')

    <div id="item-list-container">
        @each('item', $items, 'item')
    </div>

    @if ( ! is_array($items))
        <div id="pager-container">
            {!! $items->render() !!}
        </div>
    @endif
        <!-- Item Settings Modal -->
        <div class="modal fade" id="item-settings-modal" tabindex="-1" role="dialog" aria-labelledby="item-settings-modal-label">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div id="item-settings-errors" class="alert alert-danger"></div>
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="item-settings-modal-label"></h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <div id="settings-image" class="media-link-image" style=""></div>
                        </div>
                        <div class="form-group">
                            <label for="settings-value">Title</label>
                            <textarea name="value" id="settings-value" class="form-control input-lg" rows="4"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="settings-description">Description</label>
                            <textarea name="description" id="settings-description" class="form-control input-lg" rows="4"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="settings-tags">Tags</label>
                            <textarea name="tags" id="settings-tags" class="form-control input-lg" rows="4"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" id="settings-item-id" value="">
                        <button type="button" id="destroy-item" class="btn btn-danger pull-left">Delete</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" id="update-link-settings-submit">Save changes</button>
                    </div>
                </div>
            </div>
        </div>
@endsection