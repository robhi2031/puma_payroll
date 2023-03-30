"use strict";
// Class Definition
//Keyword Select2
$('#keyword').select2({
    dropdownAutoWidth: true,
    tags: true,
    maximumSelectionLength: 25,
    placeholder: 'Isi keyword/ kata kunci sistem ...',
    tokenSeparators: [','],
    width: '100%',
    language: { noResults: () => 'Gunakan tanda koma (,) sebagai pemisah tag'}
});
//CopyRight Input
$('#copyright').summernote({
    placeholder: 'Isi copyright sistem ...',
    toolbar: [
        ['style', ['bold', 'italic', 'underline']], ['insert', ['link']], ['view', ['codeview']]
    ],
    height: 100, minHeight: null, maxHeight: null, dialogsInBody: false, focus: false, popatmouse: false, lang: 'id-ID'
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
const _loadEditSystemInfo = () => {
    $("#form-editSystemInfo")[0].reset(), $("#keyword").html('').trigger('change'), $('#copyright').summernote('code', '');
    _loadDropifyFile('', '#thumb'), _loadDropifyFile('', '#login_bg'), _loadDropifyFile('', '#login_logo'), _loadDropifyFile('', '#backend_logo'), _loadDropifyFile('', '#backend_logo_icon');
    let target = document.querySelector('#cardSystemInfo'), blockUi = new KTBlockUI(target, {message: messageBlockUi});
    blockUi.block(), blockUi.destroy();
    $.ajax({
        url: base_url+ "api/system_info",
        type: "GET",
        dataType: "JSON",
        success: function (data) {
            blockUi.release(), blockUi.destroy();
            $('#name').val(data.row.name),
            $('#short_name').val(data.row.short_name),
            $('#description').val(data.row.description);
            //Keyword System
            var selected = '', i;
            for (i = 0; i < data.row.keyword_explode.length; i++) {
                selected += '<option value="' + data.row.keyword_explode[i] + '" selected>' + data.row.keyword_explode[i] + '</option>';
            }
            $("#keyword").html(selected).trigger('change');
            //Summernote CopyRight
            var copyright = data.row.copyright;
            $('#copyright').summernote('code', copyright);
            _loadDropifyFile(data.row.url_thumb, '#thumb'), _loadDropifyFile(data.row.url_loginBg, '#login_bg'), _loadDropifyFile(data.row.url_loginLogo, '#login_logo'), _loadDropifyFile(data.row.url_backendLogo, '#backend_logo'), _loadDropifyFile(data.row.url_backendLogoIcon, '#backend_logo_icon');
        }, error: function (jqXHR, textStatus, errorThrown) {
            console.log('Load data is error');
            blockUi.release(), blockUi.destroy();
        }
    });
}
// Handle Button Reset / Batal Form User Profile
$('#btn-resetFormSystemInfo').on('click', function (e) {
    e.preventDefault();
    _loadEditSystemInfo();
});
//Handle Enter Submit Form Edit User Profile
$("#form-editSystemInfo input").keyup(function(event) {
    if (event.keyCode == 13 || event.key === 'Enter') {
        $("#btn-saveSystemInfo").click();
    }
});
// Handle Button Save Form User Profile
$('#btn-saveSystemInfo').on('click', function (e) {
    e.preventDefault();
    $('#btn-saveSystemInfo').html('<span class="spinner-border spinner-border-sm align-middle me-3"></span> Mohon Tunggu...').attr('disabled', true);
    let name = $('#name'), short_name = $('#short_name'), description = $('#description'), keyword = $('#keyword'), copyright = $('#copyright'),
    thumb = $('#thumb'), thumb_preview = $('#iGroup-thumb .dropify-preview .dropify-render').html(),
    login_bg = $('#login_bg'), login_bg_preview = $('#iGroup-login_bg .dropify-preview .dropify-render').html(),
    login_logo = $('#login_logo'), login_logo_preview = $('#iGroup-login_logo .dropify-preview .dropify-render').html(),
    backend_logo = $('#backend_logo'), backend_logo_preview = $('#iGroup-backend_logo .dropify-preview .dropify-render').html(),
    backend_logo_icon = $('#backend_logo_icon'), backend_logo_icon_preview = $('#iGroup-backend_logo_icon .dropify-preview .dropify-render').html();

    if (name.val() == '') {
        toastr.error('Nama sistem masih kosong...', 'Uuppss!', {"progressBar": true, "timeOut": 1500});
        name.focus();
        $('#btn-saveSystemInfo').html('<i class="las la-save fs-1 me-3"></i>Simpan').attr('disabled', false);
        return false;
    } if (short_name.val() == '') {
        toastr.error('Nama alias/ nama pendek sistem masih kosong...', 'Uuppss!', {"progressBar": true, "timeOut": 1500});
        short_name.focus();
        $('#btn-saveSystemInfo').html('<i class="las la-save fs-1 me-3"></i>Simpan').attr('disabled', false);
        return false;
    } if (description.val() == '') {
        toastr.error('Deskripsi sistem masih kosong...', 'Uuppss!', {"progressBar": true, "timeOut": 1500});
        description.focus();
        $('#btn-saveSystemInfo').html('<i class="las la-save fs-1 me-3"></i>Simpan').attr('disabled', false);
        return false;
    } if (keyword.val() == '' || keyword.val() == null) {
        toastr.error('Keyword/ kata kunci sistem masih kosong...', 'Uuppss!', {"progressBar": true, "timeOut": 1500});
        keyword.focus().select2('open');
        $('#btn-saveSystemInfo').html('<i class="las la-save fs-1 me-3"></i>Simpan').attr('disabled', false);
        return false;
    } if (copyright.summernote('isEmpty')) {
        toastr.error('Copyright system masih kosong...', 'Uuppss!', {"progressBar": true, "timeOut": 1500});
        copyright.summernote('focus');
        $('#btn-saveSystemInfo').html('<i class="las la-save fs-1 me-3"></i>Simpan').attr('disabled', false);
        return false;
    } if (thumb_preview == '') {
        toastr.error('Gambar thumbnail sistem masih kosong...', 'Uuppss!', {"progressBar": true, "timeOut": 1500});
        $('#iGroup-thumb .dropify-wrapper').addClass('border-2 border-danger').stop().delay(1500).queue(function () {
            $(this).removeClass('border-2 border-danger');
        });
        thumb.focus();
        $('#btn-saveSystemInfo').html('<i class="las la-save fs-1 me-3"></i>Simpan').attr('disabled', false);
        return false;
    } if (login_bg_preview == '') {
        toastr.error('Gambar background login sistem masih kosong...', 'Uuppss!', {"progressBar": true, "timeOut": 1500});
        $('#iGroup-login_bg .dropify-wrapper').addClass('border-2 border-danger').stop().delay(1500).queue(function () {
            $(this).removeClass('border-2 border-danger');
        });
        login_bg.focus();
        $('#btn-saveSystemInfo').html('<i class="las la-save fs-1 me-3"></i>Simpan').attr('disabled', false);
        return false;
    } if (login_logo_preview == '') {
        toastr.error('Logo login sistem masih kosong...', 'Uuppss!', {"progressBar": true, "timeOut": 1500});
        $('#iGroup-login_logo .dropify-wrapper').addClass('border-2 border-danger').stop().delay(1500).queue(function () {
            $(this).removeClass('border-2 border-danger');
        });
        login_logo.focus();
        $('#btn-saveSystemInfo').html('<i class="las la-save fs-1 me-3"></i>Simpan').attr('disabled', false);
        return false;
    } if (backend_logo_preview == '') {
        toastr.error('Logo backend sistem masih kosong...', 'Uuppss!', {"progressBar": true, "timeOut": 1500});
        $('#iGroup-backend_logo .dropify-wrapper').addClass('border-2 border-danger').stop().delay(1500).queue(function () {
            $(this).removeClass('border-2 border-danger');
        });
        backend_logo.focus();
        $('#btn-saveSystemInfo').html('<i class="las la-save fs-1 me-3"></i>Simpan').attr('disabled', false);
        return false;
    } if (backend_logo_icon_preview == '') {
        toastr.error('Logo icon backend sistem masih kosong...', 'Uuppss!', {"progressBar": true, "timeOut": 1500});
        $('#iGroup-backend_logo_icon .dropify-wrapper').addClass('border-2 border-danger').stop().delay(1500).queue(function () {
            $(this).removeClass('border-2 border-danger');
        });
        backend_logo_icon.focus();
        $('#btn-saveSystemInfo').html('<i class="las la-save fs-1 me-3"></i>Simpan').attr('disabled', false);
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
            var target = document.querySelector('#cardSystemInfo'), blockUi = new KTBlockUI(target, {message: messageBlockUi});
            blockUi.block(), blockUi.destroy();
            var formData = new FormData($('#form-editSystemInfo')[0]), ajax_url= base_url+ "api/manage_systeminfo/update";
            $.ajax({
                url: ajax_url,
                headers: { 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') },
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                dataType: "JSON",
                success: function (data) {
                    $('#btn-saveSystemInfo').html('<i class="las la-save fs-1 me-3"></i>Simpan').attr('disabled', false);
                    blockUi.release(), blockUi.destroy();
                    if (data.status==true){
                        Swal.fire({title: "Success!", text: data.message, icon: "success", allowOutsideClick: false}).then(function (result) {
                            location.reload();
                        });
                    } else {
                        Swal.fire({title: "Ooops!", text: data.message, icon: "warning", allowOutsideClick: false});
                    }
                }, error: function (jqXHR, textStatus, errorThrown) {
                    $('#btn-saveSystemInfo').html('<i class="las la-save fs-1 me-3"></i>Simpan').attr('disabled', false);
                    blockUi.release(), blockUi.destroy();
                    Swal.fire({title: "Ooops!", text: "Terjadi kesalahan yang tidak diketahui, Periksa koneksi jaringan internet lalu coba kembali. Mohon hubungi pengembang jika masih mengalami masalah yang sama.", icon: "error", allowOutsideClick: false});
                }
            });
        }else{
            $('#btn-saveSystemInfo').html('<i class="las la-save fs-1 me-3"></i>Simpan').attr('disabled', false);
        }
    });
});
// Class Initialization
jQuery(document).ready(function() {
    _loadEditSystemInfo();
});