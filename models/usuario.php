<?php
namespace Model;
class Usuario extends ActiveRecord {
    protected static $tabla = 'usuarios';
    protected static $columnasDB = ['id','nombre', 'email', 'password', 'token', 'confirmado'];

    public function __construct ($args = []) {
        $this->id = $args['id'] ?? null;
        $this->nombre = $args['nombre'] ?? '';
        $this->email = $args['email'] ?? '';
        $this->password = $args['password'] ?? '';
        $this->password2 = $args['password2'] ?? '';
        $this->password_actual = $args['password_actual'] ?? '';
        $this->password_nuevo = $args['password_nuevo'] ?? '';
        $this->token = $args['token'] ?? '';
        $this->confirmado = $args['confirmado'] ?? 0;
    }

    // Validar el Login de ususario
    public function validarLogin() {
        if(!$this->email) {
            self::$alertas['error'] [] = "El email del Usuario es Obligatorio";
        }
        if(!$this->password) {
            self::$alertas['error'] [] = "El password no puede ir vacio";
        }
        if (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            self::$alertas['error'][] = 'El Email No es valido';
        }
        return self::$alertas;

    }

    //Validacion Para cuentas nuevas
    public function validarNuevaCuenta() {
        if(!$this->nombre) {
            self::$alertas['error'] [] = "El Nombre del Usuario es Obligatorio";
        }
        if(!$this->email) {
            self::$alertas['error'] [] = "El email del Usuario es Obligatorio";
        }
        if(!$this->password) {
            self::$alertas['error'] [] = "El password no puede ir vacio";
        }
        if(strlen($this->password) < 6) {
            self::$alertas['error'] [] = "El password debe contener al menos 6 caracteres";
        }
        if($this->password !== $this->password2 ) {
            self::$alertas['error'] [] = "Los password son diferentes";
        }
        return self::$alertas;
    }

    public function nuevo_Password () : array {
        if(!$this->password_actual) {
            self::$alertas['error'] [] = "El password actual no puede ir vacio";
        }
        if(!$this->password_nuevo) {
            self::$alertas['error'] [] = "El password nuevo no puede ir vacio";
        }
        if(strlen($this->password_nuevo) < 6) {
            self::$alertas['error'] [] = "El password debe contener al menos 6 caracteres";
        }
        return self::$alertas;

    }

    //Valida un email 
        public function validarEmail(){
            if (!$this->email) {
                self::$alertas['error'][] = 'El Email es obligatorio';
                
            }
            if (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
                self::$alertas['error'][] = 'El Email No es valido';
            }
            return self::$alertas;
        }
    public function comprobar_password():bool {
        return password_verify($this->password_actual, $this->password);
    }


    //Hashea el Password
        public function hashPassword() : void {
            $this->password = password_hash($this->password, PASSWORD_BCRYPT);
        }
    
    // Generar un T>oken
        public function crearToken() :void {
            $this->token = md5(uniqid());
        }

    // Valida el Password
        public function ValidarPassword() {
            if(!$this->password) {
                self::$alertas['error'] [] = "El password no puede ir vacio";
            }
            if(strlen($this->password) < 6) {
                self::$alertas['error'] [] = "El password debe contener al menos 6 caracteres";
            }
            return self::$alertas;   

        }
    // Validar Perfiles
    public function validar_perfil() {
        if(!$this->nombre) {
            self::$alertas['error'] [] = "El Nombre del Usuario es Obligatorio";
        }
        if(!$this->email) {
            self::$alertas['error'] [] = "El email del Usuario es Obligatorio";
        }
        return self::$alertas;
    }



}