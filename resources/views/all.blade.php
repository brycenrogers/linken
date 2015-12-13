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

    @if (!is_array($items))
        <div id="pager-container">
            {!! $items->render() !!}
        </div>
    @endif
            <!-- Item Settings Modal -->
        <div class="modal fade" id="itemSettingsModal" tabindex="-1" role="dialog" aria-labelledby="itemSettingsModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="itemSettingsModalLabel"></h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="settingsValue">Title</label>
                            <textarea name="value" id="settingsValue" class="form-control input-lg" rows="4"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="settingsValue">Description</label>
                            <textarea name="description" id="settingsDescription" class="form-control input-lg" rows="6"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <a href="" id="destroyItem" class="btn btn-danger">Delete</a>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary">Save changes</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Item Share Modal -->
        <div class="modal fade" id="itemShareModal" tabindex="-1" role="dialog" aria-labelledby="itemShareModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="itemShareModalLabel">Share</h4>
                    </div>
                    <div class="modal-body">

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-success">Send</button>
                    </div>
                </div>
            </div>
        </div>
@endsection