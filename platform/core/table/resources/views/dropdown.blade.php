<div class="dropdown dropdown-hover">
    <a href="javascript:;">{{ $title }}
        <i class="fa fa-angle-right"></i>
    </a>
    <div class="dropdown-content">
        @foreach ($links as $link)
            {{ $link }}
        @endforeach
    </div>
</div>