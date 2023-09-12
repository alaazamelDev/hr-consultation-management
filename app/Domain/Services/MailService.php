<?php

namespace App\Domain\Services;

use App\Mail\SingUp;
use Illuminate\Support\Facades\Mail;

class MailService
{
    public function sendMail($first_name,$username , $password): void
    {
        Mail::to('fake_email@gmail.com')->send(new SingUp($first_name,$username,$password));
    }
}
