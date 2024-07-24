<?php

namespace App\Http\Controllers;

use App\Models\Enderecos;
use Illuminate\Http\Request;

class EnderecosController extends Controller
{
    /**
     * Display a listing of the resource for a specific person.
     *
     * @param  int  $pessoaId
     * @return \Illuminate\Http\Response
     */
    public function index($pessoaId)
    {
        return Enderecos::where('idPessoa', $pessoaId)->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'idPessoa' => 'required|exists:pessoas,id',
            'tipo' => 'required|string',
            'cep' => 'required|string',
            'numero' => 'required|string',
            'cidade' => 'required|string',
        ]);

        return Enderecos::create($request->all());
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Enderecos::find($id);
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

        $endereco = Enderecos::find($id);
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
        return Enderecos::destroy($id);
    }
}
