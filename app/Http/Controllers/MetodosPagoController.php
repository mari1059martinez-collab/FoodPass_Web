<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MetodosPagoController extends Controller
{
    public function index()
    {
        $usuario = Auth::user();
        $pedidos = \App\Models\Pedido::where('user_id', Auth::id())
            ->with('detalles.platillo')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('m_todos_de_pago_foodpass.m_todos_de_pago', compact('usuario', 'pedidos'));
    }
}