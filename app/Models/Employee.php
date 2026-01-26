<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\Multitenantable; // <--- 1. Importado

class Employee extends Model
{
    use HasFactory;
    use Multitenantable; // <--- 2. ¡FALTABA ESTA LÍNEA! Para que funcione el filtro por sucursal

    protected $guarded = [];

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
}
