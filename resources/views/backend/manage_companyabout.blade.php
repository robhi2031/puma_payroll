@extends('backend.layouts', ['activeMenu' => 'COMPANY', 'activeSubMenu' => 'About'])
@section('content')
<!--begin::Company Info-->
<div class="card mb-5 mb-xl-10" id="cardCompanyInfo">
    <!--begin::Edit-->
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-start flex-wrap mb-10">
            <h3 class="fw-bolder m-0 mb-3"><i class="las la-pen text-dark fs-2 me-3"></i>Edit Informasi Perusahaan</h3>
            <a href="javascript:history.back();" class="btn btn-sm btn btn-bg-light btn-color-danger ms-3"><i class="las la-undo fs-3"></i> Kembali</a>
        </div>
        <!--begin::Form-->
        <form id="form-editCompanyInfo" class="form" onsubmit="return false">
            <!--begin::Input group-->
            <div class="row mb-6">
                <label class="col-lg-4 col-form-label required fw-bold fs-6" for="name">Nama</label>
                <div class="col-lg-8">
                    <input type="text" name="name" id="name" class="form-control form-control-lg form-control-solid mb-3 mb-lg-0" maxlength="255" placeholder="Isikan nama perusahaan ..." />
                </div>
            </div>
            <!--end::Input group-->
            <!--begin::Input group-->
            <div class="row mb-6">
                <label class="col-lg-4 col-form-label required fw-bold fs-6" for="short_description">Deskripsi Singkat</label>
                <div class="col-lg-8">
                    <textarea name="short_description" id="short_description" class="form-control form-control-lg form-control-solid mb-3 mb-lg-0" rows="3" maxlength="255" placeholder="Isikan deskripsi singkat perusahaan ..."></textarea>
                </div>
            </div>
            <!--end::Input group-->
            <!--begin::Input group-->
            <div class="row mb-6" id="iGroup-logo">
                <label class="col-lg-4 col-form-label required fw-bold fs-6">Logo</label>
                <div class="col-lg-8">
                    <input type="file" class="dropify-upl mb-3 mb-lg-0" id="logo" name="logo" accept=".png, .jpg, .jpeg" data-show-remove="false" data-allowed-file-extensions="jpg png jpeg" data-max-file-size="2M" />
                    <div class="form-text">*) Type file: <code>*.jpeg | *.jpeg | *.png</code></div>
                    <div class="form-text">*) Max. size file: <code>2MB</code></div>
                </div>
            </div>
            <!--end::Input group-->
            <!--begin::Input group-->
            <div class="row mb-6">
                <label class="col-lg-4 col-form-label required fw-bold fs-6" for="profile">Profil/ Tentang</label>
                <div class="col-lg-8">
                    <textarea name="profile" id="profile" class="form-control form-control-lg form-control-solid mb-3 mb-lg-0 summernote"></textarea>
                </div>
            </div>
            <!--end::Input group-->
            <!--begin::Input group-->
            <div class="row mb-6">
                <label class="col-lg-4 col-form-label required fw-bold fs-6" for="email">Email</label>
                <div class="col-lg-8">
                    <div class="input-group input-group-solid mb-2">
                        <span class="input-group-text"><i class="las la-envelope fs-1"></i></span>
                        <input type="text" class="form-control form-control-lg form-control-solid no-space" name="email" id="email" placeholder="Isikan email institusi ..." />
                    </div>
                    <div class="form-text">*) Pastikan email sesuai format dan masih aktif digunakan, contoh: <code>andre123@gmail.com</code></div>
                </div>
            </div>
            <!--end::Input group-->
            <!--begin::Input group-->
            <div class="row mb-6">
                <label class="col-lg-4 col-form-label required fw-bold fs-6" for="phone_number">No. Telpon/Hp</label>
                <div class="col-lg-8">
                    <div class="input-group input-group-solid mb-2">
                        <span class="input-group-text"><i class="las la-phone fs-1"></i></span>
                        <input type="text" class="form-control form-control-lg form-control-solid mask-13-custom" name="phone_number" id="phone_number" placeholder="Isikan No. Telpon/Hp institusi ..." />
                    </div>
                    <div class="form-text">*) Pastikan No. Telpon/Hp sesuai format dan masih aktif digunakan, contoh: <code>+6283122222222</code></div>
                </div>
            </div>
            <!--end::Input group-->
            <!--begin::Input group-->
            <div class="row mb-6">
                <label class="col-lg-4 col-form-label required fw-bold fs-6" for="office_address">Alamat Kantor</label>
                <div class="col-lg-8">
                    <textarea name="office_address" id="office_address" class="form-control form-control-lg form-control-solid mb-3 mb-lg-0" rows="2" maxlength="225" placeholder="Isikan alamat kantor institusi ..."></textarea>
                </div>
            </div>
            <!--end::Input group-->
            <!--begin::Input group-->
            <div class="row mb-6">
                <label class="col-lg-4 col-form-label required fw-bold fs-6" for="office_lat_coordinate">Titik Koordinat Lokasi Kantor</label>
                <div class="col-lg-8">
                    <div class="row">
                        <div class="col-lg-6 fv-row">
                            <div class="input-group input-group-solid mb-2">
                                <span class="input-group-text"><i class="las la-map-marker-alt fs-1"></i></span>
                                <input type="text" class="form-control form-control-lg form-control-solid coordinate-input" name="office_lat_coordinate" id="office_lat_coordinate" placeholder="-5.142836" />
                            </div>
                            <div class="form-text">*) Pastikan titik koordinat sudah sesuai koordinat lintang, contoh: <code>-5.142836</code></div>
                        </div>
                        <div class="col-lg-6 fv-row">
                            <div class="input-group input-group-solid mb-2">
                                <span class="input-group-text"><i class="las la-map-marker-alt fs-1"></i></span>
                                <input type="text" class="form-control form-control-lg form-control-solid coordinate-input" name="office_long_coordinate" id="office_long_coordinate" placeholder="119.4382801" />
                            </div>
                            <div class="form-text">*) Pastikan titik koordinat sudah sesuai koordinat bujur, contoh: <code>119.4382801</code></div>
                        </div>
                    </div>
                </div>
            </div>
            <!--end::Input group-->
            <!--begin::Input group-->
            <div class="row mb-6">
                <label class="col-lg-4 col-form-label required fw-bold fs-6" for="npwp">No. NPWP</label>
                <div class="col-lg-8">
                    <input type="text" name="npwp" id="npwp" class="form-control form-control-lg form-control-solid mb-3 mb-lg-0" maxlength="25" placeholder="Isikan no. npwp perusahaan ..." />
                </div>
            </div>
            <!--end::Input group-->
            <!--begin::Input group-->
            <div class="row mb-6">
                <label class="col-lg-4 col-form-label required fw-bold fs-6" for="no_jamsostek">No. Jamsostek</label>
                <div class="col-lg-8">
                    <input type="text" name="no_jamsostek" id="no_jamsostek" class="form-control form-control-lg form-control-solid mb-3 mb-lg-0 mask-15" maxlength="15" placeholder="Isikan no. jamsostek perusahaan ..." />
                </div>
            </div>
            <!--end::Input group-->
            <!--begin::Akun Bank Perusahaan-->
            <div class="row mt-12">
                <div class="col-lg-12">
                    <div class="fs-5 fw-bold text-gray-800 mb-0">Akun Bank Perusahaan</div>
                    <input type="hidden" name="fid_bank_account" />
                    <!--begin::Input group-->
                    <div class="row mb-6">
                        <label class="col-lg-4 col-form-label required fw-bold fs-6" for="bank_name">Nama Bank</label>
                        <div class="col-lg-8">
                            <input type="text" name="bank_name" id="bank_name" class="form-control form-control-lg form-control-solid mb-3 mb-lg-0" maxlength="150" placeholder="Isikan nama bank ..." />
                        </div>
                    </div>
                    <!--end::Input group-->
                    <!--begin::Input group-->
                    <div class="row mb-6">
                        <label class="col-lg-4 col-form-label required fw-bold fs-6" for="account_name">Nama Akun Bank</label>
                        <div class="col-lg-8">
                            <input type="text" name="account_name" id="account_name" class="form-control form-control-lg form-control-solid mb-3 mb-lg-0" maxlength="150" placeholder="Isikan nama akun bank ..." />
                        </div>
                    </div>
                    <!--end::Input group-->
                    <!--begin::Input group-->
                    <div class="row mb-6">
                        <label class="col-lg-4 col-form-label required fw-bold fs-6" for="account_number">No. Rekening Bank</label>
                        <div class="col-lg-8">
                            <input type="text" name="account_number" id="account_number" class="form-control form-control-lg form-control-solid mb-3 mb-lg-0 mask-17" maxlength="17" placeholder="Isikan no. rekening bank ..." />
                        </div>
                    </div>
                    <!--end::Input group-->
                </div>
            </div>
            <!--end::Akun Bank Perusahaan-->
            <div class="row mt-5">
                <div class="col-lg-12 d-flex justify-content-end">
                    <button type="button" class="btn btn-light btn-active-light-danger me-2" id="btn-resetFormCompanyInfo"><i class="las la-redo-alt fs-1 me-3"></i>Batal</button>
                    <button type="button" class="btn btn-primary" id="btn-saveCompanyInfo"><i class="las la-save fs-1 me-3"></i>Simpan</button>
                </div>
            </div>
        </form>
    </div>
    <!--end::Edit-->
</div>
<!--end::Company Info-->
@endsection