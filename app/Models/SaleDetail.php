<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SaleDetail extends Model
{
    protected $guarded = [];

    // Relación con el producto (Para saber el nombre y precio)
    public function product()
    {
        // AQUÍ ESTÁ LA MAGIA: Le decimos que traiga el producto INCLUSO si está "eliminado" (Soft Delete)
        return $this->belongsTo(Product::class)->withTrashed();
    }
}
