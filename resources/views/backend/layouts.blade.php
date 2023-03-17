<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <meta http-equiv="x-ua-compatible" content="ie=edge" />
        <meta charset="utf-8" />
        <meta name="csrf-token" content="{{ csrf_token() }}" />
        <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1.0, shrink-to-fit=no">
        <meta name="robots" content="noodp" />
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
        <meta name="msapplication-TileColor" content="#6CC4A1">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
        <meta name="HandheldFriendly" content="true" />
        @include('backend.partials.styles')
    </head>
    <body id="kt_app_body" data-kt-app-page-loading-enabled="true" data-kt-app-page-loading="on"
        data-kt-app-layout="dark-sidebar" data-kt-app-header-fixed="true" data-kt-app-header-fixed-mobile="true"
        data-kt-app-sidebar-enabled="true" data-kt-app-sidebar-fixed="true" data-kt-app-sidebar-hoverable="true"
        data-kt-app-sidebar-push-header="true" data-kt-app-sidebar-push-toolbar="true"
        data-kt-app-sidebar-push-footer="true" class="app-default">
        <!--begin::Theme mode setup on page load-->
        <script>
            var defaultThemeMode = "light";
            var themeMode;
            if (document.documentElement) {
                if (document.documentElement.hasAttribute("data-bs-theme-mode")) {
                    themeMode = document.documentElement.getAttribute("data-bs-theme-mode");
                } else {
                    if (localStorage.getItem("data-bs-theme") !== null) {
                        themeMode = localStorage.getItem("data-bs-theme");
                    } else {
                        themeMode = defaultThemeMode;
                    }
                }
                if (themeMode === "system") {
                    themeMode = window.matchMedia("(prefers-color-scheme: dark)").matches ? "dark" : "light";
                }
                document.documentElement.setAttribute("data-bs-theme", themeMode);
            }
        </script>
        <!--end::Theme mode setup on page load-->
        <!--begin::loader-->
        <div class="app-page-loader">
            <span class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </span>
        </div>
        <!--end::Loader-->
        <!--begin::App-->
        <div class="d-flex flex-column flex-root app-root" id="kt_app_root">
            <!--begin::Page-->
            <div class="app-page  flex-column flex-column-fluid " id="kt_app_page">
                @include('backend.partials.header')
                <!--begin::Wrapper-->
                <div class="app-wrapper  flex-column flex-row-fluid " id="kt_app_wrapper">
                    @include('backend.partials.sidebar')
                    <!--begin::Main-->
                    <div class="app-main flex-column flex-row-fluid" id="kt_app_main">
                        <!--begin::Content wrapper-->
                        <div class="d-flex flex-column flex-column-fluid">
                            <!--begin::Content-->
                            <div id="kt_app_content" class="app-content  flex-column-fluid ">
                                <!--begin::Content container-->
                                <div id="kt_app_content_container" class="app-container container-xxl ">
                                    <!-- Body Content start -->
                                    @yield('content')
                                    <!-- Body Content end -->
                                </div>
                                <!--end::Content container-->
                            </div>
                            <!--end::Content-->
                        </div>
                        <!--end::Content wrapper-->
                        @include('backend.partials.footer')
                    </div>
                    <!--end:::Main-->
                </div>
                <!--end::Wrapper-->
            </div>
            <!--end::Page-->
        </div>
        <!--end::App-->
        @include('backend.partials.scripts')
    </body>
</html>