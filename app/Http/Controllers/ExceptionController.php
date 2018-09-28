<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Exception;
use Illuminate\Support\Facades\DB;

class ExceptionController extends Controller
{

    public function index()
    {
        //
    }


    public function store(Request $request)
    {
        //
    }


    public function show($id)
    {
        //
    }


    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }


    public function tipos()
    {
        $tipos = DB::table('type_exceptions')
        ->OrderBy('name','asc')
        ->get();

        return $tipos;
    }
}
