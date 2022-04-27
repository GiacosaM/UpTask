<?php 

namespace Controllers;

use Model\proyecto;
use Model\Tarea;

class TareaController {
    public static function index () {
            session_start();
            
            $proyectoid = $_GET['id'];
            

            
            
            if (!$proyectoid) header('Location:/dashboard');

            $proyecto = proyecto::where('url', $proyectoid);

            if(!$proyecto || $proyecto->propietarioid !== $_SESSION['id']) header 
            ('Location: /404');

            $tareas = Tarea::belongsTo('proyectoid', $proyecto->id);
            echo json_encode(['tareas' => $tareas]);
    }

    public static function crear () {
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            session_start();
            $proyectoid = $_POST['proyectoid'];
            $proyecto = proyecto::where('url', $proyectoid );

        if (!$proyecto || $proyecto->propietarioid !== $_SESSION['id']) {
            $respuesta = [
                'tipo' => 'error',
                'mensaje' => 'Hubo un Error al agregar la tarea'
            ];
            echo json_encode($respuesta);
            return;
        } 
        // >Todo Bien, instanciar y crear la tarea
        $tarea = new Tarea($_POST);
        $tarea->proyectoid = $proyecto->id;
        $resultado = $tarea->guardar();

        $respuesta = [
            'tipo' => 'exito',
            'id' =>$resultado['id'],
            'mensaje' => 'Tarea Creada Correctamente',
            'proyectoid' => $proyecto->id
        ];
        echo json_encode($respuesta);



        }
    }
    public static function actualizar () {
        if($_SERVER['REQUEST_METHOD'] === 'POST'){

            //Validar que el proyecto exista
            $proyecto = proyecto::where('url', $_POST['proyectoid']);

            session_start();

            if (!$proyecto || $proyecto->propietarioid !== $_SESSION['id']) {
                $respuesta = [
                    'tipo' => 'error',
                    'mensaje' => 'Hubo un Error al actualizar la tarea'
                ];
                echo json_encode($respuesta);
                return;
            } 
            $tarea = new Tarea($_POST);
            $tarea->proyectoid = $proyecto->id;
            $resultado = $tarea->guardar();

            if ($resultado) {
                    $respuesta = [
                    'tipo' => 'exito',
                    'id' =>$tarea->id,
                    'proyectoid' => $proyecto->id,
                    'mensaje' => 'Actualizado Correctamente'
                ];
                echo json_encode(['respuesta' => $respuesta]);
            }    
        
        };

    }
    public static function eliminar () {
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
             //Validar que el proyecto exista
            $proyecto = proyecto::where('url', $_POST['proyectoid']);

            session_start();

            if (!$proyecto || $proyecto->propietarioid !== $_SESSION['id']) {
                $respuesta = [
                    'tipo' => 'error',
                    'mensaje' => 'Hubo un Error al actualizar la tarea'
                ];
                echo json_encode($respuesta);
                return;
            } 
            $tarea = new Tarea($_POST);
            $resultado = $tarea->eliminar();

            $resultado = [
                'resultado'=> $resultado,
                'mensaje'=> 'Eliminado Correctamente',
                'tipo'=>'exito'
            ];
            echo json_encode( $resultado);
        }
        
    }
}