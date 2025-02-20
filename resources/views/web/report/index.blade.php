@extends('web.layouts.master')
@section('content')

    <div class="card-border-shape mt-16">
        <div class="row">
            <div class="col-lg-3 col-md-6 col-12 mb-3">
                <div class="card-home">
                    <h2 class="text-primary fw-700">1,512</h2>
                        <div class="d-flex justify-content-between">
                            <p class="fs-14 text-secondary">عدد التقارير</p>
                            <div class="status-true">
                                <img class="h-16" src="{{'web/image/arrow-up-right.png'}}" alt="no-icon">
                                <p>34.1%</p>
                            </div>
                        </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-12 mb-3">
                <div class="card-home">
                    <h2 class="text-brown fw-700">637</h2>
                        <div class="d-flex justify-content-between">
                            <p class="fs-14 text-secondary">عدد التقارير الجديدة</p>
                            <div class="status-true">
                                <img class="h-16" src="{{'web/image/arrow-up-right.png'}}" alt="no-icon">
                                <p>4.1%</p>
                            </div>
                        </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-12 mb-3">
                <div class="card-home">
                    <h2 class="text-red fw-700">1,456</h2>
                        <div class="d-flex justify-content-between">
                            <p class="fs-14 text-secondary">عدد التقارير المرفوضة</p>
                            <div class="status-false">
                                <img class="h-16" src="{{'web/image/arrow-down-right.png'}}" alt="no-icon">
                                <p>4.1%</p>
                            </div>
                        </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-12 mb-3">
                <div class="">
                    <h2 class="text-green fw-700">6,243</h2>
                        <div class="d-flex justify-content-between">
                            <p class="fs-14 text-secondary">عدد التقارير المعتمدة</p>
                            <div class="status-normal">
                                <img class="h-16" src="{{'web/image/arrow-right.png'}}" alt="no-icon">
                                <p>0.0%</p>
                            </div>
                        </div>
                </div>
            </div>
        </div>
    </div>
    <div class="shape"></div>

    <div class="card-border mt-16">
        <div class="d-flex justify-content-between">
            <h5 class="text-primary">قائمة التقارير</h5>
        </div>
        <div>
            <div class="tabs">
                <div class="tabs-list d-flex mt-4">
                    <div class="show" data-content=".content-one">قائمة تقارير المخالفات</div>
                    <div data-content=".content-two">قائمة تقارير المشرفين (العامه)</div>
                </div>
                <hr class="hr-card"/>
                <div class="content-list">
                    <div class="content-one">
                        <div class="d-flex justify-content-end">
                            <button type="button" class="btn-filter">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="25" viewBox="0 0 24 25"
                                     fill="none">
                                    <path
                                        d="M19.25 5.41992H4.75L9.31174 11.1221C9.59544 11.4767 9.75 11.9173 9.75 12.3715V18.9199C9.75 19.4722 10.1977 19.9199 10.75 19.9199H13.25C13.8023 19.9199 14.25 19.4722 14.25 18.9199V12.3715C14.25 11.9173 14.4046 11.4767 14.6883 11.1221L19.25 5.41992Z"
                                        stroke="#857854" stroke-width="1.6" stroke-linecap="round"
                                        stroke-linejoin="round"/>
                                </svg>
                            </button>
                        </div>
                        <div class="scroll">
                            <table id="example" class="data-table violation_report_table" style="width:100%">
                                <thead>
                                <tr>
                                    <td>اسم التقرير</td>
                                    <td>مرسل التقرير</td>
                                    <td>الدور</td>
{{--                                    <td>نوع التقرير</td>--}}
                                    <td>حالة التقرير</td>
                                    <td>تاريخ الارسال</td>
                                    <td></td>
                                </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                    <div class="content-two">
                        <div class="d-flex justify-content-end mb-3">
                            <button type="button" class="btn-filter">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="25" viewBox="0 0 24 25"
                                     fill="none">
                                    <path
                                        d="M19.25 5.41992H4.75L9.31174 11.1221C9.59544 11.4767 9.75 11.9173 9.75 12.3715V18.9199C9.75 19.4722 10.1977 19.9199 10.75 19.9199H13.25C13.8023 19.9199 14.25 19.4722 14.25 18.9199V12.3715C14.25 11.9173 14.4046 11.4767 14.6883 11.1221L19.25 5.41992Z"
                                        stroke="#857854" stroke-width="1.6" stroke-linecap="round"
                                        stroke-linejoin="round"/>
                                </svg>
                            </button>
                        </div>
                        <div class="scroll">
                            <table id="example1" class="data-table general_report_table" style="width:100%">
                                <thead>
                                <tr>
                                    <td>اسم التقرير</td>
                                    <td>مرسل التقرير</td>
{{--                                    <td>نوع التقرير</td>--}}
                                    <td>حالة التقرير</td>
                                    <td>تاريخ الارسال</td>
                                    <td></td>
                                </tr>
                                </thead>

                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        $(document).ready(function () {
            $('.violation_report_table').DataTable({
                "lengthMenu": [
                    [10, 25, 50, -1],
                    [10, 25, 50, "All"]
                ],
                "processing": true,
                "serverSide": false,
                "ajax": {
                    "url": "{{ route('violation_report.datatable') }}",
                    "type": "GET"
                },
                "columns": [

                    {
                        data: 'title',
                        name: 'title'
                    },
                    {
                        data: 'user',
                        name: 'user'
                    },

                    {
                        data: 'role',
                        name: 'role'
                    },

                    {
                        data: 'status',
                        name: 'status',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'created_at',
                        name: 'created_at'
                    },
                    {
                        data: 'actions',
                        name: 'actions',
                        orderable: false,
                        searchable: false
                    },
                ],
                "error": function (xhr, error, thrown) {
                    console.log('DataTables Ajax error:', xhr, error, thrown);
                },
            });
        });
    </script>

    <script>
        $(document).ready(function () {
            $('.general_report_table').DataTable({
                "lengthMenu": [
                    [10, 25, 50, -1],
                    [10, 25, 50, "All"]
                ],
                "processing": true,
                "serverSide": false,
                "ajax": {
                    "url": "{{ route('general_report.datatable') }}",
                    "type": "GET"
                },
                "columns": [

                    {
                        data: 'title',
                        name: 'title'
                    },
                    {
                        data: 'user',
                        name: 'user'
                    },

                    // {
                    //     data: 'role',
                    //     name: 'role'
                    // },

                    {
                        data: 'status',
                        name: 'status',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'created_at',
                        name: 'created_at'
                    },
                    {
                        data: 'actions',
                        name: 'actions',
                        orderable: false,
                        searchable: false
                    },
                ],
                "error": function (xhr, error, thrown) {
                    console.log('DataTables Ajax error:', xhr, error, thrown);
                },
            });
        });
    </script>

    <script>
        $(document).ready(function () {
            $("#js-example-basic-single").select2({
                dropdownParent: $(".modal-content"),
            });
            $("#js-example-basic-single1").select2({
                dropdownParent: $(".modal-content"),
            });
            $("#js-example-basic-single2").select2({
                dropdownParent: $(".modal-content"),
            });
        });

    </script>
    <script>
        $(document).on('submit', '#dailyReportForm', function (e) {
            e.preventDefault();
            var formData = new FormData(this);
            var url = $('#dailyReportForm').attr('action');
            $.ajax({
                url: url,
                type: 'POST',
                data: formData,
                beforeSend: function () {
                    $('#addButton').html('<span class="spinner-border spinner-border-sm mr-2" ' +
                        ' ></span> <span style="margin-left: 4px;">جاري الارسال ...</span>'
                    ).attr('disabled', true);
                },
                success: function (data) {

                    $('#exampleModal').modal('hide');

                    // Reset the form fields
                    $('#dailyReportForm')[0].reset();

                    // Reload the DataTable
                    $('.daily_report_table').DataTable().ajax.reload();

                    // Restore the submit button
                    $('#addButton').html('إرسال').attr('disabled', false);
                },
                error: function (data) {
                },
                cache: false,
                contentType: false,
                processData: false
            });
        });
    </script>
@endsection

