<?php

namespace App\Http\Controllers;

use App\Models\Prescription;
use App\Models\Patient;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class PrescriptionController extends Controller
{
    public function index()
    {
        $prescriptions = \App\Models\Prescription::with(['patient', 'user'])
                            ->latest()
                            ->paginate(20);

        return view('prescriptions.index', compact('prescriptions'));
    }

    // Paso 1: Mostrar formulario (Recibimos el ID del paciente)
    public function create(Patient $patient)
    {
        return view('prescriptions.create', compact('patient'));
    }

    // Paso 2: Guardar la receta
    public function store(Request $request, Patient $patient)
    {
        $data = $request->all();
        $data['user_id'] = auth()->id(); // Guardamos quién hizo la receta
        $data['patient_id'] = $patient->id;

        $prescription = Prescription::create($data);

        // CAMBIO: Redirigir al historial + variable para imprimir en nueva pestaña
        return redirect()->route('prescriptions.history', $patient->id)
            ->with('success', 'Receta guardada exitosamente.')
            ->with('print_prescription_id', $prescription->id); // <--- ESTO ACTIVA EL SCRIPT EN LA VISTA
    }

    // Paso 3: Generar PDF Ticket
    public function print($id)
    {
        // 1. Buscamos la receta
        $prescription = Prescription::with('patient', 'user')->findOrFail($id);

        // 2. Lógica para el LOGO en Base64
        // Asegúrate de que la ruta coincida con tu archivo real (ej: grupo.jpg)
        $path = public_path('images/grupo.jpg');
        $logoBase64 = null;

        if (file_exists($path)) {
            $type = pathinfo($path, PATHINFO_EXTENSION);
            $data = file_get_contents($path);
            $logoBase64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
        }

        // 3. Cargamos la vista
        $pdf = Pdf::loadView('prescriptions.pdf', compact('prescription', 'logoBase64'));

        // 4. Configurar tamaño Media Carta
        $pdf->setPaper([0, 0, 306.14, 396.85], 'portrait');

        return $pdf->stream('receta-'.$prescription->id.'.pdf');
    }

    // Nuevo método: Pantalla para buscar paciente antes de la receta
    public function selectPatient(Request $request)
    {
        $search = $request->input('search');
        $patients = [];

        if($search){
            $patients = Patient::where('name', 'LIKE', "%{$search}%")
                            ->orWhere('ci', 'LIKE', "%{$search}%")
                            ->limit(5)
                            ->get();
        }

        return view('prescriptions.select_patient', compact('patients', 'search'));
    }

    // 1. Mostrar formulario de edición
    public function edit($id)
    {
        $prescription = Prescription::findOrFail($id);
        $patient = $prescription->patient;

        return view('prescriptions.edit', compact('prescription', 'patient'));
    }

    // 2. Guardar los cambios
    public function update(Request $request, $id)
    {
        $prescription = Prescription::findOrFail($id);
        $prescription->update($request->all());

        return redirect()->route('prescriptions.history', $prescription->patient_id)
                         ->with('success', 'Receta actualizada correctamente.');
    }

    // 3. Eliminar receta
    public function destroy($id)
    {
        $prescription = Prescription::findOrFail($id);
        $patientId = $prescription->patient_id;

        $prescription->delete();

        return redirect()->route('prescriptions.history', $patientId)
                         ->with('success', 'Receta eliminada.');
    }

    // --- NUEVO MÉTODO AGREGADO ---
    // Este es el que faltaba para solucionar el Error 500
    public function byPatient(Patient $patient)
    {
        $prescriptions = $patient->prescriptions()->with('user')->latest()->get();

        return view('prescriptions.history', compact('patient', 'prescriptions'));
    }
}
