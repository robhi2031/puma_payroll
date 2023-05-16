@extends('backend.layouts', ['activeMenu' => 'EMPLOYEE', 'activeSubMenu' => 'Manpower'])
@section('content')
<!--begin::Card Form-->
<div class="card" id="card-formManpower" style="display: none;">
    <!--begin::Card header-->
    <div class="card-header">
        <div class="card-title"></div>
        <div class="card-toolbar">
            <div class="d-flex justify-content-end">
                <button type="button" class="btn btn-sm btn btn-bg-light btn-color-danger me-2" id="btn-closeFormProject" onclick="_closeCard('form_project');"><i class="fas fa-times"></i> Tutup</button>
            </div>
        </div>
    </div>
    <!--end::Card header-->
    <!--begin::Form-->
    <form id="form-project" class="form" onsubmit="return false">
        <input type="hidden" name="id" />
        <!--begin::Card body-->
        <div class="card-body">
            <!--begin::Input group-->
            <div class="row mb-6">
                <label class="col-lg-4 col-form-label required fw-bold fs-6" for="code">Kode</label>
                <div class="col-lg-8">
                    <input type="text" name="code" id="code" class="form-control form-control-lg form-control-solid no-space mb-3 mb-lg-0" maxlength="12" placeholder="Isi kode proyek/ pekerjaan ..." />
                    <div class="form-text">*) Contoh: <code>EXP-001</code></div>
                </div>
            </div>
            <!--end::Input group-->
            <!--begin::Input group-->
            <div class="row mb-6">
                <label class="col-lg-4 col-form-label required fw-bold fs-6" for="name">Nama</label>
                <div class="col-lg-8">
                    <input type="text" name="name" id="name" class="form-control form-control-lg form-control-solid mb-3 mb-lg-0" maxlength="225" placeholder="Isi nama proyek/ pekerjaan ..." />
                    <div class="form-text">*) Contoh: <code>Explorasi Lokasi Tambang Bla-Bla PT. Ujung Kulon</code></div>
                </div>
            </div>
            <!--end::Input group-->
            <!--begin::Input group-->
            <div class="row mb-6">
                <label class="col-lg-4 col-form-label required fw-bold fs-6" for="desc">Deskripsi</label>
                <div class="col-lg-8">
                    <textarea type="text" class="form-control form-control-solid" name="desc" id="desc" maxlength="255" rows="3" style="resize: none;" placeholder="Isi deskripsi proyek/ pekerjaan ..." ></textarea>
                </div>
            </div>
            <!--end::Input group-->
            <!--begin::Input group-->
            <div class="row mb-6">
                <label class="col-lg-4 col-form-label required fw-bold fs-6" for="client">Klien/ Mitra</label>
                <div class="col-lg-8">
                    <input type="text" name="client" id="client" class="form-control form-control-lg form-control-solid mb-3 mb-lg-0" maxlength="225" placeholder="Isi klien/ mitra proyek/ pekerjaan ..." />
                </div>
            </div>
            <!--end::Input group-->
            <!--begin::Input group-->
            <div class="row mb-6">
                <label class="col-lg-4 col-form-label required fw-bold fs-6" for="location">Lokasi</label>
                <div class="col-lg-8">
                    <input type="text" name="location" id="location" class="form-control form-control-lg form-control-solid mb-3 mb-lg-0" maxlength="225" placeholder="Isi lokasi proyek/ pekerjaan ..." />
                </div>
            </div>
            <!--end::Input group-->
            <!--begin::Input group-->
            <div class="row mb-6">
                <label class="col-lg-4 col-form-label required fw-bold fs-6" for="start_date">Tanggal</label>
                <div class="col-lg-8">
                    <div class="input-group">
                        <input type="text" class="form-control form-control-sm date-flatpickr" name="start_date" id="start_date" maxlength="10" placeholder="dd/mm/YYYY" readonly />
                        <span class="input-group-text">s/d</span>
                        <input type="text" class="form-control form-control-sm date-flatpickr" name="end_date" id="end_date" maxlength="10" placeholder="dd/mm/YYYY" readonly />
                    </div>
                </div>
            </div>
            <!--end::Input group-->
            <!--begin::Input group-->
            <div class="row mb-6" id="iGroup-status">
                <label class="col-lg-4 col-form-label required fw-bold fs-6" for="status">Status</label>
                <div class="col-lg-8">
                    <select class="show-tick form-select-solid selectpicker" data-width="100%" data-style="btn-sm btn-primary" name="status" id="status" data-container="body" title="Pilih status proyek/ pekerjaan ...">
                        <option value="Not Started" data-icon="bi bi-dash-circle">Not Started</option>
                        <option value="In Progress" data-icon="bi bi-bootstrap-reboot">In Progress</option>
                        <option value="Completed" data-icon="bi bi-check2-circle">Completed</option>
                        <option value="Stop" data-icon="bi bi-sign-stop">Stop</option>
                    </select>
                </div>
            </div>
            <!--end::Input group-->
        </div>
        <!--end::Card body-->
        <!--begin::Actions-->
        <div class="card-footer d-flex justify-content-end py-6 px-9">
            <button type="button" class="btn btn-light btn-active-light-danger me-2" id="btn-reset" onclick="_clearFormProject();"><i class="las la-redo-alt fs-1 me-3"></i>Batal</button>
            <button type="button" class="btn btn-primary" id="btn-save"><i class="las la-save fs-1 me-3"></i>Simpan</button>
        </div>
        <!--end::Actions-->
    </form>
    <!--end::Form-->
