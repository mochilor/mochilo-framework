<?php

namespace Mochilo\Mail;

use Mochilo\Config;
use PHPMailer\PHPMailer\OAuth;
use PHPMailer\PHPMailer\PHPMailer;
use League\OAuth2\Client\Provider\Google;

class OAUTHMailer implements MailerInterface
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
        $this->mailer->isSMTP();
        $this->mailer->SMTPAuth = true;
        $this->mailer->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $this->mailer->AuthType = 'XOAUTH2';

        $provider = new Google(
            [
                'clientId' => $config->get('mail.client_id'),
                'clientSecret' => $config->get('mail.client_secret'),
            ]
        );
        $this->mailer->setOAuth(
            new OAuth(
                [
                    'provider' => $provider,
                    'clientId' => $config->get('mail.client_id'),
                    'clientSecret' => $config->get('mail.client_secret'),
                    'refreshToken' => $config->get('mail.refresh_token'),
                    'userName' => $config->get('mail.username'),
                ]
            )
        );

        $this->mailer->Host = $config->get('mail.host');
        $this->mailer->Port = $config->get('mail.port'); // 465
        $this->mailer->CharSet = PHPMailer::CHARSET_UTF8;
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
        $this->mailer->send();
    }

    public function getErrorMessage():? string
    {
        return $this->errorMessage;
    }
}
