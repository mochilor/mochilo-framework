<?php

namespace Mochilo\Mail;

use Mochilo\Config;
use PHPMailer\PHPMailer\PHPMailer;

class SMTPMailer implements MailerInterface
{
    /**
     * @var PHPMailer
     */
    private $mailer;

    /**
     * @var string
     */
    private $errorMessage;

    /**
     * Mailer constructor.
     *
     * @param PHPMailer $mailer
     */
    public function __construct(PHPMailer $mailer)
    {
        $this->mailer = $mailer;
    }

    public function setup(Config $config)
    {
        $this->mailer->IsSMTP();
        $this->mailer->SMTPAuth = true;
        $this->mailer->Host = $config->get('mail.host');
        $this->mailer->Port = $config->get('mail.port');
        $this->mailer->Username = $config->get('mail.username');
        $this->mailer->Password = $config->get('mail.password');
        $this->mailer->SMTPAuth = $config->get('mail.smtp_auth');
        $this->mailer->SMTPSecure = $config->get('mail.smtp_secure');
        $this->setFrom($config->get('mail.address'), $config->get('mail.name'));
    }

    public function addTo(string $address, string $name = null)
    {
        $this->mailer->AddAddress($address, $name);
    }

    public function addReplyTo(string $address, string $name = null)
    {
        $this->mailer->addReplyTo($address, $name);
    }

    public function addCc(string $address, string $name = null)
    {
        $this->mailer->addCC($address, $name);
    }

    public function setFrom(string $address, string $name = null)
    {
        try {
            $this->mailer->setFrom($address, $name);
        } catch (\Exception $e) {
            //
        }
    }

    public function setSubject(string $subject)
    {
        $this->mailer->Subject = $subject;
    }

    public function setHtmlMessage(string $body)
    {
        $this->mailer->msgHTML($body);
    }

    public function send()
    {
        try {
            $this->mailer->send();
        } catch (\Exception $e) {
            $this->errorMessage = $e->getMessage();
        }
    }

    public function getErrorMessage():? string
    {
        return $this->errorMessage;
    }
}
