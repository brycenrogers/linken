@extends((( App::environment() == 'dev') ? 'layouts.layout' : 'layouts.layoutDist' ))

@section('title', $title)

@section('addPane')
    @include('panes.addPane')
@endsection

@section('controlPane')
    @include('panes.controlPane')
@endsection

@section('pageContent')
    <div class="row">
        <div class="col-md-8">
            <div class="blurb">
                <span class="pull-left">
                    Discover links added by other users based on your tagging interests
                </span>
            </div>
        </div>
        <div class="col-md-4">
            <div class="discover-tag-container">
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div id="discover-container">
                @foreach ($items as $tag => $itemArray)
                    <div class="discover-header">
                        <div class="discover-header-container">
                            {{ $tag }}
                        </div>
                    </div>
                    <div class="discover-list-item">
                        <div class="discover-list-container">
                            @each('item', $itemArray, 'item')
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection