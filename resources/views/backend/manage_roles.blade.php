@extends('backend.layouts', ['activeMenu' => 'SETTINGS', 'activeSubMenu' => 'Roles'])
@section('content')
<!--begin::Card Form-->
<div class="card" id="card-formRole" style="display: none;">
    <!--begin::Card header-->
    <div class="card-header">
        <div class="card-title"></div>
        <div class="card-toolbar">
            <div class="d-flex justify-content-end">
                <button type="button" class="btn btn-sm btn btn-bg-light btn-color-danger me-2" id="btn-closeFormRole" onclick="_closeCard('form_role');"><i class="fas fa-times"></i> Tutup</button>
            </div>
        </div>
    </div>
    <!--end::Card header-->
    <!--begin::Form-->
    <form id="form-role" class="form" onsubmit="return false">
        <input type="hidden" name="id" />
        <!--begin::Card body-->
        <div class="card-body">
            <!--begin::Input group-->
            <div class="row mb-6">
                <label class="col-lg-4 col-form-label required fw-bold fs-6" for="name">Nama</label>
                <div class="col-lg-8">
                    <div class="row">
                        <div class="col-lg-12 fv-row">
                            <input type="text" name="name" id="name" class="form-control form-control-lg form-control-solid mb-3 mb-lg-0" maxlength="50" placeholder="Isi nama role ..." />
                        </div>
                    </div>
                </div>
            </div>
            <!--end::Input group-->
        </div>
        <!--end::Card body-->
        <!--begin::Actions-->
        <div class="card-footer d-flex justify-content-end py-6 px-9">
            <button type="button" class="btn btn-light btn-active-light-danger me-2" id="btn-reset" onclick="_clearFormRole();"><i class="las la-redo-alt fs-1 me-3"></i>Batal</button>
            <button type="button" class="btn btn-primary" id="btn-save"><i class="las la-save fs-1 me-3"></i>Simpan</button>
        </div>
        <!--end::Actions-->
    </form>
    <!--end::Form-->
</div>
<!--end::Card Form-->
<!--begin::Card Set Permissions-->
<div class="card" id="card-setPermissions" style="display: none;">
    <!--begin::Card header-->
    <div class="card-header">
        <div class="card-title"></div>
        <div class="card-toolbar">
            <div class="d-flex justify-content-end">
                <button type="button" class="btn btn-sm btn-primary me-2" id="btn-addPermission" onclick="_addPermission();"><i class="fas fa-plus"></i> Tambah</button>
                <button type="button" class="btn btn-sm btn btn-bg-light btn-color-danger me-2" id="btn-closeSetPermissions" onclick="_closeCard('dt_permissions');"><i class="fas fa-times"></i> Tutup</button>
            </div>
        </div>
    </div>
    <!--end::Card header-->
    <!--begin::Card body-->
    <div class="card-body">
        <div class="table-responsive">
            <!--begin::Table-->
            <table class="table table-rounded align-middle table-row-bordered border" id="dt-permissions">
                <thead>
                    <tr class="fw-bolder text-uppercase bg-light">
                        <th class="text-center align-middle px-2 border-bottom-2 border-gray-200">No.</th>
                        <th class="align-middle px-2 border-bottom-2 border-gray-200">Menu / Permission</th>
                        <th class="text-center align-middle px-2 border-bottom-2 border-gray-200">Create</th>
                        <th class="text-center align-middle px-2 border-bottom-2 border-gray-200">Read</th>
                        <th class="text-center align-middle px-2 border-bottom-2 border-gray-200">Update</th>
                        <th class="text-center align-middle px-2 border-bottom-2 border-gray-200">Delete</th>
                    </tr>
                </thead>
            </table>
            <!--end::Table-->
        </div>
    </div>
    <!--end::Card body-->
