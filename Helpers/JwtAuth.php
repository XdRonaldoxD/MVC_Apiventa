<?php

use Firebase\JWT\JWT;

class JwtAuth
{

    public $key;
    function __construct()
    {
        $this->key = "ESTE-ES-MI-LLAVE-BOTICA3354335467547";
    }

    public function signup($email, $contra, $getToken = null)
    {
        $usuario=new Usuario();
        $usuario->setEmail_usuario($email);
        $usuario->setPassword_usuario($contra);
        $user=$usuario->TreandoUsuario();
        if (!empty($user['session_usuario'])) {
            return array(
                'status' => 'error',
                'message'  => "Se Inicio Session con la cuenta",
                'id_usuario' => $user['id_usuario'],
            );
        }
        // return response()->json($user);
        $signup = false;
        if ($user) {
            $signup = true;
        }

        if ($signup) {
            //Generar un toke y devolver
            $token = array(
                'sub' => $user['id_usuario'],
                'email' => $user['email_usuario'],
                'nombre' => $user['nombre_usuario'],
                'apellido' => $user['apellido_usuario'],
                'tipo_usuario' => $user['rol_usuario'],
                'imagen' => $user['path_usuario'],
                //creacion del dato es el iat create_at
                'iat' => time(),
                //despues de una semana
                'expiracion' => time() + (1 * 24 * 60 * 60)
            );

            //el HS256 es para cifrar la llave
            $jwt = JWT::encode($token, $this->key, 'HS256');
            //decodificando el mismo token
            $decode = JWT::decode($jwt, $this->key, array('HS256'));

            if (is_null($getToken)) {
                return $jwt;
            } else {
                $usuario2=new Usuario();
                $usuario2->setEmail_usuario($email);
                $usuario2->setPassword_usuario($contra);
                $user2=$usuario2->TreandoUsuario();

                //INICIO VERIFICACION DATOS EN SESION
                $new_sessid   = JwtAuth::generarCodigo(12);
                // Guardar Session usuario
                $user=new Usuario();
                $user->setSession_usuario($new_sessid);
                $user->setId_usuario($user2['id_usuario']);
                $user->ActualizarSession();
                //FIN
                $decode = array(
                    'sub' => $user2['id_usuario'],
                    'email' => $user2['email_usuario'],
                    'nombre' => $user2['nombre_usuario'],
                    'apellido' => $user2['apellido_usuario'],
                    'tipo_usuario' => $user2['rol_usuario'],
                    'session_id' => $new_sessid,
                    'imagen' => $user2['path_usuario'],
                    //creacion del dato es el iat create_at
                    'iat' => time(),
                    //despues de una semana
                    'expiracion' => time() + (1 * 24 * 60 * 60));
                return $decode;
            }
        } else {
            //Generar UN error
            return array('status' => 'error', 'message' => 'Login a Fallado');
        }
    }

    static function generarCodigo($longitud)
    {
        $pattern = '1234567890abcdefghijklmnopqrstuvwxyz';
        $max = strlen($pattern) - 1;
        $key='';
        for ($i = 0; $i < $longitud; $i++) {
        $key .= $pattern[mt_rand(0, $max)];
        }
        return $key;
    }

    //metodo para decodoficar el toke e usar en los controladores
    //recoger el toker y ver si es correcto o no
    public function checktoken($jwt, $getIdentity = false)
    {
        $auth = false;
        try {
            //Remplaza las comillas y los quitas
            $jwt = str_replace('"', '', $jwt);
            $decode = JWT::decode($jwt, $this->key, array('HS256'));
        } catch (\UnexpectedValueException $e) {
            $auth = false;
        } catch (\DomainException $e) {
            $auth = false;
        }
        if (isset($decode) &&  is_object($decode) && isset($decode->sub)) {
            $auth = true;
        }
        if ($getIdentity) {
            return $decode;
        }
        return $auth;
    }
}
