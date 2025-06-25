<?php namespace VaahCms\Modules\Course\Observers;
use VaahCms\Modules\Course\Models\Student;
use WebReinvent\VaahCms\Libraries\VaahMail;
use App\Mail\StudentRegisteredMail;

class StudentObserver {
    public function created(Student $student)
{
    
    $student->load('courses');
        
}


}
