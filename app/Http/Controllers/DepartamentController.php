<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DepartamentController extends Controller
{



    public function store(Request $request)
    {
        // Si trae imagen
        if($request->image <> ""){
            $data = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $request->image));
            $nombre = uniqid("logo");
            $filepath = "images/logos/". $nombre .".".$request->extension;
            file_put_contents($filepath,$data);
            return $data;
        }
        return null;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
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
        //
    }
}
