<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Departament;
use App\Event;
use Illuminate\Support\Facades\DB;

class DepartamentController extends Controller
{

    public function index()
    {
        $departaments = DB::table('departaments')
                    ->OrderBy('nombre','asc')
                    ->get();

        return $departaments;
    }



    public function store(Request $request)
    {

        $dep = new Departament();

        $dep->nombre = $request->nombre;
        // Si trae imagen
        if($request->image <> ""){
            $data = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $request->image));
            $nombre = uniqid("logo");
            $filepath = "images/logos/". $nombre .".".$request->extension;
            file_put_contents($filepath,$data);
            $dep->logo = $filepath;
        }

        $dep->save();
        $dep->save = true;

        $evento = new Event();
        $evento->evento = "Se creó correctamente el departamento " . $dep->nombre;
        $evento->tipo = "Info";
        $evento->save();

        return $dep;

    }


    public function update(Request $request, $id)
    {
        $dep = Departament::find($id);
        $dep->nombre = $request->nombre;

        if(file_exists($dep->logo)){  //Existe Logo Anterior

            if($request->image <> "" && $request->image <> $dep->logo){ // Si es así cambio el logo
                unlink($dep->logo);
                $data = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $request->image));
                $nombre = uniqid("logo");
                $filepath = "images/logos/". $nombre .".".$request->extension;
                file_put_contents($filepath,$data);
                $dep->logo = $filepath;
            }
        }else{ // No existe logo
            if($request->image <> ""){
                $data = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $request->image));
                $nombre = uniqid("logo");
                $filepath = "images/logos/". $nombre .".".$request->extension;
                file_put_contents($filepath,$data);
                $dep->logo = $filepath;
            }
        }

        $dep->save();
        $dep->save = true;

        $evento = new Event();
        $evento->evento = "Se actualizó correctamente el departamento " . $dep->nombre;
        $evento->tipo = "Info";
        $evento->save();
        return $dep;
    }


    public function destroy($id)
    {
        $dep = Departament::find($id);
        if($dep->logo <> "" && file_exists($dep->logo)){
            unlink($dep->logo);
        }
        $dep->delete();
    }
}
