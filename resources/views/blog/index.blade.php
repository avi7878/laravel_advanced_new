@extends('layouts.main')
@section('title')
Blog
@endsection
@section('content')
<style>
    .parson-fashion ul {
        list-style: none;
        display: flex;
        flex-wrap: wrap;
        padding: 0;
    }

    .parson-fashion ul li {
        margin: 4px;
    }

    .parson-fashion ul li a {
        display: block;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 15px;
        /* Adjust the value based on your preference */
        text-decoration: none;
        color: #333;
        transition: background-color 0.3s, color 0.3s;
    }

    .parson-fashion ul li a:hover {
        background-color: #eee;
        color: #555;
    }
</style>

<section class="innerbanner uniblog-banner">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="bannaer-text2">
                    <h4 class="fw-bold py-3 mb-4">Blogs</h4>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="paddingtop uniblog-design">
    <div class="container">
        <div class="content-wapper">
            <div class="row">
                <div class="col-xxl-8 col-lg-8 col-md-12" id="pagination-ajax-container">
                    {{ view('blog/list',['blogList'=>$blogList]); }}
                </div>
                <div class="col-xxl-4 col-lg-4 col-md-12">
                    <div class="blog-sidebar">
                        <div class="card search-blog">
                            <div class="card-body">
                                <div class="search">
                                    <input name="s" onchange="pagination.search(this.value)" id="search-keyword" type="search" class="form-control" value="{{Request::get('s')}}" placeholder="To search type">
                                </div>
                            </div>
                        </div>
                        <div class="card cat-blog mt-3">
                            <div class="card-body parson-fashion">
                                <h5>Categories</h5>
                                <ul>
                                    @foreach($categoryList as $category)
                                    <li>      
                                        <button onclick="pagination.filter('category',{{$category->id}})" id="TagifyReadonly" class="noroute form-control btn-outline-primary btn-xs">
                                            {{ $category->category }}
                                        </button>
                                    </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                        <div class="card top-article mt-3">
                            <div class="card-body">
                                <h5>Top Article</h5>
                                @foreach($topArticles as $top)
                                <br>
                                <div class="row">
                                    <div class="col-md-3">
                                        <a href="blog/{{$top->slug }}">
                                            <img src="<?= $general->getFileUrl($top->image, 'blog') ?>" class="w-100" alt="">
                                        </a>
                                    </div>
                                    <div class="col-md-9">
                                        <div class="article-text">
                                            <a href="blog/{{$top->slug }}">
                                                <h6>{{$top->title}}</h6>
                                            </a>
                                            <p>{{ $top->created_at }}</p>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            <div class="card cat-blog mt-3">
                                <div class="card-body parson-fashion">
                                    <h5>Tags</h5>
                                    <ul>
                                        @if($allTags)

                                        @foreach(explode(',',$allTags) as $tag)
                                       
                                        <li> <button onclick="pagination.filterMultiple('tag','{{$tag}}')" style="display: inline-block; margin-bottom: 12px; border-color: #EAEAEA; padding: 4px 15px; border: 1px solid; border-radius: 20px;" class="noroute btn btn-block btn-outline-primary btn-xs">{{$tag}}</button>
                                        </li>
                                        @endforeach
                                        @endif
                                    </ul>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
</section>
@endsection
@push('scripts')
<script>
    var pagination = false;
    documentReady(function() {
        pagination = new Pagination();
        pagination.initLoadData=false;
        pagination.postData.filter = <?=  json_encode(Request::get('filter',[]))?>;
        pagination.init('blog/list');
    })
</script>
@endpush