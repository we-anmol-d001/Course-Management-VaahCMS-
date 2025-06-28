<?php namespace VaahCms\Modules\Course\Traits;

use Illuminate\Database\Eloquent\Model;
use VaahCms\Modules\Course\Mails\DeleteRecordMail;
use WebReinvent\VaahCms\Models\User;
use WebReinvent\VaahCms\Libraries\VaahMail;

trait SendEmailDeleteTrait {
    public static function recordDeleted($student)
    {
        $super=User::whereHas('roles',function($query){
            $query->where('name','Super Administrator');
        })->first();

        
    
        $mail = new DeleteRecordMail($student, $super->name); 
        $to = $super->email;
        VaahMail::addInQueue($mail, $to);   
    }


}
