<?php

namespace APP\Module;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Mail
{
    private $mailer;
    private $from_mail = MAIL_FROM;
    private $from_name = MAIL_NAME;
    public $sample = null;

    public function __construct()
    {
        $this->mailer          = new PHPMailer();
        $this->mailer->CharSet = 'UTF-8';

        $this->mailer->isSMTP();
        $this->mailer->SMTPAuth  = true;
        $this->mailer->SMTPDebug = 0;

        $this->from_mail        = MAIL_LOGIN;
        $this->mailer->Host     = MAIL_HOST;
        $this->mailer->Port     = MAIL_PORT;
        $this->mailer->Username = MAIL_LOGIN;
        $this->mailer->Password = MAIL_PASSWORD;

        $this->mailer->SMTPOptions = array(
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            )
        );
    }

    public function send($to_mail, $to_name, $tema, $message, $attachment = false, $attachmentName = '') {

        $this->mailer->setFrom($this->from_mail, $this->from_name);
        $this->mailer->addAddress($to_mail, $to_name);
        $this->mailer->Subject = $tema;
        $this->mailer->msgHTML($message);

        if ($attachment) {
            $this->mailer->addStringAttachment($attachment, $attachmentName);
        }

        try {
            $result = $this->mailer->send();
        } catch (Exception $e) {
            return $e->getMessage();
        }
        return $result;
    }
}
