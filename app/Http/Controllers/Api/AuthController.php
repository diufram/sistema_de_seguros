<?php

namespace App\Http\Controllers\Api;

use App\Models\User;


use App\Traits\ApiResponder;
use Laravel\Sanctum\Sanctum;
use Illuminate\Http\JsonResponse;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    use ApiResponder;

    ///EXCLUSIVO PARA CLIENTES
    public function login(): JsonResponse
    {
        request()->validate([
            "email" => "required|email",
            "password" => "required|min:8|max:20",
            "device_name" => "required"

        ]);


        $cliente = User::select(["id", "name", "password", "email"])
            ->where("email", request("email"))
            ->first();

        /* Verificacion si el cliente existe */
        if (!$cliente || !Hash::check(request("password"), $cliente->password)) {
            throw ValidationException::withMessages([
                "email" => [__("Credenciales incorrectas")]
            ]);
        }

        $token = $cliente->createToken(request("device_name"))->plainTextToken;

        return $this->success(
            __("Bienvenid@"),
            [
                "cliente" => $cliente->toArray(),
                "token" => $token,
            ]
        );
    }



    //TODO: PARA EL REGISTRO DEL CLIENTE
    public function signup(): JsonResponse
    {
        request()->validate([
            "name" => "required|min:2|max:60",
            "email" => "required|email|unique:users",
            "password" => "required|min:8|max:20",
            "passwordConfirmation" => "required|same:password|min:8|max:20",
        ]);

        User::create([
            "name" => request("name"),
            /*  "ci" => request("ci"),
            "telefono" => request("telefono"), */
            "email" => request("email"),
            "password" => bcrypt(request("password")),
            "created_at" => now(),

        ])->assignRole('cliente');

        return $this->success(
            __("Cuenta creada")
        );
    }



    //TODO: Funcion para cerrar sesion
    public function logout(): JsonResponse
    {
        //Recuperando el token
        $token = request()->bearerToken();

        /** @var PersonalAccessToken $model */

        $model = Sanctum::$personalAccessTokenModel;

        $accessToken = $model::findToken($token);
        /* si existe el token se eliminara */

        $accessToken->delete();


        return $this->success(
            __("Has cerrado sesion con exito!"),
            data: null,
            code: 204,

        );
    }



}
