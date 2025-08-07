
@extends('layouts.main')
@section('title')
Blog View
@endsection
@section('content')
<!-- ============================ end header============================ -->
{{-- <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Blog / </span> Details</h4> --}}
<section class=" paddingtop blogtext">
    <div class="container">
        <div class="row">
          <nav aria-label="breadcrumb fw-bold my-3 mb-4">
            <ol class="breadcrumb">
              <li class="breadcrumb-item">
                <a href="blog" class="pjax">Blog</a>
              </li>
              <li class="breadcrumb-item active">Details</li>
            </ol>
          </nav>
    <div class="card mb-4 col-md-7" style="padding:0;">
        <div class="demo-image" style="height: 416px; width: 731px; max-width: 100%; object-fit: cover;">
            <img class="img-fluid" style="height:80%; width: 100%;" src="<?= $general->getFileUrl($model->image, 'blog') ?>" alt="Card image cap" />
        </div>
        <div class="row">
           
          <div class="col-md-6">
            <p class="card-header fw-bold " style="font-size:20px;">Title: {{$model->title}}</p>
          </div>
          <div class="col-md-5 text-end mt-4">Tags:
            <?php
            $tags = explode(',', $model->tags);
            foreach ($tags as $tag) {
              echo '<span class="badge bg-label-primary">' . trim($tag) . '</span> ';
            }
            ?>

          </div>
        </div>
        <p class="fw-bold" style="margin-left:22px;"> Category: {{$blogCategory->category}} </p>
        <p class="fw-bold" style="margin-left:22px;">Create At: {{ date('d-m-Y h:i a', strtotime($model->created_at)) }}</p>
        <div class="card-body">
          <p>
            <?= $model->description ?>
          </p>

        </div>
      </div>
  </div>
</div>
</section>

<!-- ============================ footer ============================ -->

@endsection
