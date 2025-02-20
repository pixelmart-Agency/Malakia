@extends('web.layouts.master')
@section('content')
    <style>
        #map {
            height: 300px;
        }
    </style>
    <div class="breadcrumb mt-4 mb-4">
        <a href="index.html"><img class="h-24" src="{{ asset('web/image/home.png') }}" alt="no-icon"></a>
        <img class="h-24 me-3 ms-3" src="{{ asset('web/image/icon-breadcrumb.png') }}" alt="no-icon">
        <a class="link-breadcrumb" href="{{ route('axesManagement') }}">إدارة المحاور</a>
        <img class="h-24 me-3 ms-3" src="{{ asset('web/image/icon-breadcrumb.png') }}" alt="no-icon">
        <span class="fs-14 fw-400 text-secondary">محور {{ $axis->name }}</span>
    </div>
    <div class="card-border mt-16">
        <div class="d-flex justify-content-between flex-wrap">
            <h5 class="text-primary">{{ $axis->name }}</h5>
            <div class="d-flex">
                <button type="button" class="btn-filter">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="25" viewBox="0 0 24 25"
                        fill="none">
                        <path
                            d="M19.25 5.41992H4.75L9.31174 11.1221C9.59544 11.4767 9.75 11.9173 9.75 12.3715V18.9199C9.75 19.4722 10.1977 19.9199 10.75 19.9199H13.25C13.8023 19.9199 14.25 19.4722 14.25 18.9199V12.3715C14.25 11.9173 14.4046 11.4767 14.6883 11.1221L19.25 5.41992Z"
                            stroke="#857854" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </button>
                <button type="button" class="main-button blur" data-bs-toggle="modal" data-bs-target="#exampleModal1">
                    الاسئلة الخاصة بالمحور
                </button>
                <button type="button" class="main-button" data-bs-toggle="modal" data-bs-target="#exampleModal">
                    إضافة موقع
                </button>
            </div>
        </div>
        <hr class="hr-card">
        <div class="scroll">
            <table id="example" class="data-table axisManagement-table" style="width:100%">
                <thead>
                    <tr>
                        <td>id</td>
                        <td>اسم الموقع</td>
                        <td>الفريق الميداني</td>
                        <td>المشرف</td>
                        <td></td>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

    <!-- END MAIN CONTENT -->


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
                    <form class="row g-3" action="{{ route('area.store') }}" method="POST" id="StoreForm">
                        @csrf
                        <input type="hidden" name="axis_id" value="{{ $axis->id }}">
                        <div class="col-12">
                            <div class="change-direction">
                                <label for="validationDefault01" class="form-label fs-14 fw-500 text-primary">اسم
                                    المنطقة
                                    <span class="text-red">*</span>
                                </label>
                                <input type="text" class="form-control fs-12 fw-400 text-secondary bg-gray"
                                    id="validationDefault01" name="name" placeholder="أدخل اسم المنطقة" required />
                            </div>
                        </div>
                        <div class="col-12 d-flex flex-column">
                            <label for="validationDefault04" class="form-label fs-14 fw-500 text-primary">الفريق
                                الميداني
                                <span class="text-red">*</span>
                            </label>
                            <select class="form-select w-100 fs-12 fw-400 text-primary bg-gray" name="team_ids[]"
                                id="js-example-basic-single" multiple required>
                                <option disabled class="text-secondary fs-12 fw-400" value="">اختر الفريق
                                </option>
                                @foreach ($teams as $team)
                                    <option value="{{ $team->id }}">{{ $team->full_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12 d-flex flex-column">
                            <label for="validationDefault04" class="form-label fs-14 fw-500 text-primary">المشرف
                                الميداني
                                <span class="text-red">*</span>
                            </label>
                            <select class="form-select w-100 fs-12 fw-400 text-primary bg-gray"
                                id="js-example-basic-single1" name="leader_id" required>
                                <option disabled class="text-secondary fs-12 fw-400" value="">اختر المشرف
                                </option>
                                @foreach ($leaders as $leader)
                                    <option value="{{ $leader->id }}">{{ $leader->full_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label fs-14 fw-500 text-primary">إحداثيات الموقع</label>
                            <div id="maps-container">
                                <div class="map-wrapper">
                                    <div class="map" id="map-0" style="height: 300px;"></div>
                                    <input type="hidden" name="coordinates[0][north]" id="north-0">
                                    <input type="hidden" name="coordinates[0][south]" id="south-0">
                                    <input type="hidden" name="coordinates[0][east]" id="east-0">
                                    <input type="hidden" name="coordinates[0][west]" id="west-0">
                                </div>
                            </div>
                            <a id="add-form" class="main-button blur"
                                style="background-color: transparent; margin-right: 0; padding-right: 0;">
                                <img class="h-16" src="{{ asset('web/image/plus.svg') }}" alt="no-icon"> إضافة
                                إحداثيات أخرى للموقع
                            </a>
                        </div>

                        <div class="col-12 d-flex justify-content-between border-top pt-3 mt-4">
                            <button type="button" class="view border-0" data-bs-dismiss="modal">
                                الغاء
                            </button>
                            <button type="submit" class="main-button" id="submitBtn">
                                إضافة منطقة جديد
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="exampleModal1" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content select-content">
                <div class="modal-header d-flex justify-content-between align-items-start">
                    <h5 class="text-primary fs-18" id="exampleModalLabel">
                        الاسئلة الخاصة بالمحور
                    </h5>
                    <a class="view border-0">
                        <img class="h-24" src="{{ asset('web/image/edit-icon.png') }}" alt="no-image">
                        تعديل
                    </a>
                </div>
                <div class="modal-body" style="background-color: #F7F7F8;">
                    {{--                    @dd($axis) --}}
                    @foreach ($axis->axisQuestions as $question)
                        {{--                        @dd($question->id) --}}
                        <div class="mt-3">
                            <button class="btn-ollapse d-flex justify-content-between w-100" data-bs-toggle="collapse"
                                data-bs-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
                                <div class="head-collapse d-flex">
                                    <img class="h-24" src="{{ asset('web/image/menu.png') }}" alt="no-image">
                                    <span class="status-accept" style="padding: 0 8px;">1</span>
                                    <h6 class="fw-400 me-2">السؤال </h6>
                                </div>
                                <div class="d-flex">
                                    <a><img class="h-24" src="{{ asset('web/image/trash.png') }}" alt="no-image"></a>
                                    <div class="collapse-icon me-2">
                                        <i class="fa-solid fa-angle-down"></i>
                                    </div>
                                </div>
                            </button>
                            <div class="collapse me-3" id="collapseExample">
                                <h6 class="fw-400 mt-3 bg-white p-2" style="border-radius: 8px;">
                                    {{ $question->question }}</h6>
                                <h6 class="fw-400 mt-3">الاجابة </h6>
                                @foreach ($answers as $answer)
                                    @if ($answer->axis_question_id == $question->id)
                                        <p class="fw-400 mt-3 bg-white p-2" style="border-radius: 8px;">
                                            {{ $answer->answer }}</p>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <script defer
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCK_HenDkNSugL5gakTqdIagO4y8KR-udc&callback=initMap&v=weekly">
    </script>
@endsection
@section('js')
    @include('web.layouts.datatable')

    <script>
        let axisId = new URLSearchParams(window.location.search).get('axis_id'); // Get parameter from URL

        let columns = [{
                data: 'id',
                name: 'id'
            },
            {
                data: 'area_locations',
                name: 'area_locations'
            },
            {
                data: 'area_team',
                name: 'area_team',
                orderable: false,
                searchable: false
            },
            {
                data: 'supervisors',
                name: 'supervisors',
                orderable: false,
                searchable: false
            },
            {
                data: 'actions',
                name: 'actions',
                orderable: false,
                searchable: false
            }
        ];

        let ajax = {
            url: "{{ route('area.datatable') }}",
            type: "GET",
            data: function(d) {
                d.axis_id = axisId; // Send axis_id to backend
            },
            error: function(xhr, error, thrown) {
                console.error('DataTables AJAX error:', xhr.responseText);
            }
        };

        showData(columns, ajax);
    </script>
    <script>
        $(document).ready(function() {
            $("#js-example-basic-single").select2({
                dropdownParent: $("#exampleModal .modal-content"),
            });
            $("#js-example-basic-single1").select2({
                dropdownParent: $("#exampleModal .modal-content"),
            });
        });
    </script>

    <script>
        $('#StoreForm').on('submit', function(e) {
            e.preventDefault(); // Prevent page reload
            let formDate = new FormData(this);
            let url = $(this).attr('action');
            $.ajax({
                url: url,
                method: "POST",
                data: formDate,
                contentType: false,
                processData: false,
                beforeSend: function() {
                    $('#submitBtn').html('<span class="spinner-border spinner-border-sm mr-2" ' +
                        ' ></span> <span style="margin-left: 4px;">جاري الارسال ...</span>'
                    ).attr('disabled', true);
                },
                success: function(response) {
                    if (response.status) {
                        toastr.success(response.msg);
                        $('#exampleModal').modal('hide');
                        $('#example').DataTable().ajax.reload();
                    } else {
                        toastr.error(response.msg);
                    }
                },
                error: function(xhr) {
                    toastr.error(xhr.responseJSON.msg);
                }
            });
        });
    </script>

    {{-- <script>
        let bounds = {
            north: 21.4100,
            south: 21.3680,
            east: 39.8900,
            west: 39.8250,
        };
        document.getElementById("north").value = bounds.north;
        document.getElementById("south").value = bounds.south;
        document.getElementById("east").value = bounds.east;
        document.getElementById("west").value = bounds.west;
    </script>

    <script>
        function initMap() {
            // إنشاء خريطة بمركز مكة المكرمة
            const map = new google.maps.Map(document.getElementById("map"), {
                center: {
                    lat: 21.3891,
                    lng: 39.8579
                },
                zoom: 12, // تكبير أكثر لرؤية التفاصيل
            });

            // حدود المستطيل حول مكة
            const bounds = {
                north: 21.4100,
                south: 21.3680,
                east: 39.8900,
                west: 39.8250,
            };

            // إنشاء مستطيل
            const rectangle = new google.maps.Rectangle({
                bounds: bounds,
                editable: true,
                draggable: true,
            });

            rectangle.setMap(map);

            // الاستماع إلى أحداث التعديل والتحريك
            ["bounds_changed", "dragstart", "drag", "dragend"].forEach((eventName) => {
                rectangle.addListener(eventName, () => {
                    const newBounds = rectangle.getBounds()?.toJSON();
                    console.log({
                        bounds: newBounds,
                        eventName
                    });

                    // تحديث القيم في حقول الإدخال المخفية
                    document.getElementById("north").value = newBounds.north;
                    document.getElementById("south").value = newBounds.south;
                    document.getElementById("east").value = newBounds.east;
                    document.getElementById("west").value = newBounds.west;
                });
            });
        }

        window.initMap = initMap;
    </script> --}}


    <script>
        let mapCounter = 0; // عداد للخرائط الجديدة

        function createMap(mapId) {
            const mapElement = document.getElementById(mapId);

            if (!mapElement) return;

            const map = new google.maps.Map(mapElement, {
                center: {
                    lat: 21.3891,
                    lng: 39.8579
                },
                zoom: 12,
            });

            const bounds = {
                north: 21.4100,
                south: 21.3680,
                east: 39.8900,
                west: 39.8250,
            };

            const rectangle = new google.maps.Rectangle({
                bounds: bounds,
                editable: true,
                draggable: true,
            });

            rectangle.setMap(map);

            const index = mapId.split("-")[1]; // استخراج رقم الخريطة
            updateBounds(rectangle, index);

            ["bounds_changed", "dragstart", "drag", "dragend"].forEach((eventName) => {
                rectangle.addListener(eventName, () => {
                    updateBounds(rectangle, index);
                });
            });
        }

        function updateBounds(rectangle, index) {
            const newBounds = rectangle.getBounds()?.toJSON();
            if (!newBounds) return;

            document.getElementById(`north-${index}`).value = newBounds.north;
            document.getElementById(`south-${index}`).value = newBounds.south;
            document.getElementById(`east-${index}`).value = newBounds.east;
            document.getElementById(`west-${index}`).value = newBounds.west;
        }

        document.getElementById("add-form").addEventListener("click", function() {
            mapCounter++;

            const mapsContainer = document.getElementById("maps-container");

            const mapWrapper = document.createElement("div");
            mapWrapper.classList.add("map-wrapper");
            mapWrapper.innerHTML = `
        <div class="map" id="map-${mapCounter}" style="height: 300px; margin-top: 10px;"></div>
        <input type="hidden" name="coordinates[${mapCounter}][north]" id="north-${mapCounter}">
        <input type="hidden" name="coordinates[${mapCounter}][south]" id="south-${mapCounter}">
        <input type="hidden" name="coordinates[${mapCounter}][east]" id="east-${mapCounter}">
        <input type="hidden" name="coordinates[${mapCounter}][west]" id="west-${mapCounter}">
    `;

            mapsContainer.appendChild(mapWrapper);

            setTimeout(() => {
                createMap(`map-${mapCounter}`);
            }, 500);
        });

        window.initMap = function() {
            createMap("map-0");
        };
    </script>
@endsection
