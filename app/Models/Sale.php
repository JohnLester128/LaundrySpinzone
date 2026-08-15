<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Sale extends Model { protected $fillable = ['service_request_id', 'branch_id', 'amount', 'sold_at']; protected function casts(): array { return ['amount' => 'decimal:2', 'sold_at' => 'datetime']; } public function serviceRequest() { return $this->belongsTo(ServiceRequest::class); } public function branch() { return $this->belongsTo(Branch::class); } }
