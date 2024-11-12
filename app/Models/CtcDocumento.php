<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CtcDocumento extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function personas() {
        return $this->morphToMany(CtcPersona::class, 'model', 'ctt_persona_contactos_documentos');
    }
}
