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
<!--  List Table -->
<div class="card">
    <div class="card-datatable text-nowrap">
        <div id="DataTables_Table_0_wrapper" class="dt-container dt-bootstrap5 dt-empty-footer">
            <div class="row card-header flex-column flex-md-row pb-0">
                <div
                    class="d-md-flex justify-content-between align-items-center dt-layout-start col-md-auto me-auto mt-0">
                    <h5 class="card-title mb-0 text-md-start text-center">Seo Meta</h5>
                </div>
                <div
                    class="d-md-flex justify-content-between align-items-center dt-layout-end col-md-auto ms-auto mt-0">
                    <div class="dt-buttons btn-group flex-wrap mb-0">
                        <div class="btn-group">
                            @if($sessionUser->hasPermission('admin/seo/sitemap-generate'))
                            <button class="btn buttons-collection btn-label-primary me-4"><span><span
                                        class="d-flex align-items-center gap-2"><span class="d-none d-sm-inline-block"
                                            onclick="$('#sitemapmodel').modal('show')">Sitemap</span></span></span></button>
                            @endif
                        </div>
                        @if($sessionUser->hasPermission('admin/seo/create'))
                        <button class="btn create-new btn-primary" tabindex="0" aria-controls="DataTables_Table_0"
                            type="button"><span><span class="d-flex align-items-center gap-2"><i
                                        class="icon-base bx bx-plus icon-sm"></i> <span class="d-none d-sm-inline-block"
                                        onclick="document.getElementById('cust_a').click();">Create</span></span></span></button>
                        <a href="admin/seo/create" id="cust_a" class="pjax" style="display: none;"></a>
                        @endif
                    </div>
                </div>
            </div>
            <div class="justify-content-between dt-layout-table">
                <div class="d-md-flex justify-content-between align-items-center col-12 dt-layout-full col-md">
                    <table class="datatables-basic table table-bordered table-responsive dataTable dtr-column"
                        id="DataTables_Table_0" aria-describedby="DataTables_Table_0_info" style="width: 100%;">
                        <colgroup>
                            <col data-dt-column="1" style="width: 72.7292px;">
                            <col data-dt-column="3" style="width: 359.812px;">
                            <col data-dt-column="4" style="width: 343.792px;">
                            <col data-dt-column="5" style="width: 147.958px;">
                            <col data-dt-column="7" style="width: 170.229px;">
                            <col data-dt-column="8" style="width: 145.562px;">
                        </colgroup>
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
        </div>
    </div>
</div>
@endsection
@push('scripts')
<!-- Modal -->
<div class="modal fade" id="sitemapmodel" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="sitemapmodellabel">SiteMap</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                SiteMap Url : <a href="{{url('sitemap.xml')}}" class="noroute pjax"
                    target="_blank">{{url('sitemap.xml')}}</a>
                <!-- <button class="btn btn-primary" onclick="app.ajaxGet('admin/seo/sitemap-update');">Update SiteMap</button> -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary " data-bs-dismiss="modal"
                    onclick="app.ajaxGet('admin/seo/sitemap-update');">Update SiteMap</button>
            </div>
        </div>
    </div>
</div>
<script>
documentReady(function() {
    datatableObj = $('#DataTables_Table_0').DataTable({
        ajax: dataTableAjax({
            url: '{{ route("admin/seo/list") }}',
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