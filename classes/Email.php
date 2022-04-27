<?php

namespace Classes;

       
        use PHPMailer\PHPMailer\PHPMailer;
        

    
class Email {

    public $email;
    public $nombre;
    public $token;
    
    public function __construct($email, $nombre, $token)
    {
        $this->email = $email;
        $this->nombre = $nombre;
        $this->token = $token;
    }

    public function enviarConfirmacion() {

    $mail = new PHPMailer();
    $mail->SMTPSecure = 'tls';
    $mail->Username = $_ENV['MAIL_USER'];
    $mail->Password = $_ENV['MAIL_PASS'];
    $mail->AddAddress($this->email,'uptask.com');
    $mail->FromName = "UpTask";
    $mail->Subject = "Confirmacion de Alta de Cuenta";
    $mail->Host = "smtp.live.com";
    $mail->Port = 587;
    $mail->IsSMTP();
    $mail->SMTPAuth = true;
    $mail->From = $mail->Username;
    $mail->isHTML(TRUE);
    $mail->CharSet = 'UTF-8';
    $contenido = '<html>';
    $contenido .= "<p><strong>Hola " . $this->nombre . "</strong> Has Creado tu cuenta en 
    UpTask, solo debes confirmarla en el siguiente enlace </P>";
    $contenido .= "<p>Presiona aquí: <a href='http://". $_SERVER["HTTP_HOST"] . "/confirmar?token=".$this->token."'>Confirmar Cuenta</a> </p>";
    $contenido .= "<p>Si tu no creaste esta cuenta, puedes ignorar este mensaje </p>";
    $contenido .= '</html>';
    $mail->Body = $contenido;
    $mail->send();
    }

    public function enviarInstrucciones() {
    
        $mail = new PHPMailer();
        $mail->SMTPSecure = 'tls';
        $mail->Username = $_ENV['MAIL_USER'];
        $mail->Password = $_ENV['MAIL_PASS'];
        $mail->AddAddress($this->email,'uptask.com');
        $mail->FromName = "UpTask";
        $mail->Subject = 'Reestablece tu Password';
        $mail->Host = "smtp.live.com";
        $mail->Port = 587;
        $mail->IsSMTP();
        $mail->SMTPAuth = true;
        $mail->From = $mail->Username;

        $mail->isHTML(TRUE);
        $mail->CharSet = 'UTF-8';
        $contenido = '<html>';
        $contenido .= "<p><strong>Hola " . $this->nombre . "</strong> Parece que has olvidado 
        tu Password, sigue el siguiente enlace para recuperarla </P>";
        $contenido .= "<p>Presiona aquí: <a href='http://". $_SERVER["HTTP_HOST"] . "/reestablecer?token=" . 
        $this->token . "'>Reestablecer Password</a></p>";
        $contenido .= "<p>Si tu no creaste esta cuenta, puedes ignorar este mensaje </p>";
        $contenido .= '</html>';
        $mail->Body = $contenido;
        $mail->send(); 

    }
}
