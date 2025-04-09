<nav aria-label="Page navigation example">
  <ul class="pagination">

    <li class="page-item page-pre">
      @if($page ==1)
      <a class="page-link" data-page="1" href="javascript:void(0);" aria-label="Previous" disabled>
        <span aria-hidden="true"><i class="fa fa-angle-left mr-2"></i></span>
      </a>
      @else
      <a class="page-link" href="javascript:void(0);" data-page="{{ $page-1 }}" aria-label="Previous">
        <span aria-hidden="true"><i class="fa fa-angle-left mr-2"></i></span>
      </a>
      @endIf
    </li>

    @If($page >4)
    <li class="page-item"><a class="page-link" data-page="1" class="" href="javascript:void(0);">1</a></li>
    <li class="page-item"><a class="page-link" href="javascript:void(0);">...</a></li>
    @endIf

    @If($page-3>0)
    <li class="page-item"><a class="page-link" data-page="{{$page-3}}" href="javascript:void(0);">{{$page-3}}</a></li>
    @endIf
    @If($page-2>0)
    <li class="page-item"><a class="page-link" data-page="{{$page-2}}" href="javascript:void(0);">{{$page-2}}</a></li>
    @endIf
    @If($page-1>0)
    <li class="page-item"><a class="page-link" data-page="{{$page-1}}" href="javascript:void(0);">{{$page-1}}</a></li>
    @endIf

    <li class="page-item active"><a class="page-link" data-page="{{$page}}" href="javascript:void(0);">{{$page}}</a></li>

    @If($page+1<=$lastPage) 
    <li class="page-item "><a class="page-link" data-page="{{$page+1}}" href="javascript:void(0);">{{$page+1}}</a></li>
    @endIf
    @If($page+2<=$lastPage)
    <li class="page-item"><a class="page-link" data-page="{{$page+2}}" href="javascript:void(0);">{{$page+2}}</a></li>
    @endIf
    @If($page+3<=$lastPage) 
    <li class="page-item"><a class="page-link" data-page="{{$page+3}}" href="javascript:void(0);">{{$page+3}}</a></li>
    @endIf

    @If($lastPage > $page+3)
      @If($page<$lastPage)
      <li class="page-item"><a class="page-link" href="javascript:void(0);">...</a></li>
      <li class="page-item"><a class="page-link last" data-page="{{$lastPage}}" href="javascript:void(0);">{{$lastPage}}</a></li>
      @endIf
    @endIf
    <li class="page-item page-next">
      @If($page == $lastPage)
      <a class="page-link" data-page="{{$lastPage}}" aria-label="Next" href="javascript:void(0);" disabled>
        <span aria-hidden="true"><i class="fa fa-angle-right ml-2"></i></span>
      </a>
      @else
      <a class="page-link" aria-label="Next" data-page="{{ $page+1 }}" href="javascript:void(0);">
        <span aria-hidden="true"><i class="fa fa-angle-right ml-2"></i></span>
      </a>
      @endif
    </li>
  </ul>
</nav>