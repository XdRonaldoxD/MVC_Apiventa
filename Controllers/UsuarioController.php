<?php

use Illuminate\Support\Facades\Request;

require_once "models/Usuario.php";

class UsuarioController
{

    public function login()
    {
        $DatosPost = file_get_contents("php://input");
        $DatosPost = json_decode($DatosPost);
        $jwtAuth = new JwtAuth();
        $email = helpers::validar_input($DatosPost->email);
        $password = helpers::validar_input($DatosPost->password);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $signup = array(
                'status' => 'error',
                'code' => 404,
                'message' => 'El usuario no se ha creado',
                'error' => "Error de datos"
            );
        } else {
            $pwd = hash('sha256', $password);
            if (isset($DatosPost->getToken)) {
                $signup = $jwtAuth->signup($email, $pwd, $DatosPost->getToken);
            } else {
                $signup = $jwtAuth->signup($email, $pwd);
            }
        }
        // var_dump($signup);
        echo json_encode($signup);
    }

    public function RegistrarUsuario()
    {
        $params_array = file_get_contents("php://input");
        $params_array = json_decode($params_array);

        //Cifrar las contraseña - Cifrando 4 veces
        $pwd = hash('sha256', $params_array->password_usuario);
        $user = new usuario();
        $user->setNombre_usuario($params_array->nombre_usuario);
        $user->setApellido_usuario($params_array->apellido_usuario);
        $user->setEmail_usuario($params_array->email_usuario);
        $user->setPassword_usuario($pwd);
        $user->setRol_usuario("ROLE_USER");
        $user->setVigencia_usuario(1);
        $id=$user->Registrar();
        $data = array(
            'status' => 'success',
            'code' => 200,
            'message' => 'El usuario creado',
            'dato_user' => $id
        );
        echo json_encode($data);
    }
    public function EliminarSesion()
    {
        $params_array = file_get_contents("php://input");
        $params_array = json_decode($params_array);
        // return response()->json($params);
        $usuario =new usuario();
        $usuario->setId_usuario($params_array);
        $usuario->setSession_usuario(null);
        $usuario->ActualizarSession();
        echo json_encode('Sesion destruida con exito');
    }

    public function VerificarToken(Request $request)
    {
        $tokne = $request->header('Authorization');
        $jwtAth = new JwtAuth();
        $checktoken = $jwtAth->checktoken($tokne);
        if ($checktoken) {
            echo "login correcto";
        } else {
            echo "login incorrecto";
        }
    }


}
