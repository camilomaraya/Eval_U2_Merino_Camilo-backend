<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ClienteController extends Controller
{
    // GET /api/v1/clientes — Listar todos los clientes
    public function index(): JsonResponse
    {
        $clientes = Cliente::all();

        return response()->json($clientes, 200);
    }

    // POST /api/v1/clientes — Crear un cliente nuevo
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'rut'      => 'required|string|max:12|unique:clientes,rut',
            'nombre'   => 'required|string|max:50',
            'apellido' => 'required|string|max:50',
            'email'    => 'required|email|max:100|unique:clientes,email',
            'telefono' => 'nullable|string|max:20',
        ]);

        $cliente = Cliente::create($validated);

        return response()->json($cliente, 201);
    }

    // GET /api/v1/clientes/{id} — Mostrar un cliente específico
    public function show(string $id): JsonResponse
    {
        $cliente = Cliente::find($id);

        if (!$cliente) {
            return response()->json(['message' => 'Cliente no encontrado'], 404);
        }

        return response()->json($cliente, 200);
    }
}
