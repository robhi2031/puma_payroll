"use strict";
//Message BlockUi
var messageBlockUi = '<div class="blockui-message"><span class="spinner-border align-middle me-3"></span> Mohon Tunggu...</div>';
//FULL SCREEN FUNCTION
var _toggleFullScreen = () => {
    if ((document.fullScreenElement && document.fullScreenElement !== null) ||
        (!document.mozFullScreen && !document.webkitIsFullScreen)) {
        if (document.documentElement.requestFullScreen) {
            document.documentElement.requestFullScreen();
        } else if (document.documentElement.mozRequestFullScreen) {
            document.documentElement.mozRequestFullScreen();
        } else if (document.documentElement.webkitRequestFullScreen) {
            document.documentElement.webkitRequestFullScreen(Element.ALLOW_KEYBOARD_INPUT);
        }

        $('#btn-fullScreenPages').attr('data-bs-original-title', 'Tutup mode fullscreen').html('<i class="bi bi-fullscreen-exit fs-2"></i>');
    } else {
        if (document.cancelFullScreen) {
            document.cancelFullScreen();
        } else if (document.mozCancelFullScreen) {
            document.mozCancelFullScreen();
        } else if (document.webkitCancelFullScreen) {
            document.webkitCancelFullScreen();
        }
        $('#btn-fullScreenPages').attr('data-bs-original-title', 'Gunakan mode fullscreen').html('<i class="bi bi-fullscreen fs-2"></i>');
    }
}
// SITE INFO
var loadSiteInfo = () => {
    $.ajax({
        url: base_url + "common/ajax_get_siteinfo",
        type: "GET",
        dataType: "JSON",
        success: function (data) {
            const imgLogo = `<img alt="logo" src="` + data.url_headlogo_admin_site + `" class="logo w-100" id="kt_aside_logo_img" />`;
            $('#kt_aside_logo a').removeClass('placeholder-glow').html(imgLogo);
            var theme_active = $.cookie('backend_theme');
            if (theme_active == 'DARK') {
                const imgLogoDark = `<img alt="logo" src="` + data.url_headlogo_admin_site + `" class="logo h-30px" />`;
                $('#kt_aside_mobile_logo').removeClass('placeholder-glow').html(imgLogo);
            } else {
                const imgLogoLight = `<img alt="logo" src="` + data.url_headlogo_admin_site + `" class="logo h-30px" />`;
                $('#kt_aside_mobile_logo').removeClass('placeholder-glow').html(imgLogoLight);
            }
            $('#copyRight').removeClass('w-100').html(data.copyright_site);
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log('Load data is error');
        }
    });
}
//Upload Image to Local Server with Summernote JS
var _uploadFile_editor = function(image, idCustom) {
	var data = new FormData();
	data.append("image", image);
	$.ajax({
		data: data,
		type: "POST",
		url: base_url+ "common/ajax_uploadimg_editor",
		cache: false,
        contentType: false,
        processData: false,
        dataType: "JSON",
		success: function(data){
			if(data.status){
				//console.log(url);
				if(idCustom){
					$(idCustom).summernote("insertImage", data.url_img);
				}else{
					$('.summernote').summernote("insertImage", data.url_img);
				}
				//var image = $('<img>').attr('src', url);
				//$('#summernote').summernote("insertNode", url);
			}else{
				Swal.fire({
					title: "Ooops!",
					html: "Gagal upload file gambar, Terjadi error: <code>" +data.error_info+ "</code> Coba lagi dengan file gambar yang lain atau silahkan hubungi pengembang!",
					icon: "warning", allowOutsideClick: false
				});
			}
		}, error: function (jqXHR, textStatus, errorThrown) {
			console.log('Error upload images to text editor');
			toastr.error('Gambar yang diupload tidak sesuai, gunakan gambar yang lain dengan size Maksimal 1Mb!', 'Uuppss!', {"progressBar": true, "timeOut": 1500});
		}
	});
};
// USER INFO
var loadUserInfo = () => {
    $.ajax({
        url: base_url + "common/ajax_get_userinfo",
        type: "GET",
        dataType: "JSON",
        success: function (data) {
            let rows = data.rows;
            $('#breadcrumb-levelName').removeClass('w-150px').html(`<li class="breadcrumb-item text-muted">` + rows.level_name + `</li>`);
            //User Account
            var thumbUser = `<img src="` + rows.urlUserThumb + `" class="bg-secondary" alt="` + rows.user_thumb + `" />`;
            if (!rows.user_thumb) {
                thumbUser = `<span class="symbol-label fs-2x fw-bolder ` + rows.randColorTextSymbol + ` bg-secondary" alt="user-thumb">` + rows.symbolThumb + `</span>`;
            }
            $('#kt_header_user_menu_toggle').removeClass('w-30px'), $('#symbolThumbHeader').removeClass('w-30px').html(thumbUser + `<div class="bg-success position-absolute border border-4 border-white h-15px w-15px rounded-circle translate-middle start-100 top-100 ms-n1 mt-n1"></div>`);
            $('#userInfoHeaderAccount').html(`<div class="menu-content d-flex align-items-center px-3">
                <!--begin::Avatar-->
                <div class="symbol symbol-50px symbol-md-60px me-5">
                    ` + thumbUser + `
                    <div class="bg-success position-absolute border border-4 border-white h-15px w-15px rounded-circle translate-middle start-100 top-100 ms-n2 mt-n2"></div>
                </div>
                <!--end::Avatar-->
                <!--begin::Username-->
                <div class="d-flex flex-column">
                    <div class="fw-bolder d-flex align-items-center fs-5">` + rows.nama_lengkap + `</div>
                    <a href="javascript:void(0);" class="fw-bold text-muted text-hover-success fs-7" data-bs-toggle="tooltip" title="` + rows.email + `">` + rows.username + `</a>
                </div>
                <!--end::Username-->
            </div>`);
        }, complete: function (data) {
            $('[data-bs-toggle="tooltip"]').tooltip({ trigger: 'hover' }).on('click', function () { $(this).tooltip('hide'); });
        }, error: function (jqXHR, textStatus, errorThrown) {
            console.log('Load data is error');
        }
    });
}
const countSignUpWaiting = () => {
    $.ajax({
        url: base_url+ "app_admin/ajax_countwaitingsignup",
        type: "GET",
        dataType: "JSON",
        success: function (data) {
            if(data>0) {
                $('#mi-verifikasi_user .badge-exclusive').show().attr('data-bs-original-title', data+ ' data menunggu verifikasi').text(data);
            } else {
                $('#mi-verifikasi_user .badge-exclusive').hide().attr('data-bs-original-title', '').text('');
            }
        }, error: function (jqXHR, textStatus, errorThrown) {
            console.log('Load data is error');
        }
    });
}
//Load Flatpicker
$(".date-flatpickr").flatpickr({
    enableTime: false,
    dateFormat: "d/m/Y"
});
//Load File Dropify
var _loadDropifyFile = (url_file, paramsId) => {
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
//Validate Email
function validateEmail(email) {
    var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(email);
}
// Class Initialization
jQuery(document).ready(function() {
    loadSiteInfo(), loadUserInfo(), countSignUpWaiting();
	// Handle Change Mode Theme button
	$('#menu-changeModeTheme .mode-theme').on('click', function (e) {
		e.preventDefault();
		const set_theme = $(this).attr('data-theme');
		$.cookie('backend_theme', set_theme, { expires: 1, path: '/' });
		location.reload(true);
	});
	//Logout Otomatis After 30 Menit
	var inactivityTimeout = false;
	resetTimeout();
	function onUserInactivity() {
		Swal.fire({title: "Warning!",
			text: "Tidak ada aktivitas selama 30 Menit pada sistem. Logout otomatis 15 detik dari sekarang!",
			icon: "warning", timer: 15000, showCancelButton: false, showConfirmButton: false, allowOutsideClick: false
		}).then(function (result) {
			window.location = base_url+ "logout";
		});
	}
	function resetTimeout() {
		clearTimeout(inactivityTimeout);
		inactivityTimeout = setTimeout(onUserInactivity, 10000 * 180); //30 Menit
	}
	window.onmousemove = resetTimeout;
	//Logout Otomatis After 30 Menit End
	//begin::Spotify
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
	//end::Spotify
});