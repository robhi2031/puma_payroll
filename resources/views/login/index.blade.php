@extends('login.layouts')
@section('content')
<!--begin::Wrapper-->
<div class="w-100 w-lg-400px bg-body rounded shadow-lg  px-5 pt-15 pb-5 mx-auto" id="kt_sign_in">
    <!--begin::Form-->
    <form class="w-100" id="kt_sign_in_form">
        <!--begin::Img Logo-->
        <div class="text-center mb-3" id="hLogo-login">
            <h1 class="placeholder-glow text-center">
                <p class="placeholder rounded-1 col-lg-12" style="height: 82px;"></p>
            </h1>
        </div>
        <!--end::Img Logo-->
        <!--begin::Input Layout Login 1-->
        <div id="fBody-login1">
            <!--begin::Heading-->
            <div class="text-center mb-5" id="hT-login1">
                <h1 class="placeholder-glow text-center">
                    <p class="placeholder rounded-1 col-lg-12 mb-3" style="height: 32px;"></p>
                    <p class="placeholder rounded-1 col-lg-8"></p>
                </h1>
            </div>
            <!--begin::Heading-->
            <!--begin::Input group-->
            <div class="form-floating mb-10">
                <input type="text" class="form-control" name="username" id="username" autocomplete="off" placeholder="Username atau Email" />
                <label for="username">Username atau Email</label>
            </div>
            <!--end::Input group-->
            <!--begin::Actions-->
            <div class="d-flex flex-stack mt-5">
                <!--begin::Link-->
                <a href="{{ config('app.office_url') }}" class="link-danger text-hover-danger fs-6 fw-bolder"><i class="bi bi-house-fill text-danger"></i> PJU Site</a>
                <!--end::Link-->
                <!--begin::Submit button-->
                <button type="button" id="btn-login1" class="btn btn-sm btn-primary">
                    <i class="bi bi-box-arrow-in-right fs-4"></i> Berikutnya
                </button>
                <!--end::Submit button-->
            </div>
            <!--end::Actions-->
        </div>
        <!--end::Input Layout Login 1-->
        <!--begin::Input Layout Login 2-->
        <div id="fBody-login2" style="display: none;">
            <!--begin::Heading-->
            <div class="text-center mb-5" id="hT-login2">
                <div class="ph-item justify-content-center border-0 py-3 px-0 m-0">
                    <div class="ph-col-10 p-0">
                        <div class="ph-row justify-content-center">
                            <div class="ph-col-12"></div>
                            <div class="ph-col-8"></div>
                        </div>
                    </div>
                </div>
            </div>
            <!--begin::Heading-->
            <input type="hidden" name="hideMail" />
            <!--begin::Input group-->
            <div class="form-floating mb-3">
                <input type="password" class="form-control" name="password" id="password" autocomplete="off" placeholder="Masukkan password anda" />
                <label for="password">Masukkan password anda</label>
            </div>
            <!--end::Input group-->
            <!--begin::Wrapper | flex-stack-->
            <div class="d-flex justify-content-end mb-10">
                <div class="form-check form-check-custom form-check-solid form-check-sm form-check-success">
                    <input class="form-check-input" type="checkbox" name="showPass_checkbox" id="showPass_checkbox" />
                    <label class="form-check-label" for="showPass_checkbox">Tampilkan password</label>
                </div>
            </div>
            <!--end::Wrapper-->
            <!--begin::Actions-->
            <div class="d-flex flex-stack mt-5">
                <!--begin::Link--
                <a href="#" class="link-danger text-hover-danger fs-6 fw-bolder"><i class="bi bi-house-fill text-danger"></i> Halaman Awal</a>
                <!--end::Link-->
                <!--begin::Submit button-->
                <button type="button" id="btn-login2" class="btn btn-sm btn-primary">
                    <i class="bi bi-box-arrow-in-right fs-4"></i> Login
                </button>
                <!--end::Submit button <span class="spinner-border spinner-border-sm align-middle me-3"></span> Mohon Tunggu...-->
            </div>
            <!--end::Actions-->
        </div>
        <!--end::Input Layout Login 2-->
    </form>
    <!--end::Form-->
</div>
<!--end::Wrapper-->
@endsection