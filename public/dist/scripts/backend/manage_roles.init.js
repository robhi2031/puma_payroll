"use strict";
//Class Definition
var save_method;
var table;
var tablePermissions;
//Load Datatables Roles
const _loadDtRoles = () => {
    table = $('#dt-roles').DataTable({
        searchDelay: 300,
        processing: true,
        serverSide: true,
        ajax: {
            url: base_url+ 'api/manage_roles/show',
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
            $("#search-dtRoles").on("keyup", function () {
                table.search(this.value).draw();
                if ($(this).val().length > 0) {
                    $("#clear-searchDtRoles").show();
                } else {
                    $("#clear-searchDtRoles").hide();
                }
            });
            //Clear Search Table
            $("#clear-searchDtRoles").on("click", function () {
                $("#search-dtRoles").val(""),
                table.search("").draw(),
                $("#clear-searchDtRoles").hide();
            });
            //Custom Table
            $("#dt-roles_length select").selectpicker(),
            $('[data-bs-toggle="tooltip"]').tooltip({ 
                trigger: "hover"
            }).on("click", function () {
                $(this).tooltip("hide");
            });
        },
    });
    $("#dt-roles").css("width", "100%"),
    $("#search-dtRoles").val(""),
    $("#clear-searchDtRoles").hide();
}
//Load Datatables Permissions
const _loadDtPermissions = (idp) => {
    tablePermissions = $('#dt-permissions').DataTable({
        searchDelay: 300,
        processing: true,
        serverSide: true,
        ajax: {
            url: base_url+ 'api/manage_roles/show_permissions',
            headers: { 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') },
            type: 'GET',
            data: function ( data ) {
                data.idp= idp;
            }
        },
        destroy: true,
        draw: true,
        deferRender: true,
        responsive: false,
        autoWidth: false,
        paging: false,
        ordering: false,
        info: false,
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', width: "5%", className: "align-top text-center border px-2", searchable: false },
            { data: 'name', name: 'name', width: "55%", className: "align-top border px-2" },
            { data: 'create', name: 'create', width: "10%", className: "align-top text-center border px-2", orderable: false, searchable: false },
            { data: 'read', name: 'read', width: "10%", className: "align-top text-center border px-2", orderable: false, searchable: false },
            { data: 'update', name: 'update', width: "10%", className: "align-top text-center border px-2", orderable: false, searchable: false },
            { data: 'delete', name: 'delete', width: "10%", className: "align-top text-center border px-2", orderable: false, searchable: false },
        ],
        //"dom": "<'row'<'col-sm-6 d-flex align-items-center justify-conten-start'l><'col-sm-6 d-flex align-items-center justify-content-end'f>><'table-responsive'tr><'row'<'col-sm-12 col-md-5 d-flex align-items-center justify-content-center justify-content-md-start'i><'col-sm-12 col-md-7 d-flex align-items-center justify-content-center justify-content-md-end'p>>",
        fnDrawCallback: function (settings, display) {
            //Tooltip
            $('[data-bs-toggle="tooltip"]').tooltip("dispose"), $(".tooltip").hide();
            $('[data-bs-toggle="tooltip"]').tooltip({ 
                trigger: "hover"
            }).on("click", function () {
                $(this).tooltip("hide");
            });
        },
    });
    $("#dt-permissions").css("width", "100%");
}
//Close Content Card by Open Method
const _closeCard = (card) => {
    if(card=='form_role') {
        save_method = '';
        _clearFormRole(), $('#card-formRole .card-header .card-title').html('');
    } if(card=='dt_permissions') {
        $('[name="idpRole"]').val("");
    }
    $('#card-formRole').hide(), $('#card-setPermissions').hide(), $('#card-dtRoles').show();
}
//Clear Form Role
const _clearFormRole = () => {
    if (save_method == "" || save_method == "add_role") {
        $("#form-role")[0].reset(), $('[name="id"]').val("");
    } else {
        let idp = $('[name="id"]').val();
        _editRole(idp);
    }
}
//Add Role
const _addRole = () => {
    save_method = "add_role";
    _clearFormRole(),
    $("#card-formRole .card-header .card-title").html(
        `<h3 class="fw-bolder fs-2 text-gray-900"><i class="bi bi-window-plus fs-2 text-gray-900 me-2"></i>Form Tambah Role</h3>`
    ),
    $("#card-dtRoles").hide(), $("#card-formRole").show();
};
//Edit Role
const _editRole = (idp) => {
    save_method = "update_role";
    let target = document.querySelector("#card-formRole"), blockUi = new KTBlockUI(target, { message: messageBlockUi });
    blockUi.block(), blockUi.destroy();
    //Ajax load from ajax
    $.ajax({
        url: base_url+ 'api/manage_roles/show',
        headers: { 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') },
        type: 'GET',
        dataType: 'JSON',
        data: {
            idp,
        },
        success: function (data) {
            blockUi.release(), blockUi.destroy();
            if (data.status == true) {
                $('[name="id"]').val(data.row.id), $('#name').val(data.row.name);

                $("#card-formRole .card-header .card-title").html(
                    `<h3 class="fw-bolder fs-2 text-gray-900"><i class="bi bi-pencil-square fs-2 text-gray-900 me-2"></i>Form Edit Role</h3>`
                ),
                $("#card-dtRoles").hide(), $("#card-formRole").show();
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
//Save Role by Enter
$("#form-role input").keyup(function (event) {
    if (event.keyCode == 13 || event.key === "Enter") {
        $("#btn-save").click();
    }
});
//Save Role Form
$("#btn-save").on("click", function (e) {
    e.preventDefault();
    $("#btn-save").html('<span class="spinner-border spinner-border-sm align-middle me-3"></span> Mohon Tunggu...').attr("disabled", true);
    let name = $("#name");
    if (name.val() == "") {
        toastr.error("Nama Role masih kosong...", "Uuppss!", { progressBar: true, timeOut: 1500 });
        name.focus();
        $("#btn-save").html('<i class="las la-save fs-1 me-3"></i>Simpan').attr("disabled", false);
        return false;
    }

    let textConfirmSave = "Simpan perubahan data sekarang ?";
    if (save_method == "add_role") {
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
            let target = document.querySelector("#card-formRole"),
            blockUi = new KTBlockUI(target, { message: messageBlockUi });
            blockUi.block(), blockUi.destroy();
            let formData = new FormData($("#form-role")[0]), ajax_url = base_url+ "api/manage_roles/store";
            if(save_method == 'update_role') {
                ajax_url = base_url+ "api/manage_roles/update";
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
                        if (save_method == "add_role") {
                            Swal.fire({
                                title: "Success!",
                                text: "Role berhasil ditambahkan",
                                icon: "success",
                                allowOutsideClick: false,
                            }).then(function (result) {
                                _closeCard('form_role'), _loadDtRoles();
                            });
                        } else {
                            Swal.fire({
                                title: "Success!",
                                text: "Role berhasil diperbarui",
                                icon: "success",
                                allowOutsideClick: false,
                            }).then(function (result) {
                                _closeCard('form_role'), _loadDtRoles();
                            });
                        }
                    } else {
                        if (data.error_code == "data_available") {
                            Swal.fire({
                                title: "Ooops!",
                                text: "Role yang sama sudah ada pada sistem, Coba lagi dengan data yang berbeda!",
                                icon: "warning",
                                allowOutsideClick: false,
                            }).then(function (result) {
                                name.focus();
                            });
                        } else {
                            Swal.fire({
                                title: "Ooops!",
                                text: data.message,
                                icon: "warning",
                                allowOutsideClick: false,
                            });
                        }
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
//Setting Permissions for Role
const _settingPermissions = (idp, name) => {
    _loadDtPermissions(idp), $('[name="idpRole"]').val(idp);
    $("#card-setPermissions .card-header .card-title").html(
        `<h3 class="fw-bolder fs-2 text-gray-900"><i class="bi bi-ui-checks fs-2 text-gray-900 me-2"></i>Setting Permissions Role <span class="badge badge-light-success fw-bold fs-3 px-2">` +name+ `</span></h3>`
    ),
    $("#card-dtRoles").hide(), $("#card-setPermissions").show();
}
//Get Permissions Select Custom
const _cboPermissionsSelest2 = () => {
    $('#cbo_permission').select2({
        width: '100%', placeholder: 'Pilih permission/ menu ...', allowClear: true, dropdownParent: $('#modal-addPermission'),
        ajax: {
            url: base_url+ "api/manage_roles/select2_permissions",
            headers: { 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') },
            dataType: 'json',
            data: function (params) {
                var query = {
                    search: params.term,
                    page: params.page || 1
                }
                // Query parameters will be ?search=[term]&page=[page]
                return query;
            },
            processResults: function (data, params) {
                params.page = params.page || 1;
                return {
                    //results: data.results,
                    results: $.map(data.row.results, function (item) {
                        return {
                            id: item.id,
                            text: item.text
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
//Add Permission on Role
const _addPermission = () => {
    let nameRole = $("#card-setPermissions .card-header .card-title .badge").html();
    $("#form-rolePermission")[0].reset(),
    $("#cbo_permission").html('').trigger('change'), _cboPermissionsSelest2();
    $('#modal-addPermission .modal-header .modal-title').html(`<i class="bi bi-window-plus fs-2 text-gray-900 me-2"></i> Tambah Permission pada <span class="badge badge-light-success fw-bold fs-3 px-2">` +nameRole+ `</span>`);
    $('#modal-addPermission').modal('show');
    $('#modal-addPermission').on('shown.bs.modal', function() {
        $('#cbo_permission').focus().select2('open');
    });
}
//Save Permission on Role Form
$("#btn-savePermission").on("click", function (e) {
    e.preventDefault();
    $("#btn-savePermission").html('<span class="spinner-border spinner-border-sm align-middle me-3"></span> Mohon Tunggu...').attr("disabled", true);
    let cbo_permission = $("#cbo_permission");
    if (cbo_permission.val() == '' || cbo_permission.val() == null) {
        toastr.error("Permission/ menu masih kosong...", "Uuppss!", { progressBar: true, timeOut: 1500 });
        cbo_permission.focus().select2('open');
        $("#btn-savePermission").html('<i class="las la-save fs-1 me-3"></i>Simpan').attr("disabled", false);
        return false;
    }

    let textConfirmSave = "Tambahkan permission pada role ?";
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
            let target = document.querySelector("#modal-addPermission .modal-content"), blockUi = new KTBlockUI(target, { message: messageBlockUi });
            blockUi.block(), blockUi.destroy();
            let formData = new FormData($("#form-rolePermission")[0]), ajax_url = base_url+ "api/manage_roles/store_permissionrole";
            $.ajax({
                url: ajax_url,
                headers: { 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') },
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                dataType: "JSON",
                success: function (data) {
                    $("#btn-savePermission").html('<i class="las la-save fs-1 me-3"></i>Simpan').attr("disabled", false);
                    blockUi.release(), blockUi.destroy();
                    if (data.status == true) {
                        Swal.fire({
                            title: "Success!",
                            text: "Role berhasil ditambahkan",
                            icon: "success",
                            allowOutsideClick: false,
                        }).then(function (result) {
                            $('#modal-addPermission .modal-header .modal-title').html(''), $('#modal-addPermission').modal('hide'),
                            $("#dt-permissions").DataTable().ajax.reload(), $("#dt-permissions").css("width", "100%");
                        });
                    } else {
                        Swal.fire({
                            title: "Ooops!",
                            text: data.message,
                            icon: "warning",
                            allowOutsideClick: false,
                        });
                    }
                }, error: function (jqXHR, textStatus, errorThrown) {
                    $("#btn-savePermission").html('<i class="las la-save fs-1 me-3"></i>Simpan').attr("disabled", false);
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
            $("#btn-savePermission").html('<i class="las la-save fs-1 me-3"></i>Simpan').attr("disabled", false);
        }
    });
});
//Update Permission for Role
const _updatePermission = (idpMenu, idpRole, value, type) => {
    let textLbl = 'Disable';
    if(value=='false') {
        textLbl = 'Enable';
    }
    let textSwal = textLbl+ ' <strong>' +type+ '</strong> permission?';
    Swal.fire({
        title: "",
        html: textSwal,
        icon: "question",
        showCancelButton: true,
        allowOutsideClick: false,
        confirmButtonText: "Ya",
        cancelButtonText: "Tidak, Batalkan!"
    }).then(result => {
        if (result.value) {
            var target = document.querySelector('#card-setPermissions'), blockUi = new KTBlockUI(target, { message: messageBlockUi });
            blockUi.block(), blockUi.destroy();
            // Load Ajax
            $.ajax({
                url: base_url+ "api/manage_roles/update_permissionbyrole",
                headers: { 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') },
                type: "POST",
                dataType: "JSON",
                data: {
                    idpMenu, idpRole, value, type
                }, success: function (data) {
                    blockUi.release(), blockUi.destroy();
                    Swal.fire({ title: "Success!", html: data.message, icon: "success", allowOutsideClick: false }).then(function (result) {
                        $("#dt-permissions").DataTable().ajax.reload(), $("#dt-permissions").css("width", "100%");
                    });
                }, error: function (jqXHR, textStatus, errorThrown) {
                    blockUi.release(), blockUi.destroy();
                    Swal.fire({ title: "Ooops!", text: "Terjadi kesalahan yang tidak diketahui, Periksa koneksi jaringan internet lalu coba kembali. Mohon hubungi pengembang jika masih mengalami masalah yang sama.", icon: "error", allowOutsideClick: false }).then(function (result) {
                        console.log("Update data is error!");
                        $("#dt-permissions").DataTable().ajax.reload(), $("#dt-permissions").css("width", "100%");
                    });
                }
            });
        }
    });
}
//Class Initialization
jQuery(document).ready(function() {
    _loadDtRoles();
});