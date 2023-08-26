"use strict";
//Class Definition
var table;
//Load Datatables Log Aktivitas User
const _loadDtLogs = () => {
    table = $('#dt-logs').DataTable({
        searchDelay: 300,
        processing: true,
        serverSide: true,
        ajax: {
            url: base_url+ 'api/users_activity/show',
            headers: { 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') },
            type: 'GET',
            data: function ( data ) {
                data.tgl_start= $('#filterDt-startDate').val();
                data.tgl_end= $('#filterDt-endDate').val();
            }
        },
        destroy: true,
        draw: true,
        deferRender: true,
        responsive: false,
        autoWidth: false,
        LengthChange: true,
        paginate: true,
        pageResize: true,
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', width: "5%", className: "align-top text-center border px-2", searchable: false },
            { data: 'user', name: 'user', width: "20%", className: "align-top border px-2" },
            { data: 'description', name: 'description', width: "25%", className: "align-top border px-2" },
            { data: 'timestamp', name: 'timestamp', width: "20%", className: "align-top border px-2", searchable: false },
            { data: 'agent', name: 'agent', width: "30%", className: "align-top border px-2" },
        ],
        oLanguage: {
            sEmptyTable: "Tidak ada Data yang dapat ditampilkan..",
            sInfo: "Menampilkan _START_ s/d _END_ dari _TOTAL_",
            sInfoEmpty: "Menampilkan 0 - 0 dari 0 entri.",
            sInfoFiltered: "",
            sProcessing: `<div class="d-flex justify-content-center align-items-center"><span class="spinner-border align-middle me-3"></span> Mohon Tunggu...</div>`,
            sZeroRecords: "Tidak ada Data yang dapat ditampilkan..",
            sLengthMenu: `<select class="mb-2 show-tick form-select-solid" data-width="fit" data-style="btn-sm btn-secondary" data-container="body">
                <option value="10">10</option>
                <option value="20">20</option>
                <option value="30">30</option>
                <option value="40">40</option>
                <option value="50">50</option>
                <option value="-1">Semua</option>
            </select>`,
            oPaginate: {
                sPrevious: "Sebelumnya",
                sNext: "Selanjutnya",
            },
        },
        //"dom": "<'row'<'col-sm-6 d-flex align-items-center justify-conten-start'l><'col-sm-6 d-flex align-items-center justify-content-end'f>><'table-responsive'tr><'row'<'col-sm-12 col-md-5 d-flex align-items-center justify-content-center justify-content-md-start'i><'col-sm-12 col-md-7 d-flex align-items-center justify-content-center justify-content-md-end'p>>",
        fnDrawCallback: function (settings, display) {
            $('[data-bs-toggle="tooltip"]').tooltip("dispose"), $(".tooltip").hide();
            //Search Table
            $("#search-dtLogs").on("keyup", function () {
                table.search(this.value).draw();
                if ($(this).val().length > 0) {
                    $("#clear-searchDtLogs").show();
                } else {
                    $("#clear-searchDtLogs").hide();
                }
            });
            //Clear Search Table
            $("#clear-searchDtLogs").on("click", function () {
                $("#search-dtLogs").val(""),
                table.search("").draw(),
                $("#clear-searchDtLogs").hide();
            });
            //Custom Table
            $("#dt-logs_length select").selectpicker(),
            $('[data-bs-toggle="tooltip"]').tooltip({ 
                trigger: "hover"
            }).on("click", function () {
                $(this).tooltip("hide");
            });
        },
    });
    $("#dt-logs").css("width", "100%"),
    $("#search-dtLogs").val(""),
    $("#clear-searchDtLogs").hide();
}
const _cleanLogs = () => {
    Swal.fire({
        title: "",
        text: "Bersihkan semua log aktivitas user ?",
        icon: "question",
        showDenyButton: true,
        showCancelButton: true,
        allowOutsideClick: false,
        confirmButtonText: '<i class="fas fa-trash-alt text-white me-2"></i>Semua',
        denyButtonText: '<i class="bi bi-calendar2-week text-white me-2"></i>Filter by Tanggal',
        cancelButtonText: 'Batal'
    }).then(result => {
        if (result.isConfirmed) {
            let target = document.querySelector('#card-dtLogs'), blockUi = new KTBlockUI(target, {message: messageBlockUi});
            blockUi.block(), blockUi.destroy();
            // Load Ajax
            $.ajax({
                url : base_url+ "api/users_activity/delete",
                headers: { 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') },
                type: "POST",
                dataType: "JSON",
                data: {
                    'startDate': '', 'endDate': '',
                },
                success: function(data) {
                    blockUi.release(), blockUi.destroy();
                    Swal.fire({title: "Success!", text: data.message, icon: "success", allowOutsideClick: false}).then(function(result){
                        _loadDtLogs();
                    });
                }, error: function (jqXHR, textStatus, errorThrown){
                    blockUi.release(), blockUi.destroy();
                    Swal.fire({title: "Ooops!", text: "Terjadi kesalahan yang tidak diketahui, Periksa koneksi jaringan internet lalu coba kembali. Mohon hubungi pengembang jika masih mengalami masalah yang sama.", icon: "error", allowOutsideClick: false}).then(function(result){
                        console.log("Update data is error!");
                        _loadDtLogs();
                    });
                }
            });
        } else if (result.isDenied) {
            Swal.fire({
                title: 'Bersihkan Log Aktivitas User',
                html: `<form class="form" id="form-filterMdlLog">
                    <label class="col-form-label fw-bold pt-0 fs-6">Filter Tgl. Aktivitas</label>
                    <div class="input-group">
                        <input type="text" class="form-control dateAlert-flatpickr" name="filterDtAlert-startDate" id="filterDtAlert-startDate" maxlength="10" placeholder="dd/mm/YYYY" readonly />
                        <span class="input-group-text">s/d</span>
                        <input type="text" class="form-control dateAlert-flatpickr" name="filterDtAlert-endDate" id="filterDtAlert-endDate" maxlength="10" placeholder="dd/mm/YYYY" readonly />
                    </div>
                </form>`,
                confirmButtonText: '<i class="las la-quidditch text-white fs-3 me-2"></i> Bersihkan',
                position: 'top',
                focusConfirm: false,
                allowOutsideClick: false,
                showCancelButton: true,
                cancelButtonText: 'Batal',
                willOpen: () => {
                    //Load Flatpicker
                    $(".dateAlert-flatpickr").flatpickr({
                        enableTime: false,
                        dateFormat: "d/m/Y"
                    }), Swal.getConfirmButton().focus();
                },
                preConfirm: () => {
                    const startDate = Swal.getPopup().querySelector('#filterDtAlert-startDate').value
                    const endDate = Swal.getPopup().querySelector('#filterDtAlert-endDate').value
                    if (!startDate || !endDate) {
                        Swal.showValidationMessage(`Tgl. awal dan Tgl. akhir aktivitas user masih kosong!`)
                    } return {
                        startDate, endDate
                    }
                }
            }).then((result) => {
                let awalVal = `${result.value.startDate}`;
                let akhirVal = `${result.value.endDate}`;
                let target = document.querySelector('#card-dtLogs'), blockUi = new KTBlockUI(target, {message: messageBlockUi});
                blockUi.block(), blockUi.destroy();
                // Load Ajax
                $.ajax({
                    url : base_url+ "api/users_activity/delete",
                    headers: { 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') },
                    type: "POST",
                    dataType: "JSON",
                    data: {
                        'startDate': awalVal, 'endDate': akhirVal,
                    },
                    success: function(data){
                        blockUi.release(), blockUi.destroy();
                        Swal.fire({title: "Success!", html: data.message, icon: "success", allowOutsideClick: false}).then(function(result){
                            _loadDtLogs();
                        });
                    }, error: function (jqXHR, textStatus, errorThrown){
                        blockUi.release(), blockUi.destroy();
                        Swal.fire({title: "Ooops!", text: "Terjadi kesalahan yang tidak diketahui, mohon hubungi pengembang...", icon: "error", allowOutsideClick: false}).then(function(result){
                            console.log("Clean data is error!");
                            _loadDtLogs();
                        });
                    }
                });
            });
        }
    });
}
//Class Initialization
jQuery(document).ready(function() {
    _loadDtLogs();
    //FLATPICKER OPTIONS
    var startDate = $('#filterDt-startDate').val(), endDate = $('#filterDt-endDate').val();
    $("#filterDt-startDate").flatpickr({
        defaultDate: startDate,
        dateFormat: "d/m/Y"
    });
    $("#filterDt-endDate").flatpickr({
        defaultDate: endDate,
        dateFormat: "d/m/Y"
    });
    //IF CHANGE DATE FILTER
    $('.date-flatpickr').change(function () {
        _loadDtLogs();
    });
});