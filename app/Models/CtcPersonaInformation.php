<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CtcPersonaInformation extends Model
{
    use HasFactory, SoftDeletes;

    public $table = 'ctc_personas_information';

    protected $guarded = [];

    protected $casts = [
        'respuestas' => 'array',
    ];

    public function persona() {
        return $this->belongsTo(CtcPersona::class, 'persona_id');
    }
}
