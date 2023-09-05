"use strict";
//Class Definition
var save_method;
var table;
//Load Datatables Job Position
const _loadDtJobPosition = () => {
    table = $('#dt-jobPosition').DataTable({
        searchDelay: 300,
        processing: true,
        serverSide: true,
        ajax: {
            url: base_url+ 'api/manage_jobposition/show',
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
            // { data: 'code', name: 'code', width: "20%", className: "align-top border px-2" },
            { data: 'name', name: 'name', width: "75%", className: "align-top border px-2" },
            { data: 'action', name: 'action', width: "20%", className: "align-top text-center border px-2", orderable: false, searchable: false },
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
            $("#search-dtJobPosition").on("keyup", function () {
                table.search(this.value).draw();
                if ($(this).val().length > 0) {
                    $("#clear-searchDtJobPosition").show();
                } else {
                    $("#clear-searchDtJobPosition").hide();
                }
            });
            //Clear Search Table
            $("#clear-searchDtJobPosition").on("click", function () {
                $("#search-dtJobPosition").val(""),
                table.search("").draw(),
                $("#clear-searchDtJobPosition").hide();
            });
            //Custom Table
            $("#dt-jobPosition_length select").selectpicker(),
            $('[data-bs-toggle="tooltip"]').tooltip({ 
                trigger: "hover"
            }).on("click", function () {
                $(this).tooltip("hide");
            });
        },
    });
    $("#dt-jobPosition").css("width", "100%"),
    $("#search-dtJobPosition").val(""),
    $("#clear-searchDtJobPosition").hide();
}
//Close Content Card by Open Method
const _closeCard = (card) => {
    if(card=='form_jobPosition') {
        save_method = '';
        _clearFormJobPosition(), $('#card-formJobPosition .card-header .card-title').html('');
    }
    $('#card-formJobPosition').hide(), $('#card-dtJobPosition').show();
}
//Clear Form Job Position
const _clearFormJobPosition = () => {
    if (save_method == "" || save_method == "add_jobPosition") {
        $("#form-jobPosition")[0].reset(), $('[name="id"]').val("");
    } else {
        let idp = $('[name="id"]').val();
        _editJobPosition(idp);
    }
}
//Add Job Position
const _addJobPosition = () => {
    save_method = "add_jobPosition";
    _clearFormJobPosition(),
    $("#card-formJobPosition .card-header .card-title").html(
        `<h3 class="fw-bolder fs-2 text-gray-900"><i class="bi bi-window-plus fs-2 text-gray-900 me-2"></i>Form Tambah Posisi Pekerjaan</h3>`
    ),
    $("#card-dtJobPosition").hide(), $("#card-formJobPosition").show();
};
//Edit Job Position
const _editJobPosition = (idp) => {
    save_method = "update_jobPosition";
    let target = document.querySelector("#card-formJobPosition"), blockUi = new KTBlockUI(target, { message: messageBlockUi });
    blockUi.block(), blockUi.destroy();
    //Ajax load from ajax
    $.ajax({
        url: base_url+ 'api/manage_jobposition/show',
        headers: { 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') },
        type: 'GET',
        dataType: 'JSON',
        data: {
            idp,
        },
        success: function (data) {
            blockUi.release(), blockUi.destroy();
            if (data.status == true) {
                $('[name="id"]').val(data.row.id), $('#code').val(data.row.code), $('#name').val(data.row.name);

                $("#card-formJobPosition .card-header .card-title").html(
                    `<h3 class="fw-bolder fs-2 text-gray-900"><i class="bi bi-pencil-square fs-2 text-gray-900 me-2"></i>Form Edit Posisi Pekerjaan</h3>`
                ),
                $("#card-dtJobPosition").hide(), $("#card-formJobPosition").show();
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
//Save Job Position by Enter
$("#form-jobPosition input").keyup(function (event) {
    if (event.keyCode == 13 || event.key === "Enter") {
        $("#btn-save").click();
    }
});
//Save Job Position Form
$("#btn-save").on("click", function (e) {
    e.preventDefault();
    $("#btn-save").html('<span class="spinner-border spinner-border-sm align-middle me-3"></span> Mohon Tunggu...').attr("disabled", true);
    let code = $("#code"), name = $("#name");
    /* if (code.val() == "") {
        toastr.error("Kode posisi pekerjaan masih kosong...", "Uuppss!", { progressBar: true, timeOut: 1500 });
        code.focus();
        $("#btn-save").html('<i class="las la-save fs-1 me-3"></i>Simpan').attr("disabled", false);
        return false;
    } */ if (name.val() == "") {
        toastr.error("Nama posisi pekerjaan masih kosong...", "Uuppss!", { progressBar: true, timeOut: 1500 });
        name.focus();
        $("#btn-save").html('<i class="las la-save fs-1 me-3"></i>Simpan').attr("disabled", false);
        return false;
    }

    let textConfirmSave = "Simpan perubahan data sekarang ?";
    if (save_method == "add_jobPosition") {
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
            let target = document.querySelector("#card-formJobPosition"),
            blockUi = new KTBlockUI(target, { message: messageBlockUi });
            blockUi.block(), blockUi.destroy();
            let formData = new FormData($("#form-jobPosition")[0]), ajax_url = base_url+ "api/manage_jobposition/store";
            if(save_method == 'update_jobPosition') {
                ajax_url = base_url+ "api/manage_jobposition/update";
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
                            _closeCard('form_jobPosition'), _loadDtJobPosition();
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
                            } if (data.row.error_code == "name_available") {
                                name.focus();
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
    _loadDtJobPosition();
    //Lock Space
	$('.no-space').on('keypress', function (e) {
		return e.which !== 32;
	});
});