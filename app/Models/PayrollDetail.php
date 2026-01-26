<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PayrollDetail extends Model
{
    use HasFactory;

    // Esto permite guardar todos los campos (lates, absences, salary, etc)
    // sin tener que definirlos uno por uno en $fillable.
    protected $guarded = [];

    /**
     * Relación con el Empleado.
     * Un detalle de planilla pertenece a un empleado.
     */
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
