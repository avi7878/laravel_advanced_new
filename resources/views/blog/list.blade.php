<div class="row row-cols-1 row-cols-md-2 g-4 mb-5">
@foreach($blogList['data'] as $list)
     <div class="col">
            <div class="card h-100">
                <a class="router" href="blog/{{ $list->slug }}"><img class="card-img-top" src="{{$general->getFileUrl($list->image, 'blog') }}" alt="Card image cap"></a>
                <div class="card-body">
                    <a href="blog/{{ $list->slug }}"><h5 class="card-title">{{ $list->title }}</h5></a>
                    <p class="card-text">
                       <?php $shortDesc = Str::limit(strip_tags($list->description), 200); ?>
                        {{ $shortDesc }}
                        @if(strlen(strip_tags($list->description)) > 200)
                            <a href="blog/{{ $list->slug }}" class="item">Read More</a>
                        @endif
                    </p>
                    <div class="date-r">
                        <a href="javascript:void(0);">{{ $list->created_at }}</a>
                    </div>
                </div>
            </div>
        </div>
@endforeach
</div>
<nav aria-label="Page navigation">
    <?= @$blogList['links']; ?>
</nav>