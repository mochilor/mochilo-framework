<?php

namespace Mochilo\Mail;

use Mochilo\Config;
use Mochilo\Data;
use Twig_Environment;

class BaseMail
{
    /**
     * @var MailerInterface
     */
    protected $mailer;

    /**
     * @var Twig_Environment
     */
    protected $twig;

    /**
     * @var Config
     */
    protected $config;

    /**
     * @var Data
     */
    protected $data;

    /**
     * BaseMail constructor.
     *
     * @param MailerInterface $mailer
     * @param Twig_Environment $twig
     * @param Config $config
     * @param Data $data
     */
    public function __construct(MailerInterface $mailer, Twig_Environment $twig, Config $config, Data $data)
    {
        $this->twig = $twig;
        $this->config = $config;
        $this->data = $data;
        $this->setMailer($mailer);
    }

    /**
     * @param MailerInterface $mailer
     */
    protected function setMailer(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
        $this->mailer->setup($this->config);
    }

    /**
     * @return null|string
     */
    public function getError():? string
    {
        return $this->mailer->getErrorMessage();
    }
}
