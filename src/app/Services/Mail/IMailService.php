<?php

namespace App\Services\Mail;


interface IMailService
{

    public function sendInvoice();

    public function sendResetPassword();

    public function checkValidMail(string $emailTo);
}
