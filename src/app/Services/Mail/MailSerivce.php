<?php

namespace App\Services\Invoice;

use App\Mail\UserActivationEmail;
use Illuminate\Support\Facades\Mail;
use App\Services\Mail\IMailService;

class MailService implements IMailService
{

    public function __construct() {}

    public function sendInvoice() {}

    public function sendResetPassword() {}

    public function checkValidMail(string $emailTo)
    {
        Mail::to($emailTo)->send(new UserActivationEmail('random otp'));
        //check
    }
}
