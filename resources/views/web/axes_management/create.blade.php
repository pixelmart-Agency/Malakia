@extends('web.layouts.master')
@section('content')
    <div class="breadcrumb mt-4 mb-4">
        <a href="index.html"><img class="h-24" src="{{ asset('web/image/home.png') }}" alt="no-icon"></a>
        <img class="h-24 me-3 ms-3" src="{{ asset('web/image/icon-breadcrumb.png') }}" alt="no-icon">
        <a class="link-breadcrumb" href="{{ route('axesManagement') }}">إدارة المحاور </a>
        <img class="h-24 me-3 ms-3" src="{{ asset('web/image/icon-breadcrumb.png') }}" alt="no-icon">
        <span class="fs-14 fw-400 text-secondary">اضافة محور</span>
    </div>
    <div class="card-border mt-16">
        <form action="{{ route('axesManagement.store') }}" method="post" class="row g-3 form-group">
            @csrf
            <div class="d-flex justify-content-between flex-wrap">
                <h5 class="text-primary">اضافة محور</h5>
                <div class="d-flex">
                    <button type="button" class="view border-0 me-3" data-bs-dismiss="modal">
                        الغاء
                    </button>
                    <button type="button" class="main-button" id="submitForm">
                        إضافة وحفظ
                    </button>
                </div>
            </div>
            <hr class="hr-card">
            <div>
                <div class="col-12">
                    <div class="change-direction">
                        <label class="form-label fs-14 fw-500 text-primary">اسم المحور
                            <span class="text-red">*</span>
                        </label>
                        <input name="name" type="text" class="form-control fs-12 fw-400 text-secondary bg-gray"
                            placeholder="أدخل اسم المحور" required>
                    </div>
                </div>
                <div id="forms-container">
                    <div class="col-12 group">
                        <div class="p-2 drag-handle" data-index="1">
                            <div id="buttons-container" class="d-flex">
                                <div class="drag-handle d-flex align-items-center" style="cursor: grab; padding: 10px;">
                                    <i class="fa-solid fa-grip-vertical"></i>
                                </div>
                                <!-- Default Button (Cannot be deleted) -->
                                <button type="button" class="btn-ollapse btn-question d-flex justify-content-between w-100"
                                    data-bs-toggle="collapse" data-bs-target="#collapseExample1" aria-expanded="false"
                                    aria-controls="collapseExample">
                                    <div class="head-collapse d-flex question-label-div">
                                        {{-- <img class="h-24" src="{{ asset('web/image/menu.png') }}" alt="no-image"> --}}
                                        <span class="status-accept" style="padding: 0 8px;">1</span>
                                        <h6 class="fw-400 me-2 lable-question">صيغة السؤال</h6>
                                    </div>
                                    <div class="d-flex">
                                        <!-- Trash icon only for cloned elements -->
                                        <a class="delete-form"><img class="h-24" src="{{ asset('web/image/trash.png') }}"
                                                alt="no-image"></a>
                                        <div class="collapse-icon me-2">
                                            <i class="fa-solid fa-angle-down"></i>
                                        </div>
                                    </div>
                                </button>

                            </div>
                            <form class="row g-3 form-group">
                                <div class="collapse me-3" id="collapseExample1">
                                    <div class="col-12">
                                        <div class="change-direction">
                                            <label class="form-label fs-14 fw-500 text-primary"> السؤال
                                                <span class="text-red">*</span>
                                            </label>
                                            <input name="questions[0][name]" type="text" data-index="1"
                                                class="form-control fs-12 fw-400 text-secondary bg-gray input-question"
                                                placeholder="أدخل السؤال" required>
                                        </div>
                                    </div>
                                    <div class="col-12 d-flex flex-column mt-3">
                                        <label for="validationDefault04" class="form-label fs-14 fw-500 text-primary">
                                            نوع الاجابة
                                            <span class="text-red">*</span>
                                        </label>
                                        <select name="questions[0][answer_type]" data-index="1"
                                            class="form-select w-100 answer-type-select fs-12 fw-400 text-primary bg-gray js-example-basic-single1"
                                            id="js-example-basic-single" required>
                                            <option selected value="0">
                                                مقالي
                                            </option>
                                            <option value="1">
                                                اختيار متعدد
                                            </option>
                                            <option value="2">
                                                نعم ولا
                                            </option>
                                        </select>
                                    </div>
                                    <div class="col-12">
                                        <div class="mt-2 change-check">
                                            <input class="form-check-input me-2" name="questions[0][require_file]"
                                                type="checkbox" value="1" id="flexCheckDefault1">
                                            <label class="form-check-label fs-14" for="flexCheckDefault1">
                                                طلب رفع الادلة والشواهد
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-12 div-multiple-answer mt-3 d-none" data-index="1">
                                        <div class="row">
                                            <div class="col-2 mb-2">
                                                <div class="view text-center">الاجابة الاولي</div>
                                            </div>
                                            <div class="col-10 mb-2">
                                                <input type="text"
                                                    class="form-control w-100 fs-12 fw-400 text-primary bg-gray"
                                                    name="questions[0][answer1]" required>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-2 mb-2">
                                                <div class="view text-center">الاجابة الثانية</div>
                                            </div>
                                            <div class="col-10 mb-2">
                                                <input type="text"
                                                    class="form-control w-100 fs-12 fw-400 text-primary bg-gray"
                                                    name="questions[0][answer2]" required>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-2 mb-2">
                                                <div class="view text-center">الاجابة الثالثة</div>
                                            </div>
                                            <div class="col-10 mb-2">
                                                <input type="text"
                                                    class="form-control w-100 fs-12 fw-400 text-primary bg-gray"
                                                    name="questions[0][answer3]" required>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-2 mb-2">
                                                <div class="view text-center">الاجابة الرابعة</div>
                                            </div>
                                            <div class="col-10 mb-2">
                                                <input type="text"
                                                    class="form-control w-100 fs-12 fw-400 text-primary bg-gray"
                                                    name="questions[0][answer4]" required>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <button id="duplicate-question" class="main-button blur"
        style="background-color: transparent; margin-right: 0; padding-right: 0;">
        <img class="h-16" src="{{ asset('web/image/plus.svg') }}" alt="no-icon">
        اضافة سؤال اخر
    </button>
    </div>
