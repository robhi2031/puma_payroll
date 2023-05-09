"use strict";
//Class Definition
var save_method;
var table;
//Load Datatables Permissions
const _loadDtPermissions = () => {
    table = $('#dt-permissions').DataTable({
        searchDelay: 300,
        processing: true,
        serverSide: true,
        ajax: {
            url: base_url+ 'api/manage_permissions/show',
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
            { data: 'name', name: 'name', width: "20%", className: "align-top border px-2" },
            { data: 'icon', name: 'icon', width: "5%", className: "align-top border px-2", searchable: false },
            { data: 'route_name', name: 'route_name', width: "15%", className: "align-top border px-2" },
            { data: 'parent', name: 'parent', width: "15%", className: "align-top border px-2", searchable: false },
            { data: 'has_child', name: 'has_child', width: "5%", className: "align-top border px-2" },
            { data: 'crud', name: 'crud', width: "15%", className: "align-top border px-2", searchable: false },
            { data: 'order_line', name: 'order_line', width: "5%", className: "align-top border px-2" },
            { data: 'action', name: 'action', width: "15%", className: "align-top text-center border px-2", orderable: false, searchable: false },
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
            $("#search-dtPermissions").on("keyup", function () {
                table.search(this.value).draw();
                if ($(this).val().length > 0) {
                    $("#clear-searchDtPermissions").show();
                } else {
                    $("#clear-searchDtPermissions").hide();
                }
            });
            //Clear Search Table
            $("#clear-searchDtPermissions").on("click", function () {
                $("#search-dtPermissions").val(""),
                table.search("").draw(),
                $("#clear-searchDtPermissions").hide();
            });
            //Custom Table
            $("#dt-permissions_length select").selectpicker(),
            $('[data-bs-toggle="tooltip"]').tooltip({ 
                trigger: "hover"
            }).on("click", function () {
                $(this).tooltip("hide");
            });
        },
    });
    $("#dt-permissions").css("width", "100%"),
    $("#search-dtPermissions").val(""),
    $("#clear-searchDtPermissions").hide();
}
//Close Content Card by Open Method
const _closeCard = (card) => {
    if(card=='form_permission') {
        save_method = '';
        _clearFormPermission(), $('#card-formPermission .card-header .card-title').html('');
    }
    $('#card-formPermission').hide(), $('#card-dtPermissions').show();
}
//Clear Form Permission
const _clearFormPermission = () => {
    $('#iGroup-icon span.input-group-text').html('...');
    //Has Route
    $('#has_route').prop('checked', false), $('#route_name').val(''), $('#iGroup-routeName').hide();
    //Has Parent
    $('#has_parent').prop('checked', false), $("#cbo_parent").html('').trigger('change'), $('#iGroup-cboParent').hide();
    //Has Crud
    $('.crud').prop('checked', false), $('#iGroup-crud').hide();
    //Old Name
    $('[name="old_name"]').val("");
    if (save_method == "" || save_method == "add_permission") {
        $("#form-permission")[0].reset(), $('[name="id"]').val("");
    } else {
        let idp = $('[name="id"]').val();
        _editPermission(idp);
    }
}
//Add Permission
const _addPermission = () => {
    save_method = "add_permission";
    _clearFormPermission(),
    $("#card-formPermission .card-header .card-title").html(
        `<h3 class="fw-bolder fs-2 text-gray-900"><i class="bi bi-window-plus fs-2 text-gray-900 me-2"></i>Form Tambah Permission/ Menu</h3>`
    ),
    $("#card-dtPermissions").hide(), $("#card-formPermission").show();
};
//Edit Permission
const _editPermission = (idp) => {
    save_method = "update_permission";
    let target = document.querySelector("#card-formPermission"), blockUi = new KTBlockUI(target, { message: messageBlockUi });
    blockUi.block(), blockUi.destroy();
    //Ajax load from ajax
    $.ajax({
        url: base_url+ 'api/manage_permissions/show',
        headers: { 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') },
        type: 'GET',
        dataType: 'JSON',
        data: {
            idp,
        },
        success: function (data) {
            blockUi.release(), blockUi.destroy();
            if (data.status == true) {
                $('[name="id"]').val(data.row.id),
                $('#name').val(data.row.name);
                $('[name="old_name"]').val(data.row.name);
                if(data.row.icon !== null && data.row.icon !== '') {
                    $('#iGroup-icon span.input-group-text').html('<i class="bi ' +data.row.icon+ ' text-dark fs-2"></i>');
                }
                $('#icon').val(data.row.icon);
                $('#order_line').val(data.row.order_line);
                //Route Custom
                if (data.row.has_route == 'Y') {
                    $('#has_route').prop('checked', true),
                    $('#route_name').val(data.row.route_name),
                    $('#iGroup-routeName').show();
                }
                //Parent Custom
                if (data.row.parent_id !== null && data.row.parent_id !== '') {
                    $('#has_parent').prop('checked', true);
                    _cboParentSelect2();
                    const selectedParent = $("<option selected='selected'></option>").val(data.row.parent.id).text(data.row.parent.name);
                    $("#cbo_parent").append(selectedParent).trigger('change');
                    $('#iGroup-cboParent').show();
                }
                //Has Child
                if (data.row.has_child == 'Y') {
                    $('#has_child').prop('checked', true);
                }
                //Crud Custom
                if (data.row.is_crud == 'Y') {
                    $('#is_crud').prop('checked', true);
                    if(data.row.create==1) {
                        $('#create').prop('checked', true)
                    } if(data.row.read==1) {
                        $('#read').prop('checked', true)
                    } if(data.row.update==1) {
                        $('#update').prop('checked', true)
                    } if(data.row.delete==1) {
                        $('#delete').prop('checked', true)
                    }
                    $('#iGroup-crud').show();
                }
                $("#card-formPermission .card-header .card-title").html(
                    `<h3 class="fw-bolder fs-2 text-gray-900"><i class="bi bi-pencil-square fs-2 text-gray-900 me-2"></i>Form Edit Permission/ Menu</h3>`
                ),
                $("#card-dtPermissions").hide(), $("#card-formPermission").show();
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
//Change Icon Input for Form
$("#icon").on("keyup", function () {
    let value = this.value;
    if(value==null || value=='') {
        $('#iGroup-icon span.input-group-text').html('...');
    } else {
        $('#iGroup-icon span.input-group-text').html('<i class="bi ' +value+ ' text-dark fs-2"></i>');
    }
});
//Has Route Change Switch
$("#has_route").change(function() {
    if(save_method=='add_permission') {
        $('#route_name').val('');
    } if(this.checked) {
        $('#iGroup-routeName').show();
    }else{
        $('#iGroup-routeName').hide();
    }
});
//Has Parent Change Switch
$("#has_parent").change(function() {
    if(save_method=='add_permission') {
        $("#cbo_parent").html('').trigger('change');
    } if(this.checked) {
        $('#iGroup-cboParent').show();
    }else{
        $('#iGroup-cboParent').hide();
    }
    _cboParentSelect2();
});
//Has Crud Change Switch
$("#is_crud").change(function() {
    if(save_method=='add_permission') {
        $('.crud').prop('checked', false);
    } if(this.checked) {
        $('.crud').prop('checked', true);
        $('#iGroup-crud').show();
    }else{
        $('#iGroup-crud').hide();
    }
    _cboParentSelect2();
});
//Save Permission by Enter
$("#form-permission input").keyup(function (event) {
    if (event.keyCode == 13 || event.key === "Enter") {
        $("#btn-save").click();
    }
});
//Save Permission Form
$("#btn-save").on("click", function (e) {
    e.preventDefault();
    $("#btn-save").html('<span class="spinner-border spinner-border-sm align-middle me-3"></span> Mohon Tunggu...').attr("disabled", true);
    let name = $("#name"), order_line = $("#order_line"), has_route = $("#has_route"), has_parent = $("#has_parent");
    if (name.val() == "") {
        toastr.error("Nama permission/ menu masih kosong...", "Uuppss!", { progressBar: true, timeOut: 1500 });
        name.focus();
        $("#btn-save").html('<i class="las la-save fs-1 me-3"></i>Simpan').attr("disabled", false);
        return false;
    } if (order_line.val() == "") {
        toastr.error("Order line permission/ menu masih kosong...", "Uuppss!", { progressBar: true, timeOut: 1500 });
        order_line.focus();
        $("#btn-save").html('<i class="las la-save fs-1 me-3"></i>Simpan').attr("disabled", false);
        return false;
    } if (has_route.is(':checked')) {
        let route_name = $("#route_name");
        if (route_name.val() == "") {
            toastr.error("Nama route masih kosong...", "Uuppss!", { progressBar: true, timeOut: 1500 });
            route_name.focus();
            $("#btn-save").html('<i class="las la-save fs-1 me-3"></i>Simpan').attr("disabled", false);
            return false;
        }
    } if (has_parent.is(':checked')) {
        let cbo_parent = $("#cbo_parent");
        if (cbo_parent.val() == '' || cbo_parent.val() == null) {
            toastr.error("Parent permission/ menu masih kosong...", "Uuppss!", { progressBar: true, timeOut: 1500 });
            cbo_parent.focus().select2('open');
            $("#btn-save").html('<i class="las la-save fs-1 me-3"></i>Simpan').attr("disabled", false);
            return false;
        }
    } else {
        let icon = $("#icon");
        if (icon.val() == "") {
            toastr.error("Icon permission/ menu masih kosong...", "Uuppss!", { progressBar: true, timeOut: 1500 });
            icon.focus();
            $("#btn-save").html('<i class="las la-save fs-1 me-3"></i>Simpan').attr("disabled", false);
            return false;
        } 
    }

    let textConfirmSave = "Simpan perubahan data sekarang ?";
    if (save_method == "add_permission") {
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
            let target = document.querySelector("#card-formPermission"), blockUi = new KTBlockUI(target, { message: messageBlockUi });
            blockUi.block(), blockUi.destroy();
            let formData = new FormData($("#form-permission")[0]), ajax_url = base_url+ "api/manage_permissions/store";
            if(save_method == 'update_permission') {
                ajax_url = base_url+ "api/manage_permissions/update";
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
                        if (save_method == "add_permission") {
                            Swal.fire({
                                title: "Success!",
                                text: "Permission/ Menu berhasil ditambahkan",
                                icon: "success",
                                allowOutsideClick: false,
                            }).then(function (result) {
                                _closeCard('form_permission'), _loadDtPermissions();
                            });
                        } else {
                            Swal.fire({
                                title: "Success!",
                                text: "Permission/ Menu berhasil diperbarui",
                                icon: "success",
                                allowOutsideClick: false,
                            }).then(function (result) {
                                _closeCard('form_permission'), _loadDtPermissions();
                            });
                        }
                    } else {
                        Swal.fire({
                            title: "Ooops!",
                            text: data.message,
                            icon: "warning",
                            allowOutsideClick: false,
                        }).then(function (result) {
                            if (data.row.error_code == "name_available") {
                                name.focus();
                            } if (data.row.error_code == "order_line_available") {
                                order_line.focus();
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
//Get Parent Permissions Select Custom
const _cboParentSelect2 = () => {
    $('#cbo_parent').select2({
        width: '100%', placeholder: 'Pilih parent permission/ menu ...', allowClear: true,
        ajax: {
            url: base_url+ "api/manage_permissions/select2_parentpermissions",
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
//Deleted Permission
const _deletePermission = (idp) => {
    Swal.fire({
        title: "",
        html: 'Yakin ingin hapus data permission ?',
        icon: "question",
        showCancelButton: true,
        allowOutsideClick: false,
        confirmButtonText: "Yakin",
        cancelButtonText: "Tidak, Batalkan!"
    }).then(result => {
        if (result.value) {
            let target = document.querySelector('#card-dtPermissions'), blockUi = new KTBlockUI(target, { message: messageBlockUi });
            blockUi.block(), blockUi.destroy();
            // Load Ajax
            $.ajax({
                url: base_url+ "api/manage_permissions/delete",
                headers: { 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') },
                type: "POST",
                dataType: "JSON",
                data: {
                    idp
                }, success: function (data) {
                    blockUi.release(), blockUi.destroy();
                    if (data.status == true) {
                        Swal.fire({ title: "Success!", html: data.message, icon: "success", allowOutsideClick: false }).then(function (result) {
                            _loadDtPermissions();
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
                    blockUi.release(), blockUi.destroy();
                    Swal.fire({ title: "Ooops!", text: "Terjadi kesalahan yang tidak diketahui, Periksa koneksi jaringan internet lalu coba kembali. Mohon hubungi pengembang jika masih mengalami masalah yang sama.", icon: "error", allowOutsideClick: false }).then(function (result) {
                        console.log("Update data is error!");
                    });
                }
            });
        }
    });
}
//Class Initialization
jQuery(document).ready(function() {
    _loadDtPermissions();
    //Mask Custom
    $('#order_line').mask('099.099');
    //Lock Space Username
	$('.no-space').on('keypress', function (e) {
		return e.which !== 32;
	});
});