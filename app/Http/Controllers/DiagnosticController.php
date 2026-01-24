<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Diagnostic;

class DiagnosticController extends Controller
{
    public function store(Request $request) {
    $request->validate(['name' => 'required|unique:diagnostics,name']);
    \App\Models\Diagnostic::create(['name' => $request->name]);
    return back()->with('success', 'Diagnóstico agregado correctamente.');
}
}
