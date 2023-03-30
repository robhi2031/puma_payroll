"use strict";
// Class Definition
//Message BlockUi
const messageBlockUi = '<div class="blockui-message bg-light text-dark"><span class="spinner-border text-primary"></span> Please wait ...</div>';
//Validate Email
const validateEmail = (email) => {
    var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(email);
}
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
}
//System INFO
const _loadSystemInfo = () => {
	$.ajax({
        url: base_url+ "api/system_info",
        type: "GET",
        dataType: "JSON",
        success: function (data) {
            let headerLogo = `
                <img alt="Logo" src="` +data.row.url_backendLogo+ `" class="h-40px app-sidebar-logo-default" />
                <img alt="Logo" src="` +data.row.url_backendLogoIcon+ `" class="h-40px app-sidebar-logo-minimize" />
            `;
            $('#kt_app_sidebar_logo a').html(headerLogo);
            let headerLogoMobile = `<img alt="Logo-mobile" src="` +data.row.url_backendLogoIcon+ `" class="h-30px" />`;
            $('#logoMobile a').html(headerLogoMobile);
            $('#footerCopyright').html(data.row.copyright);
        }, error: function (jqXHR, textStatus, errorThrown) {
            console.log('Load data is error');
        }
    });
};
//User INFO
const _loadUserInfo = () => {
	$.ajax({
        url: base_url+ "api/user_info",
        type: "GET",
        dataType: "JSON",
        success: function (data) {
            let thumbHeader;
            let userThumbHeader = `<img src="` +data.row.url_thumb+ `" alt="avatar-user" />`;
            let userSymbol = `<div class="symbol-label fs-1 fw-bold ` +data.row.symbol_color+ `">` +data.row.text_symbol+ `</div>`;
            if(data.row.thumb==null || data.row.thumb=='') {
                thumbHeader = userSymbol;
            } else {
                thumbHeader = userThumbHeader;
            }
            $('#kt_header_user_menu_toggle .avatar-header').html(thumbHeader);
            $('#nameUserHeader').html(`<div class="fw-bold d-flex align-items-center fs-5">
                ` +data.row.name+ `
            </div>
            <a href="javascript:void(0);" class="fw-semibold text-muted text-hover-primary fs-7"> ` +data.row.email+ ` </a>`);
        }, error: function (jqXHR, textStatus, errorThrown) {
            console.log('Load data is error');
        }
    });
};
// Class Initialization
jQuery(document).ready(function() {
    _loadSystemInfo(), _loadUserInfo();
});