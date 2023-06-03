@extends('backend.layouts', ['activeMenu' => 'EMPLOYEE', 'activeSubMenu' => 'Manpower'])
@section('content')
    <!--begin::Card Form-->
    <div class="card" id="card-formManpower" style="display: none;">
        <!--begin::Card header-->
        <div class="card-header">
            <div class="card-title"></div>
            <div class="card-toolbar">
                <div class="d-flex justify-content-end">
                    <button type="button" class="btn btn-sm btn btn-bg-light btn-color-danger me-2" id="btn-closeFormManpower" onclick="_closeCard('form_manpower');"><i class="fas fa-times"></i> Tutup</button>
                </div>
            </div>
        </div>
        <!--end::Card header-->
        <!--begin::Form-->
        <form id="form-manpower" class="form" onsubmit="return false">
            <input type="hidden" name="id" /><input type="hidden" name="fid_bank_account" />
            <!--begin::Card body-->
            <div class="card-body">
                <!--begin::Input group-->
                <div class="row mb-6 hide-add" id="iGroup-pjuBn" style="display: none;">
                    <label class="col-lg-4 col-form-label fw-bold fs-6" for="pju_bn">PJU. BN</label>
                    <div class="col-lg-8">
                        <input type="text" name="pju_bn" id="pju_bn" class="form-control form-control-lg form-control-solid no-space mb-3 mb-lg-0" maxlength="16" placeholder="Tidak dapat diedit ..." readonly />
                    </div>
                </div>
                <!--end::Input group-->
                <!--begin::Input group-->
                <div class="row mb-6">
                    <label class="col-lg-4 col-form-label fw-bold fs-6" for="ext_bn">EXT. BN</label>
                    <div class="col-lg-8">
                        <input type="text" name="ext_bn" id="ext_bn" class="form-control form-control-lg form-control-solid no-space mb-3 mb-lg-0" maxlength="16" placeholder="Isi EXT. BN karyawan ..." />
                        <div class="form-text">*) Contoh: <code>VALE-001</code></div>
                        <div class="form-text">*) Jika tidak ada, kosongkan saja</div>
                    </div>
                </div>
                <!--end::Input group-->
                <!--begin::Input group-->
                <div class="row mb-6">
                    <label class="col-lg-4 col-form-label required fw-bold fs-6" for="name">Nama</label>
                    <div class="col-lg-8">
                        <input type="text" name="name" id="name" class="form-control form-control-lg form-control-solid mb-3 mb-lg-0" maxlength="150" placeholder="Isi nama karyawan ..." />
                    </div>
                </div>
                <!--end::Input group-->
                <!--begin::Input group-->
                <div class="row mb-6">
                    <label class="col-lg-4 col-form-label required fw-bold fs-6" for="email">Email</label>
                    <div class="col-lg-8">
                        <div class="input-group input-group-solid mb-2">
                            <span class="input-group-text"><i class="las la-envelope fs-1"></i></span>
                            <input type="text" class="form-control form-control-lg form-control-solid no-space" name="email" id="email" placeholder="Isikan email karyawan ..." />
                        </div>
                        <div class="form-text">*) Pastikan email sesuai format dan masih aktif digunakan, contoh: <code>andre123@gmail.com</code></div>
                    </div>
                </div>
                <!--end::Input group-->
                <!--begin::Input group-->
                <div class="row mb-6">
                    <label class="col-lg-4 col-form-label required fw-bold fs-6" for="project_code">Proyek</label>
                    <div class="col-lg-8">
                        <select class="form-select" name="project_code" id="project_code"></select>
                        <div class="form-text">*) Ketik nama/ kode proyek untuk mempercepat pencarian</div>
                    </div>
                </div>
                <!--end::Input group-->
                <!--begin::Input group-->
                <div class="row mb-6">
                    <label class="col-lg-4 col-form-label required fw-bold fs-6" for="jobposition_code">Posisi</label>
                    <div class="col-lg-8">
                        <select class="form-select" name="jobposition_code" id="jobposition_code"></select>
                        <div class="form-text">*) Ketik nama/ kode posisi untuk mempercepat pencarian</div>
                    </div>
                </div>
                <!--end::Input group-->
                <!--begin::Input group-->
                <div class="row mb-6">
                    <label class="col-lg-4 col-form-label required fw-bold fs-6" for="department">Departemen</label>
                    <div class="col-lg-8">
                        <input type="text" name="department" id="department" class="form-control form-control-lg form-control-solid mb-3 mb-lg-0" maxlength="150" placeholder="Isi departemen karyawan ..." />
                    </div>
                </div>
                <!--end::Input group-->
                <!--begin::Input group-->
                <div class="row mb-6">
                    <label class="col-lg-4 col-form-label required fw-bold fs-6" for="npwp">NPWP</label>
                    <div class="col-lg-8">
                        <input type="text" name="npwp" id="npwp" class="form-control form-control-lg form-control-solid mb-3 mb-lg-0" maxlength="25" placeholder="Isi nomor pokok wajib pajak karyawan ..." />
                    </div>
                </div>
                <!--end::Input group-->
                <!--begin::Input group-->
                <div class="row mb-6">
                    <label class="col-lg-4 col-form-label required fw-bold fs-6" for="kpj">Nomor KPJ</label>
                    <div class="col-lg-8">
                        <input type="text" name="kpj" id="kpj" class="form-control form-control-lg form-control-solid mb-3 mb-lg-0" maxlength="25" placeholder="Isi nomor kartu bpjs ketenagakerjaan karyawan ..." />
                    </div>
                </div>
                <!--end::Input group-->
                <!--begin::Input group-->
                <div class="row mb-6">
                    <label class="col-lg-4 col-form-label required fw-bold fs-6" for="kis">Nomor KIS</label>
                    <div class="col-lg-8">
                        <input type="text" name="kis" id="kis" class="form-control form-control-lg form-control-solid mb-3 mb-lg-0" maxlength="25" placeholder="Isi nomor kartu indonesia sehat karyawan ..." />
                    </div>
                </div>
                <!--end::Input group-->
                <!--begin::Input group-->
                <div class="row mb-6" id="iGroup-maritalStatus">
                    <label class="col-lg-4 col-form-label required fw-bold fs-6" for="marital_status">Status Pernikahan</label>
                    <div class="col-lg-8">
                        <select class="show-tick form-select-solid selectpicker" data-width="100%" data-style="btn-sm btn-primary" name="marital_status" id="marital_status" data-container="body" title="Pilih status pernikahan karyawan ...">
                            @php
                                $options_ms = get_selectpicker('marital_status')
                            @endphp
                            @foreach ($options_ms as $ms)
                                <option value="{{ $ms->name }}">{{ $ms->name }} ({{ $ms->desc }})</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <!--end::Input group-->
                <!--begin::Input group-->
                <div class="row mb-6" id="iGroup-shiftCode">
                    <label class="col-lg-4 col-form-label required fw-bold fs-6" for="shift_code">Shift Code</label>
                    <div class="col-lg-8">
                        <select class="show-tick form-select-solid selectpicker" data-width="100%" data-style="btn-sm btn-primary" name="shift_code" id="shift_code" data-container="body" title="Pilih jenis shift karyawan ...">
                            @php
                                $options_shift = get_selectpicker('shift');
                                $disabledOpt = '';
                            @endphp
                            @foreach ($options_shift as $shift)
                                @if ($shift->code == '532')
                                    @php $disabledOpt = 'disabled' @endphp
                                @endif
                                <option value="{{ $shift->code }}" {{ $disabledOpt }}>{{ $shift->code }} ({{ $shift->desc }})</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <!--end::Input group-->
                <!--begin::Input group-->
                <div class="row mb-6 hide-add" id="iGroup-shiftGroup" style="display: none;">
                    <label class="col-lg-4 col-form-label required fw-bold fs-6" for="shift_group">Shift Group</label>
                    <div class="col-lg-8">
                        <select class="show-tick form-select-solid selectpicker" data-width="100%" data-style="btn-sm btn-primary" name="shift_group" id="shift_group" data-container="body" title="Pilih shift group karyawan ...">
                            {{-- @php
                                $options_sg = get_selectpicker('sg')
                            @endphp
                            @foreach ($options_sg as $sg)
                                <option value="{{ $sg->code }}">{{ $sg->code }}</option>
                            @endforeach --}}
                        </select>
                    </div>
                </div>
                <!--end::Input group-->
                <!--begin::Input group-->
                <div class="row mb-6" id="iGroup-payCode">
                    <label class="col-lg-4 col-form-label required fw-bold fs-6" for="pay_code">Pay Code</label>
                    <div class="col-lg-8">
                        <select class="show-tick form-select-solid selectpicker" data-width="100%" data-style="btn-sm btn-primary" name="pay_code" id="pay_code" data-container="body" title="Pilih pay code karyawan ...">
                            @php
                                $options_paycode = get_selectpicker('paycode')
                            @endphp
                            @foreach ($options_paycode as $paycode)
                                <option value="{{ $paycode->code }}">{{ $paycode->code }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <!--end::Input group-->
                <!--begin::Input group-->
                <div class="row mb-6" id="iGroup-isDaily">
                    <label class="col-lg-4 col-form-label required fw-bold fs-6" for="is_daily">Karyawan Harian</label>
                    <div class="col-lg-8">
                        <div class="form-check form-switch form-check-custom form-check-solid mb-3">
                            <input class="form-check-input" type="checkbox" id="is_daily" name="is_daily" />
                            <label class="form-check-label" for="is_daily">TIDAK</label>
                        </div>
                        <!--begin::Input group-->
                        <div class="mb-3 hide-add" id="iGroup-dailyBasic" style="display: none;">
                            <label class="col-form-label required fw-bold fs-6" for="daily_basic">Daily Basic</label>
                            <div class="input-group input-group-solid mb-2">
                                <span class="input-group-text">Rp.</span>
                                <input type="text" class="form-control form-control-lg form-control-solid zero-money" name="daily_basic" id="daily_basic" placeholder="0" />
                            </div>
                            <div class="form-text">*) contoh: <code>100000</code></div>
                        </div>
                        <!--end::Input group-->
                    </div>
                </div>
                <!--end::Input group-->
                <!--begin::Salary dan Tunjangan-->
                <div class="fs-3 fw-bold text-gray-800 mb-0 mt-12">Detail Salary & Tunjangan Karyawan</div>
                <div class="row">
                    <div class="col-lg-4">
                        <!--begin::Input group-->
                        <div class="mb-3">
                            <label class="col-form-label required fw-bold fs-6" for="basic_salary">Basic Salary</label>
                            <div class="input-group input-group-solid mb-2">
                                <span class="input-group-text">Rp.</span>
                                <input type="text" class="form-control form-control-lg form-control-solid zero-money" name="basic_salary" id="basic_salary" placeholder="0" />
                            </div>
                            <div class="form-text">*) contoh: <code>4500000</code></div>
                        </div>
                        <!--end::Input group-->
                    </div>
                    <div class="col-lg-4">
                        <!--begin::Input group-->
                        <div class="mb-3">
                            <label class="col-form-label fw-bold fs-6" for="ot_rate">OT Rate</label>
                            <div class="input-group input-group-solid mb-2">
                                <span class="input-group-text">Rp.</span>
                                <input type="text" class="form-control form-control-lg form-control-solid zero-money" name="ot_rate" id="ot_rate" placeholder="0" />
                            </div>
                            <div class="form-text">*) contoh: <code>50000</code></div>
                        </div>
                        <!--end::Input group-->
                    </div>
                    <div class="col-lg-4">
                        <!--begin::Input group-->
                        <div class="mb-3">
                            <label class="col-form-label fw-bold fs-6" for="attendance_fee">Tunjangan Kehadiran</label>
                            <div class="input-group input-group-solid mb-2">
                                <span class="input-group-text">Rp.</span>
                                <input type="text" class="form-control form-control-lg form-control-solid zero-money" name="attendance_fee" id="attendance_fee" placeholder="0" />
                            </div>
                            <div class="form-text">*) contoh: <code>100000</code></div>
                        </div>
                        <!--end::Input group-->
                    </div>
                    <div class="col-lg-4">
                        <!--begin::Input group-->
                        <div class="mb-3">
                            <label class="col-form-label fw-bold fs-6" for="leave_day">Leave Day</label>
                            <div class="input-group input-group-solid mb-2">
                                <span class="input-group-text">Rp.</span>
                                <input type="text" class="form-control form-control-lg form-control-solid zero-money" name="leave_day" id="leave_day" placeholder="0" />
                            </div>
                            <div class="form-text">*) contoh: <code>100000</code></div>
                        </div>
                        <!--end::Input group-->
                    </div>
                    <div class="col-lg-4">
                        <!--begin::Input group-->
                        <div class="mb-3">
                            <label class="col-form-label fw-bold fs-6" for="premi_sore">Premi Sore</label>
                            <div class="input-group input-group-solid mb-2">
                                <span class="input-group-text">Rp.</span>
                                <input type="text" class="form-control form-control-lg form-control-solid zero-money" name="premi_sore" id="premi_sore" placeholder="0" />
                            </div>
                            <div class="form-text">*) contoh: <code>100000</code></div>
                        </div>
                        <!--end::Input group-->
                    </div>
                    <div class="col-lg-4">
                        <!--begin::Input group-->
                        <div class="mb-3">
                            <label class="col-form-label fw-bold fs-6" for="premi_malam">Premi Malam</label>
                            <div class="input-group input-group-solid mb-2">
                                <span class="input-group-text">Rp.</span>
                                <input type="text" class="form-control form-control-lg form-control-solid zero-money" name="premi_malam" id="premi_malam" placeholder="0" />
                            </div>
                            <div class="form-text">*) contoh: <code>100000</code></div>
                        </div>
                        <!--end::Input group-->
                    </div>
                    <div class="col-lg-4">
                        <!--begin::Input group-->
                        <div class="mb-3">
                            <label class="col-form-label fw-bold fs-6" for="thr">THR</label>
                            <div class="input-group input-group-solid mb-2">
                                <span class="input-group-text">Rp.</span>
                                <input type="text" class="form-control form-control-lg form-control-solid zero-money" name="thr" id="thr" placeholder="0" />
                            </div>
                            <div class="form-text">*) contoh: <code>100000</code></div>
                        </div>
                        <!--end::Input group-->
                    </div>
                    <div class="col-lg-4">
                        <!--begin::Input group-->
                        <div class="mb-3">
                            <label class="col-form-label fw-bold fs-6" for="transport">Transport</label>
                            <div class="input-group input-group-solid mb-2">
                                <span class="input-group-text">Rp.</span>
                                <input type="text" class="form-control form-control-lg form-control-solid zero-money" name="transport" id="transport" placeholder="0" />
                            </div>
                            <div class="form-text">*) contoh: <code>100000</code></div>
                        </div>
                        <!--end::Input group-->
                    </div>
                    <div class="col-lg-4">
                        <!--begin::Input group-->
                        <div class="mb-3">
                            <label class="col-form-label fw-bold fs-6" for="uang_cuti">Uang Cuti</label>
                            <div class="input-group input-group-solid mb-2">
                                <span class="input-group-text">Rp.</span>
                                <input type="text" class="form-control form-control-lg form-control-solid zero-money" name="uang_cuti" id="uang_cuti" placeholder="0" />
                            </div>
                            <div class="form-text">*) contoh: <code>100000</code></div>
                        </div>
                        <!--end::Input group-->
                    </div>
                    <div class="col-lg-4">
                        <!--begin::Input group-->
                        <div class="mb-3">
                            <label class="col-form-label fw-bold fs-6" for="uang_makan">Uang Makan</label>
                            <div class="input-group input-group-solid mb-2">
                                <span class="input-group-text">Rp.</span>
                                <input type="text" class="form-control form-control-lg form-control-solid zero-money" name="uang_makan" id="uang_makan" placeholder="0" />
                            </div>
                            <div class="form-text">*) contoh: <code>100000</code></div>
                        </div>
                        <!--end::Input group-->
                    </div>
                    <div class="col-lg-4">
                        <!--begin::Input group-->
                        <div class="mb-3">
                            <label class="col-form-label fw-bold fs-6" for="bonus">Bonus</label>
                            <div class="input-group input-group-solid mb-2">
                                <span class="input-group-text">Rp.</span>
                                <input type="text" class="form-control form-control-lg form-control-solid zero-money" name="bonus" id="bonus" placeholder="0" />
                            </div>
                            <div class="form-text">*) contoh: <code>100000</code></div>
                        </div>
                        <!--end::Input group-->
                    </div>
                    <div class="col-lg-4">
                        <!--begin::Input group-->
                        <div class="mb-3">
                            <label class="col-form-label fw-bold fs-6" for="interim_location">Interim Location</label>
                            <div class="input-group input-group-solid mb-2">
                                <span class="input-group-text">Rp.</span>
                                <input type="text" class="form-control form-control-lg form-control-solid zero-money" name="interim_location" id="interim_location" placeholder="0" />
                            </div>
                            <div class="form-text">*) contoh: <code>100000</code></div>
                        </div>
                        <!--end::Input group-->
                    </div>
                    <div class="col-lg-4">
                        <!--begin::Input group-->
                        <div class="mb-3">
                            <label class="col-form-label fw-bold fs-6" for="tunjangan_jabatan">Tunjangan Jabatan</label>
                            <div class="input-group input-group-solid mb-2">
                                <span class="input-group-text">Rp.</span>
                                <input type="text" class="form-control form-control-lg form-control-solid zero-money" name="tunjangan_jabatan" id="tunjangan_jabatan" placeholder="0" />
                            </div>
                            <div class="form-text">*) contoh: <code>100000</code></div>
                        </div>
                        <!--end::Input group-->
                    </div>
                    <div class="col-lg-4">
                        <!--begin::Input group-->
                        <div class="mb-3">
                            <label class="col-form-label fw-bold fs-6" for="p_biaya_fasilitas">Potongan Biaya Fasilitas</label>
                            <div class="input-group input-group-solid mb-2">
                                <span class="input-group-text">Rp.</span>
                                <input type="text" class="form-control form-control-lg form-control-solid zero-money" name="p_biaya_fasilitas" id="p_biaya_fasilitas" placeholder="0" />
                            </div>
                            <div class="form-text">*) contoh: <code>100000</code></div>
                        </div>
                        <!--end::Input group-->
                    </div>
                    <div class="col-lg-4">
                        <!--begin::Input group-->
                        <div class="mb-3">
                            <label class="col-form-label fw-bold fs-6" for="pengobatan">Pengobatan</label>
                            <div class="input-group input-group-solid mb-2">
                                <span class="input-group-text">Rp.</span>
                                <input type="text" class="form-control form-control-lg form-control-solid zero-money" name="pengobatan" id="pengobatan" placeholder="0" />
                            </div>
                            <div class="form-text">*) contoh: <code>100000</code></div>
                        </div>
                        <!--end::Input group-->
                    </div>
                </div>
                <!--end::Salary dan Tunjangan-->
                <!--begin::Akun Bank Karyawan-->
                <div class="fs-3 fw-bold text-gray-800 mt-12 mb-0">Akun Bank Karyawan</div>
                <input type="hidden" name="fid_bank_account" />
                <div class="row">
                    <div class="col-lg-4">
                        <!--begin::Input group-->
                        <div class="mb-3">
                            <label class="col-form-label required fw-bold fs-6" for="bank_name">Nama Bank</label>
                            <input type="text" name="bank_name" id="bank_name" class="form-control form-control-lg form-control-solid mb-3 mb-lg-0" maxlength="150" placeholder="Isikan nama bank ..." />
                            <div class="form-text">*) contoh: <code>BANK MANDIRI</code></div>
                        </div>
                        <!--end::Input group-->
                    </div>
                    <div class="col-lg-4">
                        <!--begin::Input group-->
                        <div class="mb-3">
                            <label class="col-form-label required fw-bold fs-6" for="account_name">Nama Akun Bank</label>
                            <input type="text" name="account_name" id="account_name" class="form-control form-control-lg form-control-solid mb-3 mb-lg-0" maxlength="150" placeholder="Isikan nama akun bank ..." />
                            <div class="form-text">*) contoh: <code>ARI WIJAYA</code></div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <!--begin::Input group-->
                        <div class="mb-3">
                            <label class="col-form-label required fw-bold fs-6" for="account_number">No. Rekening Bank</label>
                            <input type="text" name="account_number" id="account_number" class="form-control form-control-lg form-control-solid mb-3 mb-lg-0 mask-16" maxlength="16" placeholder="Isikan no. rekening bank ..." />
                            <div class="form-text">*) contoh: <code>1234567890</code></div>
                        </div>
                        <!--end::Input group-->
                    </div>
                </div>
                <!--end::Akun Bank Perusahaan-->
                <!--begin::Input group-->
                <div class="row mt-12 mb-6 hide-add" id="iGroup-workStatus" style="display: none;">
                    <label class="col-lg-4 col-form-label required fw-bold fs-6" for="work_status">Status</label>
                    <div class="col-lg-8">
                        <div class="form-check form-switch form-check-custom form-check-solid mb-3">
                            <input class="form-check-input" type="checkbox" id="work_status" name="work_status" disabled />
                            <label class="form-check-label" for="work_status">NON ACTIVE</label>
                        </div>
                    </div>
                </div>
                <!--end::Input group-->
            </div>
            <!--end::Card body-->
            <!--begin::Actions-->
            <div class="card-footer d-flex justify-content-end py-6 px-9">
                <button type="button" class="btn btn-light btn-active-light-danger me-2" id="btn-reset" onclick="_clearFormManpower();"><i class="las la-redo-alt fs-1 me-3"></i>Batal</button>
                <button type="button" class="btn btn-primary" id="btn-save"><i class="las la-save fs-1 me-3"></i>Simpan</button>
            </div>
            <!--end::Actions-->
        </form>
        <!--end::Form-->
    </div>
    <!--end::Card Form-->
    <!--begin::Modal Import Data-->
    <div class="modal fade" id="modal-importManpower" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-hidden="true">
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
                    <div class="scroll-y me-n7 pe-7" data-kt-scroll="true"
                        data-kt-scroll-activate="{default: false, lg: true}" data-kt-scroll-max-height="auto"
                        data-kt-scroll-offset="185px">
                        <!--begin::Notice-->
                        <div class="notice d-flex bg-light-primary rounded flex-shrink-0 p-3">
                            <div class="d-flex flex-stack flex-grow-1 flex-wrap flex-md-nowrap">
                                <div class="mb-3 mb-md-0 fw-semibold">
                                    <h4 class="text-gray-900 fw-bold">Tidak memiliki format Manpower?</h4>
                                    <div class="fs-6 text-gray-700 pe-7">Silahkan download file format dengan cara, klik tombol download.
                                    </div>
                                </div>
                                <a href="https://docs.google.com/spreadsheets/d/1eKGrTNj5_2Y0ZvALtiZmhxnIaj3Faa1XGmVFNpaywtg/edit?usp=sharing" target="_blank" class="btn btn-primary btn-sm align-self-center text-nowrap">
                                    <i class="bi bi-download"></i> Download
                                </a>
                            </div>
                        </div>
                        <!--end::Notice-->
                        <form id="form-importManpower" class="form" onsubmit="return false">
                            <!--begin::Row-->
                            <div class="row">
                                <div class="col-md-12">
                                    <!--begin::Input group-->
                                    <div class="mb-3" id="iGroup-fileImport">
                                        <label class="col-form-label required fw-bold fs-6" for="file_import">File</label>
                                        <input type="file" class="form-control" id="file_import" name="file_import"
                                            accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel"
                                            data-msg-placeholder="Pilih {files}...">
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
                        <button type="button" class="btn btn-light btn-active-light-danger btn-sm me-2"
                            data-bs-dismiss="modal"><i class="las la-times fs-1 me-3"></i>Tutup</button>
                        <button type="button" class="btn btn-primary btn-sm" id="btn-saveImportManpower"><i
                            class="bi bi-cloud-arrow-up fs-1 me-3"></i>Proses Import</button>
                    </div>
                    <!--end::Actions-->
                </div>
                <!--end::Modal footer-->
            </div>
        </div>
    </div>
    <!--end::Modal Import Data-->
    <!--begin::Modal Detail Manpower-->
    <div class="modal fade" id="modal-dtlManpower" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable modal-dialog-centered">
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
                    <div class="scroll-y me-n7 pe-7" data-kt-scroll="true"
                        data-kt-scroll-activate="{default: false, lg: true}" data-kt-scroll-max-height="auto"
                        data-kt-scroll-offset="185px">
                        <div class="row">
                            <div class="col-lg-6 col-sm-12">
                                <h1 class="page-heading text-dark fw-bold fs-3 mt-0 mb-3">
                                    Identitas Karyawan
                                </h1>
                                <div class="border border-dashed border-gray-300 rounded fs-6 px-3 py-4">
                                    <div class="mb-3">
                                        <div class="fw-bold">PJU. BN</div>
                                        <div class="text-gray-600">ID-45453423</div>
                                    </div>
                                    <div class="mb-3">
                                        <div class="fw-bold">EXT. BN</div>
                                        <div class="text-gray-600">ID-45453423</div>
                                    </div>
                                    <div class="mb-3">
                                        <div class="fw-bold">Nama</div>
                                        <div class="text-gray-600">ID-45453423</div>
                                    </div>
                                    <div class="mb-3">
                                        <div class="fw-bold">Email</div>
                                        <div class="text-gray-600">ID-45453423</div>
                                    </div>
                                    <div class="mb-3">
                                        <div class="fw-bold">Project</div>
                                        <div class="text-gray-600">ID-45453423</div>
                                    </div>
                                    <div class="mb-3">
                                        <div class="fw-bold">Job Position</div>
                                        <div class="text-gray-600">ID-45453423</div>
                                    </div>
                                    <div class="mb-3">
                                        <div class="fw-bold">Departemen</div>
                                        <div class="text-gray-600">ID-45453423</div>
                                    </div>
                                    <div class="mb-3">
                                        <div class="fw-bold">NPWP</div>
                                        <div class="text-gray-600">ID-45453423</div>
                                    </div>
                                    <div class="mb-3">
                                        <div class="fw-bold">KPJ</div>
                                        <div class="text-gray-600">ID-45453423</div>
                                    </div>
                                    <div class="mb-3">
                                        <div class="fw-bold">KIS</div>
                                        <div class="text-gray-600">ID-45453423</div>
                                    </div>
                                    <div class="mb-3">
                                        <div class="fw-bold">Marital Status</div>
                                        <div class="text-gray-600">ID-45453423</div>
                                    </div>
                                    <div class="mb-3">
                                        <div class="fw-bold">Shift</div>
                                        <div class="text-gray-600">ID-45453423</div>
                                    </div>
                                    <div class="mb-3">
                                        <div class="fw-bold">Pay Code</div>
                                        <div class="text-gray-600">ID-45453423</div>
                                    </div>
                                    <h1 class="page-heading text-dark fw-bold fs-3 mt-5 mb-3">
                                        Data Akun Bank
                                    </h1>
                                    <div class="mb-3">
                                        <div class="fw-bold">Nama Bank</div>
                                        <div class="text-gray-600">ID-45453423</div>
                                    </div>
                                    <div class="mb-3">
                                        <div class="fw-bold">Nama Akun Bank</div>
                                        <div class="text-gray-600">ID-45453423</div>
                                    </div>
                                    <div class="mb-3">
                                        <div class="fw-bold">Nomor Rekening Bank</div>
                                        <div class="text-gray-600">ID-45453423</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-sm-12">
                                <h1 class="page-heading text-dark fw-bold fs-3 mt-0 mb-3">
                                    Salary & Tunjangan
                                </h1>
                                <div class="border border-dashed border-gray-300 rounded fs-6 px-3 py-4">
                                    <div class="mb-3" id="g-dailyEmployee" style="display: none;">
                                        <div class="fw-bold">Daily Employee</div>
                                        <div class="text-gray-600">ID-45453423</div>
                                    </div>
                                    <div class="mb-3">
                                        <div class="fw-bold">Basic Salary</div>
                                        <div class="text-gray-600">ID-45453423</div>
                                    </div>
                                    <div class="mb-3">
                                        <div class="fw-bold">OT Rate</div>
                                        <div class="text-gray-600">ID-45453423</div>
                                    </div>
                                    <div class="mb-3">
                                        <div class="fw-bold">Attendance Fee</div>
                                        <div class="text-gray-600">ID-45453423</div>
                                    </div>
                                    <div class="mb-3">
                                        <div class="fw-bold">Leave Day</div>
                                        <div class="text-gray-600">ID-45453423</div>
                                    </div>
                                    <div class="mb-3">
                                        <div class="fw-bold">Premi Sore</div>
                                        <div class="text-gray-600">ID-45453423</div>
                                    </div>
                                    <div class="mb-3">
                                        <div class="fw-bold">Premi Malam</div>
                                        <div class="text-gray-600">ID-45453423</div>
                                    </div>
                                    <div class="mb-3">
                                        <div class="fw-bold">THR</div>
                                        <div class="text-gray-600">ID-45453423</div>
                                    </div>
                                    <div class="mb-3">
                                        <div class="fw-bold">Transport</div>
                                        <div class="text-gray-600">ID-45453423</div>
                                    </div>
                                    <div class="mb-3">
                                        <div class="fw-bold">Uang Cuti</div>
                                        <div class="text-gray-600">ID-45453423</div>
                                    </div>
                                    <div class="mb-3">
                                        <div class="fw-bold">Uang Makan</div>
                                        <div class="text-gray-600">ID-45453423</div>
                                    </div>
                                    <div class="mb-3">
                                        <div class="fw-bold">Bonus</div>
                                        <div class="text-gray-600">ID-45453423</div>
                                    </div>
                                    <div class="mb-3">
                                        <div class="fw-bold">Interim Location</div>
                                        <div class="text-gray-600">ID-45453423</div>
                                    </div>
                                    <div class="mb-3">
                                        <div class="fw-bold">Tunjangan Jabatan</div>
                                        <div class="text-gray-600">ID-45453423</div>
                                    </div>
                                    <div class="mb-3">
                                        <div class="fw-bold">P. Biaya Fasilitas</div>
                                        <div class="text-gray-600">ID-45453423</div>
                                    </div>
                                    <div class="mb-3">
                                        <div class="fw-bold">Pengobatan</div>
                                        <div class="text-gray-600">ID-45453423</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--end::Modal body-->
                <!--begin::Modal footer-->
                <div class="modal-footer py-3">
                    <!--begin::Actions-->
                    <div class="d-flex justify-content-end">
                        <button type="button" class="btn btn-light btn-active-light-danger btn-sm me-2"
                            data-bs-dismiss="modal">
                            <i class="las la-times fs-1 me-3"></i>Tutup
                        </button>
                    </div>
                    <!--end::Actions-->
                </div>
                <!--end::Modal footer-->
            </div>
        </div>
    </div>
    <!--end::Modal Detail Manpower-->
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
                    <button type="button" class="btn btn-sm btn-primary me-2" id="btn-addManpower" onclick="_addManpower();"><i class="fas fa-plus"></i> Tambah</button>
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
                            <input type="text" class="form-control form-control-sm form-control-solid border-left-0"
                                name="search-dtManpower" id="search-dtManpower" placeholder="Pencarian..." />
                            <span class="input-group-text border-left-0 cursor-pointer text-hover-danger"
                                id="clear-searchDtManpower" style="display: none;">
                                <i class="las la-times fs-3"></i>
                            </span>
                        </div>
                    </div>
                    <!--end::Search-->
                </div>
            </div>
            <div class="table-responsive">
                <!--begin::Table-->
                <table class="table table-rounded align-middle table-row-bordered border" id="dt-manpower">
                    <thead>
                        <tr class="fw-bolder text-uppercase bg-light">
                            <th class="text-center align-middle px-2 border-bottom-2 border-gray-200">No.</th>
                            <th class="align-middle px-2 border-bottom-2 border-gray-200">BN</th>
                            {{-- <th class="align-middle px-2 border-bottom-2 border-gray-200">EXT BN</th> --}}
                            <th class="align-middle px-2 border-bottom-2 border-gray-200">EMPLOYEE NAME</th>
                            <th class="align-middle px-2 border-bottom-2 border-gray-200">PROJECT</th>
                            <th class="align-middle px-2 border-bottom-2 border-gray-200">JOB POSITION</th>
                            <th class="align-middle px-2 border-bottom-2 border-gray-200">DEPARTMENT</th>
                            <th class="align-middle px-2 border-bottom-2 border-gray-200">SHIFT</th>
                            <!-- Shift Code/ Shift Group -->
                            {{-- <th class="align-middle px-2 border-bottom-2 border-gray-200">PAY CODE</th> --}}
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
