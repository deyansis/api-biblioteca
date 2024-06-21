<?php
declare(strict_types=1);

namespace App\Services;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Mail_Service
{
    private $mailer;

    public function __construct()
    {
        $this->mailer = new PHPMailer(true);
        $this->setup();
    }

    private function setup()
{
    try {
        // Configuración del servidor SMTP
        $this->mailer->isSMTP();
        $this->mailer->Host = 'mail.efsystemas.net'; 
        $this->mailer->SMTPAuth = true;
        $this->mailer->Username = 'bibliotecaucv@efsystemas.net';
        $this->mailer->Password = '1997M@rzo'; // Debes asegurarte de usar la contraseña correcta del correo.
        
        // Configuración del cifrado y puerto
        $this->mailer->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; // Usar SMTPS en lugar de STARTTLS para el puerto 465.
        $this->mailer->Port = 465; // Puerto SMTP seguro.

        // Configuración del remitente
        $this->mailer->setFrom('bibliotecaucv@efsystemas.net', 'Centro de ayuda Biblioteca');
        
        // Permitir envío de correos en formato HTML
        $this->mailer->isHTML(true);
    } catch (Exception $e) {
        echo "Error al configurar el correo: {$this->mailer->ErrorInfo}";
    }
}

    public function sendMail($to, $subject, $body)
    {
        try {
            $this->mailer->addAddress($to);
            $this->mailer->Subject = $subject;
            $this->mailer->Body = $body;
            $this->mailer->send();
           
        } catch (Exception $e) {
            throw new Exception("Failed to send email: " . $e->getMessage(), $e->getCode(), $e);
        }
    }

}