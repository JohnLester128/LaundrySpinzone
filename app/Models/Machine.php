<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Machine extends Model
{
    protected $fillable = ['branch_id', 'name', 'brand', 'model', 'type', 'status'];

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function activeWashingRequest()
    {
        return $this->hasOne(ServiceRequest::class, 'washing_machine_id')
            ->ofMany(['id' => 'max'], function ($query) {
                $query->where('status', 'washing');
            });
    }

    public function activeDryerRequest()
    {
        return $this->hasOne(ServiceRequest::class, 'dryer_machine_id')
            ->ofMany(['id' => 'max'], function ($query) {
                $query->where('status', 'drying');
            });
    }

    public function getActiveServiceRequestAttribute()
    {
        return $this->type === 'washer' ? $this->activeWashingRequest : $this->activeDryerRequest;
    }
}
