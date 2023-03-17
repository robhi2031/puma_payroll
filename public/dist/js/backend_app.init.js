"use strict";
// Class Definition
//Block & Unblock on Div
const _blockUI = (targetVal, isBlocked) => {
    let target = document.querySelector(targetVal);
    let blockUI = new KTBlockUI(target, {
        message: '<div class="blockui-message bg-light text-dark"><span class="spinner-border text-primary"></span> Mohon Tunggu...</div>',
    });
    if (isBlocked==1) {
        blockUI.block(), blockUI.destroy();
    } else {
        blockUI.release(), blockUI.destroy();
    }
}
//Validate Email
const validateEmail = (email) => {
    var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
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
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log('Load data is error');
        }
    });
};
//System INFO
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
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log('Load data is error');
        }
    });
};
// Class Initialization
jQuery(document).ready(function() {
    _loadSystemInfo(), _loadUserInfo();
});