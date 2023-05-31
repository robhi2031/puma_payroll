"use strict";
//Class Definition
var save_method;
var table;
//Load Datatables Manpower
const _loadDtManpower = () => {
    table = $('#dt-manpower').DataTable({
        searchDelay: 300,
        processing: true,
        serverSide: true,
        ajax: {
            url: base_url+ 'api/manage_manpower/show',
            headers: { 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') },
            type: 'GET',
        },
        destroy: true,
        draw: true,
        deferRender: true,
        responsive: false,
        autoWidth: false,
        LengthChange: true,
        paginate: true,
        pageResize: true,
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', width: "5%", className: "align-top text-center border px-2", searchable: false },
            { data: 'bn', name: 'bn', width: "10%", className: "align-top border px-2" },
            { data: 'name', name: 'name', width: "20%", className: "align-top border px-2" },
            { data: 'project', name: 'project', width: "20%", className: "align-top border px-2" },
            { data: 'job_position', name: 'job_position', width: "15%", className: "align-top border px-2" },
            { data: 'department', name: 'department', width: "10%", className: "align-top border px-2" },
            { data: 'shift_code', name: 'shift_code', width: "10%", className: "align-top border px-2" },
            { data: 'work_status', name: 'work_status', width: "5%", className: "align-top border px-2" },
            { data: 'action', name: 'action', width: "5%", className: "align-top text-center border px-2", orderable: false, searchable: false },
        ],
        oLanguage: {
            sEmptyTable: "Tidak ada Data yang dapat ditampilkan..",
            sInfo: "Menampilkan _START_ s/d _END_ dari _TOTAL_",
            sInfoEmpty: "Menampilkan 0 - 0 dari 0 entri.",
            sInfoFiltered: "",
            sProcessing: `<div class="d-flex justify-content-center align-items-center"><span class="spinner-border align-middle me-3"></span> Mohon Tunggu...</div>`,
            sZeroRecords: "Tidak ada Data yang dapat ditampilkan..",
            sLengthMenu: `<select class="mb-2 show-tick form-select-solid" data-width="fit" data-style="btn-sm btn-secondary" data-container="body">
                <option value="10">10</option>
                <option value="20">20</option>
                <option value="30">30</option>
                <option value="40">40</option>
                <option value="50">50</option>
                <option value="-1">Semua</option>
            </select>`,
            oPaginate: {
                sPrevious: "Sebelumnya",
                sNext: "Selanjutnya",
            },
        },
        //"dom": "<'row'<'col-sm-6 d-flex align-items-center justify-conten-start'l><'col-sm-6 d-flex align-items-center justify-content-end'f>><'table-responsive'tr><'row'<'col-sm-12 col-md-5 d-flex align-items-center justify-content-center justify-content-md-start'i><'col-sm-12 col-md-7 d-flex align-items-center justify-content-center justify-content-md-end'p>>",
        fnDrawCallback: function (settings, display) {
            $('[data-bs-toggle="tooltip"]').tooltip("dispose"), $(".tooltip").hide();
            //Search Table
            $("#search-dtManpower").on("keyup", function () {
                table.search(this.value).draw();
                if ($(this).val().length > 0) {
                    $("#clear-searchDtManpower").show();
                } else {
                    $("#clear-searchDtManpower").hide();
                }
            });
            //Clear Search Table
            $("#clear-searchDtManpower").on("click", function () {
                $("#search-dtManpower").val(""),
                table.search("").draw(),
                $("#clear-searchDtManpower").hide();
            });
            //Custom Table
            $("#dt-manpower_length select").selectpicker(),
            $('[data-bs-toggle="tooltip"]').tooltip({ 
                trigger: "hover"
            }).on("click", function () {
                $(this).tooltip("hide");
            });
        },
    });
    $("#dt-manpower").css("width", "100%"),
    $("#search-dtManpower").val(""),
    $("#clear-searchDtManpower").hide();
}
//Close Content Card by Open Method
const _closeCard = (card) => {
    if(card=='form_manpower') {
        save_method = '';
        _clearFormManpower(), $('#card-formManpower .card-header .card-title').html('');
    }
    $('#card-formManpower').hide(), $('#card-dtManpower').show();
}
//Open Modal Import Data
const _openModalImportManPower = () => {
    $("#form-importManpower")[0].reset(), $('#file_import').val('').fileinput('reset').fileinput('refresh');
    $('#modal-importManpower .modal-header .modal-title').html(`<i class="las la-file-import fs-2 text-gray-900 me-2"></i> Import Data Karyawan`);
    $('#modal-importManpower').modal('show');
}
//Save Import Manpower Form
$("#btn-saveImportManpower").on("click", function (e) {
    e.preventDefault();
    $("#btn-saveImportManpower").html('<span class="spinner-border spinner-border-sm align-middle me-3"></span> Mohon Tunggu...').attr("disabled", true);
    let file_import = $('#file_import');

    if (file_import.val() == '') {
        toastr.error('File masih kosong...', 'Uuppss!', {"progressBar": true, "timeOut": 1500});
        $('#iGroup-fileImport .file-input').addClass('file-input-error rounded').stop().delay(2500).queue(function(){
            $(this).removeClass('file-input-error rounded');
        });
        file_import.focus();
        $('#btn-saveImportManpower').html('<i class="bi bi-cloud-arrow-up fs-1 me-3"></i>Proses Import').attr('disabled', false);
        return false;
    }

    Swal.fire({
        title: "",
        text: "Proses import file data karyawan ?",
        icon: "question",
        showCancelButton: true,
        allowOutsideClick: false,
        confirmButtonText: "Ya",
        cancelButtonText: "Batal",
    }).then((result) => {
        if (result.value) {
            let target = document.querySelector("#modal-importManpower .modal-content"), blockUi = new KTBlockUI(target, { message: messageBlockUi });
            blockUi.block(), blockUi.destroy();
            let formData = new FormData($("#form-importManpower")[0]), ajax_url = base_url+ "api/manage_manpower/import";
            $.ajax({
                url: ajax_url,
                headers: { 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') },
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                dataType: "JSON",
                success: function (data) {
                    $('#btn-saveImportManpower').html('<i class="bi bi-cloud-arrow-up fs-1 me-3"></i>Proses Import').attr('disabled', false);
                    blockUi.release(), blockUi.destroy();
                    if (data.status == true) {
                        Swal.fire({
                            title: "Success!",
                            text: data.message,
                            icon: "success",
                            allowOutsideClick: false,
                        }).then(function (result) {
                            $("#form-importManpower")[0].reset(), $('#file_import').val('').fileinput('reset').fileinput('refresh'), _loadDtProject(),
                            $('#modal-importManpower .modal-header .modal-title').html(''), $('#modal-importManpower').modal('hide');
                        });
                    } else {
                        Swal.fire({
                            title: "Ooops!",
                            text: data.message,
                            icon: "warning",
                            allowOutsideClick: false,
                        }).then(function (result) {
                            // if (data.row.error_code == "code_available") {
                            //     code.focus();
                            // }
                        });
                    }
                }, error: function (jqXHR, textStatus, errorThrown) {
                    $('#btn-saveImportManpower').html('<i class="bi bi-cloud-arrow-up fs-1 me-3"></i>Proses Import').attr('disabled', false);
                    blockUi.release(), blockUi.destroy();
                    Swal.fire({
                        title: "Ooops!",
                        text: "Terjadi kesalahan yang tidak diketahui, Periksa koneksi jaringan internet lalu coba kembali. Mohon hubungi pengembang jika masih mengalami masalah yang sama.",
                        icon: "error",
                        allowOutsideClick: false,
                    });
                }
            });
        } else {
            $('#btn-saveImportManpower').html('<i class="bi bi-cloud-arrow-up fs-1 me-3"></i>Proses Import').attr('disabled', false);
        }
    });
});
//Load Project Select Custom
const _cboProjectSelest2 = () => {
    $('#project_code').select2({
        width: '100%', placeholder: 'Pilih Project ...', allowClear: true,
        ajax: {
            url: base_url+ "api/manage_manpower/select2_project",
            headers: { 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') },
            dataType: 'json',
            data: function (params) {
                let query = {
                    search: params.term,
                    page: params.page || 1
                }
                // Query parameters will be ?search=[term]&page=[page]
                return query;
            }, processResults: function (data, params) {
                params.page = params.page || 1;
                return {
                    //results: data.results,
                    results: $.map(data.row.results, function (item) {
                        return {
                            id: item.code,
                            text: item.code+ ' - ' +item.text
                        }
                    }),
                    pagination: {
                        more: (params.page * 20) < data.row.count
                    }
                };
            },
            cache: true
        }
    });
}
//Load Job Position Select Custom
const _cboJobPositionSelest2 = () => {
    $('#jobposition_code').select2({
        width: '100%', placeholder: 'Pilih Posisi ...', allowClear: true,
        ajax: {
            url: base_url+ "api/manage_manpower/select2_jobposition",
            headers: { 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') },
            dataType: 'json',
            data: function (params) {
                let query = {
                    search: params.term,
                    page: params.page || 1
                }
                // Query parameters will be ?search=[term]&page=[page]
                return query;
            }, processResults: function (data, params) {
                params.page = params.page || 1;
                return {
                    //results: data.results,
                    results: $.map(data.row.results, function (item) {
                        return {
                            id: item.code,
                            text: item.code+ ' - ' +item.text
                        }
                    }),
                    pagination: {
                        more: (params.page * 20) < data.row.count
                    }
                };
            },
            cache: true
        }
    });
}
//Clear Form Manpower
const _clearFormManpower = () => {
    if (save_method == "" || save_method == "add_manpower") {
        $("#form-manpower")[0].reset(), $('[name="id"]').val(""),
        $("#project_code").html('').trigger('change'), _cboProjectSelest2(),
        $("#jobposition_code").html('').trigger('change'), _cboJobPositionSelest2(),
        $("#card-formManpower .selectpicker").selectpicker('val', '');
        $('#card-formManpower .hide-add').hide();
    } else {
        let idp = $('[name="id"]').val();
        _editManpower(idp);
    }
}
//Add Manpower
const _addManpower = () => {
    save_method = "add_manpower";
    _clearFormManpower(),
    $("#card-formManpower .card-header .card-title").html(
        `<h3 class="fw-bolder fs-2 text-gray-900"><i class="bi bi-window-plus fs-2 text-gray-900 me-2"></i>Form Tambah Data Karyawan</h3>`
    ),
    $("#card-dtManpower").hide(), $("#card-formManpower").show();
};
//Edit Manpower
const _editManpower = (idp) => {
    save_method = "update_manpower";
    $("#form-manpower")[0].reset(), $('[name="id"]').val(""),
    $("#project_code").html('').trigger('change'), _cboProjectSelest2(),
    $("#jobposition_code").html('').trigger('change'), _cboJobPositionSelest2(),
    $('#card-formManpower .hide-add').hide();
    let target = document.querySelector("#card-formManpower"), blockUi = new KTBlockUI(target, { message: messageBlockUi });
    blockUi.block(), blockUi.destroy();
    //Ajax load from ajax
    $.ajax({
        url: base_url+ 'api/manage_manpower/show',
        headers: { 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') },
        type: 'GET',
        dataType: 'JSON',
        data: {
            idp,
        },
        success: function (data) {
            blockUi.release(), blockUi.destroy();
            console.log(data);
            if (data.status == true) {
                $('[name="id"]').val(data.row.id),
                $('#pju_bn').val(data.row.pju_bn), $('#iGroup-pjuBn').show(),
                $('#ext_btn').val(data.row.ext_btn),
                $('#name').val(data.row.name),
                $('#email').val(data.row.email);

                let selectedProject = $("<option selected='selected'></option>").val(data.row.project_code).text(data.row.project_code+ ' - ' +data.row.project_name);
                $("#project_code").append(selectedProject).trigger('change');
                let selectedPosition = $("<option selected='selected'></option>").val(data.row.jobposition_code).text(data.row.jobposition_code+ ' - ' +data.row.job_position);
                $("#jobposition_code").append(selectedPosition).trigger('change');

                $('#department').val(data.row.department),
                $('#npwp').val(data.row.npwp),
                $('#kpj').val(data.row.kpj),
                $('#kis').val(data.row.kis),
                $('#marital_status').selectpicker('val', data.row.marital_status),
                $('#shift_code').selectpicker('val', data.row.shift_code),
                $('#pay_code').selectpicker('val', data.row.pay_code);

                $('#is_daily').prop('checked', false);
                $('#iGroup-isDaily .form-check-label').text('TIDAK');
                if(data.row.is_daily == 'Y') {
                    $('#is_daily').prop('checked', true);
                    $('#iGroup-isDaily .form-check-label').text('YA');
                    $('#iGroup-dailyBasic').show();
                }
                $('#daily_basic').val(data.row.daily_basic),
                $('#basic_salary').val(data.row.basic_salary),
                $('#ot_rate').val(data.row.ot_rate),
                $('#attendance_fee').val(data.row.attendance_fee),
                $('#leave_day').val(data.row.leave_day),
                $('#premi_sore').val(data.row.premi_sore),
                $('#premi_malam').val(data.row.premi_malam),
                $('#thr').val(data.row.thr),
                $('#transport').val(data.row.transport),
                $('#uang_cuti').val(data.row.uang_cuti),
                $('#uang_makan').val(data.row.uang_makan),
                $('#bonus').val(data.row.bonus),
                $('#interim_location').val(data.row.interim_location),
                $('#tunjangan_jabatan').val(data.row.tunjangan_jabatan),
                $('#p_biaya_fasilitas').val(data.row.p_biaya_fasilitas),
                $('#pengobatan').val(data.row.pengobatan),
                $('#bank_name').val(data.row.bank_name),
                $('#account_name').val(data.row.account_name),
                $('#account_number').val(data.row.account_number);

                $('#work_status').prop('checked', false);
                $('#iGroup-workStatus .form-check-label').text('NON ACTIVE');
                if(data.row.work_status == 'ACTIVE') {
                    $('#work_status').prop('checked', true);
                    $('#iGroup-workStatus .form-check-label').text('ACTIVE');
                }

                $("#card-formManpower .card-header .card-title").html(
                    `<h3 class="fw-bolder fs-2 text-gray-900"><i class="bi bi-pencil-square fs-2 text-gray-900 me-2"></i>Form Edit Data Karyawan</h3>`
                ),
                $("#card-dtManpower").hide(), $("#card-formManpower").show();
            } else {
                Swal.fire({title: "Ooops!", text: data.message, icon: "warning", allowOutsideClick: false});
            }
        }, error: function (jqXHR, textStatus, errorThrown) {
            blockUi.release(), blockUi.destroy();
            console.log("load data is error!");
            Swal.fire({
                title: "Ooops!",
                text: "Terjadi kesalahan yang tidak diketahui, Periksa koneksi jaringan internet lalu coba kembali. Mohon hubungi pengembang jika masih mengalami masalah yang sama.",
                icon: "error",
                allowOutsideClick: false,
            });
        },
    });
}
//Save Manpower by Enter
$("#form-manpower input").keyup(function (event) {
    if (event.keyCode == 13 || event.key === "Enter") {
        $("#btn-save").click();
    }
});
//Save Manpower Form
$("#btn-save").on("click", function (e) {
    e.preventDefault();
    $("#btn-save").html('<span class="spinner-border spinner-border-sm align-middle me-3"></span> Mohon Tunggu...').attr("disabled", true);
    let name = $("#name"), email = $("#email"), project_code = $("#project_code"),
        jobposition_code = $("#jobposition_code"), department = $("#department"), npwp = $("#npwp"),
        kpj = $("#kpj"), kis = $("#kis"), marital_status = $("#marital_status"),
        shift_code = $("#shift_code"), pay_code = $("#pay_code"),
        basic_salary = $("#basic_salary"), bank_name = $("#bank_name"), account_name = $("#account_name"), account_number = $("#account_number");

    if (name.val() == "") {
        toastr.error("Nama karyawan masih kosong...", "Uuppss!", { progressBar: true, timeOut: 1500 });
        name.focus();
        $("#btn-save").html('<i class="las la-save fs-1 me-3"></i>Simpan').attr("disabled", false);
        return false;
    } if (email.val() == "") {
        toastr.error("Deskripsi karyawan masih kosong...", "Uuppss!", { progressBar: true, timeOut: 1500 });
        email.focus();
        $("#btn-save").html('<i class="las la-save fs-1 me-3"></i>Simpan').attr("disabled", false);
        return false;
    } if (!validateEmail(email.val())) {
        toastr.error('Email karyawan tidak valid!  contoh: ardi.jeg@gmail.com ...', 'Uuppss!', {"progressBar": true, "timeOut": 1500});
        email.focus();
        $('#btn-save').html('<i class="las la-save fs-1 me-3"></i>Simpan').attr('disabled', false);
        return false;
    } if (project_code.val() == "") {
        toastr.error("Proyek masih kosong...", "Uuppss!", { progressBar: true, timeOut: 1500 });
        project_code.focus();
        $("#btn-save").html('<i class="las la-save fs-1 me-3"></i>Simpan').attr("disabled", false);
        return false;
    } if (jobposition_code.val() == "") {
        toastr.error("Job position karyawan masih kosong...", "Uuppss!", { progressBar: true, timeOut: 1500 });
        jobposition_code.focus();
        $("#btn-save").html('<i class="las la-save fs-1 me-3"></i>Simpan').attr("disabled", false);
        return false;
    } if (department.val() == "") {
        toastr.error("Departemen masih kosong...", "Uuppss!", { progressBar: true, timeOut: 1500 });
        department.focus();
        $("#btn-save").html('<i class="las la-save fs-1 me-3"></i>Simpan').attr("disabled", false);
        return false;
    } if (npwp.val() == "") {
        toastr.error("No. pokok wajib pajak karyawan masih kosong...", "Uuppss!", { progressBar: true, timeOut: 1500 });
        npwp.focus();
        $("#btn-save").html('<i class="las la-save fs-1 me-3"></i>Simpan').attr("disabled", false);
        return false;
    } if (kpj.val() == "") {
        toastr.error("No. kartu bpjs ketenagakerjaan karyawan masih kosong...", "Uuppss!", { progressBar: true, timeOut: 1500 });
        kpj.focus();
        $("#btn-save").html('<i class="las la-save fs-1 me-3"></i>Simpan').attr("disabled", false);
        return false;
    } if (kis.val() == "") {
        toastr.error("No. kartu indonesia sehat karyawan masih kosong...", "Uuppss!", { progressBar: true, timeOut: 1500 });
        kis.focus();
        $("#btn-save").html('<i class="las la-save fs-1 me-3"></i>Simpan').attr("disabled", false);
        return false;
    } if (marital_status.val() == "") {
        toastr.error("Status pernikahan masih kosong...", "Uuppss!", { progressBar: true, timeOut: 1500 });
        $('#iGroup-maritalStatus button').removeClass('btn-primary').addClass('btn-danger').stop().delay(1500).queue(function () {
			$(this).removeClass('btn-danger').addClass('btn-primary');
		});
        marital_status.focus();
        $("#btn-save").html('<i class="las la-save fs-1 me-3"></i>Simpan').attr("disabled", false);
        return false;
    } if (shift_code.val() == "") {
        toastr.error("Kode shift masih kosong...", "Uuppss!", { progressBar: true, timeOut: 1500 });
        $('#iGroup-shiftCode button').removeClass('btn-primary').addClass('btn-danger').stop().delay(1500).queue(function () {
			$(this).removeClass('btn-danger').addClass('btn-primary');
		});
        shift_code.focus();
        $("#btn-save").html('<i class="las la-save fs-1 me-3"></i>Simpan').attr("disabled", false);
        return false;
    } if (pay_code.val() == "") {
        toastr.error("Kode pembayaran gaji karyawan masih kosong...", "Uuppss!", { progressBar: true, timeOut: 1500 });
        $('#iGroup-payCode button').removeClass('btn-primary').addClass('btn-danger').stop().delay(1500).queue(function () {
			$(this).removeClass('btn-danger').addClass('btn-primary');
		});
        pay_code.focus();
        $("#btn-save").html('<i class="las la-save fs-1 me-3"></i>Simpan').attr("disabled", false);
        return false;
    } if (npwp.val() == "") {
        toastr.error("No. pokok wajib pajak karyawan masih kosong...", "Uuppss!", { progressBar: true, timeOut: 1500 });
        npwp.focus();
        $("#btn-save").html('<i class="las la-save fs-1 me-3"></i>Simpan').attr("disabled", false);
        return false;
    } if($('#is_daily').is(':checked')) {
        let daily_basic = $("#daily_basic");
        if (daily_basic.val() == "") {
            toastr.error("Daily basic karyawan masih kosong...", "Uuppss!", { progressBar: true, timeOut: 1500 });
            daily_basic.focus();
            $("#btn-save").html('<i class="las la-save fs-1 me-3"></i>Simpan').attr("disabled", false);
            return false;
        }
    } if (basic_salary.val() == "") {
        toastr.error("Basic salary karyawan masih kosong...", "Uuppss!", { progressBar: true, timeOut: 1500 });
        basic_salary.focus();
        $("#btn-save").html('<i class="las la-save fs-1 me-3"></i>Simpan').attr("disabled", false);
        return false;
    } if (bank_name.val() == "") {
        toastr.error("Nama Bank masih kosong...", "Uuppss!", { progressBar: true, timeOut: 1500 });
        bank_name.focus();
        $("#btn-save").html('<i class="las la-save fs-1 me-3"></i>Simpan').attr("disabled", false);
        return false;
    } if (account_name.val() == "") {
        toastr.error("Nama akun bank karyawan masih kosong...", "Uuppss!", { progressBar: true, timeOut: 1500 });
        account_name.focus();
        $("#btn-save").html('<i class="las la-save fs-1 me-3"></i>Simpan').attr("disabled", false);
        return false;
    } if (account_number.val() == "") {
        toastr.error("No. rekening bank karyawan masih kosong...", "Uuppss!", { progressBar: true, timeOut: 1500 });
        account_number.focus();
        $("#btn-save").html('<i class="las la-save fs-1 me-3"></i>Simpan').attr("disabled", false);
        return false;
    }

    let textConfirmSave = "Simpan perubahan data sekarang ?";
    if (save_method == "add_manpower") {
        textConfirmSave = "Tambahkan data sekarang ?";
    }

    Swal.fire({
        title: "",
        text: textConfirmSave,
        icon: "question",
        showCancelButton: true,
        allowOutsideClick: false,
        confirmButtonText: "Ya",
        cancelButtonText: "Batal",
    }).then((result) => {
        if (result.value) {
            let target = document.querySelector("#card-formManpower"),
            blockUi = new KTBlockUI(target, { message: messageBlockUi });
            blockUi.block(), blockUi.destroy();
            let formData = new FormData($("#form-project")[0]), ajax_url = base_url+ "api/manage_manpower/store";
            if(save_method == 'update_project') {
                ajax_url = base_url+ "api/manage_manpower/update";
            }
            $.ajax({
                url: ajax_url,
                headers: { 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') },
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                dataType: "JSON",
                success: function (data) {
                    $("#btn-save").html('<i class="las la-save fs-1 me-3"></i>Simpan').attr("disabled", false);
                    blockUi.release(), blockUi.destroy();
                    if (data.status == true) {
                        Swal.fire({
                            title: "Success!",
                            text: data.message,
                            icon: "success",
                            allowOutsideClick: false,
                        }).then(function (result) {
                            _closeCard('form_project'), _loadDtProject();
                        });
                    } else {
                        Swal.fire({
                            title: "Ooops!",
                            text: data.message,
                            icon: "warning",
                            allowOutsideClick: false,
                        }).then(function (result) {
                            if (data.row.error_code == "code_available") {
                                code.focus();
                            }
                        });
                    }
                }, error: function (jqXHR, textStatus, errorThrown) {
                    $("#btn-save").html('<i class="las la-save fs-1 me-3"></i>Simpan').attr("disabled", false);
                    blockUi.release(), blockUi.destroy();
                    Swal.fire({
                        title: "Ooops!",
                        text: "Terjadi kesalahan yang tidak diketahui, Periksa koneksi jaringan internet lalu coba kembali. Mohon hubungi pengembang jika masih mengalami masalah yang sama.",
                        icon: "error",
                        allowOutsideClick: false,
                    });
                }
            });
        } else {
            $("#btn-save").html('<i class="las la-save fs-1 me-3"></i>Simpan').attr("disabled", false);
        }
    });
});
//Class Initialization
jQuery(document).ready(function() {
    _loadDtManpower();
    //Load File Import Upload
    $("#file_import").fileinput({
        maxFileSize: 8192, //8Mb
        language: "id", showUpload: false, showRemove: false, dropZoneEnabled: false, showPreview: false,
        allowedFileExtensions: ["xlsx", "xls", "csv"], browseClass: "btn btn-dark btn-file btn-square rounded-right",
        browseLabel: "Cari File...", showCancel: false, removeClass: "btn btn-bg-light btn-color-danger", removeLabel: "Hapus"
    });
    //Lock Space
	$('.no-space').on('keypress', function (e) {
		return e.which !== 32;
	});
    //Mask Input
    $('#npwp').mask('00.000.000.0-000.000');
    $('#kpj').mask('000000000000');
    $('#kis').mask('00000000000000');
    $('.zero-money').mask('000000000');
    $('.mask-16').mask('0000000000000000');
    //Change Check Switch
    $("#is_daily").change(function() {
        if(this.checked) {
            $('#iGroup-isDaily .form-check-label').text('YA');
            $('#iGroup-dailyBasic').show(), $('#daily_basic').val('');
        }else{
            $('#iGroup-isDaily .form-check-label').text('TIDAK');
            $('#iGroup-dailyBasic').hide(), $('#daily_basic').val('');
        }
    });
    $("#work_status").change(function() {
        if(this.checked) {
            $('#iGroup-workStatus .form-check-label').text('ACTIVE');
        }else{
            $('#iGroup-workStatus .form-check-label').text('NON ACTIVE');
        }
    });
});