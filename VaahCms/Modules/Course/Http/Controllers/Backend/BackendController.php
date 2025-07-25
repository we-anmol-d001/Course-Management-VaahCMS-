<?php namespace VaahCms\Modules\Course\Http\Controllers\Backend;

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

class BackendController extends Controller
{


    public function __construct()
    {

    }

    public function index(Request $request)
    {


        /**
         * Check user has permission else redirect
         */
        /*
        $permission_slug = 'has-access-course';
        if(!\Auth::user()->hasPermission($permission_slug))
        {
            $error_message = trans("vaahcms::messages.permission_denied");
            if(env('APP_DEBUG'))
            {
                $error_message .= ' Permission Slug: '.$permission_slug;
            }
            $response['errors'][] = $error_message;
            return redirect('vh.backend')->withErrors($response['errors']);
        }
        */

        
        return view('course::backend.pages.app');
        


    }

    public function getAssets(Request $request)
    {

        /**
         * Check user has permission else return permission denied error
         */

        /*
        $permission_slug = 'has-access-course';
        if(!\Auth::user()->hasPermission($permission_slug))
        {
            $response['success'] = false;
            $error_message = trans("vaahcms::messages.permission_denied");
            if(env('APP_DEBUG'))
            {
                $error_message .= ' Permission Slug: '.$permission_slug;
            }
            $response['errors'][] = $error_message;
            return vh_response($response);
        }
        */

        $data=[];

        $steps = array();

//        $steps[] = $this->isSetupComplete();

        $data['module'] = [
            'name' => config('course.name'),
            'version' => config('settings.global.course_version')??config('course.version'),
            'is_dev' => config('course.is_dev'),
            'setup' => [
                'steps' => $steps
            ]
        ];

        $v_version = config('vaahcms.version');

        if(env('VAAHCMS_VERSION')){
            $v_version = env('VAAHCMS_VERSION');
        }

        $data['versions'] = [
            'laravel_version' => Application::VERSION,
            'php_version' => PHP_VERSION,
            'vaahcms_version' => $v_version,
            'app_version' => config('app.version','0.0.1'),
        ];

        $data['server'] = [
            'host' => $request->getHost(),
            'current_year' => \Carbon::now()->format('Y'),
            'current_date' => \Carbon::now()->format('Y-m-d'),
            'current_time' => \Carbon::now()->format('H:i:s'),
            'current_date_time' => \Carbon::now()->format('Y-m-d H:i:s'),
            'http' => 'http://',
        ];

        $data['vaahcms'] = [
            'name' => config('vaahcms.app_name'),
            'slug' => config('vaahcms.app_slug'),
            'version' => $v_version,
            'website' => config('vaahcms.website'),
            'docs' => config('vaahcms.documentation'),
        ];

        $data['timezone'] = env("APP_TIMEZONE");
        $data['server_date_time'] = \Carbon::now();

        $response['success'] = true;
        $response['data'] = $data;

        return vh_response($response);

    }


    //-------------------------------------------------
    public function isSetupComplete(){

        $response['is_complete'] = false;
        $response['message'] = "Your module is successfully setup.";

        return $response;

    }

}
