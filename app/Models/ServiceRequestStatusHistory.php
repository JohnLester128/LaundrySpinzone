<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class ServiceRequestStatusHistory extends Model { protected $fillable = ['service_request_id', 'status', 'changed_by', 'started_at', 'ended_at']; protected function casts(): array { return ['started_at' => 'datetime', 'ended_at' => 'datetime']; } }
