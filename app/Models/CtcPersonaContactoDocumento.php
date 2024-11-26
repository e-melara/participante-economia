<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CtcPersonaContactoDocumento extends Model
{
    use HasFactory;

    protected $table = 'ctt_persona_contactos_documentos';

    protected $guarded = [];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function model()
    {
        return $this->morphTo('model');
    }

    public function persona()
    {
        return $this->belongsTo(CtcPersona::class, 'persona_id');
    }
}
