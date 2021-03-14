<?php


namespace Core;

class Mailer
{
    public const ADMIN_EMAIL = 'admin@titrepro.local';

    private array $headers = ['Content-type: text/html; charset=utf-8'];
    private string $subject;
    private string $to;
    private string $from = self::ADMIN_EMAIL;
    private string $content;

    /**
     * Génère le contenu de l'email
     *
     * @param string      $templateName Nom du template à charger
     * @param object|null $obj          Objet contenant les données à afficher dans l'email
     *
     * @return $this
     * @throws \Exception
     */
    private function generateMailContent(string $templateName, ?object $obj): self
    {
        $templatePath = APP_ROOT . 'App/Views/MailTemplate/' . $templateName . 'Template.php';
        if (is_readable($templatePath))
        {
            ob_start();
            require $templatePath;
            $this->content = ob_get_clean();
        }
        else
        {
            throw new \Exception('Le fichier [' . $templatePath . '] n\'est pas lisible.');
        }
        return $this;
    }

    /**
     * Génère le contenu de l'email et envoi l'email
     *
     * @param string      $templateName Nom du template à charger
     * @param object|null $obj          Object contenant les données à afficher dans l'email
     *
     * @return bool Si l'email est envoyé avec succès true, si non false.
     * @throws \Exception
     */
    public function sendMail(string $templateName, ?object $obj): bool
    {
        $this->generateMailContent($templateName, $obj);
        return mail($this->getTo(), $this->getSubject(), $this->content, $this->getHeadersString());
    }

    /**
     * @return string
     */
    public function getTo(): string
    {
        return $this->to;
    }

    /**
     * @param string $to
     *
     * @return Mailer
     */
    public function setTo(string $to): Mailer
    {
        $this->to = $to;
        return $this;
    }

    /**
     * @return string
     */
    public function getSubject(): string
    {
        return $this->subject;
    }

    /**
     * @param string $subject
     *
     * @return Mailer
     */
    public function setSubject(string $subject): Mailer
    {
        $this->subject = $subject;
        return $this;
    }

    /**
     * @return string
     */
    public function getHeadersString(): string
    {
        return implode('\n\r', $this->headers);
    }

    /**
     * @return array
     */
    public function getHeadersArray(): array
    {
        return $this->headers;
    }

    /**
     * @param string $header
     *
     * @return Mailer
     */
    public function setHeaders(string $header): Mailer
    {
        $this->headers[] = $header;
        return $this;
    }

    /**
     * @return string
     */
    public function getFrom(): string
    {
        return $this->from;
    }

    /**
     * @param string $from
     *
     * @return Mailer
     */
    public function setFrom(string $from): Mailer
    {
        $this->from = $from;
        return $this;
    }
}