"use strict";
//Class Definition
var save_method;
var table;
//Load Datatables Users
const _loadDtUsers = () => {
    table = $('#dt-users').DataTable({
        searchDelay: 300,
        processing: true,
        serverSide: true,
        ajax: {
            url: base_url+ 'api/manage_users/show',
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
            { data: 'role', name: 'role', width: "1%", className: "align-top border px-2", visible: false, orderable: false },
            { data: 'name', name: 'name', width: "24%", className: "align-top border px-2" },
            { data: 'email', name: 'email', width: "15%", className: "align-top border px-2" },
            { data: 'phone_number', name: 'phone_number', width: "15%", className: "align-top border px-2" },
            { data: 'is_active', name: 'is_active', width: "5%", className: "align-top border px-2", searchable: false },
            { data: 'last_login', name: 'last_login', width: "20%", className: "align-top border px-2", searchable: false },
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
            //Grouping
            var api = this.api();
            var rows = api.rows({
                page: 'current'
            }).nodes();
            var last = null;
            api.column(1, {
                page: 'current'
            }).data().each(function (group, i) {
                if (last !== group) {
                    $(rows).eq(i).before(
                        '<tr class="group fw-bold text-uppercase"><td class="px-3 border bg-light-dark" colspan="7"><i class="bi bi-person-badge fs-3 me-1 text-dark"></i> ' + group + '</td></tr>'
                    );
                    last = group;
                }
            });
            //Search Table
            $("#search-dtUsers").on("keyup", function () {
                table.search(this.value).draw();
                if ($(this).val().length > 0) {
                    $("#clear-searchDtUsers").show();
                } else {
                    $("#clear-searchDtUsers").hide();
                }
            });
            //Clear Search Table
            $("#clear-searchDtUsers").on("click", function () {
                $("#search-dtUsers").val(""),
                table.search("").draw(),
                $("#clear-searchDtUsers").hide();
            });
            //Custom Table
            $("#dt-users_length select").selectpicker(),
            $('[data-bs-toggle="tooltip"]').tooltip({ 
                trigger: "hover"
            }).on("click", function () {
                $(this).tooltip("hide");
            });
            $('.image-popup').magnificPopup({
                type: 'image', closeOnContentClick: true, closeBtnInside: false, fixedContentPos: true,
                image: {
                    verticalFit: true
                }
            });
        },
    });
    $("#dt-users").css("width", "100%"),
    $("#search-dtUsers").val(""),
    $("#clear-searchDtUsers").hide();
}
//Load Selectpicker Role
const loadSelectpicker_role = (value) => {
    $.ajax({
        url: base_url+ "api/manage_users/selectpicker_role",
        type: "GET",
        dataType: "JSON",
        success: function (data) {
            var output = '';
            var i;
            for (i = 0; i < data.row.length; i++) {
                output += '<option value="' + data.row[i].id + '">' + data.row[i].name + '</option>';
            }
            if(value !== null && value !== '') {
                value = value.toString();
            }
            $('#role').html(output).selectpicker('refresh').selectpicker('val', value);
        }, error: function (jqXHR, textStatus, errorThrown) {
            console.log('Load data is error');
        }
    });
}
//Close Content Card by Open Method
const _closeCard = (card) => {
    if(card=='form_user') {
        save_method = '';
        _clearFormUser(), $('#card-formUser .card-header .card-title').html('');
    }
    $('#card-formUser').hide(), $('#card-dtUsers').show();
}
//Clear Form User
const _clearFormUser = () => {
    $('#avatar_remove').val(1),
    $('#iGroup-userThumb .image-input-outline').addClass('image-input-empty'),
    $('#iGroup-userThumb .image-input-outline .image-input-wrapper').attr('style', 'background-image: none;'),
    _loadFotoUser('', ''), $('[name="oldRole_id"]').val("");
    if (save_method == "" || save_method == "add_user") {
        $("#form-user")[0].reset(), $('[name="id"]').val(""), loadSelectpicker_role('');
        $('.password-group').show(), $('#iGroup-isActive').hide();
        // Handle Button Cancel
        $('#btn-cancelUserThumb').on('click', function (e) {
            e.preventDefault();
            _loadFotoUser('', '');
        });
    } else {
        let idp = $('[name="id"]').val();
        _editUser(idp);
    }
}
//Add User
const _addUser = () => {
    save_method = "add_user";
    _clearFormUser(),
    $("#card-formUser .card-header .card-title").html(
        `<h3 class="fw-bolder fs-2 text-gray-900"><i class="bi bi-window-plus fs-2 text-gray-900 me-2"></i>Form Tambah User</h3>`
    ),
    $("#card-dtUsers").hide(), $("#card-formUser").show();
}
//Edit User
const _editUser = (idp) => {
    save_method = "update_user";
    $('#form-user')[0].reset(), $('.password-group').hide(), $('#iGroup-isActive').show();
    let target = document.querySelector("#card-formUser"), blockUi = new KTBlockUI(target, { message: messageBlockUi });
    blockUi.block(), blockUi.destroy();
    //Ajax load from ajax
    $.ajax({
        url: base_url+ 'api/manage_users/show',
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
                _loadFotoUser(data.row.thumb, data.row.url_thumb),
                $('[name="oldRole_id"]').val(data.row.role_id),
                loadSelectpicker_role(data.row.role_id),
                $('#name').val(data.row.name),
                $('#username').val(data.row.username),
                $('#email').val(data.row.email),
                $('#phone_number').val(data.row.phone_number);
                if (data.row.is_active == 'Y') {
                    $('#is_active').prop('checked', true),
                    $('#iGroup-isActive .form-check-label').text('AKTIF');
                } else {
                    $('#is_active').prop('checked', false),
                    $('#iGroup-isActive .form-check-label').text('TIDAK AKTIF');
                }
                $("#card-formUser .card-header .card-title").html(
                    `<h3 class="fw-bolder fs-2 text-gray-900"><i class="bi bi-pencil-square fs-2 text-gray-900 me-2"></i>Form Edit User</h3>`
                ),
                $("#card-dtUsers").hide(), $("#card-formUser").show();
            } else {
                Swal.fire({title: "Ooops!", text: data.message, icon: "warning", allowOutsideClick: false});
            }
        }, complete: function(data) {
            // Handle Button Cancel
            $('#btn-cancelUserThumb').on('click', function (e) {
                e.preventDefault();
                _loadFotoUser(data.responseJSON.row.thumb, data.responseJSON.row.url_thumb);
            });
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
//Load Foto User Profile
const _loadFotoUser = (foto, url_foto) => {
    $('#iGroup-userThumb .image-input-outline').removeClass('image-input-changed image-input-empty'),
    $('#avatar_remove').val(0);
    if(!foto){
        $('#avatar_remove').val(1),
        $('#iGroup-userThumb .image-input-outline').addClass('image-input-empty'),
        $('#iGroup-userThumb .image-input-outline .image-input-wrapper').attr('style', 'background-image: none;');
    } else {
        $('#iGroup-userThumb .image-input-outline .image-input-wrapper').attr('style', `background-image: url('` +url_foto+ `');`);
    }
}
//Save User by Enter
$("#form-user input").keyup(function (event) {
    if (event.keyCode == 13 || event.key === "Enter") {
        $("#btn-save").click();
    }
});
//Save User Form
$("#btn-save").on("click", function (e) {
    e.preventDefault();
    $("#btn-save").html('<span class="spinner-border spinner-border-sm align-middle me-3"></span> Mohon Tunggu...').attr("disabled", true);
    let userThumb = $('#iGroup-userThumb .image-input-wrapper'), role = $("#role"),
    name = $("#name"), username = $("#username"), email = $("#email"), phone_number = $("#phone_number");

    if (userThumb.attr('style')=='' || userThumb.attr('style')=='background-image: none;' || userThumb.attr('style')=='background-image: url();') {
        toastr.error('Foto User masih kosong...', 'Uuppss!', {"progressBar": true, "timeOut": 1500});
        $('#iGroup-userThumb .image-input').addClass('border border-2 border-danger').stop().delay(1500).queue(function () {
			$(this).removeClass('border border-2 border-danger');
		});
        $('#avatar').focus();
        $('#btn-save').html('<i class="las la-save fs-1 me-3"></i>Simpan').attr('disabled', false);
        return false;
    } if (role.val() == '') {
        toastr.error('Role user masih kosong...', 'Uuppss!', {"progressBar": true, "timeOut": 1500});
        $('#iGroup-role button').removeClass('btn-primary').addClass('btn-danger').stop().delay(1500).queue(function () {
			$(this).removeClass('btn-danger').addClass('btn-primary');
		});
        role.focus();
        $('#btn-save').html('<i class="las la-save fs-1 me-3"></i>Simpan').attr('disabled', false);
        return false;
    }
    if (name.val() == "") {
        toastr.error("Nama permission/ menu masih kosong...", "Uuppss!", { progressBar: true, timeOut: 1500 });
        name.focus();
        $("#btn-save").html('<i class="las la-save fs-1 me-3"></i>Simpan').attr("disabled", false);
        return false;
    } if (username.val() == "") {
        toastr.error("Username masih kosong...", "Uuppss!", { progressBar: true, timeOut: 1500 });
        username.focus();
        $("#btn-save").html('<i class="las la-save fs-1 me-3"></i>Simpan').attr("disabled", false);
        return false;
    } if (email.val() == "") {
        toastr.error("Email masih kosong...", "Uuppss!", { progressBar: true, timeOut: 1500 });
        email.focus();
        $("#btn-save").html('<i class="las la-save fs-1 me-3"></i>Simpan').attr("disabled", false);
        return false;
    } if (!validateEmail(email.val())) {
        toastr.error('Email user tidak valid!  contoh: ardi.jeg@gmail.com ...', 'Uuppss!', {"progressBar": true, "timeOut": 1500});
        email.focus();
        $('#btn-save').html('<i class="las la-save fs-1 me-3"></i>Simpan').attr('disabled', false);
        return false;
    } if (phone_number.val() == "") {
        toastr.error("No. Telpon/ Hp masih kosong...", "Uuppss!", { progressBar: true, timeOut: 1500 });
        phone_number.focus();
        $("#btn-save").html('<i class="las la-save fs-1 me-3"></i>Simpan').attr("disabled", false);
        return false;
    } if(save_method == 'add_user') {
        let pass = $('#pass_user'), repass = $('#repass_user');
        if (pass.val() == '') {
            toastr.error('Password masih kosong...', 'Uuppss!', {"progressBar": true, "timeOut": 1500});
            pass.focus();
            $('#btn-save').html('<i class="las la-save fs-1 me-3"></i>Simpan').attr('disabled', false);
            return false;
        }
        if (pass.val().length < 6) {
            toastr.error('Password tidak boleh kurang dari 6 karakter...', 'Uuppss!', {"progressBar": true, "timeOut": 1500});
            pass.focus();
            $('#btn-save').html('<i class="las la-save fs-1 me-3"></i>Simpan').attr('disabled', false);
            return false;
        }
        if (repass.val() == '') {
            toastr.error('Ulangi Password...', 'Uuppss!', {"progressBar": true, "timeOut": 1500});
            repass.focus();
            $('#btn-save').html('<i class="las la-save fs-1 me-3"></i>Simpan').attr('disabled', false);
            return false;
        }
        if (pass.val() != repass.val()) {
            toastr.error('Ulangi Password harus sama...', 'Uuppss!', {"progressBar": true, "timeOut": 1500});
            repass.focus();
            $('#btn-save').html('<i class="las la-save fs-1 me-3"></i>Simpan').attr('disabled', false);
            return false;
        }
    }

    let textConfirmSave = "Simpan perubahan data sekarang ?";
    if (save_method == "add_user") {
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
            let target = document.querySelector("#card-formUser"), blockUi = new KTBlockUI(target, { message: messageBlockUi });
            blockUi.block(), blockUi.destroy();
            let formData = new FormData($("#form-user")[0]), ajax_url = base_url+ "api/manage_users/store";
            if(save_method == 'update_user') {
                ajax_url = base_url+ "api/manage_users/update";
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
                            _closeCard('form_user'), _loadDtUsers();
                        });
                    } else {
                        Swal.fire({
                            title: "Ooops!",
                            html: data.message,
                            icon: "warning",
                            allowOutsideClick: false,
                        }).then(function (result) {
                            if (data.row.error_code == "username_available") {
                                username.focus();
                            } if (data.row.error_code == "email_available") {
                                username.focus();
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
//Update Status Data User
const _updateStatus = (idp, value) => {
    let textLbl = 'Nonaktifkan';
    if(value=='Y') {
        textLbl = 'Aktifkan';
    }
    let textSwal = textLbl+ ' user sekarang ?';
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
            var target = document.querySelector('#card-dtUsers'), blockUi = new KTBlockUI(target, { message: messageBlockUi });
            blockUi.block(), blockUi.destroy();
            // Load Ajax
            $.ajax({
                url: base_url+ "api/manage_users/update_statususers",
                headers: { 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') },
                type: "POST",
                dataType: "JSON",
                data: {
                    idp, value
                }, success: function (data) {
                    blockUi.release(), blockUi.destroy();
                    Swal.fire({ title: "Success!", html: data.message, icon: "success", allowOutsideClick: false }).then(function (result) {
                        _loadDtUsers();
                    });
                }, error: function (jqXHR, textStatus, errorThrown) {
                    blockUi.release(), blockUi.destroy();
                    Swal.fire({ title: "Ooops!", text: "Terjadi kesalahan yang tidak diketahui, Periksa koneksi jaringan internet lalu coba kembali. Mohon hubungi pengembang jika masih mengalami masalah yang sama.", icon: "error", allowOutsideClick: false }).then(function (result) {
                        console.log("Update data is error!");
                        _loadDtUsers();
                    });
                }
            });
        }
    });
}
//Class Initialization
jQuery(document).ready(function() {
    _loadDtUsers();
    //Change Check Switch
    $("#is_active").change(function() {
        if(this.checked) {
            $('#iGroup-isActive .form-check-label').text('AKTIF');
        }else{
            $('#iGroup-isActive .form-check-label').text('TIDAK AKTIF');
        }
    });
    //Mask Custom
    $('#order_line').mask('099.099');
    //Lock Space Username
	$('.no-space').on('keypress', function (e) {
		return e.which !== 32;
	});
    /* [ Show pass ] */
    let showPass = 0;
    $(document).on("mouseenter mouseleave", ".btn-showPass", function (e) {
        if (e.type == "mouseenter") {
            $('.password').attr('type', 'text');
            $('.btn-showPass i').removeClass('las la-eye-slash').addClass('las la-eye');
            $('.btn-showPass').attr('title', 'Tampilkan password');
            showPass = 1;
        } else {
            $('.password').attr('type', 'password');
            $('.btn-showPass i').removeClass('las la-eye').addClass('las la-eye-slash');
            $('.btn-showPass').attr('title', 'Sembunyikan password');
            showPass = 0;
        }
    });
});