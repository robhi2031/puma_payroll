"use strict";
// Class Definition
var messageBlockUi = '<div class="blockui-message"><span class="spinner-border text-primary"></span> Mohon Tunggu...</div>';
// FORM CLASS LOGIN
var KTLogin = function() {
	//Block & Unblock on Div
	var _handleSignInForm = function() {
		$('#username').focus();
		//Handle Enter Submit
		$("#username").keyup(function(event) {
			if (event.keyCode == 13 || event.key === 'Enter') {
				$("#btn-login1").click();
			}
		});
		// Handle submit button
		$('#btn-login1').on('click', function (e) {
			e.preventDefault();
			$('#btn-login1').html('<span class="spinner-border spinner-border-sm align-middle me-3"></span> Mohon Tunggu...').attr('disabled', true);
			var username = $('#username');
			if (username.val() == '') {
				toastr.error('Username atau Email masih kosong...', 'Uuppss!', {"progressBar": true, "timeOut": 1500});
				username.focus();
				$('#btn-login1').html('<i class="bi bi-box-arrow-in-right fs-4"></i> Berikutnya').attr('disabled', false);
				return false;
			}

			var target = document.querySelector('#kt_sign_in'), blockUi = new KTBlockUI(target, {message: messageBlockUi});
			blockUi.block(), blockUi.destroy();
			var formData = new FormData($('#kt_sign_in_form')[0]);
			$.ajax({
				url: base_url+ "api/auth/first_login",
				headers: { 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') },
				type: "POST",
				data: formData,
				contentType: false,
				processData: false,
				dataType: "JSON",
				success: function (data) {
					$('#btn-login1').html('<i class="bi bi-box-arrow-in-right fs-4"></i> Berikutnya').attr('disabled', false);
					blockUi.release(), blockUi.destroy();
					if (data.status==true){
						var tUserInfo=`<!--begin::Title-->
						<h4 class="text-dark  fw-500 mb-2">` +data.row.name+ `</h4>
						<!--end::Title-->
						<div class="btn-group">
							<button class="btn btn-secondary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
								<!--begin::Svg Icon | path: assets/media/icons/duotune/communication/com006.svg-->
								<span class="svg-icon svg-icon-2x"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
								<path opacity="0.3" d="M22 12C22 17.5 17.5 22 12 22C6.5 22 2 17.5 2 12C2 6.5 6.5 2 12 2C17.5 2 22 6.5 22 12ZM12 7C10.3 7 9 8.3 9 10C9 11.7 10.3 13 12 13C13.7 13 15 11.7 15 10C15 8.3 13.7 7 12 7Z" fill="black"/>
								<path d="M12 22C14.6 22 17 21 18.7 19.4C17.9 16.9 15.2 15 12 15C8.8 15 6.09999 16.9 5.29999 19.4C6.99999 21 9.4 22 12 22Z" fill="black"/>
								</svg></span>
								<!--end::Svg Icon-->` +data.row.email+ `
							</button>
							<ul class="dropdown-menu">
								<li><a class="dropdown-item" href="` +base_url+ `logout"><i class="bi bi-person-x me-2 text-dark fs-5"></i> Gunakan akun lain</a></li>
							</ul>
						</div>`;
						$('[name="hideMail"]').val(data.row.email), $('#hT-login2').html(tUserInfo), $('#fBody-login1').hide(), $('#fBody-login2').addClass('loginAnimated-fadeInRight').show(), $('#password').focus();
					}else{
						Swal.fire({title: "Ooops!", text: data.message, icon: "error", allowOutsideClick: false}).then(function (result) {
							location.reload(true);
						});
					}
				}, error: function (jqXHR, textStatus, errorThrown) {
					$('#btn-login1').html('<i class="bi bi-box-arrow-in-right fs-4"></i> Berikutnya').attr('disabled', false);
					blockUi.release(), blockUi.destroy();
					Swal.fire({title: "Ooops!", text: "Terjadi kesalahan yang tidak diketahui, mohon hubungi pengembang!", icon: "error", allowOutsideClick: false}).then(function (result) {
						location.reload(true);
					});
				}
			});
		});
	}

	var _handleSignIn2Form = function() {
		/* Show Hide Password */
		$('#showPass_checkbox').change(function (e) {
			e.preventDefault();
			if ($('#showPass_checkbox').is(":checked")){
				$('#password').attr('type', 'text');
			}else{
				$('#password').attr('type', 'password');
			}
		});
		//Handle Enter Submit
		$("#password").keyup(function(event) {
			if (event.keyCode == 13 || event.key === 'Enter') {
				$("#btn-login2").click();
			}
		});
		// Handle submit button
        $('#btn-login2').on('click', function (e) {
			e.preventDefault();
			$('#btn-login2').html('<span class="spinner-border spinner-border-sm align-middle me-3"></span> Mohon Tunggu...').attr('disabled', true);
			var password = $('#password');
			if (password.val() == '') {
				toastr.error('Password masih kosong...', 'Uuppss!', {"progressBar": true, "timeOut": 1500});
				password.focus();
				$('#btn-login2').html('<i class="bi bi-box-arrow-in-right fs-4"></i> Login').attr('disabled', false);
				return false;
			}

			var target = document.querySelector('#kt_sign_in'), blockUi = new KTBlockUI(target, {message: messageBlockUi});
			blockUi.block(), blockUi.destroy();
			var formData = new FormData($('#kt_sign_in_form')[0]);
			$.ajax({
				url: base_url+ "api/auth/second_login",
				headers: { 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') },
				type: "POST",
				data: formData,
				contentType: false,
				processData: false,
				dataType: "JSON",
				success: function (data) {
					$('#btn-login2').html('<i class="bi bi-box-arrow-in-right fs-4"></i> Login').attr('disabled', false);
					blockUi.release(), blockUi.destroy();
					if (data.status==true){
						Swal.fire({
							title: "Success!",
							text: "Login berhasil, sistem akan mengarahkan anda ke halaman dashboard dalam beberapa detik...",
							icon: "success",
							timer: 3000,
							timerProgressBar: true,
							showConfirmButton: false,
							allowOutsideClick: false
						}).then(function (result) {
							$('#kt_sign_in').hide(), window.location = base_url;
						});
					}else{
						Swal.fire({title: "Ooops!", text: data.message, icon: "error", allowOutsideClick: false}).then(function (result) {
							if(data.row.error_code=='PASSWORD_NOT_VALID') {
								password.val('').focus();
							}else{
								location.reload(true);
							}
						});
					}
				},
				error: function (jqXHR, textStatus, errorThrown) {
					$('#btn-login2').html('<i class="bi bi-box-arrow-in-right fs-4"></i> Login').attr('disabled', false);
					blockUi.release(), blockUi.destroy();
					Swal.fire({title: "Ooops!", text: "Terjadi kesalahan yang tidak diketahui, mohon hubungi pengembang!", icon: "error", allowOutsideClick: false}).then(function (result) {
						location.reload(true);
					});
				}
			});
		});
		/*/ Handle forgot button
		$('#kt_login_forgot').on('click', function (e) {
			e.preventDefault();
			//_showForm('forgot');
			Swal.fire({
				title: "Warning!",
				html: 'Mohon maaf fitur ini sedang tahap pengembangan. Agar dapat login ke sistem, silahkan lakukan konfirmasi akun user ke pihak Admin PIC Aplikasi.',
				icon: "warning",
				allowOutsideClick: false
			})
		});*/
	}
	/*var _handleForgotForm = function(e) {
        // Handle submit button
        $('#kt_login_forgot_submit').on('click', function (e) {
            e.preventDefault();
        });
        // Handle cancel button
        $('#kt_login_forgot_cancel').on('click', function (e) {
            e.preventDefault();
            _showForm('signin2');
        });
    }*/
    // Public Functions
    return {
        // public functions
        init: function() {
            _handleSignInForm();
            _handleSignIn2Form();
            //_handleForgotForm();
        }
    };
}();
// System INFO
var loadSystemInfo = function() {
	// Public Functions
	return {
		// public functions
		init: function() {
			$.ajax({
				url: base_url+ "api/system_info",
				type: "GET",
				dataType: "JSON",
				success: function (data) {
					$('#bg-login').attr('style', 'background: linear-gradient(0deg, rgb(21 33 26), #026529db), url(' +data.row.url_loginBg+ '); background-size: cover;');
					$('#hLogo-login').html(`<a href="` +base_url+ `app_login" title="LOGIN - ` +data.row.short_name+ `">
						<img src="` +data.row.url_loginLogo+ `" class="mb-5" height="52" alt="` +data.row.login_logo+ `">
					</a>`);
					$('#hT-login1').html(`<!--begin::Title-->
					<h1 class="text-dark mb-2">Login</h1>
					<!--end::Title-->
					<!--begin::Sub Title-->
					<div class="text-gray-400 fw-bold fs-6">E-PAYROLL</div>
					<!--end::Sub Title-->`);
					$('#kt_footer .copyRight').html(data.row.copyright);
				},
				error: function (jqXHR, textStatus, errorThrown) {
					console.log('Load data is error');
				}
			});
		}
	};
}();
// Class Initialization
jQuery(document).ready(function() {
    loadSystemInfo.init(), KTLogin.init();
});