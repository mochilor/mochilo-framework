<?php

namespace Mochilo\Mail;

use Mochilo\Config;

class NativeMailer implements MailerInterface
{
    private $to = [];
    private $replyTo = [];
    private $cc = [];
    private $from = [];
    private $subject;
    private $body;
    private $errors;

    public function setup(Config $config)
    {
        $this->setFrom($config->get('mail.address'));
    }

    public function addTo(string $address, string $name = null)
    {
        $this->to[] = $address;
    }

    public function addReplyTo(string $address, string $name = null)
    {
        $this->replyTo[] = $address;
    }

    public function addCc(string $address, string $name = null)
    {
        $this->cc[] = $address;
    }

    public function setFrom(string $address, string $name = null)
    {
        $this->from = $address;
    }

    public function setSubject(string $subject)
    {
        $this->subject = $subject;
    }

    public function setHtmlMessage(string $body)
    {
        $this->body = $body;
    }

    public function send()
    {
        $to = implode(", ", $this->to);
        $replyTo = implode(", ", $this->replyTo);
        $cc = implode(", ", $this->cc);

        $subject = $this->subject;
        $message = $this->body;
        $headers = [
            'MIME-Version: 1.0',
            'Content-type: text/html; charset=UTF-8',
            sprintf('From: %s', $this->from),
            sprintf('X-Mailer: PHP/%s', phpversion()),
        ];

        if (!empty($replyTo)) {
            $headers[] = sprintf('Reply-To: %s', $replyTo);
        }

        if (!empty($cc)) {
            $headers[] = sprintf('Cc: %s', $cc);
        }

        $headersString = implode("\r\n", $headers) . "\r\n";

        if (!mail($to, $subject, $message, $headersString)) {
            $this->errors = 'Error while sending email!';
        }
    }

    public function getErrorMessage():? string
    {
        return $this->errors;
    }
}
