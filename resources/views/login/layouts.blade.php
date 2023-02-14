<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
	<!--begin::Head-->
	<head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <meta http-equiv="x-ua-compatible" content="ie=edge" />
        <meta charset="utf-8" />
        <meta name="csrf-token" content="{{ csrf_token() }}" />
        <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1.0, shrink-to-fit=no">
        <meta name="robots" content="noodp" />
        <meta name="description" content="{{ $data['app_desc'] }}" />
        <meta name="keywords" content="{{ $data['app_keywords'] }}" />
        <meta name="author" content="Robhi Tranzad" />
        <meta name="email" content="robhi.sanjaya@gmail.com" />
        <meta name="website" content="{{ $data['url'] }}" />
        <meta name="Version" content="{{ $data['app_version'] }}" />
        <meta name="docsearch:language" content="id">
        <meta name="docsearch:version" content="{{ $data['app_version'] }}">
        <title>{{ isset($data['title']) ? $data['title'] : 'Unknown'; }} - {{ $data['app_name'] }}</title>
        <link rel="canonical" href="{{ $data['url'] }}">
        <!-- Favicons -->
        <meta name="msapplication-TileColor" content="#FFF" />
        <meta name="theme-color" content="#FFF" />
        <link rel="apple-touch-icon" sizes="57x57" href="{{ asset('/dist/img/favicon/apple-icon-57x57.png') }}" />
        <link rel="apple-touch-icon" sizes="60x60" href="{{ asset('/dist/img/favicon/apple-icon-60x60.png') }}" />
        <link rel="apple-touch-icon" sizes="72x72" href="{{ asset('/dist/img/favicon/apple-icon-72x72.png') }}" />
        <link rel="apple-touch-icon" sizes="114x114" href="{{ asset('/dist/img/favicon/apple-icon-114x114.png') }}" />
        <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('/dist/img/favicon/apple-icon-76x76.png') }}" />
        <link rel="apple-touch-icon" sizes="120x120" href="{{ asset('/dist/img/favicon/apple-icon-120x120.png') }}" />
        <link rel="apple-touch-icon" sizes="152x152" href="{{ asset('/dist/img/favicon/apple-icon-152x152.png') }}" />
        <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('/dist/img/favicon/apple-icon-180x180.png') }}" />
        <link rel="icon" type="image/png" href="{{ asset('/dist/img/favicon/favicon-32x32.png') }}" sizes="32x32" />
        <link rel="icon" type="image/png" href="{{ asset('/dist/img/favicon/android-icon-36x36.png') }}" sizes="36x36" />
        <link rel="icon" type="image/png" href="{{ asset('/dist/img/favicon/android-icon-48x48.png') }}" sizes="48x48" />
        <link rel="icon" type="image/png" href="{{ asset('/dist/img/favicon/android-icon-72x72.png') }}" sizes="72x72" />
        <link rel="icon" type="image/png" href="{{ asset('/dist/img/favicon/android-icon-96x96.png') }}" sizes="96x96" />
        <link rel="icon" type="image/png" href="{{ asset('/dist/img/favicon/android-icon-144x144.png') }}" sizes="144x144" />
        <link rel="icon" type="image/png" href="{{ asset('/dist/img/favicon/android-icon-192x192.png') }}" sizes="192x192" />
        <link rel="icon" type="image/png" href="{{ asset('/dist/img/favicon/favicon-96x96.png') }}" sizes="96x96" />
        <link rel="icon" type="image/png" href="{{ asset('/dist/img/favicon/favicon-16x16.png') }}" sizes="16x16" />
        <meta name="msapplication-TileImage" content="{{ asset('/dist/img/favicon/ms-icon-144x144.png') }}" />
        <meta name="msapplication-square70x70logo" content="{{ asset('/dist/img/favicon/ms-icon-70x70.png') }}" />
        <meta name="msapplication-square150x150logo" content="{{ asset('/dist/img/favicon/ms-icon-150x150.png') }}" />
        <meta name="msapplication-wide310x150logo" content="{{ asset('/dist/img/favicon/ms-icon-310x150.png') }}" />
        <meta name="msapplication-square310x310logo" content="{{ asset('/dist/img/favicon/ms-icon-310x310.png') }}" />
        <link href="{{ asset('/dist/img/favicon/apple-startup-320x460.png') }}" media="(device-width: 320px) and (device-height: 480px) and (-webkit-device-pixel-ratio: 1)" rel="apple-touch-startup-image" />
        <link href="{{ asset('/dist/img/favicon/apple-startup-640x920.png') }}" media="(device-width: 320px) and (device-height: 480px) and (-webkit-device-pixel-ratio: 2)" rel="apple-touch-startup-image" />
        <link href="{{ asset('/dist/img/favicon/apple-startup-640x1096.png') }}" media="(device-width: 320px) and (device-height: 568px) and (-webkit-device-pixel-ratio: 2)" rel="apple-touch-startup-image" />
        <link href="{{ asset('/dist/img/favicon/apple-startup-748x1024.png') }}" media="(device-width: 768px) and (device-height: 1024px) and (-webkit-device-pixel-ratio: 1) and (orientation: landscape)" rel="apple-touch-startup-image" />
        <link href="{{ asset('/dist/img/favicon/apple-startup-750x1024.png') }}" media="" rel="apple-touch-startup-image" />
        <link href="{{ asset('/dist/img/favicon/apple-startup-750x1294.png') }}" media="(device-width: 375px) and (device-height: 667px) and (-webkit-device-pixel-ratio: 2)" rel="apple-touch-startup-image" />
        <link href="{{ asset('/dist/img/favicon/apple-startup-768x1004.png') }}" media="(device-width: 768px) and (device-height: 1024px) and (-webkit-device-pixel-ratio: 1) and (orientation: portrait)" rel="apple-touch-startup-image" />
        <link href="{{ asset('/dist/img/favicon/apple-startup-1182x2208.png') }}" media="(device-width: 414px) and (device-height: 736px) and (-webkit-device-pixel-ratio: 3) and (orientation: landscape)" rel="apple-touch-startup-image" />
        <link href="{{ asset('/dist/img/favicon/apple-startup-1242x2148.png') }}" media="(device-width: 414px) and (device-height: 736px) and (-webkit-device-pixel-ratio: 3) and (orientation: portrait)" rel="apple-touch-startup-image" />
        <link href="{{ asset('/dist/img/favicon/apple-startup-1496x2048.png') }}" media="(device-width: 768px) and (device-height: 1024px) and (-webkit-device-pixel-ratio: 2) and (orientation: landscape)" rel="apple-touch-startup-image" />
        <link href="{{ asset('/dist/img/favicon/apple-startup-1536x2008.png') }}" media="(device-width: 768px) and (device-height: 1024px) and (-webkit-device-pixel-ratio: 2) and (orientation: portrait)" rel="apple-touch-startup-image" />
        <link rel="manifest" href="{{ asset('/dist/img/favicon/manifest.json') }}" />

        <meta name="application-name" content="{{ $data['app_name'] }}">
        <meta name="msapplication-TileImage" content="{{ $data['thumb'] }}">
        <meta name="msapplication-TileColor" content="#6CC4A1">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
        <meta name="HandheldFriendly" content="true" />
        <!-- Twitter -->
        <meta name="twitter:widgets:csp" content="on">
        <meta name="twitter:card" content="summary">
        <meta name="twitter:url" content="{{ $data['url'] }}">
        <meta name="twitter:site" content="{{ $data['app_name'] }}">
        <meta name="twitter:creator" content="@robhitranzad">
        <meta name="twitter:title" content="{{ $data['title'] }} - {{ $data['app_name'] }}">
        <meta name="twitter:description" content="{{ $data['app_desc'] }}">
        <meta name="twitter:image" content="{{ $data['thumb'] }}">
        <!-- Facebook -->
        <meta property="og:locale" content="id_ID" />
        <meta property="og:type" content="website" />
        <meta property="og:url" content="{{ $data['url'] }}">
        <meta property="og:title" content="{{ $data['title'] }} - {{ $data['app_name'] }}">
        <meta property="og:description" content="{{ $data['app_desc'] }}">
        <meta property="og:type" content="website">
        <meta property="og:image" content="{{ $data['thumb'] }}">
        <meta property="og:image:type" content="image/png">
        <meta property="og:image:width" content="1000">
        <meta property="og:image:height" content="500">
		@include('login.partials.styles')
	</head>
	<!--end::Head-->
	<!--begin::Body-->
	<body id="kt_body" class="bg-body">
		<!--begin::Main-->
		<!--begin::Root-->
		<div class="d-flex flex-column flex-root">
            @include('login.partials.header')
			<!--begin::Authentication - Sign-in -->
			<div id="bg-login" class="d-flex flex-column flex-column-fluid bgi-position-y-bottom position-x-center bgi-no-repeat bgi-size-contain bgi-attachment-fixed" style="background: linear-gradient(0deg, rgb(21 33 26), #026529db);">
				<!--begin::Content-->
				<div class="d-flex flex-center flex-column flex-column-fluid p-5 pb-lg-10">
                    <!-- Body Content start -->
                    @yield('content')
                    <!-- Body Content end -->
				</div>
				<!--end::Content-->
			</div>
			<!--end::Authentication - Sign-in-->
            @include('login.partials.footer')
		</div>
		<!--end::Root-->
		<!--end::Main-->
        @include('login.partials.scripts')
	</body>
	<!--end::Body-->
</html>