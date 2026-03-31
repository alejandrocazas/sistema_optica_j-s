<?php

use Illuminate\Support\Facades\Route;
use Carbon\Carbon;
use Illuminate\Http\Request;

// Controladores
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\CashController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\PrescriptionController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\InventoryController;
use Illuminate\Support\Facades\DB;
use App\Livewire\PayrollComponent;

// Modelos (Para el dashboard)
use App\Models\Sale;
use App\Models\Patient;
use App\Models\Product;
use App\Models\PayrollDetail;
use App\Models\Employee;

// --- RUTA PÚBLICA ---
Route::get('/', function () {
    return redirect()->route('login');
});

// --- RUTAS PROTEGIDAS (AUTH) ---
Route::middleware(['auth', 'verified'])->group(function () {

    // 1. DASHBOARD (Acceso: TODOS)
    Route::get('/dashboard', function () {
        // Obtener sucursal del usuario actual
        $branchId = auth()->user()->branch_id;

        // 1. Ventas de Hoy
        $ventasHoy = \App\Models\Sale::whereDate('created_at', now())->sum('total');

        // 2. Total Pacientes
        $pacientesTotal = \App\Models\Patient::count();

        // 3. Trabajos en Laboratorio
        $trabajosPendientes = \App\Models\Sale::where('status', 'laboratorio')->count();

        // 4. STOCK BAJO (CORREGIDO PARA MULTI-SUCURSAL)
        $productosBajoStock = DB::table('branch_product')
                                ->where('branch_id', $branchId)
                                ->where('stock', '<=', 9) // Umbral de alerta
                                ->count();

        // 5. Últimas Ventas
        $ultimasVentas = \App\Models\Sale::with('patient')->latest()->take(5)->get();

        return view('dashboard', compact('ventasHoy', 'pacientesTotal', 'trabajosPendientes', 'productosBajoStock', 'ultimasVentas'));

    })->name('dashboard');

    // 2. PERFIL DE USUARIO (Acceso: TODOS)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // 3. GESTIÓN DE PACIENTES (Acceso: Vendedor y Optometrista)
    Route::middleware(['role:vendedor,optometrista'])->group(function () {
        Route::resource('patients', PatientController::class);
        Route::get('patients/{patient}/historial', [PrescriptionController::class, 'byPatient'])->name('prescriptions.history');
    });

    // 4. MÓDULO DE VENTAS Y CAJA (Acceso: Solo Vendedor)
    Route::middleware(['role:vendedor'])->group(function () {
        // Ventas
        Route::get('/ventas', [SaleController::class, 'index'])->name('sales.index');
        Route::get('ventas/nueva', [SaleController::class, 'create'])->name('sales.create');
        Route::post('/ventas', [SaleController::class, 'store'])->name('sales.store');
        Route::get('/ventas/{sale}/imprimir', [SaleController::class, 'print'])->name('sales.print');
        Route::delete('/ventas/{sale}', [SaleController::class, 'destroy'])->name('sales.destroy');
        Route::patch('/ventas/{sale}/fecha', [SaleController::class, 'updateDate'])->name('sales.updateDate');
        Route::patch('/ventas/{sale}/observaciones', [SaleController::class, 'updateObservations'])->name('sales.updateObs');

        // Caja
        Route::get('/caja', [CashController::class, 'index'])->name('cash.index');
        Route::post('/caja/abrir', [CashController::class, 'open'])->name('cash.open');
        Route::post('/caja/cerrar', [CashController::class, 'close'])->name('cash.close');
        Route::post('/caja/gasto', [CashController::class, 'storeExpense'])->name('cash.expense');

        // Cobros de Saldos
        Route::get('/trabajo/{sale}/estado/{status}', [CashController::class, 'updateStatus'])->name('work.status');
        Route::post('/trabajo/{sale}/cobrar', [CashController::class, 'payBalance'])->name('work.pay');
    });

    // 5. MÓDULO CLÍNICO / RECETAS (Acceso: Solo Optometrista)
    Route::middleware(['role:optometrista'])->group(function () {
        Route::get('atenciones', [PrescriptionController::class, 'index'])->name('prescriptions.index');
        Route::get('atenciones/nueva', [PrescriptionController::class, 'selectPatient'])->name('prescriptions.selectPatient');
        Route::post('/diagnostics', [App\Http\Controllers\DiagnosticController::class, 'store'])->name('diagnostics.store');

        // Gestión de Recetas
        Route::get('patients/{patient}/receta/nueva', [PrescriptionController::class, 'create'])->name('prescriptions.create');
        Route::post('patients/{patient}/receta', [PrescriptionController::class, 'store'])->name('prescriptions.store');
        Route::get('receta/{id}/imprimir', [PrescriptionController::class, 'print'])->name('prescriptions.print');
        Route::get('receta/{prescription}/editar', [PrescriptionController::class, 'edit'])->name('prescriptions.edit');
        Route::put('receta/{prescription}', [PrescriptionController::class, 'update'])->name('prescriptions.update');
        Route::delete('receta/{prescription}', [PrescriptionController::class, 'destroy'])->name('prescriptions.destroy');
    });

    // 6. ADMINISTRACIÓN DE ÓPTICA (Acceso: Solo Admin - La Dueña)
    Route::middleware(['role:admin'])->group(function () {
        // Usuarios y Empleados
        Route::resource('users', UserController::class);
        Route::resource('branches', \App\Http\Controllers\BranchController::class);
        Route::get('/personal-y-planillas', PayrollComponent::class)->name('payroll.index');
        Route::get('/employees', App\Livewire\EmployeeComponent::class)->name('employees.index');
        Route::patch('/users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggle');

        // Inventario
        Route::resource('products', ProductController::class);
        Route::resource('categories', CategoryController::class);

        // Compras
        Route::get('/compras', [PurchaseController::class, 'index'])->name('purchases.index');
        Route::get('/compras/crear', [PurchaseController::class, 'create'])->name('purchases.create');
        Route::post('/compras', [PurchaseController::class, 'store'])->name('purchases.store');

        // Reportes
        Route::get('/payroll/print', function (Illuminate\Http\Request $request) {
            $month = $request->month;
            $year = $request->year;
            $branch_id = $request->branch_id;

            $payrolls = \App\Models\PayrollDetail::with('employee.branch')
                ->where('month', $month)
                ->where('year', $year)
                ->when($branch_id, function($q) use ($branch_id) {
                    $q->whereHas('employee', function($q2) use ($branch_id) {
                        $q2->where('branch_id', $branch_id);
                    });
                })
                ->get();

            return view('reports.payroll', compact('payrolls', 'month', 'year'));
        })->name('payroll.print');

        Route::get('/inventario/imprimir', [InventoryController::class, 'print'])->name('inventory.print');
        Route::get('/reportes', [ReportController::class, 'index'])->name('reports.index');
        Route::get('/reportes/pdf', [ReportController::class, 'pdf'])->name('reports.pdf');
    });

    // 7. ADMINISTRACIÓN SAAS (Acceso: EXCLUSIVO Super Admin / Desarrollador)
    Route::middleware(function ($request, $next) {
        if (auth()->user()->role !== 'superadmin') {
            abort(403, 'ACCESO DENEGADO. Área exclusiva de desarrollo y facturación SaaS.');
        }
        return $next($request);
    })->group(function () {
        Route::get('/facturacion', [App\Http\Controllers\BranchController::class, 'billing'])->name('billing.index');
        Route::post('/facturacion/{branch}/instalacion', [App\Http\Controllers\BranchController::class, 'payInstallation'])->name('billing.pay_installation');
        Route::post('/facturacion/{branch}/mensualidad', [App\Http\Controllers\BranchController::class, 'renewSubscription'])->name('billing.renew_subscription');
    });

});


require __DIR__.'/auth.php';
