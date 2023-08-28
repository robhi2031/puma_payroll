@extends('backend.layouts', ['activeMenu' => 'COMPANY', 'activeSubMenu' => 'Project'])
@section('content')
<!--begin::Card Form-->
<div class="card" id="card-formProject" style="display: none;">
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
            <!--begin::Input group--
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
                    <div class="form-text">*) Contoh: <code>Drilling Exploration at Rante Balla</code></div>
                </div>
            </div>
            <!--end::Input group-->
            <!--begin::Input group-->
            <div class="row mb-6">
                <label class="col-lg-4 col-form-label fw-bold fs-6" for="desc">Deskripsi</label>
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
            <!--begin::Input group--
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
                        <!-- <option value="Not Started" data-icon="bi bi-dash-circle">Not Started</option>
                        <option value="In Progress" data-icon="bi bi-bootstrap-reboot">In Progress</option>
                        <option value="Completed" data-icon="bi bi-check2-circle">Completed</option>
                        <option value="Stop" data-icon="bi bi-sign-stop">Stop</option> -->
                        <option value="1" data-icon="bi bi-check2-circle">Aktif</option>
                        <option value="0" data-icon="bi bi-sign-stop">Tidak Aktif</option>
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
<!--begin::List Table Data-->
<div class="card shadow" id="card-dtProject">
    <!--begin::Card header-->
    <div class="card-header">
        <div class="card-title">
            <h3 class="fw-bolder fs-2 text-gray-900">
                <i class="fas fa-th-list fs-2 text-gray-900 me-2"></i> Master Data Proyek
            </h3>
        </div>
        <div class="card-toolbar">
            <div class="d-flex justify-content-end">
                <button type="button" class="btn btn-sm btn-primary me-2" id="btn-addProject" onclick="_addProject();"><i class="fas fa-plus"></i> Tambah</button>
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
                        <input type="text" class="form-control form-control-sm form-control-solid border-left-0" name="search-dtProject" id="search-dtProject" placeholder="Pencarian..." />
                        <span class="input-group-text border-left-0 cursor-pointer text-hover-danger" id="clear-searchDtProject" style="display: none;">
                            <i class="las la-times fs-3"></i>
                        </span>
                    </div>
                </div>
                <!--end::Search-->
            </div>
        </div>
        <div class="table-responsive">
            <!--begin::Table-->
            <table class="table table-rounded align-middle table-row-bordered border" id="dt-project">
                <thead>
                    <tr class="fw-bolder text-uppercase bg-light">
                        <th class="text-center align-middle px-2 border-bottom-2 border-gray-200">No.</th>
                        {{-- <th class="align-middle px-2 border-bottom-2 border-gray-200">Kode</th> --}}
                        <th class="align-middle px-2 border-bottom-2 border-gray-200">Proyek</th>
                        <th class="align-middle px-2 border-bottom-2 border-gray-200">Desk</th>
                        <th class="align-middle px-2 border-bottom-2 border-gray-200">Klien</th>
                        <th class="align-middle px-2 border-bottom-2 border-gray-200">Lokasi</th>
                        {{-- <th class="align-middle px-2 border-bottom-2 border-gray-200">Tgl. Proyek</th> --}}
                        <th class="align-middle px-2 border-bottom-2 border-gray-200">Status</th>
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
