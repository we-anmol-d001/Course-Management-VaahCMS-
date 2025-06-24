<?php namespace VaahCms\Modules\Course\Observers;
use VaahCms\Modules\Course\Models\Student;
use WebReinvent\VaahCms\Libraries\VaahMail;
use App\Mail\StudentRegisteredMail;

class StudentObserver {
    public function created(Student $student)
{
    // dd("obsever fired");
    // dd($student->email);
    $student->load('courses');
        // $mail = new StudentRegisteredMail($student); 

        // $to = [$student->email];
        // $cc = [];
        // $bcc = [];
        // // dd($mail);

        // VaahMail::send($mail, $to); 
}


}
