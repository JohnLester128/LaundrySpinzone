<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'branch_id',
        'admin_id',
        'cloth_type_id',
        'pricing_tier_id',
        'estimated_weight',
        'actual_weight',
        'comforter_size',
        'quantity',
        'actual_quantity',
        'notes',
        'estimated_price',
        'final_price',
        'status',
        'cycle_phase',
        'wash_started_at',
        'wash_duration_minutes',
        'rest_duration_minutes',
        'dry_started_at',
        'dry_duration_minutes',
        'dry_rest_duration_minutes',
        'cycle_paused_at',
        'paused_remaining_seconds',
        'accepted_by',
        'washing_machine_id',
        'dryer_machine_id',
        'weight_verification_status',
        'verification_note',
    ];

    protected function casts(): array
    {
        return [
            'estimated_weight' => 'decimal:2',
            'actual_weight' => 'decimal:2',
            'estimated_price' => 'decimal:2',
            'final_price' => 'decimal:2',
            'quantity' => 'integer',
            'actual_quantity' => 'integer',
            'wash_started_at' => 'datetime',
            'dry_started_at' => 'datetime',
            'cycle_paused_at' => 'datetime',
            'wash_duration_minutes' => 'integer',
            'rest_duration_minutes' => 'integer',
            'dry_duration_minutes' => 'integer',
            'dry_rest_duration_minutes' => 'integer',
            'paused_remaining_seconds' => 'integer',
        ];
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public const PRESET_CLOTH_ITEMS = [
        't_shirt' => 'T-Shirt / Tops',
        'polo' => 'Polo / Collared Shirt',
        'pants' => 'Pants / Jeans',
        'shorts' => 'Shorts',
        'uniform' => 'Uniform (School/Work)',
        'jacket' => 'Jacket / Hoodie',
        'linens' => 'Towel / Blanket / Linens',
    ];

    public function clothType() { return $this->belongsTo(ClothType::class); }
    public function pricingTier() { return $this->belongsTo(PricingTier::class); }
    public function addons() { return $this->belongsToMany(Addon::class, 'service_request_addons')->withPivot('price')->withTimestamps(); }
    public function branch() { return $this->belongsTo(Branch::class); }
    public function washingMachine() { return $this->belongsTo(Machine::class, 'washing_machine_id'); }
    public function dryerMachine() { return $this->belongsTo(Machine::class, 'dryer_machine_id'); }
    public function statusHistories() { return $this->hasMany(ServiceRequestStatusHistory::class); }
    public function sale() { return $this->hasOne(Sale::class); }
    public function feedbacks() { return $this->hasMany(Feedback::class); }
    public function items() { return $this->hasMany(ServiceRequestItem::class); }

    public function hasItemBreakdown(): bool
    {
        return $this->items->count() > 0;
    }

    public function getTotalDeclaredItemsAttribute(): int
    {
        return (int) $this->items->sum('declared_qty');
    }

    public function getTotalVerifiedItemsAttribute(): int
    {
        return (int) $this->items->sum(function($item) {
            return $item->verified_qty ?? $item->declared_qty;
        });
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }

    public function acceptedBy()
    {
        return $this->belongsTo(Admin::class, 'accepted_by');
    }

    /**
     * Look up CycleTime settings matching request weight
     */
    public function getCycleSetting(): ?CycleTime
    {
        $weight = (int) ceil($this->actual_weight ?? $this->estimated_weight ?? 1);
        if ($weight < 1) $weight = 1;
        if ($weight > 8) $weight = 8;

        return CycleTime::where('kg', $weight)->first() 
            ?? CycleTime::orderBy('kg', 'asc')->first();
    }

    /**
     * Returns real-time countdown info, phase, and progress %
     */
    public function getCycleInfoAttribute(): array
    {
        $washMinutes = $this->wash_duration_minutes ?: 33;
        $restMinutes = $this->rest_duration_minutes ?: ($this->branch?->wash_buffer_minutes ?? 5);
        $dryMinutes = $this->dry_duration_minutes;
        if (!$dryMinutes) {
            $setting = $this->getCycleSetting();
            $dryMinutes = $setting ? $setting->drying_time_minutes : 25;
        }

        $isPaused = !is_null($this->cycle_paused_at);
        $now = now();

        if ($this->status === 'washing') {
            if ($isPaused) {
                $rem = $this->paused_remaining_seconds !== null ? max(0, $this->paused_remaining_seconds) : ($washMinutes * 60);
                $tot = ($this->cycle_phase === 'resting' ? $restMinutes : $washMinutes) * 60;
                $pct = $tot > 0 ? max(0, min(100, round((($tot - $rem) / $tot) * 100))) : 0;
                $m = floor($rem / 60);
                $s = $rem % 60;
                return [
                    'phase' => $this->cycle_phase ?: 'washing',
                    'remaining_seconds' => $rem,
                    'total_seconds' => $tot,
                    'elapsed_seconds' => max(0, $tot - $rem),
                    'percentage' => $pct,
                    'display_time' => sprintf('%02d:%02d', $m, $s),
                    'phase_label' => ($this->cycle_phase === 'resting' ? 'Wash Buffer (Paused)' : 'Washing (Paused)'),
                    'is_paused' => true,
                    'machine_type' => 'washer',
                    'machine_id' => $this->washing_machine_id,
                ];
            }

            if (!$this->wash_started_at) {
                $this->wash_started_at = $now;
                $this->cycle_phase = 'washing';
                $this->wash_duration_minutes = $washMinutes;
                $this->rest_duration_minutes = $restMinutes;
                $this->saveQuietly();
            }

            $washEnd = $this->wash_started_at->copy()->addMinutes($washMinutes);
            $restEnd = $washEnd->copy()->addMinutes($restMinutes);

            if ($now->lt($washEnd)) {
                $remaining = (int) max(0, $now->diffInSeconds($washEnd, false));
                $total = $washMinutes * 60;
                $elapsed = max(0, $total - $remaining);
                $pct = $total > 0 ? round(($elapsed / $total) * 100) : 0;
                $m = floor($remaining / 60);
                $s = $remaining % 60;
                return [
                    'phase' => 'washing',
                    'remaining_seconds' => $remaining,
                    'total_seconds' => $total,
                    'elapsed_seconds' => $elapsed,
                    'percentage' => max(0, min(100, $pct)),
                    'display_time' => sprintf('%02d:%02d', $m, $s),
                    'phase_label' => 'Washing in Progress',
                    'is_paused' => false,
                    'machine_type' => 'washer',
                    'machine_id' => $this->washing_machine_id,
                ];
            } elseif ($now->lt($restEnd)) {
                $remaining = (int) max(0, $now->diffInSeconds($restEnd, false));
                $total = $restMinutes * 60;
                $elapsed = max(0, $total - $remaining);
                $pct = $total > 0 ? round(($elapsed / $total) * 100) : 0;
                $m = floor($remaining / 60);
                $s = $remaining % 60;
                return [
                    'phase' => 'resting',
                    'remaining_seconds' => $remaining,
                    'total_seconds' => $total,
                    'elapsed_seconds' => $elapsed,
                    'percentage' => max(0, min(100, $pct)),
                    'display_time' => sprintf('%02d:%02d', $m, $s),
                    'phase_label' => 'Wash Buffer (Clothes in Washer)',
                    'is_paused' => false,
                    'machine_type' => 'washer',
                    'machine_id' => $this->washing_machine_id,
                ];
            } else {
                return [
                    'phase' => 'rest_completed',
                    'remaining_seconds' => 0,
                    'total_seconds' => $restMinutes * 60,
                    'elapsed_seconds' => $restMinutes * 60,
                    'percentage' => 100,
                    'display_time' => '00:00',
                    'phase_label' => 'Wash Buffer Complete · Ready for Dryer',
                    'is_paused' => false,
                    'machine_type' => 'washer',
                    'machine_id' => $this->washing_machine_id,
                ];
            }
        } elseif ($this->status === 'drying') {
            if ($this->cycle_phase === 'folding') {
                return [
                    'phase' => 'folding',
                    'remaining_seconds' => 0,
                    'total_seconds' => 0,
                    'elapsed_seconds' => 0,
                    'percentage' => 100,
                    'display_time' => 'No Timer',
                    'phase_label' => 'Folding & Packaging in Progress',
                    'is_paused' => false,
                    'machine_type' => 'dryer',
                    'machine_id' => null,
                ];
            }

            if ($isPaused) {
                $tot = $dryMinutes * 60;
                $rem = $this->paused_remaining_seconds !== null ? max(0, $this->paused_remaining_seconds) : $tot;
                $pct = $tot > 0 ? max(0, min(100, round((($tot - $rem) / $tot) * 100))) : 0;
                $m = floor($rem / 60);
                $s = $rem % 60;
                return [
                    'phase' => 'drying',
                    'remaining_seconds' => $rem,
                    'total_seconds' => $tot,
                    'elapsed_seconds' => max(0, $tot - $rem),
                    'percentage' => $pct,
                    'display_time' => sprintf('%02d:%02d', $m, $s),
                    'phase_label' => 'Drying (Paused)',
                    'is_paused' => true,
                    'machine_type' => 'dryer',
                    'machine_id' => $this->dryer_machine_id,
                ];
            }

            if (!$this->dry_started_at) {
                $this->dry_started_at = $now;
                $this->cycle_phase = 'drying';
                $this->dry_duration_minutes = $dryMinutes;
                $this->dry_rest_duration_minutes = 0;
                $this->saveQuietly();
            }

            $dryEnd = $this->dry_started_at->copy()->addMinutes($dryMinutes);

            if ($now->lt($dryEnd)) {
                $remaining = (int) max(0, $now->diffInSeconds($dryEnd, false));
                $total = $dryMinutes * 60;
                $elapsed = max(0, $total - $remaining);
                $pct = $total > 0 ? round(($elapsed / $total) * 100) : 0;
                $m = floor($remaining / 60);
                $s = $remaining % 60;
                return [
                    'phase' => 'drying',
                    'remaining_seconds' => $remaining,
                    'total_seconds' => $total,
                    'elapsed_seconds' => $elapsed,
                    'percentage' => max(0, min(100, $pct)),
                    'display_time' => sprintf('%02d:%02d', $m, $s),
                    'phase_label' => 'Drying in Progress',
                    'is_paused' => false,
                    'machine_type' => 'dryer',
                    'machine_id' => $this->dryer_machine_id,
                ];
            } else {
                return [
                    'phase' => 'folding',
                    'remaining_seconds' => 0,
                    'total_seconds' => 0,
                    'elapsed_seconds' => 0,
                    'percentage' => 100,
                    'display_time' => 'No Timer',
                    'phase_label' => 'Folding & Packaging in Progress',
                    'is_paused' => false,
                    'machine_type' => 'dryer',
                    'machine_id' => null,
                ];
            }
        } elseif (in_array($this->status, ['ready_for_pickup', 'completed'])) {
            return [
                'phase' => 'completed',
                'remaining_seconds' => 0,
                'total_seconds' => 0,
                'elapsed_seconds' => 0,
                'percentage' => 100,
                'display_time' => 'Done',
                'phase_label' => 'Cycle Finished',
                'is_paused' => false,
                'machine_type' => null,
                'machine_id' => null,
            ];
        }

        return [
            'phase' => 'idle',
            'remaining_seconds' => 0,
            'total_seconds' => 0,
            'elapsed_seconds' => 0,
            'percentage' => 0,
            'display_time' => '--:--',
            'phase_label' => 'Idle',
            'is_paused' => false,
            'machine_type' => null,
            'machine_id' => null,
        ];
    }

    /**
     * Automatically transitions phases and machine assignments based on elapsed server time
     */
    public function syncCycleProgress(): bool
    {
        if ($this->cycle_paused_at) {
            return false;
        }

        $now = now();
        $setting = $this->getCycleSetting();
        $washMinutes = $this->wash_duration_minutes ?: 33;
        $restMinutes = $this->rest_duration_minutes ?: ($this->branch?->wash_buffer_minutes ?? 5);
        $dryMinutes = $this->dry_duration_minutes;
        if (!$dryMinutes) {
            $dryMinutes = $setting ? $setting->drying_time_minutes : 25;
            $this->dry_duration_minutes = $dryMinutes;
        }
        $this->dry_rest_duration_minutes = 0;

        // 1. Status is washing
        if ($this->status === 'washing') {
            if (!$this->wash_started_at) {
                $this->wash_started_at = $now;
                $this->cycle_phase = 'washing';
                $this->wash_duration_minutes = $washMinutes;
                $this->rest_duration_minutes = $restMinutes;
                $this->save();
                return true;
            }

            $washEnd = $this->wash_started_at->copy()->addMinutes($washMinutes);
            $restEnd = $washEnd->copy()->addMinutes($restMinutes);

            if ($now->gte($washEnd)) {
                if ($now->lt($restEnd)) {
                    // Wash Buffer: clothes are still in the washing machine!
                    // Washer remains IN_USE during wash buffer!
                    if ($this->washing_machine_id) {
                        Machine::where('id', $this->washing_machine_id)->update(['status' => 'in_use']);
                    }

                    if ($this->cycle_phase !== 'resting') {
                        $this->cycle_phase = 'resting';
                        $this->save();
                        return true;
                    }
                } else {
                    // Wash buffer finished! ONLY NOW free the washer machine!
                    if ($this->washing_machine_id) {
                        Machine::where('id', $this->washing_machine_id)
                            ->where('status', '!=', 'maintenance')
                            ->update(['status' => 'available']);
                    }

                    // Advance to drying automatically!
                    // Choose the first / least numbered available dryer (Dryer 1 before Dryer 2, etc.)
                    $availableDryer = Machine::where('branch_id', $this->branch_id)
                        ->where('type', 'dryer')
                        ->where('status', 'available')
                        ->orderByRaw('CAST(REGEXP_SUBSTR(name, "[0-9]+") AS UNSIGNED) ASC, id ASC')
                        ->first();

                    if ($availableDryer) {
                        $this->status = 'drying';
                        $this->cycle_phase = 'drying';
                        $this->dryer_machine_id = $availableDryer->id;
                        $this->dry_started_at = $now;
                        $this->dry_duration_minutes = $dryMinutes;
                        $this->dry_rest_duration_minutes = 0;
                        $this->save();

                        $availableDryer->update(['status' => 'in_use']);

                        $current = $this->statusHistories()->whereNull('ended_at')->latest()->first();
                        if ($current) $current->update(['ended_at' => $now]);

                        ServiceRequestStatusHistory::create([
                            'service_request_id' => $this->id,
                            'status' => 'drying',
                            'changed_by' => $this->admin_id ?: 1,
                            'started_at' => $now,
                        ]);

                        return true;
                    } else {
                        // Keep in resting/waiting state if no dryer is free yet
                        if ($this->cycle_phase !== 'waiting_dryer') {
                            $this->cycle_phase = 'waiting_dryer';
                            $this->save();
                            return true;
                        }
                    }
                }
            }
        }

        // 2. Status is drying
        if ($this->status === 'drying') {
            if (!$this->dry_started_at) {
                $this->dry_started_at = $now;
                $this->cycle_phase = 'drying';
                $this->dry_duration_minutes = $dryMinutes;
                $this->dry_rest_duration_minutes = 0;
                $this->save();
                return true;
            }

            $dryEnd = $this->dry_started_at->copy()->addMinutes($dryMinutes);

            if ($now->gte($dryEnd)) {
                // Folding: clothes are now removed from dryer to folding table!
                // Free the dryer machine unit immediately so it becomes available for other requests.
                if ($this->cycle_phase !== 'folding') {
                    $this->cycle_phase = 'folding';
                    if ($this->dryer_machine_id) {
                        Machine::where('id', $this->dryer_machine_id)
                            ->where('status', '!=', 'maintenance')
                            ->update(['status' => 'available']);
                    }
                    $this->save();
                    return true;
                }
            }
        }

        return false;
    }
}
