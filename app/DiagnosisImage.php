<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DiagnosisImage extends Model
{
    protected $table = 'diagnosis_images';
    protected $guarded = [];

    public function diagnosis()
    {
        return $this->belongsTo('App\Diagnosis', 'diagnosis','id');
    }
}
