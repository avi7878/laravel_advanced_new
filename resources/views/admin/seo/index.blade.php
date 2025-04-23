@extends('admin.layouts.main')
@section('title')
Seo meta
@endsection
@section('content')
<?php $sessionUser = auth()->user();?>
<!-- Content -->
<div class="breadcrumb-box">
  <h4 class="fw-bold py-3 mb-4">Seo meta</h4>
  <nav aria-label="breadcrumb">
    <ol class="breadcrumb">
      <li class="breadcrumb-item">
        <a href="admin/dashboard" >Dashboard</a>
      </li>
      <li class="breadcrumb-item active">Seo meta</li>
    </ol>
  </nav>
</div>
<!--  List Table -->
<div class="card">
    <div class="card-header justify-content-between">
      <h4 class="align-middle d-sm-inline-block">Seo meta</h4>
      @if($sessionUser->hasPermission('admin/seo/create'))
      <a href="admin/seo/create" class="btn btn-primary d-sm-inline-block pjax" style="float: inline-end;">Create</a>
      @endif
      @if($sessionUser->hasPermission('admin/seo/sitemap-generate'))
      <button type="button" class="btn btn-primary d-sm-inline-block" style="float: inline-end;margin-right: 10px;" onclick="$('#sitemapmodel').modal('show')">Sitemap</button>
      @endif
    </div>
    <div class="card-datatable mx-2">
        <table class="dt-fixedheader table table-bordered table-responsive" id="data-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Url</th>
                    <th>Title</th>
                    <th>Keyword</th>
                    <th>Discription</th>
                    <th>Sitemap</th>
                    <th>Actions</th>
                </tr>
            </thead>
        </table>
    </div>
</div>
<!-- / Content -->
<!--Bootstrap Tables-->
@endsection
@push('scripts')
<!-- Modal -->
<div class="modal fade" id="sitemapmodel" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="sitemapmodellabel">SiteMap</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                SiteMap Url : <a href="{{url('sitemap.xml')}}" class="noroute" target="_blank">{{url('sitemap.xml')}}</a>
                <!-- <button class="btn btn-primary" onclick="app.ajaxGet('admin/seo/sitemap-update');">Update SiteMap</button> -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary " data-bs-dismiss="modal" onclick="app.ajaxGet('admin/seo/sitemap-update');">Update SiteMap</button>
            </div>
        </div>
    </div>
</div>
<script>
    
documentReady(function() {
    datatableObj = $('#data-table').DataTable({
        ajax: dataTableAjax({
            url: '{{route("admin/seo/list")}}',
            method: 'post'
        }),
        columns: [{
                data: "id",
                responsivePriority: 6
            }, //,visible:false
            {
                data: "url",
                responsivePriority: 6
            }, //,visible:false
            {
                data: "title",
                responsivePriority: 6
            }, //,visible:false
            {
                data: "keyword",
                responsivePriority: 4
            },
            {
                data: "description",
                responsivePriority: 4
            },
            {
                data: "sitemap_enable",
                responsivePriority: 4
            },
            {
                data: "action",
                bSortable: false,
                responsivePriority: 2
            }
        ],
        responsive: true,
        serverSide: true,
        "order": [
            [0, "desc"]
        ],
    });
});
</script>
@endpush