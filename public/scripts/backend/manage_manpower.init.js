"use strict";
//Class Definition
var save_method;
var table;
//Load Datatables Project
const _loadDtProject = () => {
    table = $('#dt-project').DataTable({
        searchDelay: 300,
        processing: true,
        serverSide: true,
        ajax: {
            url: base_url+ 'api/manage_project/show',
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
            { data: 'code', name: 'code', width: "10%", className: "align-top border px-2" },
            { data: 'name', name: 'name', width: "20%", className: "align-top border px-2" },
            { data: 'desc', name: 'desc', width: "20%", className: "align-top border px-2" },
            { data: 'client', name: 'client', width: "10%", className: "align-top border px-2" },
            { data: 'location', name: 'location', width: "10%", className: "align-top border px-2" },
            { data: 'project_date', name: 'project_date', width: "15%", className: "align-top border px-2" },
            { data: 'status', name: 'status', width: "5%", className: "align-top border px-2" },
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
            $("#search-dtProject").on("keyup", function () {
                table.search(this.value).draw();
                if ($(this).val().length > 0) {
                    $("#clear-searchDtProject").show();
                } else {
                    $("#clear-searchDtProject").hide();
                }
            });
            //Clear Search Table
            $("#clear-searchDtProject").on("click", function () {
                $("#search-dtProject").val(""),
                table.search("").draw(),
                $("#clear-searchDtProject").hide();
            });
            //Custom Table
            $("#dt-project_length select").selectpicker(),
            $('[data-bs-toggle="tooltip"]').tooltip({ 
                trigger: "hover"
            }).on("click", function () {
                $(this).tooltip("hide");
            });
        },
    });
    $("#dt-project").css("width", "100%"),
    $("#search-dtProject").val(""),
    $("#clear-searchDtProject").hide();
}
//Close Content Card by Open Method
const _closeCard = (card) => {
    if(card=='form_project') {
        save_method = '';
        _clearFormProject(), $('#card-formProject .card-header .card-title').html('');
    }
    $('#card-formProject').hide(), $('#card-dtProject').show();
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
//Clear Form Project
const _clearFormProject = () => {
    $("#card-formProject .selectpicker").selectpicker('val', '');
    $("#card-formProject .date-flatpickr").flatpickr({
        defaultDate: "",
        dateFormat: "d/m/Y"
    });
    if (save_method == "" || save_method == "add_project") {
        $("#form-project")[0].reset(), $('[name="id"]').val("");
    } else {
        let idp = $('[name="id"]').val();
        _editProject(idp);
    }
}
//Add Project
const _addProject = () => {
    save_method = "add_project";
    _clearFormProject(),
    $("#card-formProject .card-header .card-title").html(
        `<h3 class="fw-bolder fs-2 text-gray-900"><i class="bi bi-window-plus fs-2 text-gray-900 me-2"></i>Form Tambah Proyek</h3>`
    ),
    $("#card-dtProject").hide(), $("#card-formProject").show();
};
//Edit Project
const _editProject = (idp) => {
    save_method = "update_project";
    $("#form-project")[0].reset(), $('[name="id"]').val("");
    let target = document.querySelector("#card-formProject"), blockUi = new KTBlockUI(target, { message: messageBlockUi });
    blockUi.block(), blockUi.destroy();
    //Ajax load from ajax
    $.ajax({
        url: base_url+ 'api/manage_project/show',
        headers: { 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') },
        type: 'GET',
        dataType: 'JSON',
        data: {
            idp,
        },
        success: function (data) {
            blockUi.release(), blockUi.destroy();
            if (data.status == true) {
                $('[name="id"]').val(data.row.id), $('#code').val(data.row.code), $('#name').val(data.row.name),
                $('#desc').val(data.row.desc), $('#client').val(data.row.client), $('#location').val(data.row.location);
                $("#start_date").flatpickr({
                    defaultDate: data.row.start_date_indo,
                    dateFormat: "d/m/Y"
                });
                $("#end_date").flatpickr({
                    defaultDate: data.row.end_date_indo,
                    dateFormat: "d/m/Y"
                });
                $("#status").selectpicker('val', data.row.status);
                $("#card-formProject .card-header .card-title").html(
                    `<h3 class="fw-bolder fs-2 text-gray-900"><i class="bi bi-pencil-square fs-2 text-gray-900 me-2"></i>Form Edit Proyek</h3>`
                ),
                $("#card-dtProject").hide(), $("#card-formProject").show();
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
//Save Project by Enter
$("#form-project input").keyup(function (event) {
    if (event.keyCode == 13 || event.key === "Enter") {
        $("#btn-save").click();
    }
});
//Save Project Form
$("#btn-save").on("click", function (e) {
    e.preventDefault();
    $("#btn-save").html('<span class="spinner-border spinner-border-sm align-middle me-3"></span> Mohon Tunggu...').attr("disabled", true);
    let code = $("#code"), name = $("#name"), desc = $("#desc"), client = $("#client"),
        location = $("#location"), start_date = $("#start_date"), end_date = $("#end_date"),
        status = $("#status");
    if (code.val() == "") {
        toastr.error("Kode proyek masih kosong...", "Uuppss!", { progressBar: true, timeOut: 1500 });
        code.focus();
        $("#btn-save").html('<i class="las la-save fs-1 me-3"></i>Simpan').attr("disabled", false);
        return false;
    } if (name.val() == "") {
        toastr.error("Nama proyek masih kosong...", "Uuppss!", { progressBar: true, timeOut: 1500 });
        name.focus();
        $("#btn-save").html('<i class="las la-save fs-1 me-3"></i>Simpan').attr("disabled", false);
        return false;
    } if (desc.val() == "") {
        toastr.error("Deskripsi proyek masih kosong...", "Uuppss!", { progressBar: true, timeOut: 1500 });
        desc.focus();
        $("#btn-save").html('<i class="las la-save fs-1 me-3"></i>Simpan').attr("disabled", false);
        return false;
    } if (client.val() == "") {
        toastr.error("Klien/ mitra proyek masih kosong...", "Uuppss!", { progressBar: true, timeOut: 1500 });
        client.focus();
        $("#btn-save").html('<i class="las la-save fs-1 me-3"></i>Simpan').attr("disabled", false);
        return false;
    } if (location.val() == "") {
        toastr.error("Lokasi proyek masih kosong...", "Uuppss!", { progressBar: true, timeOut: 1500 });
        location.focus();
        $("#btn-save").html('<i class="las la-save fs-1 me-3"></i>Simpan').attr("disabled", false);
        return false;
    } if (start_date.val() == "") {
        toastr.error("Tgl. mulai proyek masih kosong...", "Uuppss!", { progressBar: true, timeOut: 1500 });
        start_date.focus();
        $("#btn-save").html('<i class="las la-save fs-1 me-3"></i>Simpan').attr("disabled", false);
        return false;
    } if (end_date.val() == "") {
        toastr.error("Tgl. selesai proyek masih kosong...", "Uuppss!", { progressBar: true, timeOut: 1500 });
        end_date.focus();
        $("#btn-save").html('<i class="las la-save fs-1 me-3"></i>Simpan').attr("disabled", false);
        return false;
    } if (status.val() == "") {
        toastr.error("Status proyek masih kosong...", "Uuppss!", { progressBar: true, timeOut: 1500 });
        $('#iGroup-status button').removeClass('btn-primary').addClass('btn-danger').stop().delay(1500).queue(function () {
			$(this).removeClass('btn-danger').addClass('btn-primary');
		});
        status.focus();
        $("#btn-save").html('<i class="las la-save fs-1 me-3"></i>Simpan').attr("disabled", false);
        return false;
    }

    let textConfirmSave = "Simpan perubahan data sekarang ?";
    if (save_method == "add_project") {
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
            let target = document.querySelector("#card-formProject"),
            blockUi = new KTBlockUI(target, { message: messageBlockUi });
            blockUi.block(), blockUi.destroy();
            let formData = new FormData($("#form-project")[0]), ajax_url = base_url+ "api/manage_project/store";
            if(save_method == 'update_project') {
                ajax_url = base_url+ "api/manage_project/update";
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
    _loadDtProject();
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
});