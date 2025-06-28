<?php  namespace VaahCms\Modules\Course\Mails;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Database\Eloquent\Model;


class DeleteRecordMail extends Mailable {

    use Queueable, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $record;
    public $super_admin;
    public $isBulk = false;
    public function __construct( $record, $super_admin)
    {
        
        $this->record = $record;
        $this->super_admin = $super_admin;  
        $this->isBulk = is_iterable($record) && !($record instanceof Model );
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Record deleted form Database',
        );
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function build()
    {
       
        if($this->isBulk){
            return $this->view('course::emails.bulk-delete-records');
        }
        else {
            return $this->view('course::emails.deleted-record');
        }
    }

}
