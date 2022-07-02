<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DiagnosisLivestock extends Model
{
    protected $table = 'diagnosis_livestocks';
    protected $guarded = [];

    public function diagnosis()
    {
        return $this->belongsTo('App\Diagnosis', 'diagnosis_id','id');
    }

    public function livestock()
    {
        return $this->belongsTo('App\Livestock', 'livestock_id','id');
    }
}
