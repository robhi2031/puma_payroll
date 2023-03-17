@extends('backend.layouts')
@section('content')
<!--begin::User Info-->
<div class="card mb-5 mb-xl-10" id="cardUserInfo">
    <div class="card-body">
        <!--begin::Details-->
        <div class="d-flex flex-wrap flex-sm-nowrap mb-3">
            <!--begin: Pic-->
            <div class="me-7 mb-4">
                <a href="#" class="image-popup">
                    <div class="symbol symbol-100px symbol-lg-160px symbol-fixed position-relative">
                        <img src="{{ asset('/dist/img/users-img/300-1.jpg') }}" alt="user-image" />
                        <div class="position-absolute translate-middle bottom-0 start-100 mb-6 bg-success rounded-circle border border-4 border-body h-20px w-20px"></div>
                    </div>
                </a>
            </div>
            <!--end::Pic-->

            <!--begin::Info-->
            <div class="flex-grow-1">
                <!--begin::Title-->
                <div class="d-flex justify-content-between align-items-start flex-wrap mb-2">
                    <!--begin::User-->
                    <div class="d-flex flex-column">
                        <!--begin::Name-->
                        <div class="d-flex align-items-center mb-2">
                            <a href="javascript:void(0);" class="text-gray-900 text-hover-primary fs-2 fw-bold me-1">Nama User</a>
                            <a href="javascript:void(0);" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-dismiss-="click" title="Status user Aktif!">
                                <!--begin::Svg Icon | path: icons/duotune/general/gen026.svg-->
                                <span class="svg-icon svg-icon-1 svg-icon-primary">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" viewBox="0 0 24 24">
                                        <path d="M10.0813 3.7242C10.8849 2.16438 13.1151 2.16438 13.9187 3.7242V3.7242C14.4016 4.66147 15.4909 5.1127 16.4951 4.79139V4.79139C18.1663 4.25668 19.7433 5.83365 19.2086 7.50485V7.50485C18.8873 8.50905 19.3385 9.59842 20.2758 10.0813V10.0813C21.8356 10.8849 21.8356 13.1151 20.2758 13.9187V13.9187C19.3385 14.4016 18.8873 15.491 19.2086 16.4951V16.4951C19.7433 18.1663 18.1663 19.7433 16.4951 19.2086V19.2086C15.491 18.8873 14.4016 19.3385 13.9187 20.2758V20.2758C13.1151 21.8356 10.8849 21.8356 10.0813 20.2758V20.2758C9.59842 19.3385 8.50905 18.8873 7.50485 19.2086V19.2086C5.83365 19.7433 4.25668 18.1663 4.79139 16.4951V16.4951C5.1127 15.491 4.66147 14.4016 3.7242 13.9187V13.9187C2.16438 13.1151 2.16438 10.8849 3.7242 10.0813V10.0813C4.66147 9.59842 5.1127 8.50905 4.79139 7.50485V7.50485C4.25668 5.83365 5.83365 4.25668 7.50485 4.79139V4.79139C8.50905 5.1127 9.59842 4.66147 10.0813 3.7242V3.7242Z" fill="currentColor"/>
                                        <path d="M14.8563 9.1903C15.0606 8.94984 15.3771 8.9385 15.6175 9.14289C15.858 9.34728 15.8229 9.66433 15.6185 9.9048L11.863 14.6558C11.6554 14.9001 11.2876 14.9258 11.048 14.7128L8.47656 12.4271C8.24068 12.2174 8.21944 11.8563 8.42911 11.6204C8.63877 11.3845 8.99996 11.3633 9.23583 11.5729L11.3706 13.4705L14.8563 9.1903Z" fill="white"/>
                                    </svg>
                                </span>
                                <!--end::Svg Icon-->
                            </a>
                            <span class="badge badge-light-success fw-bold ms-2 fs-8 py-1 px-3">Roles User</span>
                        </div>
                        <!--end::Name-->
                    </div>
                    <!--end::User-->
                    <!--begin::Actions-->
                    <div class="d-flex">
                        <a href="javascript:void(0);" class="btn btn-sm btn-primary me-2" onclick="_editMyProfil();"><i class="las la-edit fs-3"></i> Edit</a>
                        <a href="javascript:history.back();" class="btn btn-sm btn btn-bg-light btn-color-danger"><i class="las la-undo fs-3"></i> Kembali</a>
                    </div>
                    <!--end::Actions-->
                </div>
                <!--end::Title-->
                <!--begin::Detail User-->
                <div class="d-flex flex-row flex-column border border-gray-300 border-dashed rounded w-100 py-5 px-4 me-4 my-5">
                    <!--begin::Row-->
                    <div class="row mb-7">
                        <div class="col-lg-6">
                            <div class="w-100 mb-3">
                                <div class="fs-6 text-gray-400">Username</div>
                                <div class="d-flex align-items-center">
                                    <div class="fs-6 fw-bolder" >` + data.rows.username + `</div>
                                </div>
                            </div>
                            <div class="w-100 mb-3">
                                <div class="fs-6 text-gray-400">Email</div>
                                <div class="d-flex align-items-center">
                                    <div class="fs-6 fw-bolder" >` + data.rows.email + `</div>
                                </div>
                            </div>
                            <div class="w-100 mb-3">
                                <div class="fs-6 text-gray-400">Hp/ Telp.</div>
                                <div class="d-flex align-items-center">
                                    <div class="fs-6 fw-bolder" >` + data.rows.no_hp + `</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="w-100 mb-3">
                                <div class="fs-6 text-gray-400">Username</div>
                                <div class="d-flex align-items-center">
                                    <div class="fs-6 fw-bolder" >` + data.rows.username + `</div>
                                </div>
                            </div>
                            <div class="w-100 mb-3">
                                <div class="fs-6 text-gray-400">Email</div>
                                <div class="d-flex align-items-center">
                                    <div class="fs-6 fw-bolder" >` + data.rows.email + `</div>
                                </div>
                            </div>
                            <div class="w-100 mb-3">
                                <div class="fs-6 text-gray-400">Hp/ Telp.</div>
                                <div class="d-flex align-items-center">
                                    <div class="fs-6 fw-bolder" >` + data.rows.no_hp + `</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--end::Input group-->
                </div>
                <!--end::Detail User-->
                <!--begin::Notice-->
                <div class="notice d-flex bg-light-primary rounded border-primary border border-dashed  p-6">
                    <!--begin::Icon-->
                    <!--begin::Svg Icon | path: icons/duotune/general/gen048.svg-->
                    <span class="svg-icon svg-icon-2tx svg-icon-primary me-4">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path opacity="0.3" d="M20.5543 4.37824L12.1798 2.02473C12.0626 1.99176 11.9376 1.99176 11.8203 2.02473L3.44572 4.37824C3.18118 4.45258 3 4.6807 3 4.93945V13.569C3 14.6914 3.48509 15.8404 4.4417 16.984C5.17231 17.8575 6.18314 18.7345 7.446 19.5909C9.56752 21.0295 11.6566 21.912 11.7445 21.9488C11.8258 21.9829 11.9129 22 12.0001 22C12.0872 22 12.1744 21.983 12.2557 21.9488C12.3435 21.912 14.4326 21.0295 16.5541 19.5909C17.8169 18.7345 18.8277 17.8575 19.5584 16.984C20.515 15.8404 21 14.6914 21 13.569V4.93945C21 4.6807 20.8189 4.45258 20.5543 4.37824Z" fill="currentColor"/>
                            <path d="M10.5606 11.3042L9.57283 10.3018C9.28174 10.0065 8.80522 10.0065 8.51412 10.3018C8.22897 10.5912 8.22897 11.0559 8.51412 11.3452L10.4182 13.2773C10.8099 13.6747 11.451 13.6747 11.8427 13.2773L15.4859 9.58051C15.771 9.29117 15.771 8.82648 15.4859 8.53714C15.1948 8.24176 14.7183 8.24176 14.4272 8.53714L11.7002 11.3042C11.3869 11.6221 10.874 11.6221 10.5606 11.3042Z" fill="currentColor"/>
                        </svg>
                    </span>
                    <!--end::Svg Icon-->
                    <!--end::Icon-->
                    <!--begin::Wrapper-->
                    <div class="d-flex flex-stack flex-grow-1 flex-wrap flex-md-nowrap">
                        <!--begin::Content-->
                        <div class="mb-3 mb-md-0 fw-semibold">
                            <h4 class="text-gray-900 fw-bold">Amankan akun user !</h4>
                            <div class="fs-6 text-gray-700 pe-7">Lakukan perubahan password secara berkala untuk mencegah orang lain mengetahui password akun user anda.</div>
                        </div>
                        <!--end::Content-->
                        <!--begin::Action-->
                        <a href="javascript:void(0);" class="btn btn-sm btn-info align-self-center text-nowrap" > 
                            <i class="las la-user-lock fs-3"></i> Ubah Password
                        </a>
                        <!--end::Action-->
                    </div>
                    <!--end::Wrapper--> 
                </div>
                <!--end::Notice-->
            </div>
            <!--end::Info-->
        </div>
        <!--end::Details-->
    </div>
</div>
<!--end::User Info-->
@endsection