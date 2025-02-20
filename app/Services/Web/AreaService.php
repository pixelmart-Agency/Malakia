<?php

namespace App\Services\Web;

use App\Models\Area as ObjdModel;
use App\Models\Axis;
use App\Models\QuestionAnswer;
use App\Models\User;
use App\Services\BaseService;
use Illuminate\Support\Facades\Validator;

class AreaService extends BaseService
{
    //    protected string $folder = 'admin/admin';
    //    protected string $route = 'adminHome';

    public function __construct(
        ObjdModel $objModel,
        protected Axis $axis,
        protected User $user,
        protected QuestionAnswer $questionAnswer,
    ) {
        parent::__construct($objModel);
    }

    public function index($request)
    {
        $axisId = $request->query('axis_id'); // Get the passed axis_id
        $axis = $this->axis->with('axisQuestions')->find($axisId);
        $teams = $this->user->whereHas('roles', function ($query) use ($axisId) {
            $query->where('name', '!=', 'مشرف');
        })->get();

        $leaders = $this->user->whereHas('roles', function ($query) use ($axisId) {
            $query->where('name', '=', 'مشرف');
        })->get();
        return view('web.area.index', [
            'axis' => $axis,
            'answers' => $this->questionAnswer->where('axis_question_id', $axisId)->get(),
            'axisId' => $axisId,
            'teams' => $teams,
            'leaders' => $leaders
        ]);
    }

    public function store($request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'leader_id' => 'required',
                'axis_id' => 'required',
                'team_ids' => 'required|array|min:1',
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'error' => $validator->errors()->first(),
                    'status' => false,
                ], 500);
            }

            // dd($request->all());

            $newArea = new $this->model();
            $newArea->name = $request->name;
            $newArea->axis_id = $request->axis_id;
            $newArea->save();

            if ($newArea) {
                foreach ($request->team_ids as $team) {
                    $newArea->team()->create([
                        'user_id' => $team,
                        'type' => '0',
                    ]);
                }

                $newArea->team()->create([
                    'user_id' => $request->leader_id,
                    'type' => '1',
                ]);

                foreach ($request->coordinates as $coordinate){
//dd($coordinate['north']);
                    $newArea->location()->create([
                        'north' => $coordinate['north'],
                        'south' => $coordinate['south'],
                        'west' => $coordinate['west'],
                        'east' => $coordinate['east'],
                    ]);
                }


                return response()->json([
                    'msg' => 'تم اضافة المنطقة بنجاح',
                    'status' => true,
                ], 200);
            }

            return response()->json([
                'msg' => 'حدث خطأ',
                'status' => false,
            ], 500);
        } catch (\Exception $exception) {
            return response()->json([
                'msg' => $exception->getMessage(),
                'status' => false,
            ], 500);
        }
    }
}
