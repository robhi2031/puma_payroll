"use strict";
// Class Definition
const _loadSystemInfo = () => {
    let target = document.querySelector('#cardSystemInfo'), blockUi = new KTBlockUI(target, {message: messageBlockUi});
    blockUi.block(), blockUi.destroy();
    _loadDropifyFile('', '#thumb'), _loadDropifyFile('', '#login_bg'), _loadDropifyFile('', '#login_logo'), _loadDropifyFile('', '#backend_logo'), _loadDropifyFile('', '#backend_logo_icon');
    $.ajax({
        url: base_url+ "api/system_info",
        type: "GET",
        dataType: "JSON",
        success: function (data) {
            blockUi.release(), blockUi.destroy();
        }, error: function (jqXHR, textStatus, errorThrown) {
            console.log('Load data is error');
            blockUi.release(), blockUi.destroy();
        }
    });
}
// Handle Button Reset / Batal Form User Profile
$('#btn-resetFormMyProfile').on('click', function (e) {
    e.preventDefault();
    _clearFormEditMyProfile(), _editMyProfile();
});
// Clear Form User Profile
const _clearFormEditMyProfile = () => {
    $('#avatar_remove').val(1),
    $('#iGroup-fotoUser .image-input-outline').addClass('image-input-empty'),
    $('#iGroup-fotoUser .image-input-outline .image-input-wrapper').attr('style', 'background-image: none;');
}
//Load Foto User Profile
const _loadFotoUser = (foto, url_foto) => {
    $('#iGroup-fotoUser .image-input-outline').removeClass('image-input-changed image-input-empty'),
    $('#avatar_remove').val(0);
    if(!foto){
        $('#avatar_remove').val(1),
        $('#iGroup-fotoUser .image-input-outline').addClass('image-input-empty'),
        $('#iGroup-fotoUser .image-input-outline .image-input-wrapper').attr('style', 'background-image: none;');
    } else {
        $('#iGroup-fotoUser .image-input-outline .image-input-wrapper').attr('style', `background-image: url('` +url_foto+ `');`);
    }
}
//Handle Enter Submit Form Edit User Profile
$("#form-editProfile input").keyup(function(event) {
    if (event.keyCode == 13 || event.key === 'Enter') {
        $("#btn-saveMyProfile").click();
    }
});
// Handle Button Save Form User Profile
$('#btn-saveMyProfile').on('click', function (e) {
    e.preventDefault();
    $('#btn-saveMyProfile').html('<span class="spinner-border spinner-border-sm align-middle me-3"></span> Mohon Tunggu...').attr('disabled', true);
    var cek_foto_user = $('#iGroup-fotoUser .image-input-wrapper'),
    name = $('#name'),
    // username = $('#username'),
    email = $('#email'),
    phone_number = $('#phone_number'),
    alamat = $('#alamat'),
    cbo_jabatan = $("#cbo_jabatan"),
    cbo_pdpc = $("#cbo_pdpc");

    if (cek_foto_user.attr('style')=='' || cek_foto_user.attr('style')=='background-image: none;' || cek_foto_user.attr('style')=='background-image: url();') {
        toastr.error('Foto User masih kosong...', 'Uuppss!', {"progressBar": true, "timeOut": 1500});
        $('#iGroup-fotoUser .image-input').addClass('border border-2 border-danger').stop().delay(1500).queue(function () {
			$(this).removeClass('border border-2 border-danger');
		});
        $('#avatar').focus();
        $('#btn-saveMyProfile').html('<i class="las la-save fs-1 me-3"></i>Simpan').attr('disabled', false);
        return false;
    }
    if (name.val() == '') {
        toastr.error('Nama masih kosong...', 'Uuppss!', {"progressBar": true, "timeOut": 1500});
        name.focus();
        $('#btn-saveMyProfile').html('<i class="las la-save fs-1 me-3"></i>Simpan').attr('disabled', false);
        return false;
    }
    if (email.val() == '') {
        toastr.error('Email masih kosong...', 'Uuppss!', {"progressBar": true, "timeOut": 1500});
        email.focus();
        $('#btn-saveMyProfile').html('<i class="las la-save fs-1 me-3"></i>Simpan').attr('disabled', false);
        return false;
    } if(!validateEmail(email.val())){
        toastr.error('Email tidak valid!  contoh: ardi.jeg@gmail.com ...', 'Uuppss!', {"progressBar": true, "timeOut": 1500});
        email.focus();
        $('#btn-saveMyProfile').html('<i class="las la-save fs-1 me-3"></i>Simpan').attr('disabled', false);
        return false;
    }
    if (phone_number.val() == '') {
        toastr.error('No. Hp masih kosong...', 'Uuppss!', {"progressBar": true, "timeOut": 1500});
        phone_number.focus();
        $('#btn-saveMyProfile').html('<i class="las la-save fs-1 me-3"></i>Simpan').attr('disabled', false);
        return false;
    }

    Swal.fire({
        title: "",
        text: "Simpan perubahan sekarang ?",
        icon: "question",
        showCancelButton: true,
        allowOutsideClick: false,
        confirmButtonText: "Ya",
        cancelButtonText: "Batal"
    }).then(result => {
        if (result.value) {
            var target = document.querySelector('#cardUserInfo'), blockUi = new KTBlockUI(target, {message: messageBlockUi});
            blockUi.block(), blockUi.destroy();
            var formData = new FormData($('#form-editProfile')[0]), ajax_url= base_url+ "api/update_userprofile";
            $.ajax({
                url: ajax_url,
                headers: { 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') },
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                dataType: "JSON",
                success: function (data) {
                    $('#btn-saveMyProfile').html('<i class="las la-save fs-1 me-3"></i>Simpan').attr('disabled', false);
                    blockUi.release(), blockUi.destroy();
                    if (data.status==true){
                        Swal.fire({title: "Success!", text: data.message, icon: "success", allowOutsideClick: false}).then(function (result) {
                            location.reload();
                        });
                    } else {
                        if(data.row.error_code == 'email_available') {
                            Swal.fire({title: "Ooops!", text: data.message, icon: "warning", allowOutsideClick: false}).then(function (result) {
                                email.focus();
                            });
                        } else if(data.row.error_code == 'username_available') {
                            Swal.fire({title: "Ooops!", text: data.message, icon: "warning", allowOutsideClick: false}).then(function (result) {
                                email.focus();
                            });
                        } else {
                            Swal.fire({title: "Ooops!", text: data.message, icon: "warning", allowOutsideClick: false});
                        }
                    }
                }, error: function (jqXHR, textStatus, errorThrown) {
                    $('#btn-saveMyProfile').html('<i class="las la-save fs-1 me-3"></i>Simpan').attr('disabled', false);
                    blockUi.release(), blockUi.destroy();
                    Swal.fire({title: "Ooops!", text: "Terjadi kesalahan yang tidak diketahui, Periksa koneksi jaringan internet lalu coba kembali. Mohon hubungi pengembang jika masih mengalami masalah yang sama.", icon: "error", allowOutsideClick: false});
                }
            });
        }else{
            $('#btn-saveMyProfile').html('<i class="las la-save fs-1 me-3"></i>Simpan').attr('disabled', false);
        }
    });
});
// Class Initialization
jQuery(document).ready(function() {
    _loadSystemInfo();
});