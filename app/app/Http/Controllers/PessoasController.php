<?php

namespace App\Http\Controllers;

use App\Models\Pessoas;
use Illuminate\Http\Request;

class PessoasController extends Controller
{
    /**
     * Display a listing of the resource for a specific person.
     *
     * * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Pessoas::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $pessoa = Pessoas::create($request->all());

        return response()->json($pessoa, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Pessoas::find($id);
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
        $request->validate([
            'tipo' => 'required|string',
            'cep' => 'required|string',
            'numero' => 'required|string',
            'cidade' => 'required|string',
        ]);

        $endereco = Pessoas::find($id);
        $endereco->update($request->all());
        return $endereco;
    }



    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return Pessoas::destroy($id);
    }
}
