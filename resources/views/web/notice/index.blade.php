@extends('web.layouts.master')
@section('content')

    <div class="card-border-shape mt-16">
        <div class="row">
            <div class="col-lg-3 col-md-6 col-12 mb-3">
                <div class="card-home">
                    <h2 class="text-primary fw-700">1,512</h2>
                    <div class="d-flex justify-content-between">
                        <p class="fs-14 text-secondary">عدد البلاغات</p>
                        <div class="status-true">
                            <img class="h-16" src="{{asset('web/image/arrow-up-right.png')}}" alt="no-icon">
                            <p>34.1%</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-12 mb-3">
                <div class="card-home">
                    <h2 class="text-brown fw-700">637</h2>
                    <div class="d-flex justify-content-between">
                        <p class="fs-14 text-secondary">عدد البلاغات الجديدة</p>
                        <div class="status-true">
                            <img class="h-16" src="{{asset('web/image/arrow-up-right.png')}}" alt="no-icon">
                            <p>4.1%</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-12 mb-3">
                <div class="card-home">
                    <h2 class="text-red fw-700">1,456</h2>
                    <div class="d-flex justify-content-between">
                        <p class="fs-14 text-secondary">عدد البلاغات المرفوضة</p>
                        <div class="status-false">
                            <img class="h-16" src="{{asset('web/image/arrow-down-right.png')}}" alt="no-icon">
                            <p>4.1%</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-12 mb-3">
                <div class="">
                    <h2 class="text-green fw-700">6,243</h2>
                    <div class="d-flex justify-content-between">
                        <p class="fs-14 text-secondary">عدد البلاغات التي تم حلها </p>
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
            <h5 class="text-primary"> البلاغات</h5>
        </div>
        <div>
            <div class="tabs">
                <div class="tabs-list d-flex mt-4">
                    <div class="show" data-content=".content-one">قائمة البلاغات</div>
                    <div data-content=".content-two">ادارة البلاغات</div>
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
                            <table id="example1" class="data-table" style="width: 100%">
                                <thead>
                                <tr>
                                    <td>اسم البلاغ</td>
                                    <td>مرسل البلاغ</td>
                                    <td>درجة الأهمية</td>
                                    <td>حالة البلاغ</td>
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
                            <button
                                type="button"
                                class="main-button"
                                data-bs-toggle="modal"
                                data-bs-target="#exampleModal"
                            >
                                اضافة نوع بلاغ
                            </button>
                        </div>
                        <div class="scroll">
                            <table id="example1" class="data-table-1" style="width: 100%">
                                <thead>
                                <tr>
                                    <td>نوع البلاغ</td>
                                    <td>مستوى الأهمية</td>
                                    <td>التصعيد التلقائى</td>
                                    <td>امكانية التصعيد</td>
                                    <td>تاريخ الانشاء</td>
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

    <!-- Modal -->
    <div
        class="modal fade"
        id="exampleModal"
        tabindex="-1"
        aria-labelledby="exampleModalLabel"
        aria-hidden="true"
    >
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div
                    class="modal-header bg-gray d-flex flex-column align-items-start"
                >
                    <h5 class="text-primary fs-18" id="exampleModalLabel">
                        إنشاء نوع بلاغ
                    </h5>
                    <p class="text-secondary fs-14 fw-400">يرجى إدخال بيانات البلاغ </p>
                </div>
                <div class="modal-body">
                    <form class="row g-3" id="addNoticeTypeForm" action="{{route('notice_type.store')}}" method="POST">
                        @csrf
                        <div class="col-12">
                            <div class="change-direction">
                                <label
                                    for="validationDefault01"
                                    class="form-label fs-14 fw-500 text-primary"
                                >اسم البلاغ
                                    <span class="text-red">*</span>
                                </label>
                                <input
                                    type="text"
                                    class="form-control fs-12 fw-400 text-secondary bg-gray"
                                    id="validationDefault01"
                                    name="name"
                                    placeholder="ادخل الاسم"
                                    required
                                />
                            </div>
                        </div>
                        <div class="col-12 d-flex flex-column">
                            <label
                                for="validationDefault04"
                                class="form-label fs-14 fw-500 text-primary"
                            >مستوى الأهمية
                                <span class="text-red">*</span>
                            </label>
                            <select
                                class="form-select w-100 fs-12 fw-400 text-primary bg-gray"
                                id="js-example-basic-single"
                                required
                                name="priority"
                            >
                                <option value="suggest">
                                    إقتراح
                                </option>
                                <option value="low">
                                    منخفض
                                </option>
                                <option value="mid">
                                    متوسط
                                </option>
                                <option value="high">
                                    عالي
                                </option>
                            </select>
                        </div>
                        <div class="col-12 d-flex flex-column">
                            <label
                                for="validationDefault04"
                                class="form-label fs-14 fw-500 text-primary"
                            >مدة التصعيد التلقائى
                                <span class="text-red">*</span>
                            </label>
                            <select
                                class="form-select w-100 fs-12 fw-400 text-primary bg-gray"
                                id="js-example-basic-single1"
                                name="period"
                                required
                            >
                                <option name="none">
                                    لا يوجد
                                </option>
                                <option value="after 24 hours">
                                    بعد 24 ساعة
                                </option>
                                <option value="after 48 hours">
                                    بعد 48 ساعة
                                </option>
                            </select>
                        </div>
                        <div class="col-12 d-flex flex-column">
                            <label
                                for="validationDefault04"
                                class="form-label fs-14 fw-500 text-primary"
                            >مستوى التصعيد
                                <span class="text-red">*</span>
                            </label>
                            <select
                                class="form-select w-100 fs-12 fw-400 text-primary bg-gray"
                                id="js-example-basic-single"
                                required
                                name="level"
                            >
                                <option value="اذا لم يعالج">
                                    اذا لم يعالج
                                </option>
                                <option value="لم يتم التصعيد">
                                    لم يتم التصعيد
                                </option>
                                <option value="تصعيد مباشر">
                                    تصعيد مباشر
                                </option>
                            </select>
                        </div>
                        <div
                            class="col-12 d-flex justify-content-between border-top pt-3 mt-4"
                        >
                            <button
                                type="button"
                                class="view border-0"
                                data-bs-dismiss="modal"
                            >
                                الغاء
                            </button>
                            <button id="addButton" type="submit" class="main-button">
                                تفعيل البلاغ
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
@section('js')
    <script>
        // In your Javascript (external .js resource or <script> tag)
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




    @section('js')

        @include('web.layouts.datatable')


        <script>
            $(document).on('submit', '#addNoticeTypeForm', function (e) {
                e.preventDefault();
                var formData = new FormData(this);
                var url = $('#addNoticeTypeForm').attr('action');
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
                        $('#addnoticeTypeForm')[0].reset();
                        $('.data-table-1').DataTable().ajax.reload();
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







        <script>
            let columns = [
                {
                    data: 'name',
                    name: 'name'
                },
                {
                    data: 'user',
                    name: 'user'
                },
                {
                    data: 'priority',
                    name: 'priority'
                },
                {
                    data: 'status',
                    name: 'status'
                },
                {
                    data: 'created_at',
                    name: 'created_at',
                },
                {
                    data: 'actions',
                    name: 'actions',

                    orderable: false,
                    searchable: false
                }
            ];

            let ajax = {
                "url": "{{ route('notice.datatable') }}",
                "type": "GET"
            };

            showData(columns, ajax);
        </script>

        <script>
            let columns2 = [
                {
                    data: 'name',
                    name: 'name'
                },
                {
                    data: 'priority',
                    name: 'priority'
                },
                {
                    data: 'period',
                    name: 'period'
                },
                {
                    data: 'level',
                    name: 'level',
                },
                {
                    data: 'created_at',
                    name: 'created_at',
                },
                {
                    data: 'actions',
                    name: 'actions',

                    orderable: false,
                    searchable: false
                }
            ];

            let ajax2 = {
                "url": "{{ route('notice_type.datatable') }}",
                "type": "GET"
            };

            showData1(columns2, ajax2, '.data-table-1');
        </script>
    @endsection
