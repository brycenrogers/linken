<div class="container-item-pane container" id="item-pane-{{ $item->id }}">
    <div class="col-md-12">
        <div class="media link-padding">
            <div class="media-left">
                @if (get_class($item->itemable) == "App\Models\Link")
                    <?php $photo_url = asset('/assets/images/new-link.png') ?>
                    @if ($item->itemable->photo)
                        <?php $photo_url = asset('assets/images/thumbs/' . $item->itemable->photo) ?>
                    @endif
                    <a href="{{ $item->itemable->url }}">
                        <div class="media-link-image" style="background-image: url('{{ $photo_url }}')"></div>
                    </a>
                @else
                    <?php $photo_url = asset('/assets/images/new-note.png') ?>
                    <div class="media-link-image" style="background-image: url('{{ $photo_url }}')"></div>
                @endif
            </div>
            <div class="media-body">
                @if (get_class($item->itemable) == "App\Models\Link")
                    <h4 class="media-heading">
                        <a href="{{ $item->itemable->url }}">
                            {{ $item->value }}
                        </a>
                    </h4>
                    <div class="media-url">
                        <a href="{{ $item->itemable->url }}">{{ $item->itemable->url }}</a>
                    </div>
                @else
                    <h4 class="media-heading">
                        {{ $item->value }}
                    </h4>
                @endif
                @if ($item->description && $item->user->id == Auth::user()->id)
                    <div class="media-description">
                        {{ $item->description }}
                    </div>
                @endif
                <div class="media-footer">
                    @if (Auth::user()->id != $item->user->id)
                        <div class="media-userphoto">
                            <div class="user-photo" style="background-image: url('{{ asset('/assets/images/uploads/' . $item->user->user_photo) }}');"></div>
                        </div>
                    @endif
                    <div class="media-tags">
                        @foreach ($item->tags as $tag)
                            <a href="/tags?q={{ $tag->name }}" class="tag-link">
                                <span class="label label-tag">{{ $tag->name }}</span>
                            </a>
                        @endforeach
                    </div>
                    <div class="media-options">
                        @if ($item->user == Auth::user())
                        <a href="#item-settings-modal"
                            class="settings-link"
                            title="Settings"
                            data-toggle="modal"
                            data-itemid="{{ $item->id }}"
                            data-type="{{ (get_class($item->itemable) == "App\Models\Link") ? "Link" : "Note" }}"
                            data-value="{{ $item->value }}"
                            data-description="{{ $item->description }}"
                            data-tags="@foreach ($item->tags as $tag){{ $tag->name }},@endforeach">
                            <span class="glyphicon glyphicon-cog" aria-hidden="true"></span>
                        </a>
                        @endif
                        <a href="#item-share-modal-{{ $item->id }}"
                           class="share-link"
                           title="Share"
                           data-toggle="modal">
                            <span class="glyphicon glyphicon-share-alt" aria-hidden="true"></span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Item Share Modal -->
    <div class="modal fade item-share-modal" id="item-share-modal-{{ $item->id }}" tabindex="-1" role="dialog" aria-labelledby="item-share-modal-label">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="item-share-modal-label">Share</h4>
                </div>
                <div class="modal-body">
                    <label>Emails</label>
                    <hr>
                    <select name="emails"
                            class="share-emails form-control input-lg select2"
                            style="width: 100%; padding: 10px;"
                            aria-hidden="true" multiple></select>
                    <br>
                    <button class="btn btn-success">Send to Emails</button>
                    <label>Social</label>
                    <hr>
                    @if (get_class($item->itemable) == "App\Models\Link")
                        <div class="fb-share-button" data-href="{{ $item->itemable->url }}" data-title="{{ $item->value }}" data-layout="button"></div>
                        <a href="https://twitter.com/share" class="twitter-share-button" data-text="{{ $item->value }}" data-url="{{ $item->itemable->url }}">Tweet</a>
                    @else

                    @endif
                </div>
                <div class="modal-footer">
                    <input type="hidden" id="share-item-id" value="{{ $item->id }}">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</div>