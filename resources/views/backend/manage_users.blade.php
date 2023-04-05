@extends('backend.layouts', ['activeMenu' => 'SETTINGS', 'activeSubMenu' => 'Users'])
@section('content')
<!--begin::Card Form-->
<div class="card" id="card-formUser" style="display: none;">
    <!--begin::Card header-->
    <div class="card-header">
        <div class="card-title"></div>
        <div class="card-toolbar">
            <div class="d-flex justify-content-end">
                <button type="button" class="btn btn-sm btn btn-bg-light btn-color-danger me-2" id="btn-closeFormUser" onclick="_closeCard('form_user');"><i class="fas fa-times"></i> Tutup</button>
            </div>
        </div>
    </div>
    <!--end::Card header-->
    <!--begin::Form-->
    <form id="form-user" class="form" onsubmit="return false">
        <input type="hidden" name="id" />
        <!--begin::Card body-->
        <div class="card-body">
            <!--begin::Row-->
            <div class="row">
                <div class="col-md-12">
                    <!--begin::Input group-->
                    <div class="mb-3">
                        <div class="row">
                            <label class="col-md-12 col-form-label required fw-bold fs-6" for="avatar">Foto</label>
                            <div class="col-md-12" id="iGroup-userThumb">
                                <div class="image-input image-input-outline" data-kt-image-input="true" style="background-image: url('{{ asset('/dist/img/avatar-blank.svg') }}')">
                                    <!--begin::Preview existing avatar-->
                                    <div class="image-input-wrapper w-125px h-125px"></div>
                                    <!--end::Preview existing avatar-->
                                    <label class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="change" data-bs-toggle="tooltip" title="Ubah foto user">
                                        <i class="bi bi-pencil-fill fs-7"></i>
                                        <input type="file" name="avatar" id="avatar" accept=".png, .jpg, .jpeg" />
                                        <input type="hidden" name="avatar_remove" id="avatar_remove" value="1" />
                                    </label>
                                    <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" id="btn-cancelUserThumb" data-kt-image-input-action="cancel" data-bs-toggle="tooltip" title="Batalkan perubahan foto user">
                                        <i class="bi bi-x fs-2"></i>
                                    </span>
                                    <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="remove" data-bs-toggle="tooltip" title="Hapus foto user">
                                        <i class="bi bi-x fs-2"></i>
                                    </span>
                                </div>
                            </div> 
                            <div class="col-md-12 form-text">*) Jenis file yang diizinkan: <code>png, jpg, jpeg</code> | Ukuran file Maks: <code>2MB</code></div>
                        </div>
                    </div>
                    <!--end::Input group-->
                    <!--begin::Input group-->
                    <div class="mb-3" id="iGroup-role">
                        <label class="col-form-label required fw-bold fs-6" for="role">Role User</label>
                        <select class="show-tick form-select-solid" data-width="100%" data-style="btn-sm btn-primary" name="role" id="role" data-container="body" title="Pilih role User ..."></select>
                    </div>
                    <!--end::Input group-->
                    <!--begin::Input group-->
                    <div class="mb-3">
                        <label class="col-form-label required fw-bold fs-6" for="name">Nama</label>
                        <input type="text" class="form-control form-control-solid" name="name" id="name" maxlength="150" placeholder="Isikan nama user ..." />
                    </div>
                    <!--end::Input group-->
                    <!--begin::Input group-->
                    <div class="mb-3">
                        <label class="col-form-label required fw-bold fs-6" for="username">Username</label>
                        <div class="input-group input-group-solid">
                            <span class="input-group-text"><i class="las la-user fs-1"></i></span>
                            <input type="text" class="form-control form-control-solid no-space" name="username" id="username" maxlength="50" placeholder="Isikan username ..." />
                        </div>
                        <div class="form-text">*) Tanpa spasi, contoh: <code>andre123</code></div>
                    </div>
                    <!--end::Input group-->
                    <!--begin::Input group-->
                    <div class="mb-3">
                        <label class="col-form-label required fw-bold fs-6" for="email">Email</label>
                        <div class="input-group input-group-solid mb-2">
                            <span class="input-group-text"><i class="las la-envelope fs-1"></i></span>
                            <input type="text" class="form-control form-control-lg form-control-solid no-space" name="email" id="email" placeholder="Isikan email user ..." />
                        </div>
                        <div class="form-text">*) Pastikan email sesuai format dan masih aktif digunakan, contoh: <code>andre123@gmail.com</code></div>
                    </div>
                    <!--end::Input group-->
                    <!--begin::Input group-->
                    <div class="mb-3">
                        <label class="col-form-label required fw-bold fs-6" for="phone_number">No. Telpon/ Hp</label>
                        <div class="input-group input-group-solid mb-2">
                            <span class="input-group-text"><i class="las la-phone fs-1"></i></span>
                            <input type="text" class="form-control form-control-lg form-control-solid" name="phone_number" id="phone_number" placeholder="Isikan No. Telpon/Hp user ..." />
                        </div>
                        <div class="form-text">*) Pastikan No. Telpon/Hp sesuai format dan masih aktif digunakan, contoh: <code>+6283122222222</code></div>
                    </div>
                    <!--end::Input group-->
                    <!--begin::Input group-->
                    <div class="mb-3 password-group">
                        <label class="col-form-label required fw-bold fs-6" for="pass_user">Password</label>
                        <div class="input-group input-group-solid">
                            <input type="password" class="form-control form-control-lg form-control-solid no-space" name="pass_user" id="pass_user" minlength="6" placeholder="Isikan password user ..." />
                            <span class="input-group-text cursor-pointer btn-showPass" title="Sembunyikan password"><i class="las la-eye-slash fs-1"></i></span>
                        </div>
                        <div class="form-text">*) Tanpa spasi, Panjang karakter minimal 6 | contoh: <code>User.123456</code></div>
                    </div>
                    <!--end::Input group-->
                    <!--begin::Input group-->
                    <div class="mb-3 password-group">
                        <label class="col-form-label required fw-bold fs-6" for="repass_user">Ulangi Password</label>
                        <div class="input-group input-group-solid">
                            <input type="password" class="form-control form-control-lg form-control-solid no-space" name="repass_user" id="repass_user" minlength="6" placeholder="Ulangi password user ..." />
                            <span class="input-group-text cursor-pointer btn-showPass" title="Sembunyikan password"><i class="las la-eye-slash fs-1"></i></span>
                        </div>
                        <div class="form-text">*) Tanpa spasi, Panjang karakter minimal 6 | contoh: <code>User.123456</code></div>
                    </div>
                    <!--end::Input group-->
                    <!--begin::Input group-->
                    <div class="mb-3" id="iGroup-isActive" style="display: none;">
                        <label class="col-form-label fw-bold fs-6" for="is_active">Status User</label>
                        <div class="form-check form-switch form-check-custom form-check-solid">
                            <input class="form-check-input" type="checkbox" id="is_active" name="is_active" />
                            <label class="form-check-label" for="is_active"></label>
                        </div>
                        <div class="form-text">*) Jika <code>Tidak Aktif</code>, User tidak bisa login ke sistem</div>
                    </div>
                    <!--end::Input group-->
                </div>
            </div>
            <!--end::Row-->
        </div>
        <!--end::Card body-->
        <!--begin::Actions-->
        <div class="card-footer d-flex justify-content-end py-6 px-9">
            <button type="button" class="btn btn-light btn-active-light-danger me-2" id="btn-reset" onclick="_clearFormUser();"><i class="las la-redo-alt fs-1 me-3"></i>Batal</button>
            <button type="button" class="btn btn-primary" id="btn-save"><i class="las la-save fs-1 me-3"></i>Simpan</button>
        </div>
        <!--end::Actions-->
    </form>
    <!--end::Form-->
