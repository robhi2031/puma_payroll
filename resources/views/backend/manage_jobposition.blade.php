@extends('backend.layouts', ['activeMenu' => 'COMPANY', 'activeSubMenu' => 'Job Position'])
@section('content')
<!--begin::Card Form-->
<div class="card" id="card-formJobPosition" style="display: none;">
    <!--begin::Card header-->
    <div class="card-header">
        <div class="card-title"></div>
        <div class="card-toolbar">
            <div class="d-flex justify-content-end">
                <button type="button" class="btn btn-sm btn btn-bg-light btn-color-danger me-2" id="btn-closeFormJobPosition" onclick="_closeCard('form_jobPosition');"><i class="fas fa-times"></i> Tutup</button>
            </div>
        </div>
    </div>
    <!--end::Card header-->
    <!--begin::Form-->
    <form id="form-jobPosition" class="form" onsubmit="return false">
        <input type="hidden" name="id" />
        <!--begin::Card body-->
        <div class="card-body">
            <!--begin::Input group-->
            <div class="row mb-6">
                <label class="col-lg-4 col-form-label required fw-bold fs-6" for="code">Kode</label>
                <div class="col-lg-8">
                    <input type="text" name="code" id="code" class="form-control form-control-lg form-control-solid no-space mb-3 mb-lg-0" maxlength="8" placeholder="Isi kode posisi pekerjaan ..." />
                    <div class="form-text">*) Contoh: <code>ADM-001</code></div>
                </div>
            </div>
            <!--end::Input group-->
            <!--begin::Input group-->
            <div class="row mb-6">
                <label class="col-lg-4 col-form-label required fw-bold fs-6" for="name">Nama</label>
                <div class="col-lg-8">
                    <input type="text" name="name" id="name" class="form-control form-control-lg form-control-solid mb-3 mb-lg-0" maxlength="150" placeholder="Isi nama posisi pekerjaan ..." />
                    <div class="form-text">*) Contoh: <code>ADMIN OFFICE</code></div>
                </div>
            </div>
            <!--end::Input group-->
        </div>
        <!--end::Card body-->
        <!--begin::Actions-->
        <div class="card-footer d-flex justify-content-end py-6 px-9">
            <button type="button" class="btn btn-light btn-active-light-danger me-2" id="btn-reset" onclick="_clearFormJobPosition();"><i class="las la-redo-alt fs-1 me-3"></i>Batal</button>
            <button type="button" class="btn btn-primary" id="btn-save"><i class="las la-save fs-1 me-3"></i>Simpan</button>
        </div>
        <!--end::Actions-->
    </form>
    <!--end::Form-->
</div>
<!--end::Card Form-->
<!--begin::List Table Data-->
<div class="card shadow" id="card-dtJobPosition">
    <!--begin::Card header-->
    <div class="card-header">
        <div class="card-title">
            <h3 class="fw-bolder fs-2 text-gray-900">
                <i class="fas fa-th-list fs-2 text-gray-900 me-2"></i> Master Data Posisi Pekerjaan
            </h3>
        </div>
        <div class="card-toolbar">
            <div class="d-flex justify-content-end">
                <button type="button" class="btn btn-sm btn-primary me-2" id="btn-addJobPosition" onclick="_addJobPosition();"><i class="fas fa-plus"></i> Tambah</button>
            </div>
        </div>
    </div>
    <!--end::Card header-->
    <div class="card-body">
        <div class="d-flex flex-wrap justify-content-center align-items-center mb-5">
            <div class="ms-auto">
                <!--begin::Search-->
                <div class="d-flex align-items-center position-relative me-3 mb-md-0 mb-3">
                    <div class="input-group input-group-sm input-group-solid border">
                        <span class="input-group-text"><i class="las la-search fs-3"></i></span>
                        <input type="text" class="form-control form-control-sm form-control-solid w-250px border-left-0" name="search-dtJobPosition" id="search-dtJobPosition" placeholder="Pencarian..." />
                        <span class="input-group-text border-left-0 cursor-pointer text-hover-danger" id="clear-searchDtJobPosition" style="display: none;">
                            <i class="las la-times fs-3"></i>
                        </span>
                    </div>
                </div>
                <!--end::Search-->
            </div>
        </div>
        <div class="table-responsive">
            <!--begin::Table-->
            <table class="table table-rounded align-middle table-row-bordered border" id="dt-jobPosition">
                <thead>
                    <tr class="fw-bolder text-uppercase bg-light">
                        <th class="text-center align-middle px-2 border-bottom-2 border-gray-200">No.</th>
                        <th class="align-middle px-2 border-bottom-2 border-gray-200">Kode</th>
                        <th class="align-middle px-2 border-bottom-2 border-gray-200">Posisi</th>
                        <th class="text-center align-middle px-2 border-bottom-2 border-gray-200">Aksi</th>
                    </tr>
                </thead>
            </table>
            <!--end::Table-->
        </div>
    </div>
</div>
<!--end::List Table Data-->
@endsection
