<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use OwenIt\Auditing\Contracts\Auditable;

class CtcPersonaInformation extends Model implements Auditable
{
    use HasFactory, SoftDeletes;
    use \OwenIt\Auditing\Auditable;

    public $table = 'ctc_personas_information';

    protected $guarded = [];

    protected $casts = [
        'respuestas' => 'array',
    ];

    protected $auditExclude = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function persona() {
        return $this->belongsTo(CtcPersona::class, 'persona_id');
    }
}
