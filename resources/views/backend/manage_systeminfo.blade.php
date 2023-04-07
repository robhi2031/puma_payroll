@extends('backend.layouts', ['activeMenu' => 'SETTINGS', 'activeSubMenu' => 'System Info'])
@section('content')
<!--begin::System Info-->
<div class="card mb-5 mb-xl-10" id="cardSystemInfo">
    <!--begin::Edit-->
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-start flex-wrap mb-10">
            <h3 class="fw-bolder m-0 mb-3"><i class="las la-pen text-dark fs-2 me-3"></i>Edit Informasi Sistem</h3>
            <a href="javascript:history.back();" class="btn btn-sm btn btn-bg-light btn-color-danger ms-3"><i class="las la-undo fs-3"></i> Kembali</a>
        </div>
        <!--begin::Form-->
        <form id="form-editSystemInfo" class="form" onsubmit="return false">
            <!--begin::Input group-->
            <div class="row mb-6">
                <label class="col-lg-4 col-form-label required fw-bold fs-6" for="name">Nama</label>
                <div class="col-lg-8">
                    <div class="row">
                        <div class="col-lg-12 fv-row">
                            <input type="text" name="name" id="name" class="form-control form-control-lg form-control-solid mb-3 mb-lg-0" maxlength="255" placeholder="Isikan nama sistem ..." />
                        </div>
                    </div>
                </div>
            </div>
            <!--end::Input group-->
            <!--begin::Input group-->
            <div class="row mb-6">
                <label class="col-lg-4 col-form-label required fw-bold fs-6" for="short_name">Nama Alias/ Nama Pendek</label>
                <div class="col-lg-8">
                    <div class="row">
                        <div class="col-lg-12 fv-row">
                            <input type="text" name="short_name" id="short_name" class="form-control form-control-lg form-control-solid mb-3 mb-lg-0" maxlength="60" placeholder="Isikan nama alias / nama pendek sistem ..." />
                        </div>
                    </div>
                </div>
            </div>
            <!--end::Input group-->
            <!--begin::Input group-->
            <div class="row mb-6">
                <label class="col-lg-4 col-form-label required fw-bold fs-6" for="description">Deskripsi</label>
                <div class="col-lg-8">
                    <div class="row">
                        <div class="col-lg-12 fv-row">
                            <textarea name="description" id="description" class="form-control form-control-lg form-control-solid mb-3 mb-lg-0" rows="2" maxlength="160" placeholder="Isikan deskripsi singkat sistem ..."></textarea>
                        </div>
                    </div>
                </div>
            </div>
            <!--end::Input group-->
            <!--begin::Input group-->
            <div class="row mb-6">
                <label class="col-lg-4 col-form-label required fw-bold fs-6" for="keyword">Keyword/ Kata Kunci</label>
                <div class="col-lg-8">
                    <div class="row">
                        <div class="col-lg-12 fv-row">
                            <select class="form-control form-control-lg form-control-solid mb-3 mb-lg-0" id="keyword" name="keyword[]" multiple></select>
                            <div class="form-text">*) Pisahkan keyword dengan tanda koma, contoh: <code>puma payroll, payroll, aplikasi payroll</code></div>
                            <div class="form-text">*) Maksimal: <code>25</code> kata kunci</div>
                        </div>
                    </div>
                </div>
            </div>
            <!--end::Input group-->
            <!--begin::Input group-->
            <div class="row mb-6">
                <label class="col-lg-4 col-form-label required fw-bold fs-6" for="copyright">Copyright</label>
                <div class="col-lg-8">
                    <div class="row">
                        <div class="col-lg-12 fv-row">
                            <textarea name="copyright" id="copyright" class="form-control form-control-lg form-control-solid mb-3 mb-lg-0 summernote"></textarea>
                        </div>
                    </div>
                </div>
            </div>
            <!--end::Input group-->
            <!--begin::Input group-->
            <div class="row mb-6" id="iGroup-thumb">
                <label class="col-lg-4 col-form-label required fw-bold fs-6">Gambar Thumbnail</label>
                <div class="col-lg-8">
                    <div class="row">
                        <div class="col-lg-12 fv-row">
                            <input type="file" class="dropify-upl mb-3 mb-lg-0" id="thumb" name="thumb" data-show-remove="false" data-allowed-file-extensions="jpg png jpeg" data-max-file-size="2M" />
                            <div class="form-text">*) Type file: <code>*.jpeg | *.jpeg | *.png</code></div>
                            <div class="form-text">*) Max. size file: <code>2MB</code></div>
                        </div>
                    </div>
                </div>
            </div>
            <!--end::Input group-->
            <!--begin::Input group-->
            <div class="row mb-6" id="iGroup-login_bg">
                <label class="col-lg-4 col-form-label required fw-bold fs-6">Background Login</label>
                <div class="col-lg-8">
                    <div class="row">
                        <div class="col-lg-12 fv-row">
                            <input type="file" class="dropify-upl mb-3 mb-lg-0" id="login_bg" name="login_bg" data-show-remove="false" data-allowed-file-extensions="jpg png jpeg" data-max-file-size="2M" />
                            <div class="form-text">*) Type file: <code>*.jpeg | *.jpeg | *.png</code></div>
                            <div class="form-text">*) Max. size file: <code>2MB</code></div>
                        </div>
                    </div>
                </div>
            </div>
            <!--end::Input group-->
            <!--begin::Input group-->
            <div class="row mb-6" id="iGroup-login_logo">
                <label class="col-lg-4 col-form-label required fw-bold fs-6">Logo Login</label>
                <div class="col-lg-8">
                    <div class="row">
                        <div class="col-lg-12 fv-row">
                            <input type="file" class="dropify-upl mb-3 mb-lg-0" id="login_logo" name="login_logo" data-show-remove="false" data-allowed-file-extensions="jpg png jpeg" data-max-file-size="2M" />
                            <div class="form-text">*) Type file: <code>*.jpeg | *.jpeg | *.png</code></div>
                            <div class="form-text">*) Max. size file: <code>2MB</code></div>
                        </div>
                    </div>
                </div>
            </div>
            <!--end::Input group-->
            <!--begin::Input group-->
            <div class="row mb-6" id="iGroup-backend_logo">
                <label class="col-lg-4 col-form-label required fw-bold fs-6">Backend Logo</label>
                <div class="col-lg-8">
                    <div class="row">
                        <div class="col-lg-12 fv-row">
                            <input type="file" class="dropify-upl mb-3 mb-lg-0" id="backend_logo" name="backend_logo" data-show-remove="false" data-allowed-file-extensions="jpg png jpeg" data-max-file-size="2M" />
                            <div class="form-text">*) Type file: <code>*.jpeg | *.jpeg | *.png</code></div>
                            <div class="form-text">*) Max. size file: <code>2MB</code></div>
                        </div>
                    </div>
                </div>
            </div>
            <!--end::Input group-->
            <!--begin::Input group-->
            <div class="row mb-6" id="iGroup-backend_logo_icon">
                <label class="col-lg-4 col-form-label required fw-bold fs-6">Backend Logo Icon</label>
                <div class="col-lg-8">
                    <div class="row">
                        <div class="col-lg-12 fv-row">
                            <input type="file" class="dropify-upl mb-3 mb-lg-0" id="backend_logo_icon" name="backend_logo_icon" data-show-remove="false" data-allowed-file-extensions="jpg png jpeg" data-max-file-size="2M" />
                            <div class="form-text">*) Type file: <code>*.jpeg | *.jpeg | *.png</code></div>
                            <div class="form-text">*) Max. size file: <code>2MB</code></div>
                        </div>
                    </div>
                </div>
            </div>
            <!--end::Input group-->
            <div class="row mt-5">
                <div class="col-lg-12 d-flex justify-content-end">
                    <button type="button" class="btn btn-light btn-active-light-danger me-2" id="btn-resetFormSystemInfo"><i class="las la-redo-alt fs-1 me-3"></i>Batal</button>
                    <button type="button" class="btn btn-primary" id="btn-saveSystemInfo"><i class="las la-save fs-1 me-3"></i>Simpan</button>
                </div>
            </div>
        </form>
    </div>
    <!--end::Edit-->
</div>
<!--end::User Info-->
@endsection