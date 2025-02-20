@extends('web.layouts.master')
@section('content')
    <div class="card-border mt-16">
        <div class="d-flex justify-content-between flex-wrap">
            <h5 class="text-primary">إدارة المحاور </h5>
            <div class="d-flex">
                <button type="button" class="btn-filter">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="25" viewBox="0 0 24 25" fill="none">
                        <path
                            d="M19.25 5.41992H4.75L9.31174 11.1221C9.59544 11.4767 9.75 11.9173 9.75 12.3715V18.9199C9.75 19.4722 10.1977 19.9199 10.75 19.9199H13.25C13.8023 19.9199 14.25 19.4722 14.25 18.9199V12.3715C14.25 11.9173 14.4046 11.4767 14.6883 11.1221L19.25 5.41992Z"
                            stroke="#857854" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </button>
                <a href="{{ route('axesManagement.create') }}" class="main-button">
                    إضافة محور واسئلته
                </a>
            </div>
        </div>
        <hr class="hr-card">
        <div class="scroll">
            <table id="example" class="data-table axisManagement-table data-table" style="width:100%">
                <thead>
                    <tr>
                        <td>اسم المحور</td>
                        <td>عدد الاسئلة</td>
                        <td>عدد المواقع</td>
                        <td> الموظفين</td>
                        <td>المشرفين</td>
                        <td></td>
                    </tr>
                </thead>
            </table>
        </div>


        <!-- Modal -->
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-gray d-flex flex-column align-items-start">
                        <h5 class="text-primary fs-18" id="exampleModalLabel">
                            إضافة منطقة جديد
                        </h5>
                        <p class="text-secondary fs-14 fw-400">يرجى إدخال بيانات المنطقة الجديدة لإضافته إلى النظام</p>
                    </div>
                    <div class="modal-body">
                        <form class="row g-3">
                            <div class="col-12">
                                <div class="change-direction">
                                    <label for="validationDefault01" class="form-label fs-14 fw-500 text-primary">اسم
                                        المنطقة
                                        <span class="text-red">*</span>
                                    </label>
                                    <input type="text" class="form-control fs-12 fw-400 text-secondary bg-gray"
                                        id="validationDefault01" placeholder="أدخل اسم المنطقة" required />
                                </div>
                            </div>
                            <div class="col-12 d-flex flex-column">
                                <label for="validationDefault04" class="form-label fs-14 fw-500 text-primary">الفريق
                                    الميداني
                                    <span class="text-red">*</span>
                                </label>
                                <select class="form-select w-100 fs-12 fw-400 text-primary bg-gray"
                                    id="js-example-basic-single" multiple required>
                                    <option>
                                        سارة نصر
                                    </option>
                                    <option>
                                        أحمد حسين
                                    </option>
                                    <option>
                                        عبدالرحمن صالح
                                    </option>
                                    <option>
                                        مصطفى علي
                                    </option>
                                </select>
                            </div>
                            <div class="col-12 d-flex flex-column">
                                <label for="validationDefault04" class="form-label fs-14 fw-500 text-primary">المشرف
                                    الميداني
                                    <span class="text-red">*</span>
                                </label>
                                <select class="form-select w-100 fs-12 fw-400 text-primary bg-gray"
                                    id="js-example-basic-single1" required>
                                    <option selected disabled class="text-secondary fs-12 fw-400" value="">اختر المشرف
                                    </option>
                                    <option>
                                        سارة نصر
                                    </option>
                                    <option>
                                        أحمد حسين
                                    </option>
                                    <option>
                                        عبدالرحمن صالح
                                    </option>
                                    <option>
                                        مصطفى علي
                                    </option>
                                </select>
                            </div>
                            <div class="col-12 d-flex justify-content-between border-top pt-3 mt-4">
                                <button type="button" class="view border-0" data-bs-dismiss="modal">
                                    الغاء
                                </button>
                                <button type="submit" class="main-button">
                                    إضافة منطقة جديد
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endsection

    @section('js')
    @include('web.layouts.datatable')

        <script>
            let columns = [{
                    data: 'name',
                    name: 'name'
                },
                {
                    data: 'number_of_questions',
                    name: 'number_of_questions'
                },
                {
                    data: 'area_count',
                    name: 'area_count'
                },
                {
                    data: 'employees',
                    name: 'employees'
                },
                {
                    data: 'supervisors',
                    name: 'supervisors'
                },
                {
                    data: 'actions',
                    name: 'actions',
                    orderable: false,
                    searchable: false
                }
            ];

            let ajax = {
                        "url": "{{ route('axesManagement.datatable') }}",
                        "type": "GET"
                    };

            showData(columns,ajax);
        </script>

        <script>
            // In your Javascript (external .js resource or <script> tag)
            $(document).ready(function() {
                $("#js-example-basic-single").select2({
                    dropdownParent: $(".modal-content"),
                });
                $("#js-example-basic-single1").select2({
                    dropdownParent: $(".modal-content"),
                });
            });
        </script>
    @endsection
