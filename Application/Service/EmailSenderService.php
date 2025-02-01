<?php
namespace General\EmailSending\Application\Service;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class EmailSenderService
{
    private static string $smtpUser;
    private static string $smtpPassword;

    public static function sendEmail($subject, $name, $email, $message, $servico)
    {
        EmailSenderService::$smtpUser = $_ENV['SMTP_USER'];
        EmailSenderService::$smtpPassword = $_ENV['SMTP_PASS'];

        $mail = new PHPMailer(true);

        $mail->isSMTP();
        $mail->Host = 'smtp.hostinger.com';
        $mail->SMTPAuth = true;
        $mail->Username = EmailSenderService::$smtpUser;
        $mail->Password = EmailSenderService::$smtpPassword;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port = 465;

        $mail->CharSet = 'UTF-8';

        $mail->setFrom(EmailSenderService::$smtpUser, 'LucasTest');
        $mail->addAddress(EmailSenderService::$smtpUser, 'LucasTesteDest');

        $htmlContent = file_get_contents(__DIR__ . '/templateEmail.html');

        $htmlContent = str_replace('{name}', $name, $htmlContent);
        $htmlContent = str_replace('{email}', $email, $htmlContent);
        $htmlContent = str_replace('{message}', $message, $htmlContent);
        $htmlContent = str_replace('{servico}', $servico, $htmlContent);

        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->msgHTML($htmlContent, __DIR__);

        $altBody = "Nome do contato: $name\n";
        $altBody .= "E-mail do contato: $email\n";
        $altBody .= "Mensagem enviada pelo contato $name pelo serviço $servico:\n";
        $altBody .= "$message\n";

        $mail->AltBody = $altBody;

        try {
            if (!$mail->send()) {
                throw new Exception('Falha ao enviar o e-mail: ' . $mail->ErrorInfo);
            }
        } catch (Exception $e) {
            error_log('EmailSenderService - ' . $e->getMessage() . "\n", 3, './error.log');
            throw $e;
        }
    }
}