</div>
<!--end::Card Set Permissions-->
<!--begin::Modal Add Permission on Role-->
<div class="modal fade" id="modal-addPermission" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
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
                    <form id="form-rolePermission" class="form" onsubmit="return false">
                        <input type="hidden" name="idpRole" />
                        <!--begin::Row-->
                        <div class="row">
                            <div class="col-md-12">
                                <!--begin::Input group-->
                                <div class="mb-3">
                                    <label class="col-form-label required fw-bold fs-6" for="cbo_permission">Permission</label>
                                    <select class="form-select" name="cbo_permission" id="cbo_permission"></select>
                                    <div class="form-text">*) Ketik permission untuk mempercepat pencarian</div>
                                </div>
                                <!--end::Input group-->
                                <!--begin::Input group-->
                                <div class="mt-5 mb-3">
                                    <label class="col-form-label required fw-bold fs-6">Setting Permission</label>

                                    <label class="form-check form-switch form-switch-sm form-check-custom form-check-solid flex-stack mb-3">
                                        <span class="form-check-label ms-0 fw-bold fs-6 text-gray-700">
                                            CREATE
                                        </span>
                                        <input class="form-check-input" type="checkbox" name="create" id="create" value="1" />               
                                    </label>

                                    <label class="form-check form-switch form-switch-sm form-check-custom form-check-solid flex-stack mb-3">
                                        <span class="form-check-label ms-0 fw-bold fs-6 text-gray-700">
                                            READ
                                        </span>
                                        <input class="form-check-input" type="checkbox" name="read" id="read" value="1" />               
                                    </label>

                                    <label class="form-check form-switch form-switch-sm form-check-custom form-check-solid flex-stack mb-3">
                                        <span class="form-check-label ms-0 fw-bold fs-6 text-gray-700">
                                            UPDATE
                                        </span>
                                        <input class="form-check-input" type="checkbox" name="update" id="update" value="1" />               
                                    </label>

                                    <label class="form-check form-switch form-switch-sm form-check-custom form-check-solid flex-stack">
                                        <span class="form-check-label ms-0 fw-bold fs-6 text-gray-700">
                                            DELETE
                                        </span>
                                        <input class="form-check-input" type="checkbox" name="delete" id="delete" value="1" />               
                                    </label>
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
                    <button type="button" class="btn btn-primary" id="btn-savePermission"><i class="las la-save fs-1 me-3"></i>Simpan</button>
                </div>
                <!--end::Actions-->
            </div>
            <!--end::Modal footer-->
        </div>
    </div>
</div>
<!--end::Add Permission on Role-->
<!--begin::List Table Data-->
<div class="card shadow" id="card-dtRoles">
    <!--begin::Card header-->
    <div class="card-header">
        <div class="card-title">
            <h3 class="fw-bolder fs-2 text-gray-900">
                <i class="fas fa-th-list fs-2 text-gray-900 me-2"></i> Master Data Roles
            </h3>
        </div>
        <div class="card-toolbar">
            <div class="d-flex justify-content-end">
                <button type="button" class="btn btn-sm btn-primary me-2" id="btn-addRole" onclick="_addRole();"><i class="fas fa-plus"></i> Tambah</button>
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
                        <input type="text" class="form-control form-control-sm form-control-solid w-250px border-left-0" name="search-dtRoles" id="search-dtRoles" placeholder="Pencarian..." />
                        <span class="input-group-text border-left-0 cursor-pointer text-hover-danger" id="clear-searchDtRoles" style="display: none;">
                            <i class="las la-times fs-3"></i>
                        </span>
                    </div>
                </div>
                <!--end::Search-->
            </div>
        </div>
        <div class="table-responsive">
            <!--begin::Table-->
            <table class="table table-rounded align-middle table-row-bordered border" id="dt-roles">
                <thead>
                    <tr class="fw-bolder text-uppercase bg-light">
                        <th class="text-center align-middle px-2 border-bottom-2 border-gray-200">No.</th>
                        <th class="align-middle px-2 border-bottom-2 border-gray-200">Role</th>
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