</div>
<!--end::Card Form-->
<!--begin::Modal Import Data-->
<div class="modal fade" id="modal-importManpower" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <!--begin::Modal header-->
            <div class="modal-header">
                <h3 class="modal-title fw-bolder"></h3>
                <button type="button" class="btn btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <!--end::Modal header-->
            <!--begin::Modal body-->
            <div class="modal-body scroll-y">
                <!--begin::Scroll-->
                <div class="scroll-y me-n7 pe-7" data-kt-scroll="true" data-kt-scroll-activate="{default: false, lg: true}" data-kt-scroll-max-height="auto" data-kt-scroll-offset="185px">
                    <form id="form-importManpower" class="form" onsubmit="return false">
                        <!--begin::Row-->
                        <div class="row">
                            <div class="col-md-12">
                                <!--begin::Input group-->
                                <div class="mb-3" id="iGroup-fileImport">
                                    <label class="col-form-label required fw-bold fs-6" for="file_import">File</label>
                                    <input type="file" class="form-control" id="file_import" name="file_import" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" data-msg-placeholder="Pilih {files}...">
                                    <div class="form-text">*) Type file: <code>*.xlsx | *.xls | *.csv</code></div>
                                    <div class="form-text">*) Max. size file: <code>8MB</code></div>
                                </div>
                                <!--end::Input group-->
                            </div>
                        </div>
                        <!--end::Row-->
                    </form>
                </div>
            </div>
            <!--end::Modal body-->
            <!--begin::Modal footer-->
            <div class="modal-footer py-3">
                <!--begin::Actions-->
                <div class="d-flex justify-content-end">
                    <button type="button" class="btn btn-light btn-active-light-danger me-2" data-bs-dismiss="modal"><i class="las la-times fs-1 me-3"></i>Tutup</button>
                    <button type="button" class="btn btn-primary" id="btn-saveImportManpower"><i class="bi bi-cloud-arrow-up fs-1 me-3"></i>Proses Import</button>
                </div>
                <!--end::Actions-->
            </div>
            <!--end::Modal footer-->
        </div>
    </div>
</div>
<!--end::Modal Import Data-->
<!--begin::List Table Data-->
<div class="card shadow" id="card-dtManpower">
    <!--begin::Card header-->
    <div class="card-header">
        <div class="card-title">
            <h3 class="fw-bolder fs-2 text-gray-900">
                <i class="fas fa-th-list fs-2 text-gray-900 me-2"></i> Data Karyawan
            </h3>
        </div>
        <div class="card-toolbar">
            <div class="d-flex justify-content-end">
                <button type="button" class="btn btn-sm btn-primary me-2" id="btn-addProject" onclick="_addProject();"><i class="fas fa-plus"></i> Tambah</button>
                <!--begin::Menu-->
                <button type="button" class="btn btn-sm btn-icon btn-active-light-primary me-n3" data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-placement="bottom-end">
                    <i class="bi bi-three-dots fs-2"></i>
                </button>
                <!--begin::Menu 3-->
                <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg-light-primary fw-semibold w-200px py-3" data-kt-menu="true">
                    <div class="menu-item px-3">
                        <a href="javascript:void(0);" class="menu-link px-3" onclick="_openModalImportManPower();">
                            <i class="las la-file-import fs-3 me-1"></i>Import Data
                        </a>
                    </div>
                    <div class="menu-item px-3">
                        <a href="javascript:void(0);" class="menu-link px-3">
                            <i class="las la-file-export fs-3 me-1"></i>Export Data
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--end::Card header-->
    <div class="card-body">
        <div class="d-flex flex-wrap justify-content-center align-items-center mb-5">
            <div class="ms-auto">
                <!--begin::Search-->
                <div class="d-flex align-items-center position-relative mb-md-0 mb-3">
                    <div class="input-group input-group-sm input-group-solid border">
                        <span class="input-group-text"><i class="las la-search fs-3"></i></span>
                        <input type="text" class="form-control form-control-sm form-control-solid border-left-0" name="search-dtManpower" id="search-dtManpower" placeholder="Pencarian..." />
                        <span class="input-group-text border-left-0 cursor-pointer text-hover-danger" id="clear-searchDtManpower" style="display: none;">
                            <i class="las la-times fs-3"></i>
                        </span>
                    </div>
                </div>
                <!--end::Search-->
            </div>
        </div>
        <div class="table-responsive">
            <!--begin::Table-->
            <table class="table table-rounded align-middle table-row-bordered border" id="dt-mapower">
                <thead>
                    <tr class="fw-bolder text-uppercase bg-light">
                        <th class="text-center align-middle px-2 border-bottom-2 border-gray-200">No.</th>
                        <th class="align-middle px-2 border-bottom-2 border-gray-200">PJU BN</th>
                        {{-- <th class="align-middle px-2 border-bottom-2 border-gray-200">EXT BN</th> --}}
                        <th class="align-middle px-2 border-bottom-2 border-gray-200">EMPLOYEE NAME</th>
                        <th class="align-middle px-2 border-bottom-2 border-gray-200">PROJECT</th>
                        <th class="align-middle px-2 border-bottom-2 border-gray-200">JOB POSITION</th>
                        <th class="align-middle px-2 border-bottom-2 border-gray-200">DEPARTMENT</th>
                        <th class="align-middle px-2 border-bottom-2 border-gray-200">SHIFT</th> <!-- Shift Code/ Shift Group -->
                        <th class="align-middle px-2 border-bottom-2 border-gray-200">PAY CODE</th>
                        <th class="align-middle px-2 border-bottom-2 border-gray-200">STATUS</th>
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
