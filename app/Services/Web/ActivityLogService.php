<?php

namespace App\Services\Web;

use App\Models\Admin as ObjModel;

use App\Services\BaseService;
use Spatie\Activitylog\Models\Activity;

class ActivityLogService extends BaseService
{
//    protected string $folder = 'admin/admin';
//    protected string $route = 'adminHome';

    public function __construct(ObjModel $objModel)
    {
        parent::__construct($objModel);
    }

    public function index()
    {
//        dd(Activity::all());
        return view('web.activity_log.index');
    }


}
