<?php

namespace App\Http\Controllers\Cliente;

use App\Models\Imagen;
use App\Models\Siniestro;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class SiniestroController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $siniestro = Siniestro::all();
        return view('cliente.siniestro.index',['siniestro'=>$siniestro]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $vehiculos = DB::table('vehiculos')
        ->where('users_id','=',Auth::user()->id)
        ->where('estado','=','1')  
        ->get();    
        return view('cliente.siniestro.create',['vehiculos'=>$vehiculos]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $latitud= request('latitud');
        $longitud= request('longitud');

        $vehiculo = request('vehiculo');
        $detalle = request('detalle');
        $fecha = request('fecha');
        
        

 

        $siniestro = new Siniestro();

        $siniestro->fecha_siniestro= $fecha;
        $siniestro->detalle = $detalle;
        $siniestro->vehiculo_id = $vehiculo;
        $siniestro->users_id= Auth::user()->id;
        $siniestro->latitud = $latitud;
        $siniestro->longitud=$longitud;

        $siniestro->activo='Inactivo';
        $siniestro->estado=1;
        $siniestro->save();

        if($files = request()->file('imagenes')){
            foreach ($files as $file){
                $nombre = md5(rand(1000,10000)).'.'.$file->getClientOriginalExtension();
                $url=public_path('imagenes/siniestro');
                $file->move($url,$nombre);
                $imagen = new Imagen();
                $imagen->siniestro_id=$siniestro->id;
                $imagen->upload_path='imagenes/siniestro/';
                $imagen->nombre=$nombre;
                $imagen->save();
            }
        }

        return redirect('cliente/siniestro');
    }

    public function guardarImagenes($ima)
    {
        $image = array();
        if($files = $ima->file('imagenes')){
            dd($ima);
        }
        
    }

    public function show($id)
    {
        $siniestro = Siniestro::find($id);
        $imagen= Imagen::all();
        return view('cliente.siniestro.show',['siniestro'=>$siniestro],['imagenes'=>$imagen]);
    }

 


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $siniestro = Siniestro::find($id);
        $siniestro->estado=0;
        $siniestro->save();
        return redirect('cliente/siniestro');
    }
}
