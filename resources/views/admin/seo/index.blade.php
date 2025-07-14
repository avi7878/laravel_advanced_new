@extends('admin.layouts.main')
@section('title')
Seo meta
@endsection
@section('content')
<?php $sessionUser = auth()->user(); ?>
<!-- Content -->
<div class="breadcrumb-box">
  <h4 class="fw-bold py-3 mb-4">Seo meta</h4>
  <nav aria-label="breadcrumb">
    <ol class="breadcrumb">
      <li class="breadcrumb-item">
        <a href="admin/dashboard" class="pjax">Dashboard</a>
      </li>
      <li class="breadcrumb-item active">Seo meta</li>
    </ol>
  </nav>
</div>

<!-- Seo Meta List Table -->
<div class="card">
  <div class="card-header justify-content-between">
                <h4 class="align-middle d-sm-inline-block d-none">Seo Meta</h4>
            @if ($sessionUser->hasPermission('admin/seo/create')) 
                <a href="admin/seo/create" class="btn btn-primary d-sm-inline-block d-none pjax ms-3"
                    style="float: inline-end;" aria-label="Create SEO Meta">Create</a>
            @endif

            @if ($sessionUser->hasPermission('admin/seo/sitemap-generate'))
                <button class="btn buttons-collection btn-label-primary float-end" aria-label="Generate Sitemap"
                    onclick="$('#sitemapmodel').modal('show')">
                    <span class="d-flex align-items-center gap-2">
                        <span class="d-none d-sm-inline-block">Sitemap</span>
                    </span>
                </button>
            @endif
        </div>

  <div class="card-datatable table-responsive">
    <table class="datatable-list-table table border-top" id="seo-data-table">
      <thead>
        <tr>
          <th>#</th>
          <th>Url</th>
          <th>Title</th>
          <th>Keyword</th>
          <th>Sitemap</th>
          <th>Actions</th>
        </tr>
      </thead>
    </table>
  </div>
</div>

<!-- / Content -->
@endsection

@push('scripts')
<!-- Sitemap Modal -->
<div class="modal fade" id="sitemapmodel" tabindex="-1" role="dialog" aria-labelledby="sitemapmodellabel"
  aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">SiteMap</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        SiteMap URL: 
        <a href="{{ url('sitemap.xml') }}" class="noroute pjax" target="_blank">{{ url('sitemap.xml') }}</a>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" data-bs-dismiss="modal"
          onclick="app.ajaxGet('admin/seo/sitemap-update');">Update SiteMap</button>
      </div>
    </div>
  </div>
</div>

<!-- DataTable Init -->
<script>
  documentReady(function () {
    datatableObj = $('#seo-data-table').DataTable({
      ajax: dataTableAjax({
        url: '{{ route("admin/seo/list") }}',
        method: 'post'
      }),
      columns: [
        { data: "id", responsivePriority: 6 },
        { data: "url", responsivePriority: 6 },
        { data: "title", responsivePriority: 6 },
        { data: "keyword", responsivePriority: 4 },
        { data: "sitemap_enable", responsivePriority: 4 },
        { data: "action", sortable: false, responsivePriority: 2 }
      ],
      responsive: true,
      serverSide: true,
      order: [[0, "desc"]]
    });
  });
</script>
@endpush
