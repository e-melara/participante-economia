<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class CtcPersona extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    protected $table = 'ctc_personas';

    public function fullName() {
        return $this->nombres . ' ' . $this->apellidos;
    }

    public function photo_url() {
      return Storage::disk('public')->url($this->avatar);
    }

    public function municipio_residencia()
    {
        return $this->belongsTo(CtcMunicipio::class, 'municipio_residencia_id');
    }

    public function municipio_nacimiento()
    {
        return $this->belongsTo(CtcMunicipio::class, 'municipio_nacimiento_id');
    }

    public function genero() {
        return $this->belongsTo(CtcGenero::class, 'genero_id');
    }

    public function estado_civil() {
        return $this->belongsTo(CtcEstadoCivil::class, 'estado_civil_id');
    }

    public function documentos_contactos()
    {
        return $this->hasMany(CtcPersonaContactoDocumento::class, 'persona_id');
    }

    public function user()
    {
        return $this->hasOne(User::class, 'persona_id');
    }

    public function informacion()
    {
        return $this->hasOne(CtcPersonaInformation::class, 'persona_id');
    }
}
