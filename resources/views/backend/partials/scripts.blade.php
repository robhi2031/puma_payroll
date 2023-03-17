<!--begin::Javascript-->
<script>
    var hostUrl = "{{ asset('/dist/') }}";
</script>
<!--begin::Global Javascript Bundle(mandatory for all pages)-->
<script src="{{ asset('/dist/plugins/global/plugins.bundle.v817.js') }}"></script>
<script src="{{ asset('/dist/js/scripts.bundle.v817.js') }}"></script>
<!--end::Global Javascript Bundle-->
<!--begin::Vendors Javascript(used for this page only)-->

<!--end::Vendors Javascript-->
<!--begin::Custom Javascript(used for this page only)-->
<script type="text/javascript" src="{{ asset('/dist/js/backend_app.init.js') }}"></script>
@if(Route::is('dashboard'))
    <script type="text/javascript" src="{{ asset('/scripts/backend/main.init.js') }}"></script>
@endif
@if(Route::is('user_profile'))
    <script type="text/javascript" src="{{ asset('/scripts/backend/user_profile.init.js') }}"></script>
@endif
<!--end::Custom Javascript-->
<!--end::Javascript-->