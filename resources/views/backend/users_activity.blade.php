@extends('backend.layouts', ['activeMenu' => 'SETTINGS', 'activeSubMenu' => 'Users Activity'])
@section('content')
<!--begin::List Table Data-->
<div class="card shadow" id="card-dtLogs">
    <!--begin::Card header-->
    <div class="card-header">
        <div class="card-title">
            <h3 class="fw-bolder fs-2 text-gray-900">
                <i class="fas fa-th-list fs-2 text-gray-900 me-2"></i> Data Log Aktivitas User
            </h3>
        </div>
        @if (auth()->user()->getRoleNames()[0] == 'Super Admin')
        <div class="card-toolbar">
            <div class="d-flex justify-content-end">
                <button type="button" class="btn btn-sm btn-warning me-2" id="btn-cleanLogs" onclick="_cleanLogs();"><i class="las la-quidditch fs-3"></i> Bersihkan Log Aktivitas</button>
            </div>
        </div>
        @endif
    </div>
    <!--end::Card header-->
    <div class="card-body">
        <div class="d-flex flex-wrap justify-content-center align-items-center mb-5">
            <!--begin::Row-->
            <div class="row justify-content-center align-items-center m-0 mb-md-0 mb-5">
                <div class="col-lg-3 col-sm-12 text-center px-0">
                    <label class="col-form-label fw-bold fs-6">Filter Tgl. Aktivitas</label>
                </div>
                <div class="col-lg-9 col-sm-12 px-0">
                    <?php date_default_timezone_set("Asia/Jakarta"); $dateToday = date('d/m/Y'); ?>
                    <!--begin::Input group-->
                    <div class="input-group">
                        <input type="text" class="form-control form-control-sm date-flatpickr" name="filterDt-startDate" id="filterDt-startDate" maxlength="10" value="{{ $dateToday; }}" placeholder="dd/mm/YYYY" readonly />
                        <span class="input-group-text">s/d</span>
                        <input type="text" class="form-control form-control-sm date-flatpickr" name="filterDt-endDate" id="filterDt-endDate" maxlength="10" value="{{ $dateToday; }}" placeholder="dd/mm/YYYY" readonly />
                    </div>
                    <!--end::Input group-->
                </div>
            </div>
            <!--end::Row-->
            <div class="ms-auto">
                <!--begin::Search-->
                <div class="d-flex align-items-center position-relative mb-md-0 mb-3">
                    <div class="input-group input-group-sm input-group-solid border">
                        <span class="input-group-text"><i class="las la-search fs-3"></i></span>
                        <input type="text" class="form-control form-control-sm form-control-solid border-left-0" name="search-dtLogs" id="search-dtLogs" placeholder="Pencarian..." />
                        <span class="input-group-text border-left-0 cursor-pointer text-hover-danger" id="clear-searchDtLogs" style="display: none;">
                            <i class="las la-times fs-3"></i>
                        </span>
                    </div>
                </div>
                <!--end::Search-->
            </div>
        </div>
        <div class="table-responsive">
            <!--begin::Table-->
            <table class="table table-rounded align-middle table-row-bordered border" id="dt-logs">
                <thead>
                    <tr class="fw-bolder text-uppercase bg-light">
                        <th class="text-center align-middle px-2 border-bottom-2 border-gray-200">No.</th>
                        <th class="border-bottom-2 border-gray-200 align-middle px-2">User</th><!--Username, Ip-->
                        <th class="border-bottom-2 border-gray-200 align-middle px-2">Keterangan</th><!--Desk and Link-->
                        <th class="border-bottom-2 border-gray-200 align-middle px-2">Waktu</th><!--Tgl/ Waktu-->
                        <th class="border-bottom-2 border-gray-200 align-middle px-2">Agent</th>
                    </tr>
                </thead>
            </table>
            <!--end::Table-->
        </div>
    </div>
</div>
<!--end::List Table Data-->
@endsection