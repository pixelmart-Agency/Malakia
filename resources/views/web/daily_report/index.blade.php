@extends('web.layouts.master')
@section('content')

    <div class="card-border mt-16">
        <div class="d-flex justify-content-between">
            <h5 class="text-primary"> التقارير اليومية</h5>
        </div>
        <div>
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif
            <div class="tabs">
                <div class="tabs-list d-flex mt-4">
                    <div class="show" data-content=".content-one">قائمة التقارير اليومية</div>
                    <div data-content=".content-two">ادارة التقارير اليومية</div>
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
                            <table id="example1" class="data-table  daily_report_assign_user_table" style="width: 100%">
                                <thead>
                                <tr>
                                    <td>عنوان التقرير اليومى</td>
                                    <td>الموظف</td>
                                    <td>المشرف</td>
                                    <td>تاريخ التقرير اليومى</td>
                                    <td style="width: 15%;">حالة التقرير اليومى</td>
                                    <td>التفاصيل</td>
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
                            <button type="button" class="main-button" data-bs-toggle="modal"
                                    data-bs-target="#exampleModal">
                                اضافة تقرير يومى
                            </button>
                        </div>
                        <div class="scroll">
                            <table id="example" class="data-table daily_report_table" style="width: 100%">
                                <thead>
                                <tr>
                                    <td>عنوان التقرير اليومي</td>
                                    <td style="width: 40%;">وصف التقرير اليومي</td>
                                    <td>تاريخ إضافة التقرير اليومي</td>
                                    <td>التفاصيل</td>
                                </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-gray d-flex flex-column align-items-start">
                    <h5 class="text-primary fs-18" id="exampleModalLabel">
                        إضافة تقرير يومي
                    </h5>
                    <p class="text-secondary fs-14 fw-400">يرجى إدخال بيانات التقرير اليومي لإضافته إلى النظام</p>
                </div>
                <div class="modal-body">
                    <form class="row" id="dailyReportForm" action="{{ route('daily_report.store') }}">
                        @csrf
                        <div class="col-12">
                            <div class="change-direction">
                                <label for="validationDefault01" class="form-label fs-14 fw-500 text-primary">عنوان
                                    التقرير
                                    اليومي
                                    <span class="text-red">*</span>
                                </label>
                                <input type="text" class="form-control fs-12 fw-400 text-secondary bg-gray"
                                       id="validationDefault01" name="title" placeholder="ادخل العنوان" required/>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="change-direction">
                                <label for="validationTextarea" class="form-label fs-14 fw-500 text-primary">وصف التقرير
                                    اليومى
                                    <span class="text-red">*</span>
                                </label>
                                <textarea class="form-control fs-12 fw-400 text-secondary bg-gray h-150"
                                          id="validationTextarea" name="description"
                                          placeholder="وصف التنبيه" required></textarea>
                            </div>
                        </div>
                        <div class="col-12 d-flex flex-column">
                            <label for="validationDefault04" class="form-label fs-14 fw-500 text-primary">المحور
                                <span class="text-red">*</span>
                            </label>
                            <select class="form-select w-100 fs-12 fw-400 text-primary bg-gray"
                                    {{--                                id="js-example-basic-single" --}}
                                    required name="axis_id">
                                @foreach ($axes as $axis)
                                    <option value="{{ $axis->id }}">{{ $axis->name }}</option>
                                @endforeach


                            </select>
                        </div>
                        <div class="col-md-6 col-12 d-flex flex-column">
                            <label for="validationDefault04" class="form-label fs-14 fw-500 text-primary">نوع الرصد
                                <span class="text-red">*</span>
                            </label>
                            <select class="form-select w-100 fs-12 fw-400 text-primary bg-gray" name="monitor_type"
                                    {{--                                id="js-example-basic-single" --}} required>
                                <option selected disabled value="">
                                    اختر نوع الرصد
                                </option>
                                @foreach (\App\Enum\monitorType::cases() as $monitorType)
                                    <option value="{{ $monitorType->value }}">
                                        {{ $monitorType->lang() }}
                                    </option>
                                @endforeach
                            </select>

                        </div>
                        <div class="col-md-6 col-12 d-flex flex-column">
                            <label for="validationDefault04" class="form-label fs-14 fw-500 text-primary">الجهة ذات
                                علاقة
                                <span class="text-red">*</span>
                            </label>
                            <select class="form-select w-100 fs-12 fw-400 text-primary bg-gray" name="side_type"
                                    required>
                                <option selected disabled value="">
                                    اختر نوع الرصد
                                </option>

                                @foreach (\App\Enum\SideType::cases() as $sideType)
                                    <option value="{{ $sideType->value }}">{{ $sideType->lang() }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12 d-flex flex-column">
                            <label for="validationDefault04" class="form-label fs-14 fw-500 text-primary">الموعد النهائي
                                لتسليم التقرير
                                <span class="text-red">*</span>
                            </label>
                            <input type="date" name="deadline" min="{{ \Carbon\Carbon::today()->toDateString() }}"
                                   class="form-select w-100 fs-12 fw-400 text-primary bg-gray" required>

                        </div>
                        <div class="col-12 d-flex justify-content-between border-top pt-3 mt-4">
                            <button type="button" class="view border-0" data-bs-dismiss="modal">
                                الغاء
                            </button>
                            <button type="submit" id="addButton" class="main-button">
                                ارسال
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    {{--        model--}}
@endsection
@section('js')
    @include('web.layouts.datatable')

    <script>
        let columns1 = [
            {data: 'title', name: 'title'},
            {data: 'description', name: 'description'},
            {data: 'created_at', name: 'created_at'},
            {data: 'actions', name: 'actions', orderable: false, searchable: false}
        ];

        let ajax1 = {
            "url": "{{ route('daily_report.datatable') }}",
            "type": "GET"
        };

        $(document).ready(function () {
            showData(columns1, ajax1, '.daily_report_table');
        });
    </script>



    <script>
        let columns2 = [
            {data: 'title', name: 'title'},
            {data: 'user_id', name: 'user_id'},
            {data: 'leader_id', name: 'leader_id'},
            {data: 'deadline', name: 'deadline'},
            {data: 'status', name: 'status', orderable: false, searchable: false},
            {data: 'actions', name: 'actions', orderable: false, searchable: false}
        ];

        let ajax2 = {
            "url": "{{ route('daily_report_assign_user.datatable') }}",
            "type": "GET",

        };

        // $(document).ready(function () {
            showData(columns2, ajax2, '.daily_report_assign_user_table');
        // });
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
