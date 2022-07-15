<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use DB;
use Session;
use Mail;
use App\Http\Requests;
use Illiminate\support\Facades\Redirect;
session_start();

class mailController extends Controller
{
    public function sendMail(){
        $name = "Namaeb de Creative";
        Mail::send('pages.send-mail',compact('name'), function($mail) use ($name){
            $mail->subject('Checking order from PRO.N');
            $mail->to('leethanhlong2210@gmail.com', $name);
        });

        // $to_name = "Le Thanh Long";
        // $to_mail = "leethanhlong2210@gmal.com";

        // $data = array("name"=>"Mail from PRO.N","body"=>"Mail checking order");
        // Mail::send('pages.send-mail',$data,function($message) use ($to_name,$to_mail){
        //     $message->to($to_mail)->subject('test');
        //     $message->to($to_mail,$to_name);
        // });
        // return redirect('')->with('message','');
    }
}
