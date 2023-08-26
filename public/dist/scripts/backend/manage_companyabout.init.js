"use strict";
// Class Definition
$('#profile').summernote({
    placeholder: 'Isi profil perusahaan ...',
    height: 650, minHeight: null, maxHeight: null, dialogsInBody: false, focus: false,
    callbacks: {
        onImageUpload: function(image) {
            var target = document.querySelector('#cardCompanyInfo'), blockUi = new KTBlockUI(target, { message: messageBlockUi });
            blockUi.block(), blockUi.destroy(), _uploadFile_editor(image[0], '#profile'), blockUi.release(), blockUi.destroy();
        }
    }
});
//Load File Dropify
const _loadDropifyFile = (url_file, paramsId) => {
    if (url_file == "") {
        var drEvent1 = $(paramsId).dropify({
            defaultFile: '',
        });
        drEvent1 = drEvent1.data('dropify');
        drEvent1.resetPreview();
        drEvent1.clearElement();
        drEvent1.settings.defaultFile = '';
        drEvent1.destroy();
        drEvent1.init();
    } else {
        var drEvent1 = $(paramsId).dropify({
            defaultFile: url_file,
        });
        drEvent1 = drEvent1.data('dropify');
        drEvent1.resetPreview();
        drEvent1.clearElement();
        drEvent1.settings.defaultFile = url_file;
        drEvent1.destroy();
        drEvent1.init();
    }
}
//begin::Dropify
$('.dropify-upl').dropify({
    messages: {
        'default': '<span class="btn btn-sm btn-secondary">Drag/ drop file atau Klik disini</span>',
        'replace': '<span class="btn btn-sm btn-primary"><i class="fas fa-upload"></i> Drag/ drop atau Klik untuk menimpa file</span>',
        'remove':  '<span class="btn btn-sm btn-danger"><i class="las la-trash-alt"></i> Reset</span>',
        'error':   'Ooops, Terjadi kesalahan pada file input'
    }, error: {
        'fileSize': 'Ukuran file terlalu besar, Max. ( {{ value }} )',
        'minWidth': 'Lebar gambar terlalu kecil, Min. ( {{ value }}}px )',
        'maxWidth': 'Lebar gambar terlalu besar, Max. ( {{ value }}}px )',
        'minHeight': 'Tinggi gambar terlalu kecil, Min. ( {{ value }}}px )',
        'maxHeight': 'Tinggi gambar terlalu besar, Max. ( {{ value }}px )',
        'imageFormat': 'Format file tidak diizinkan, Hanya ( {{ value }} )'
    }
});
//end::Dropify
//Load Edit Company Info
const _loadEditCompanyInfo = () => {
    $("#form-editCompanyInfo")[0].reset(), _loadDropifyFile('', '#logo'), $('#profile').summernote('code', '');
    let target = document.querySelector('#cardCompanyInfo'), blockUi = new KTBlockUI(target, {message: messageBlockUi});
    blockUi.block(), blockUi.destroy();
    $.ajax({
        url: base_url+ "api/manage_companyabout/show",
        type: "GET",
        dataType: "JSON",
        success: function (data) {
            blockUi.release(), blockUi.destroy();
            $('#name').val(data.row.name),
            $('#short_description').val(data.row.short_description),
            _loadDropifyFile(data.row.url_logo, '#logo');
            //Summernote Profile
            let profile = data.row.profile;
            $('#profile').summernote('code', profile);
            $('#email').val(data.row.email),
            $('#phone_number').val(data.row.phone_number),
            $('#office_address').val(data.row.office_address);
            //Coordinat Split
            let lat = data.row.office_address_coordinate.split(",")[0];
            let long = data.row.office_address_coordinate.split(",")[1];
            $('#office_lat_coordinate').val(lat),
            $('#office_long_coordinate').val(long),
            $('#npwp').val(data.row.npwp),
            $('#no_jamsostek').val(data.row.no_jamsostek),
            $('[name="fid_bank_account"]').val(data.row.fid_bank_account),
            $('#bank_name').val(data.row.bank.bank_name),
            $('#account_name').val(data.row.bank.account_name),
            $('#account_number').val(data.row.bank.account_number);
        }, error: function (jqXHR, textStatus, errorThrown) {
            console.log('Load data is error');
            blockUi.release(), blockUi.destroy();
        }
    });
}
// Handle Button Reset / Batal Form Company Info
$('#btn-resetFormCompanyInfo').on('click', function (e) {
    e.preventDefault();
    _loadEditCompanyInfo();
});
//Handle Enter Submit Form Edit Company Info
$("#form-editCompanyInfo input").keyup(function(event) {
    if (event.keyCode == 13 || event.key === 'Enter') {
        $("#btn-saveCompanyInfo").click();
    }
});
// Handle Button Save Form Company Info
$('#btn-saveCompanyInfo').on('click', function (e) {
    e.preventDefault();
    $('#btn-saveCompanyInfo').html('<span class="spinner-border spinner-border-sm align-middle me-3"></span> Mohon Tunggu...').attr('disabled', true);
    let name = $('#name'), short_description = $('#short_description'),
    logo = $('#logo'), logo_preview = $('#iGroup-logo .dropify-preview .dropify-render').html(),
    profile = $('#profile'), email = $('#email'), phone_number = $('#phone_number'), office_address = $('#office_address'),
    office_lat_coordinate = $('#office_lat_coordinate'), office_long_coordinate = $('#office_long_coordinate'),
    npwp = $('#npwp'), no_jamsostek = $('#no_jamsostek'), bank_name = $('#bank_name'), account_name = $('#account_name'), account_number = $('#account_number');

    if (name.val() == '') {
        toastr.error('Nama perusahaan masih kosong...', 'Uuppss!', {"progressBar": true, "timeOut": 1500});
        name.focus();
        $('#btn-saveCompanyInfo').html('<i class="las la-save fs-1 me-3"></i>Simpan').attr('disabled', false);
        return false;
    } if (short_description.val() == '') {
        toastr.error('Deskripsi singkat perusahaan masih kosong...', 'Uuppss!', {"progressBar": true, "timeOut": 1500});
        short_description.focus();
        $('#btn-saveCompanyInfo').html('<i class="las la-save fs-1 me-3"></i>Simpan').attr('disabled', false);
        return false;
    } if (logo_preview == '') {
        toastr.error('Logo perusahaan masih kosong...', 'Uuppss!', {"progressBar": true, "timeOut": 1500});
        $('#iGroup-logo .dropify-wrapper').addClass('border-2 border-danger').stop().delay(1500).queue(function () {
            $(this).removeClass('border-2 border-danger');
        });
        logo.focus();
        $('#btn-saveCompanyInfo').html('<i class="las la-save fs-1 me-3"></i>Simpan').attr('disabled', false);
        return false;
    } if (profile.summernote('isEmpty')) {
        toastr.error('Profil/ tentang perusahaan masih kosong...', 'Uuppss!', {"progressBar": true, "timeOut": 1500});
        profile.summernote('focus');
        $('#btn-saveCompanyInfo').html('<i class="las la-save fs-1 me-3"></i>Simpan').attr('disabled', false);
        return false;
    } if (email.val() == '') {
        toastr.error('Email perusahaan masih kosong...', 'Uuppss!', {"progressBar": true, "timeOut": 1500});
        email.focus();
        $('#btn-saveCompanyInfo').html('<i class="las la-save fs-1 me-3"></i>Simpan').attr('disabled', false);
        return false;
    } if(!validateEmail(email.val())){
        toastr.error('Email perusahaan tidak valid! contoh: bp2td.mempawah@gmail.com ...', 'Uuppss!', {"progressBar": true, "timeOut": 1500});
        email.focus();
        $('#btn-saveCompanyInfo').html('<i class="las la-save fs-1 me-3"></i>Simpan').attr('disabled', false);
        return false;
    } if (phone_number.val() == '') {
        toastr.error('No. Telpon/ Hp perusahaan masih kosong...', 'Uuppss!', {"progressBar": true, "timeOut": 1500});
        phone_number.focus();
        $('#btn-saveCompanyInfo').html('<i class="las la-save fs-1 me-3"></i>Simpan').attr('disabled', false);
        return false;
    } if (office_address.val() == '') {
        toastr.error('Alamat perusahaan masih kosong...', 'Uuppss!', {"progressBar": true, "timeOut": 1500});
        office_address.focus();
        $('#btn-saveCompanyInfo').html('<i class="las la-save fs-1 me-3"></i>Simpan').attr('disabled', false);
        return false;
    } if (office_lat_coordinate.val() == '') {
        toastr.error('Titik koordinat lintang lokasi perusahaan masih kosong...', 'Uuppss!', {"progressBar": true, "timeOut": 1500});
        office_lat_coordinate.focus();
        $('#btn-saveCompanyInfo').html('<i class="las la-save fs-1 me-3"></i>Simpan').attr('disabled', false);
        return false;
    } if (office_long_coordinate.val() == '') {
        toastr.error('Titik koordinat bujur lokasi perusahaan masih kosong...', 'Uuppss!', {"progressBar": true, "timeOut": 1500});
        office_long_coordinate.focus();
        $('#btn-saveCompanyInfo').html('<i class="las la-save fs-1 me-3"></i>Simpan').attr('disabled', false);
        return false;
    } if (npwp.val() == '') {
        toastr.error('No. NPWP perusahaan masih kosong...', 'Uuppss!', {"progressBar": true, "timeOut": 1500});
        npwp.focus();
        $('#btn-saveCompanyInfo').html('<i class="las la-save fs-1 me-3"></i>Simpan').attr('disabled', false);
        return false;
    } if (no_jamsostek.val() == '') {
        toastr.error('No. JAMSOSTEK masih kosong...', 'Uuppss!', {"progressBar": true, "timeOut": 1500});
        no_jamsostek.focus();
        $('#btn-saveCompanyInfo').html('<i class="las la-save fs-1 me-3"></i>Simpan').attr('disabled', false);
        return false;
    } if (bank_name.val() == '') {
        toastr.error('Nama Bank perusahaan masih kosong...', 'Uuppss!', {"progressBar": true, "timeOut": 1500});
        bank_name.focus();
        $('#btn-saveCompanyInfo').html('<i class="las la-save fs-1 me-3"></i>Simpan').attr('disabled', false);
        return false;
    } if (account_name.val() == '') {
        toastr.error('Nama akun bank perusahaan masih kosong...', 'Uuppss!', {"progressBar": true, "timeOut": 1500});
        account_name.focus();
        $('#btn-saveCompanyInfo').html('<i class="las la-save fs-1 me-3"></i>Simpan').attr('disabled', false);
        return false;
    } if (account_number.val() == '') {
        toastr.error('No. rekening bank perusahaan masih kosong...', 'Uuppss!', {"progressBar": true, "timeOut": 1500});
        account_number.focus();
        $('#btn-saveCompanyInfo').html('<i class="las la-save fs-1 me-3"></i>Simpan').attr('disabled', false);
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
            let target = document.querySelector('#cardCompanyInfo'), blockUi = new KTBlockUI(target, {message: messageBlockUi});
            blockUi.block(), blockUi.destroy();
            let formData = new FormData($('#form-editCompanyInfo')[0]), ajax_url= base_url+ "api/manage_companyabout/update";
            $.ajax({
                url: ajax_url,
                headers: { 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') },
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                dataType: "JSON",
                success: function (data) {
                    $('#btn-saveCompanyInfo').html('<i class="las la-save fs-1 me-3"></i>Simpan').attr('disabled', false);
                    blockUi.release(), blockUi.destroy();
                    if (data.status==true){
                        Swal.fire({title: "Success!", text: data.message, icon: "success", allowOutsideClick: false}).then(function (result) {
                            _loadEditCompanyInfo();
                        });
                    } else {
                        Swal.fire({title: "Ooops!", text: data.message, icon: "warning", allowOutsideClick: false});
                    }
                }, error: function (jqXHR, textStatus, errorThrown) {
                    $('#btn-saveCompanyInfo').html('<i class="las la-save fs-1 me-3"></i>Simpan').attr('disabled', false);
                    blockUi.release(), blockUi.destroy();
                    Swal.fire({title: "Ooops!", text: "Terjadi kesalahan yang tidak diketahui, Periksa koneksi jaringan internet lalu coba kembali. Mohon hubungi pengembang jika masih mengalami masalah yang sama.", icon: "error", allowOutsideClick: false});
                }
            });
        }else{
            $('#btn-saveCompanyInfo').html('<i class="las la-save fs-1 me-3"></i>Simpan').attr('disabled', false);
        }
    });
});
// Class Initialization
jQuery(document).ready(function() {
    _loadEditCompanyInfo();
    //Mask Custom
    $('.mask-13-custom').mask('099-9999999999');
    $('.mask-15').mask('099999999999999');
    $('.mask-17').mask('09999999999999999');
    $('#npwp').mask('09.999.999.9-999.999');
    $(".coordinate-input").on("input", function(){
        var value = this.value;
        value = value.trim();
        //If minus symbol occur at the beginning
        if(value.charAt(0) === '-'){
            value = value.substring(1, value.length);
            value = "-"+value.replace(/^\.|[^\d\.]|\.(?=.*\.)|^0+(?=\d)/g, '');
        }else{
            value = value.replace(/^\.|[^\d\.]|\.(?=.*\.)|^0+(?=\d)/g, '');
        }
        this.value = value;
    });
    //Lock Space Username
    $('.no-space').on('keypress', function (e) {
        return e.which !== 32;
    });
});