
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
	<!--begin::Head-->
	<head>
	<title> {{ $data['subject'] }} </title>
    <meta charset="utf-8"> 
    <meta name="viewport" content="width=device-width"> 
    <meta http-equiv="X-UA-Compatible" content="IE=edge"> 
    <meta name="x-apple-disable-message-reformatting">
    <!--begin::Fonts(mandatory for all pages)-->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700" />
    <!--end::Fonts-->
	</head>
	<!--end::Head-->
	<!--begin::Body-->
	<body id="kt_body" class="app-blank">
		<!--begin::Root-->
		<div class="d-flex flex-column flex-root" id="kt_app_root">
			<!--begin::Email template-->
			<style>
                html,body {
                    padding:0;
                    margin:0;
                    font-family: Inter, Helvetica, "sans-serif";
                }
                a:hover { color: #009ef7; }
            </style>
			<div id="#kt_app_body_content" style="background-color:#D5D9E2; font-family:Arial,Helvetica,sans-serif; line-height: 1.5; min-height: 100%; font-weight: normal; font-size: 15px; color: #2F3044; margin:0; padding:1px 0;; width:100%;">
				<div style="background-color:#ffffff; padding: 45px 0 34px 0; border-radius: 24px; margin:40px auto; max-width: 600px;">
					<table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" height="auto" style="border-collapse:collapse">
						<tbody>
							<tr>
								<td align="center" valign="center" style="text-align:center; padding-bottom: 10px">
									<!--begin:Email content-->
									<div style="text-align:center; margin:0 15px 34px 15px">
										<!--begin:Logo-->
										<div style="margin-bottom: 10px">
											<a href="{{ url('/') }}" rel="noopener" target="_blank">
												<img src="{{ $data["systemInfo"]->url_frontendLogo }}" alt="Logo" style="height: 35px" />
											</a>
										</div>
										<!--end:Logo-->
										<!--begin:Media-->
										<div style="margin-bottom: 15px">
											<img src="{{ asset('/dist/img/refresh-lock.png') }}" alt="icon-notice" style="width: 50%;" />
										</div>
										<!--end:Media-->
										<!--begin:Text-->
										<div style="font-size: 14px; font-weight: 500; margin-bottom: 27px; font-family:Arial,Helvetica,sans-serif;">
											<p style="margin-bottom:9px; color:#181C32; font-size: 16px; font-weight:500">
                                                Hallo {{ $data["userInfo"]['name'] }},
                                                <br/> Sistem baru saja melakukan reset password akun anda pada website <b>{{ $data["systemInfo"]->short_name }}</b>.
                                            </p>
											<p style="margin-bottom:2px; color:#7E8299">
                                                Berikut ini informasi login dengan password baru yang bisa digunakan:
                                            </p>
											<p style="margin-bottom:2px; margin-left: 10px; color:#7E8299; font-weight: bold;">
                                                Username : {{ $data["userInfo"]['username'] }}
                                                <br />Password : <strong>{{ $data["userInfo"]['newPass'] }}</strong>
                                                <small style="color: #FF0032;">(rahasia, jangan dibagikan kepada orang lain)</small></p>
										</div>
										<!--end:Text-->
										<small style="display: block;">
                                            <strong>NOTED: Jangan lupa lakukan perubahan password setelah anda login, atau jika tidak silahkan catat/ ingat password baru ini untuk login kembali.</strong>
                                        </small>
										<!--begin:Action-->
										<a href='{{ url('auth') }}' target="_blank" style="background-color:#50cd89; border-radius:6px;display:inline-block; padding:11px 19px; color: #FFFFFF; font-size: 14px; font-weight:500; margin-top:20px;">
											Login Now
										</a>
										<!--begin:Action-->
									</div>
									<!--end:Email content-->
								</td>
							</tr>
							{{-- <tr>
								<td align="center" valign="center" style="text-align:center; padding-bottom: 20px;">
									<a href="{{ $data["systemInfo"]->organization_info->facebook_account }}" target="_blank" style="margin-right:10px; text-decoration: none;">
										<img alt="icon-facebook" src="{{ asset('/dist/img/icon-facebook.png') }}" style="width: 24px; height: 24px;" />
									</a>
									<a href="{{ $data["systemInfo"]->organization_info->twitter_account }}" target="_blank" style="margin-right:10px; text-decoration: none;">
										<img alt="icon-twitter" src="{{ asset('/dist/img/icon-twitter.png') }}" style="width: 24px; height: 24px;" />
									</a>
									<a href="{{ $data["systemInfo"]->organization_info->youtube_channel }}" target="_blank" style="margin-right:10px; text-decoration: none;">
										<img alt="icon-youtube" src="{{ asset('/dist/img/icon-youtube.png') }}" style="width: 24px; height: 24px;" />
									</a>
									<a href="{{ $data["systemInfo"]->organization_info->instagram_account }}" target="_blank">
										<img alt="icon-instagram" src="{{ asset('/dist/img/icon-instagram.png') }}" style="width: 24px; height: 24px;" />
									</a>
								</td>
							</tr> --}}
							<tr>
								<td align="center" valign="center" style="font-size: 13px; text-align:center; padding: 0 10px 10px 10px; font-weight: 500; color: #A1A5B7; font-family:Arial,Helvetica,sans-serif">
									<hr />
									<small>
                                        Pesan ini dikirimkan kepada anda sebagai notifikasi reset password user. Tidak perlu membalas pesan ini karena dikirim melalui sistem dan mungkin response anda tidak akan dibalas, Terimakasih.
                                    </small>
								</td>
							</tr>
							<tr>
								<td align="center" valign="center" style="font-size: 13px; padding:0 15px; text-align:center; font-weight: 500; color: #A1A5B7;font-family:Arial,Helvetica,sans-serif">
									{!! $data["systemInfo"]->copyright !!}
								</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
			<!--end::Email template-->
		</div>
		<!--end::Root-->
	</body>
	<!--end::Body-->
</html>
