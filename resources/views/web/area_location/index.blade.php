@extends('web.layouts.master')
@section('content')
    <div class="breadcrumb mt-4 mb-4">
        <a href="index.html"><img class="h-24" src="{{ asset('web/image/home.png') }}" alt="no-icon"></a>
        <img class="h-24 me-3 ms-3" src="{{ asset('web/image/icon-breadcrumb.png') }}" alt="no-icon">
        <a class="link-breadcrumb" href="{{ route('axesManagement') }}">إدارة المحاور</a>
        <img class="h-24 me-3 ms-3" src="{{ asset('web/image/icon-breadcrumb.png') }}" alt="no-icon">
        <a class="link-breadcrumb" href="{{ route('area', ['axis_id' => $area->axis_id]) }}">
         محور {{ $area->axis->name }}
        </a>
        <img class="h-24 me-3 ms-3" src="{{ asset('web/image/icon-breadcrumb.png') }}" alt="no-icon">
        <span class="fs-14 fw-400 text-secondary"> {{ $area->name }}</span>
    </div>
    <div class="card-border mt-16">
        <div class="d-flex justify-content-between flex-wrap">
            <h5 class="text-primary">{{ $area->name }}</h5>
            <div class="d-flex">
                <button type="button" class="btn-refuse" data-bs-toggle="modal" data-bs-target="#exampleModal">
                    حذف المواقع
                </button>
            </div>
        </div>
        <hr class="hr-card">
        <div class="card-details">
            <h5 class="text-primary"> المشرف</h5>
            <div class="bg-white" style="border-radius: 10px; padding: 16px">
                <div class="row">
                    {{--                    @dd($areaTeamService) --}}
                    @if (isset($supervisorUser[0]))
                        <div class="col-lg-4">
                            <div class="d-flex">
                                {{--                            @dd($supervisorUser) --}}
                                <img class="image-table" src="{{ asset($supervisorUser[0]['image'] ?? 'null') }}"
                                    alt="no-image">
                                <div>
                                    {{--                                @dd($supervisorUser[0]['full_name']) --}}
                                    <h6 class="name-table d-flex align-items-center">{{ $supervisorUser[0]['full_name'] }}
                                    </h6>
                                    <p class="fs-12 fw-400 text-secondary">سعودي</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-2">
                            <div class="table-info">{{ $supervisorUser[0]['national_id'] }}</div>
                        </div>
                        <div class="col-lg-2">
                            <div class="table-info">مراقب</div>
                        </div>
                        <div class="col-lg-2">
                            <div class="table-info">
                                {{ \Carbon\Carbon::parse($supervisorUser[0]['created_at'])->locale('ar')->translatedFormat('d F Y') }}
                            </div>
                        </div>
                        <div class="col-lg-2 d-flex justify-content-end">
                            <a class="view">
                                <img class="h-24" src="{{ asset('web/image/user-cross.svg') }}" alt="no-image">
                                تغيير
                            </a>
                        </div>
                    @else
                        لا يوجد مشرفين
                    @endif
                </div>
            </div>
        </div>
        <div class="card-details mt-3">
            <div class="d-flex justify-content-between flex-wrap">
                <h5 class="text-primary">الفريق الميدانى</h5>
                <div class="d-flex">
                    <button type="button" class="btn-filter change-color">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="25" viewBox="0 0 24 25"
                            fill="none">
                            <path
                                d="M19.25 5.41992H4.75L9.31174 11.1221C9.59544 11.4767 9.75 11.9173 9.75 12.3715V18.9199C9.75 19.4722 10.1977 19.9199 10.75 19.9199H13.25C13.8023 19.9199 14.25 19.4722 14.25 18.9199V12.3715C14.25 11.9173 14.4046 11.4767 14.6883 11.1221L19.25 5.41992Z"
                                stroke="#857854" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </button>
                    <button type="button" class="main-button" data-bs-toggle="modal" data-bs-target="#exampleModal">
                        اضافة
                    </button>
                </div>
            </div>
            <div class="bg-white mt-4" style="border-radius: 10px;">
                <div class="scroll">
                    <table id="example" class="data-table" style="width:100%">
                        <thead>
                            <tr>
                                <td> الاسم / الجنسية</td>
                                <td> رقم الهوية</td>
                                <td> الدور</td>
                                <td> تاريخ الاضافة</td>
                                <td></td>
                            </tr>
                        </thead>
                        <tbody>

                            @for ($i = 0; $i < count($teamUsers); $i++)
                                {{--                        @foreach ($teamUsers as $teamUser) --}}

                                @if ($teamUsers[$i] != null)
                                    <tr>
                                        <td>
                                            <div class="d-flex">
                                                <img class="image-table" src="{{ $teamUsers[$i]['image'] }}"
                                                    alt="no-image">
                                                <div>
                                                    <h6 class="name-table d-flex align-items-center">
                                                        {{ $teamUsers[$i]['full_name'] }}</h6>
                                                    <p class="fs-12 fw-400 text-secondary">سعودي</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="table-info">{{ $teamUsers[$i]['national_id'] }}</div>
                                        </td>
                                        <td>
                                            <div class="table-info">مراقب</div>
                                        </td>
                                        <td>

                                            <div class="table-info">
                                                {{ \Carbon\Carbon::parse($teamUsers[$i]['created_at'])->locale('ar')->translatedFormat('d F Y') }}
                                            </div>
                                        </td>
                                        <td>
                                            <a class="view" style="color: #C05E5E;">
                                                <img class="h-24" src="{{ asset('web/image/trash.svg') }}"
                                                    alt="no-image">
                                                حذف
                                            </a>
                                        </td>
                                    </tr>
                                @endif
                            @endfor

                        </tbody>
                        {{--                        @endforeach --}}
                    </table>
                </div>
            </div>
        </div>

    </div>



    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-gray d-flex flex-column align-items-start">
                    <h5 class="text-primary fs-18" id="exampleModalLabel">
                        إضافة موقع جديد
                    </h5>
                    <p class="text-secondary fs-14 fw-400">يرجى إدخال بيانات المنطقة الجديدة لإضافته إلى النظام</p>
                </div>
                <div class="modal-body">
                    <form class="row g-3">

                        <div class="col-12 d-flex flex-column">
                            <label for="validationDefault04" class="form-label fs-14 fw-500 text-primary">الفريق الميداني
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

                        <div class="col-12 d-flex justify-content-between border-top pt-3 mt-4">
                            <button type="button" class="view border-0" data-bs-dismiss="modal">
                                الغاء
                            </button>
                            <button type="submit" class="main-button">
                                إضافة عضو
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
