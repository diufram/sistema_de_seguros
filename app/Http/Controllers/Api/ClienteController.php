<?php

namespace App\Http\Controllers\Api;

use App\Models\Vehiculo;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Traits\ApiResponder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;

class ClienteController extends Controller
{

    use ApiResponder;
    /**
     * Handle the incoming request.
     *
     * @param  Request  $request
     * @return JsonResponse
     */
    //TODO: obtener la lista de vehiculos

    public function obtenerListaVehiculos()
    {
        $obtenerVehiculos = Vehiculo::where('users_id', Auth::user()->id)
            ->where('estado', 1)
            ->get();

        return $this->success(
            "listaVehiculos",
            $obtenerVehiculos->toArray(),
        );
    }


    //TODO: editar vehiculo
    public function editarVehiculo(Request $request)
    {


        $vehiculo = Vehiculo::find($request->id);

        $vehiculo->modelo = request('modelo');
        $vehiculo->marca = request('marca');
        $vehiculo->placa = request('placa');
        $vehiculo->combustible = request('combustible');
        $vehiculo->potencia = request('potencia');
        $vehiculo->altura = request('altura');
        $vehiculo->anchura = request('anchura');
        $vehiculo->nro_asiento = request('nro_asiento');
        $vehiculo->descripcion = request('descripcion');
        $vehiculo->estado = 1;

        if ($request->hasFile('imagen')) {
            $imagen = $request->file('imagen');
            $nombre = request('placa') . '.' . $imagen->getClientOriginalExtension();
            $url = public_path('imagenes/vehiculos');
            $request->imagen->move($url, $nombre);
            $vehiculo->imagen = $nombre;
            $vehiculo->path = "http://192.168.100.180:8000/imagenes/vehiculos/" . $nombre;
        }
        $vehiculo->update();

        return $this->success(
            "actualizadoVehiculo",
            $vehiculo->toArray(),
        );
    }



    public function eliminarVehiculo(Request $request)
    {
        $vehiculo = Vehiculo::find($request->id);
        $vehiculo->estado = 0;

        $vehiculo->update();
        return $this->success(
            "Eliminado",
            $vehiculo->toArray(),
        );
    }
}