</div>
<!--end::Card Form-->
<!--begin::List Table Data-->
<div class="card shadow" id="card-dtUsers">
    <!--begin::Card header-->
    <div class="card-header">
        <div class="card-title">
            <h3 class="fw-bolder fs-2 text-gray-900">
                <i class="fas fa-th-list fs-2 text-gray-900 me-2"></i> Data User
            </h3>
        </div>
        <div class="card-toolbar">
            <div class="d-flex justify-content-end">
                <button type="button" class="btn btn-sm btn-primary me-2" id="btn-addUser" onclick="_addUser();"><i class="fas fa-plus"></i> Tambah</button>
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
                        <input type="text" class="form-control form-control-sm form-control-solid w-250px border-left-0" name="search-dtUsers" id="search-dtUsers" placeholder="Pencarian..." />
                        <span class="input-group-text border-left-0 cursor-pointer text-hover-danger" id="clear-searchDtUsers" style="display: none;">
                            <i class="las la-times fs-3"></i>
                        </span>
                    </div>
                </div>
                <!--end::Search-->
            </div>
        </div>
        <div class="table-responsive">
            <!--begin::Table-->
            <table class="table table-rounded align-middle table-row-bordered border" id="dt-users">
                <thead>
                    <tr class="fw-bolder text-uppercase bg-light">
                        <th class="text-center align-middle px-2 border-bottom-2 border-gray-200">No.</th>
                        <th class="border-bottom-2 border-gray-200 align-middle px-2"></th><!--Level User Group-->
                        <th class="align-middle px-2 border-bottom-2 border-gray-200">User</th><!--Foto, Nama, Username-->
                        <th class="align-middle px-2 border-bottom-2 border-gray-200">Email</th>
                        <th class="align-middle px-2 border-bottom-2 border-gray-200">Telp./ Hp</th>
                        <th class="align-middle px-2 border-bottom-2 border-gray-200">Active</th>
                        <th class="align-middle px-2 border-bottom-2 border-gray-200">Last Login</th>
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