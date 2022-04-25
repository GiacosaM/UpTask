<?php
namespace Controllers;

use Classes\Email;
use Model\Usuario;
use MVC\Router;

class LoginController {
    public static function login (Router $router) {
        $alertas=[];
           
            if($_SERVER['REQUEST_METHOD'] === 'POST') {
                
                $usuario = new Usuario($_POST);
                $alertas = $usuario->validarLogin();

                if (empty($alertas)){
                    //Verificar que el usuario exista
                    $usuario= Usuario::where('email',$usuario->email);
                    
                    if (!$usuario || !$usuario->confirmado) {
                        Usuario::setAlerta('error', 'El Usuario NO existe, o no esta confirmado');
                    } else {
                        // El usuario existe
                        if (password_verify($_POST['password'], $usuario->password)) {
                            
                            // Iniciar la sesion del Usuario
                            session_start();
                            $_SESSION['id'] = $usuario->id;
                            $_SESSION['nombre'] = $usuario->nombre;
                            $_SESSION['email'] = $usuario->email;
                            $_SESSION['login'] = true;

                            //Redireccionar 
                            header ('Location: /dashboard');

                        } else {
                            Usuario::setAlerta('error', 'Password Incorrecto');
                        }
                }
            }
        }

            $alertas= Usuario::getAlertas();
            //Render a la vista
            $router->render('auth/login',[
                'titulo'=>'Iniciar Sesion',
                'alertas'=>$alertas
                
            ]);
    
}

    public static function logout () {
        session_start();
        $_SESSION=[];
        header('Location: /');

    
    }

    public static function crear (Router $router) {
        $alertas = [];
        $usuario = new Usuario;
        
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuario->sincronizar($_POST);
            $alertas = $usuario->validarNuevaCuenta();

            if (empty($alertas)){
                $existeUruario = Usuario::where('email', $usuario->email);

                if ($existeUruario) {
                    Usuario::setAlerta('error', 'El Usuario ya esta registrado');
                    $alertas= Usuario::getAlertas();
            } else {
                // hashear el password
                $usuario->hashPassword();

                // Eliminar Password 2
                unset($usuario->password2);

                // Generar el Token
                $usuario->crearToken();
                
                // Crear un nuevo Usuario
                $resultado = $usuario->guardar();

                // Enviar e-mail
                $email = new Email($usuario->email, $usuario->nombre, $usuario->token);
                
                $email->enviarConfirmacion();

                if ($resultado) {
                    header ('Location:/mensaje');
                }
            }
            }
        }

        //Render a la vista
        $router->render('auth/crear',[
            'titulo'=>'Crea tu Cuenta en UpTask',
            'usuario'=> $usuario,
            'alertas'=> $alertas
        ]);    
        
    }

    public static function olvide (Router $router) {
            $alertas = [];
            if($_SERVER['REQUEST_METHOD'] === 'POST') {
                $usuario = new Usuario($_POST);
                $alertas = $usuario->validarEmail();
                
                if (empty($alertas)){
                    //Buscar el usuario
                    $usuario= Usuario::where('email', $usuario->email);
                    if($usuario && $usuario->confirmado === "1") {

                        //Generar un Nvo. token 
                        $usuario->crearToken();
                        unset($usuario->password2); // Elimina la variable password2

                        // Actualizar el usuario 
                        $usuario->guardar();

                        // Enviar e-mail
                        $email = new Email($usuario->email, $usuario->nombre, $usuario->token);
                
                        $email->enviarInstrucciones();

                

                        //Imprimir Alerta
                        Usuario::setAlerta('exito', "Hemos enviado las instrucciones a tu email");
                        
                    } else{
                        Usuario::setAlerta('error', 'El Usuario no existe o no esta confirmado');
                        
                    }
                }
            }

            $alertas = Usuario::getAlertas();
            //render a la vista
            $router->render('auth/olvide',[
                'titulo'=>'Olvidaste tu Password?',
                'alertas'=>$alertas
            ]);
    }
    public static function reestablecer (Router $router) {
            $alertas = [];
            $mostrar = true;
            $token =s($_GET['token']);
            if(!$token) header('Location: /');

            //Identificar el ususario con este token 
            $usuario = Usuario::where('token', $token);
            if(empty($usuario)) {
                Usuario::setAlerta('error', 'Token No valido');
                $mostrar= false;
            }
            
                    
            if($_SERVER['REQUEST_METHOD'] === 'POST') {
                // Añadir el nvo. Password
                $usuario->sincronizar($_POST);

                // Validar el password
                $alertas= $usuario->validarPassword();
                if (empty($alertas)) {
                    //Hashear el password
                    $usuario->hashPassword();
                    
                    //Eliminar el token

                    $usuario->token=null;

                    //Guardar el usuareio en la BD
                    $resultado = $usuario->guardar();

                    //Redireccionar
                    if ($resultado){
                        header('Location: /');
                    }

                    
                }
            }

            $alertas = Usuario::getAlertas();
            //render a la vista
            $router->render('auth/reestablecer',[
                'titulo'=>'Reestablece tu Password?',
                'alertas' =>$alertas,
                'mostrar'=> $mostrar
            ]);
    }

    public static function mensaje (Router $router) {
         //render a la vista
         $router->render('auth/mensaje',[
            'titulo'=>'Cuenta Creada Exitosamente'
        ]);
    
    }

    public static function confirmar (Router $router) {
        $token = s($_GET['token']);
        
        if (!$token) header ('location: /');

        //Encontrar al Usuario con este Token
        $usuario = Usuario::where('token', $token);
        
        if (empty($usuario)) {
            //No se encontro usuario con ese Token
            Usuario::setAlerta('error', 'Token No Valido');
        } else {
                //confirmar la cuenta
                $usuario->confirmado =1;
                $usuario->token = null;
                unset($usuario->password2);
                //Guardar en la BD
                $usuario->guardar();    
                     
                Usuario::setAlerta('exito', 'Cuenta Comprobada Correctamente');   
        }


        $alertas= Usuario::getAlertas();

        //render a la vista
        $router->render('auth/confirmar',[
           'titulo'=>'Cuenta Confirmada',
           'alertas'=> $alertas
       ]);
   
   }

}