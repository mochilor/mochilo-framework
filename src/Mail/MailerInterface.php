<?php

namespace Mochilo\Mail;

use Mochilo\Config;

interface MailerInterface
{
    public function setup(Config $config);
    public function addTo(string $address, string $name = null);
    public function addCc(string $address, string $name = null);
    public function setFrom(string $address, string $name = null);
    public function setSubject(string $subject);
    public function setHtmlMessage(string $body);
    public function send();
    public function getErrorMessage():? string;
}