@endsection
@section('js')
    <!-- jQuery UI -->
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script>
        $(document).on('input', '.input-question', function() {
            var question = $(this).val();
            // البحث عن العنصر الصحيح
            var labelQuestion = $(this).closest('.group').find('.lable-question');
            // التأكد من العثور على العنصر
            if (labelQuestion.length) {
                labelQuestion.text(question);
            } else {
                console.log("Label not found");
            }
        });

        $(document).on('change', '.answer-type-select', function() {
            var answerType = $(this).val();
            var index = $(this).data('index');
            if (answerType == '2') {
                $(`.div-true-false[data-index=${index}]`).removeClass('d-none');
                $(`.div-multiple-answer[data-index=${index}]`).addClass('d-none');
            } else if (answerType == '1') {
                $(`.div-multiple-answer[data-index=${index}]`).removeClass('d-none');
                $(`.div-true-false[data-index=${index}]`).addClass('d-none');
            } else {
                $(`.div-multiple-answer[data-index=${index}]`).addClass('d-none');
                $(`.div-true-false[data-index=${index}]`).addClass('d-none');
            }
        });

        $(document).ready(function() {
            $('#submitForm').click(function() {
                $(this).prop('disabled', true).html(
                    `<i class="fa fa-spinner fa-spin"></i> انتظر قليلا..`);
                setTimeout(e => {
                    $('form').submit();
                }, 2000);
            });
        });
    </script>

    <script>
        // In your Javascript (external .js resource or <script> tag)
        $(document).ready(function() {
            $(".js-example-basic-single").select2({
                dropdownParent: $(".modal-content"),
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            // تفعيل السحب وإعادة الترتيب
            $("#forms-container").sortable({
                handle: ".drag-handle", // يجعل السحب ممكنًا فقط من خلال أيقونة معينة
                placeholder: "sortable-placeholder", // تأثير أثناء السحب
                axis: "y", // تقييد السحب عموديًا فقط
                update: function(event, ui) {
                    updateQuestionNumbers();
                }
            }).disableSelection();

            // عند إضافة سؤال جديد، نعيد تفعيل الخاصية على الحاوية
            $(document).on("click", "#duplicate-question", function() {
                var lastForm = $("#forms-container .group:last");
                var newForm = lastForm.clone();

                var lastIndex = $("#forms-container .group").length - 1;
                var newIndex = lastIndex + 1;

                newForm.attr("data-index", newIndex);
                newForm.find("[data-index]").each(function() {
                    $(this).attr("data-index", newIndex);
                });

                // تحديث أسماء الحقول بحيث تكون questions[newIndex][field]
                newForm.find("input, select, textarea").each(function() {
                    var name = $(this).attr("name");
                    if (name) {
                        var newName = name.replace(/\[\d+\]/, "[" + newIndex + "]");
                        $(this).attr("name", newName);
                    }
                    // تفريغ الحقول النصية فقط
                    if ($(this).is("input[type='text'], textarea")) {
                        $(this).val("");
                    }
                });

                // تحديث العناوين و الـ ID
                newForm.find(".lable-question").text("السؤال " + (newIndex + 1));
                newForm.find(".status-accept").text(newIndex + 1);

                // تحديث معرف collapse
                var oldCollapseId = newForm.find(".collapse").attr("id");
                var newCollapseId = `collapseExample${newIndex}`;
                newForm.find(".collapse").attr("id", newCollapseId);
                newForm.find("[data-bs-target]").attr("data-bs-target", `#${newCollapseId}`);
                newForm.find("[aria-controls]").attr("aria-controls", newCollapseId);

                // إضافة العنصر الجديد
                $("#forms-container").append(newForm);

                updateQuestionNumbers();
                $("#forms-container").sortable("refresh"); // تحديث sortable بعد إضافة عنصر جديد
            });

            // حذف السؤال وتحديث الترتيب
            $(document).on("click", ".delete-form", function() {
                $(this).closest(".group").remove();
                updateQuestionNumbers();
            });

            // تحديث ترتيب الأسئلة بعد أي تعديل
            function updateQuestionNumbers() {
                $("#forms-container .group").each(function(index) {
                    var newIndex = index; // الفهرس يبدأ من 0 كما في Laravel

                    $(this).attr("data-index", newIndex);
                    $(this).find("[data-index]").each(function() {
                        $(this).attr("data-index", newIndex);
                    });

                    // تحديث أسماء الحقول
                    $(this).find("input, select, textarea").each(function() {
                        var name = $(this).attr("name");
                        if (name) {
                            var newName = name.replace(/\[\d+\]/, "[" + newIndex + "]");
                            $(this).attr("name", newName);
                        }
                    });

                    // تحديث العنوان والرقم الظاهر
                    $(this).find(".status-accept").text(newIndex + 1);

                    // تحديث معرف collapse
                    var newCollapseId = `collapseExample${newIndex}`;
                    $(this).find(".collapse").attr("id", newCollapseId);
                    $(this).find("[data-bs-target]").attr("data-bs-target", `#${newCollapseId}`);
                    $(this).find("[aria-controls]").attr("aria-controls", newCollapseId);
                });
            }
        });
    </script>
@endsection
