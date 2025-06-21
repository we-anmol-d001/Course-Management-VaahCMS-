<?php

return [
    "name"=> "Course",
    "title"=> "Course Management",
    "slug"=> "course",
    "thumbnail"=> "https://img.site/p/300/160",
    "is_dev" => env('MODULE_COURSE_ENV')?true:false,
    "excerpt"=> "Course Management between student,course and teachers table",
    "description"=> "Course Management between student,course and teachers table",
    "download_link"=> "",
    "author_name"=> "vaah",
    "author_website"=> "https://vaah.dev",
    "version"=> "0.0.1",
    "is_migratable"=> true,
    "is_sample_data_available"=> true,
    "db_table_prefix"=> "vh_course_",
    "providers"=> [
        "\\VaahCms\\Modules\\Course\\Providers\\CourseServiceProvider"
    ],
    "aside-menu-order"=> null
];
