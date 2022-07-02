<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Diagnosis extends Model
{
    protected $table = 'diagnosis';
    protected $guarded = [];

    public function images()
    {
        return $this->hasMany('App\DiagnosisImage');
    }

    public function livestocks()
    {
        return $this->hasMany('App\DiagnosisLivestock');
    }

}
