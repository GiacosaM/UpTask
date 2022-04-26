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
        $mail->isSMTP();
        $mail->Host = 'smtp.mailtrap.io';
        $mail->SMTPAuth = true;
        $mail->Port = 2525;
        $mail->Username = '25281089791d5d';
        $mail->Password = 'e967dc1bfdaeb9';

        $mail->setFrom('cuentas@uptask.com');
        $mail->addAddress('cuentas@uptask.com', 'uptask.com');
        $mail->Subject = 'Confirma tu Cuenta';

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
        $mail->isSMTP();
        $mail->Host = 'smtp.mailtrap.io';
        $mail->SMTPAuth = true;
        $mail->Port = 2525;
        $mail->Username = '25281089791d5d';
        $mail->Password = 'e967dc1bfdaeb9';

        $mail->setFrom('cuentas@uptask.com');
        $mail->addAddress('cuentas@uptask.com', 'uptask.com');
        $mail->Subject = 'Reestablece tu Password';

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
