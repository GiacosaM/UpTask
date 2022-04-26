<?php
namespace Controllers;

use MVC\Router;
use Model\usuario;
use Model\proyecto;


class DashboardController {
    public static function index(Router $router) {
        session_start();
        isAuth(); // Verifico que este autenticado. Protejo la vista
        $id =$_SESSION['id'];
        $proyectos = proyecto::belongsTo('propietarioid', $id);
        
        
        $router->render('dashboard/index', [
            'titulo'=>'Proyectos',
            'proyectos'=>$proyectos
        ]);
    }

    public static function crear_proyecto (Router $router) {
        session_start();
        isAuth(); // Verifico que este autenticado. Protejo la vista
        $alertas = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST'){
            $proyecto = new proyecto($_POST);
        
            // Validacion 
            $alertas= $proyecto->validarProyecto();

            if (empty($aleras)) {
                //Generar una URL Unica
                $hash = md5(uniqid());
                $proyecto->url =$hash;


                // Almacenar el creador del proyecto
                $proyecto->propietarioid = $_SESSION['id'];
                //Guardar el Proyecto
                
                $proyecto->guardar();
                

                // Redireccionar+
                header('Location: /proyecto?id=' . $proyecto->url);

            }

        }


        
        $router->render('dashboard/crear-proyecto', [
            'alertas'=> $alertas,
            'titulo'=>'Crear Proyecto'
            
        ]);

    }

    public static function proyecto (Router $router) {
        session_start();
        isAuth();
        $token = $_GET['id'];
        if (!$token) header('Location: /dashboard');

        //Revisar que la persona que visita el pryecto es quien lo creo
        $proyecto = proyecto::where('url', $token);
        if ($proyecto->propietarioid !== $_SESSION['id']){
            header('Location: /dashboard');
        }
        
        $router->render('dashboard/proyecto', [
            'titulo'=>$proyecto->proyecto
        ]);

    }
    public static function perfil (Router $router) {
        session_start();
        isAuth(); // Verifico que este autenticado. Protejo la vista
        $alertas=[];
        
        $usuario = usuario::find($_SESSION['id']);
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST'){

            $usuario->sincronizar($_POST);
            
            $alertas= $usuario->validar_perfil();

            if (empty($alertas)){

                $existeUsuario = usuario::where('email', $usuario->email);
                if ($existeUsuario && $existeUsuario->id !== $usuario->id){
                    //mensaje de error
                    usuario::setAlerta('error', 'Email no valido. Cuenta ya registrada');
                    $alertas =$usuario->getAlertas();   

                } else {
                // Guardar el usuario
                $usuario->guardar();

                usuario::setAlerta('exito', 'Guardado Correctamente');
                $alertas =$usuario->getAlertas();

                //Actaulizo el nombre de usuario en la sesion
                $_SESSION['nombre'] = $usuario->nombre;
                }

                
            }
    
        }
        
        
        $router->render('dashboard/perfil', [
            'titulo'=>'Perfil',
            'usuario'=>$usuario,
            'alertas'=>$alertas
            
        ]);

    }

    public static function cambiar_password (Router $router) {
        session_start();
        isAuth();
        $alertas = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST'){
            $usuario = usuario::find($_SESSION['id']);

            //Sincornizar con los datpos del usuario
            $usuario->sincronizar($_POST);
            $alertas = $usuario->nuevo_password();
            
            if (empty($alertas)){
                $resultado = $usuario->comprobar_password();
                if ($resultado){
                    
                    $usuario->password = $usuario->password_nuevo;
                    // Eliminar prop. no necesarias
                    unset($usuario->password_actual);
                    unset($usuario->password_nuevo);
                    //Hashera nuevo password
                    $usuario->hashPassword();

                    $resultado = $usuario->guardar();

                    if ($resultado) {
                        usuario::setAlerta('exito', 'Password modificada correctamente');
                    $alertas = $usuario->getalertas();
                    }

                    //asignar el nuevo password


                } else {
                    usuario::setAlerta('error', 'Password Incorrecto');
                    $alertas = $usuario->getalertas();
                }
            }
        }

        $router->render('dashboard/cambiar-password', [
            'titulo'=>'Cambiar Password',
            'alertas'=>$alertas
            
        ]);
    }

}