"use strict";
// Class Definition
//Message BlockUi
const messageBlockUi = '<div class="blockui-message bg-light text-dark"><span class="spinner-border text-primary"></span> Please wait ...</div>';
//Validate Email
const validateEmail = (email) => {
    let re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(email);
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
//Upload Image to Local Server with Summernote JS
var _uploadFile_editor = function(image, idCustom) {
	var data = new FormData();
	data.append("image", image);
	$.ajax({
		url: base_url+ "api/ajax_upload_imgeditor",
        headers: { 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') },
		data: data,
		type: "POST",
		cache: false,
        contentType: false,
        processData: false,
        dataType: "JSON",
		success: function(data){
			if(data.status){
				//console.log(url);
				if(idCustom){
					$(idCustom).summernote("insertImage", data.row.url_img);
				}else{
					$('.summernote').summernote("insertImage", data.row.url_img);
				}
				//var image = $('<img>').attr('src', url);
				//$('#summernote').summernote("insertNode", url);
			}else{
				Swal.fire({
					title: "Ooops!",
					html: data.message,
					icon: "warning", allowOutsideClick: false
				});
			}
		}, error: function (jqXHR, textStatus, errorThrown, data) {
			console.log('Error upload images to text editor');
			toastr.error(errorThrown+ ', <br />' +jqXHR.responseJSON.errors.image[0], 'Uuppss!', {"progressBar": true, "timeOut": 1500});
		}
	});
};
// Class Initialization
jQuery(document).ready(function() {
    _loadSystemInfo(), _loadUserInfo();
});