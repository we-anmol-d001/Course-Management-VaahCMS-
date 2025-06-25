<?php  namespace VaahCms\Modules\Course\Mails;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Content;
use VaahCms\Modules\Course\Models\Student;

class StudentRegisteredMail extends Mailable {

    use Queueable, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $student;
    public function __construct(Student $student)
    {
        $this->student = $student;
        
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Student Registered Mail',
        );
    }

   

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function build()
    {
        return $this->view('course::emails.student-registered');
    }

}
