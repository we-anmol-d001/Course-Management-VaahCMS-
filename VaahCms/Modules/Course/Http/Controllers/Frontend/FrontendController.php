<?php namespace VaahCms\Modules\Course\Http\Controllers\Frontend;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

class FrontendController extends Controller
{


    public function __construct()
    {

    }

    public function index()
    {
        return 'FrontendController - Course';
    }

}