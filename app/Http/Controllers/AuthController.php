<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Clientes;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller 
{
    //
     public function mostrarLogin()
    {
        return view('login');
    }
          



public function login(Request $request)
{
    $request->validate([
        'usuario' => 'required',
        'password' => 'required',
    ]);

    $usuario = Clientes::where('User', $request->usuario)
                      ->where('password', $request->password) // comparando en texto plano
                      ->first();


    if ($usuario) {
        // Guardamos al usuario en sesión
        $request->session()->put('Id_cliente', $usuario->Id_cliente);
        $request->session()->put('User', $usuario->User);

        return redirect('/dashboard');
    }else{
        $folio = DB::table('solicitudes')->where('Folio_servicio','LIKE','%'.$request->usuario.'%')->get();
        if ($folio->count()) {
               $usuario = Clientes::where('Id_cliente', $folio[0]->Id_cliente)
                      ->where('password', $request->password) // comparando en texto plano
                      ->first();
        }

        if ($usuario) {
            
        }
        $request->session()->put('Id_cliente', $usuario->Id_cliente);
        $request->session()->put('User', $usuario->User);

        return redirect('/dashboard');
    }

    return back()->withErrors([
        'login' => 'Usuario o contraseña incorrectos',
    ]);
}



    public function logout(Request $request)
    {
        $request->session()->flush(); // eliminamos todo
        return redirect('/login');
    }
}
