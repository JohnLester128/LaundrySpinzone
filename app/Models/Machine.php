<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Machine extends Model { protected $fillable = ['branch_id', 'name', 'type', 'status']; public function branch() { return $this->belongsTo(Branch::class); } }
