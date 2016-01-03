@if ($tags)
    <ul>
        @foreach ($tags as $letter => $tagNameArray)
            <div class='letter-headers'>{{ $letter }}</div>
            <li class='divider' role='separator'></li>
            @foreach ($tagNameArray as $tag)
                <li>
                    <div class='tag'>
                        <a href="/tags?q={{ $tag }}">{{ $tag }}</a>
                    </div>
                </li>
            @endforeach
        @endforeach
    </ul>
@endif