<!--begin::Javascript-->
<script>
    var hostUrl = "{{ asset('/dist/') }}";
</script>
<!--begin::Global Javascript Bundle(mandatory for all pages)-->
<script src="{{ asset('/dist/plugins/global/plugins.bundle.v817.js') }}"></script>
<script src="{{ asset('/dist/js/scripts.bundle.v817.js') }}"></script>
<!--end::Global Javascript Bundle-->
<!--begin::Vendors Javascript(used for this page only)-->
@foreach ($data['js'] as $dt)
    <script src="{{ asset($dt) }}"></script>
@endforeach
<!--end::Vendors Javascript-->
<!--end::Javascript-->