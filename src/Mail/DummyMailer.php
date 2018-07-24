<?php

namespace Mochilo\Mail;

use Mochilo\Config;

class DummyMailer implements MailerInterface
{
    private $host;
    private $port;
    private $username;
    private $password;
    private $to = [];
    private $replyTo = [];
    private $cc = [];
    private $from = [];
    private $subject;
    private $body;

    public function setup(Config $config)
    {
        $this->host = $config->get('mail.host');
        $this->port = $config->get('mail.port');
        $this->username = $config->get('mail.username');
        $this->password = $config->get('mail.password');
        $this->setFrom($config->get('mail.address'), $config->get('mail.name'));
    }

    public function addTo(string $address, string $name = null)
    {
        $this->to[] = [
            'address' => $address,
            'name' => $name,
        ];
    }

    public function addReplyTo(string $address, string $name = null)
    {
        $this->replyTo[] = [
            'address' => $address,
            'name' => $name,
        ];
    }

    public function addCc(string $address, string $name = null)
    {
        $this->cc[] = [
            'address' => $address,
            'name' => $name,
        ];
    }

    public function setFrom(string $address, string $name = null)
    {
        $this->from = [
            'address' => $address,
            'name' => $name,
        ];
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

    }

    public function getErrorMessage():? string
    {
        return null;
    }
}
