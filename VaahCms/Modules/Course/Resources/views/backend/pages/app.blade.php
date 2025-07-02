@extends(((version_compare(config('vaahcms.version'), '2.0.0', '<' )) ? 'vaahcms::backend.vaahone.layouts.backend' : 'vaahcms::backend.vaahtwo.layouts.backend' ))


@section('vaahcms_extend_backend_css')

{{--        <link href="{{vh_module_assets_url("Course", "build/index.css")}}" rel="stylesheet" media="screen">--}}

@endsection


@section('vaahcms_extend_backend_js')

    @if(env('MODULE_COURSE_ENV') == 'develop')
        <script type="module" src="http://localhost:8025/main.js"></script>
    @else
        <script type="module" src="{{vh_module_assets_url("Course", "build/index.js")}}"></script>
    @endif

@endsection

@section('content')

    <div class="primevue">
        <div id="appCourse">
       

        </div>
    </div>

@endsection
