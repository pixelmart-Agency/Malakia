<?php

namespace App\Services\Web;

use App\Models\Admin as ObjModel;

use App\Services\BaseService;

class HomeService extends BaseService
{
//    protected string $folder = 'admin/admin';
//    protected string $route = 'adminHome';

    public function __construct(ObjModel $objModel)
    {
        parent::__construct($objModel);
    }

    public function index()
    {
        return view('web.home.index');
    }


}
