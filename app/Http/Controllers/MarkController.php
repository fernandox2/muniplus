<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mark;
use Illuminate\Support\Facades\DB;

class MarkController extends Controller
{

    public function index()
    {
        //
    }


    public function store(Request $request)
    {
        //
    }


    public function FindMarksToDay()
    {
        $hoy = date("Y-m-d"); 
        $marks = DB::table('marks')
        ->where('fecha', $hoy)
        ->get();

        return count($marks);
    }

    public function ErrorsToDay()
    {
        $hoy = date("Y-m-d");

        $eventos = DB::table('events')
        ->where('created_at', $hoy)
        ->where('tipo', 'Error')
        ->get();

        return count($eventos);
    }

    public function LastFiveEventError()
    {
        
        $eventos = DB::table('events')
        ->where('tipo', 'Error')
        ->orderBy('created_at', 'desc')
        ->limit(5)
        ->get();

        return $eventos;
    }

    public function LastFiveEventInfo()
    {
        
        $eventos = DB::table('events')
        ->where('tipo', 'Info')
        ->orderBy('created_at', 'desc')
        ->limit(5)
        ->get();

        return $eventos;
    }


    public function update(Request $request, $id)
    {
        //
    }


    public function destroy($id)
    {
        //
    }
}
