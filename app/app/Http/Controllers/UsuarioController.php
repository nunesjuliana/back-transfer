<?php

namespace App\Http\Controllers;

use App\Http\Requests\UsuarioRequest;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use App\Http\Services\UsuarioService;
use App\Http\Models\Usuario;
use Exception;

class UsuarioController extends Controller
{

    public function __construct(UsuarioService $usuarioService)
    {
        $this->usuarioService = $usuarioService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return Usuario::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UsuarioRequest $request)
    {
        //
        return Usuario::create($request->all());

    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Usuario  $usuario
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Usuario $usuario)
    {
        //
        $usuario->update($request->all());
        return $usuario;

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Usuario  $usuario
     * @return \Illuminate\Http\Response
     */
    public function destroy(Usuario $usuario)
    {
        //
        $usuario->delete();

    }

    public function transaction(Request $request)
    {
        $email_payer = $request->payer;
        $email_payee = $request->payee;
        $value = $request->value;

        $result = $this->usuarioService->processTransaction($email_payer, $email_payee, $value);

        return $result;

    }

}
