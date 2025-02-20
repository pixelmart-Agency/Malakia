<?php

namespace App\Services\Web;

use App\Models\Axis as ObjModel;

use App\Models\AxisQuestion;
use App\Models\QuestionAnswer;
use App\Services\BaseService;

class AxesManagementService extends BaseService
{
    //    protected string $folder = 'admin/admin';
    //    protected string $route = 'adminHome';

    public function __construct(
        ObjModel $objModel,
        protected AxisQuestion $axisQuestion,
        protected QuestionAnswer $questionAnswer,
    ) {
        parent::__construct($objModel);
    }

    public function index()
    {
        return view('web.axes_management.index');
    }

    public function create()
    {
        return view('web.axes_management.create');
    }

    public function store($request)
    {
//        try {

            $questions = $request->input('questions');
            $axis = new $this->model();
            $axis->name = $request->name;
            $axis->save();

            foreach ($questions as $index => $question) {
                $newQuestion = new $this->axisQuestion();
                $newQuestion->question = $question['name'];
                $newQuestion->axis_id = $axis->id;
                $newQuestion->answer_type = $question['answer_type'] == '0' ? '0' : $question['answer_type'];
                $newQuestion->false_parent_id = isset($question['false_parent_id']) ? $question['false_parent_id'] : null;
                $newQuestion->true_parent_id = isset($question['true_parent_id']) ? $question['true_parent_id'] : null;
                $newQuestion->order_number = $index + 1;
                if (isset($question['require_file'])) {
                    $newQuestion->require_file = $question['require_file'] == null ? '0' : '1';
                }
                $newQuestion->save();

                if ($newQuestion->answer_type == '1') {
                    $newQuestion->answers()->create([
                        'answer' => $question['answer1'],
                    ]);
                    $newQuestion->answers()->create([
                        'answer' => $question['answer2'],
                    ]);
                    $newQuestion->answers()->create([
                        'answer' => $question['answer3'],
                    ]);
                    $newQuestion->answers()->create([
                        'answer' => $question['answer4'],
                    ]);
                } elseif ($newQuestion->answer_type == '2') {
                    $newQuestion->answers()->create([
                        'answer' => 'نعم',
                    ]);
                    $newQuestion->answers()->create([
                        'answer' => 'لا',
                    ]);
                }
            }
            return redirect()->route('axesManagement')->with('success', 'تم الحفظ بنجاح');
//        } catch (\Exception $e) {
//            return redirect()->route('axesManagement')->with('error', 'هناك خطا ما');
//        }
    }
}
