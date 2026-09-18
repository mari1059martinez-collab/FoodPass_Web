<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Platillo;

class CartController extends Controller
{
    public function show(Request $request)
    {
        $cart = session()->get('cart', []);
        return response()->json(['success' => true, 'cart' => $cart]);
    }

    public function add(Request $request)
    {
        $request->validate([
            'platillo_id' => 'required|exists:platillos,id',
            'cantidad' => 'integer|min:1'
        ]);

        $platillo = Platillo::findOrFail($request->platillo_id);
        $cantidad = $request->input('cantidad', 1);

        $cart = session()->get('cart', []);

        if (isset($cart[$platillo->id])) {
            $cart[$platillo->id]['cantidad'] += $cantidad;
        } else {
            $cart[$platillo->id] = [
                'id' => $platillo->id,
                'nombre' => $platillo->nombre,
                'precio' => (float) $platillo->precio,
                'cantidad' => $cantidad,
                'imagen' => $platillo->imagen
            ];
        }

        session()->put('cart', $cart);

        return response()->json([
            'success' => true,
            'message' => 'Platillo agregado al carrito',
            'cart' => $cart
        ]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'platillo_id' => 'required|integer',
            'cantidad' => 'required|integer|min:1'
        ]);

        $cart = session()->get('cart', []);

        if (isset($cart[$request->platillo_id])) {
            $cart[$request->platillo_id]['cantidad'] = $request->cantidad;
            session()->put('cart', $cart);
        }

        return response()->json([
            'success' => true,
            'cart' => $cart
        ]);
    }

    public function remove(Request $request)
    {
        $request->validate([
            'platillo_id' => 'required|integer'
        ]);

        $cart = session()->get('cart', []);

        if (isset($cart[$request->platillo_id])) {
            unset($cart[$request->platillo_id]);
            session()->put('cart', $cart);
        }

        return response()->json([
            'success' => true,
            'cart' => $cart
        ]);
    }
}
