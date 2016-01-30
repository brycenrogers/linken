@extends('layouts.layout')

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
                            <label for="settings-value">Title</label>
                            <textarea name="value" id="settings-value" class="form-control input-lg" rows="4"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="settings-description">Description</label>
                            <textarea name="description" id="settings-description" class="form-control input-lg" rows="4"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="edit-tags">Tags</label>
                            <select name="tags" multiple class="form-control input-lg select2" id="edit-tags" style="width: 100%; padding: 10px;" aria-hidden="true"></select>
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

        <!-- Item Share Modal -->
        <div class="modal fade" id="item-share-modal" tabindex="-1" role="dialog" aria-labelledby="item-share-modal-label">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="item-share-modal-label">Share</h4>
                    </div>
                    <div class="modal-body">
                        <label for="share-emails">Emails</label>
                        <select id="share-emails"
                                name="emails"
                                class="form-control input-lg select2"
                                style="width: 100%; padding: 10px;"
                                aria-hidden="true" multiple></select>
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" id="share-item-id" value="">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-success">Send</button>
                    </div>
                </div>
            </div>
        </div>
@endsection