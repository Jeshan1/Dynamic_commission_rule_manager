<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class CommissionRuleDestination extends Model
{
    use HasFactory;
    protected $table = 'commission_rule_destinations';

    protected $fillable = ['commission_rule_id', 'airport_code'];

    public $timestamps = true;

    public function rule()
    {
        return $this->belongsTo(CommissionRule::class);
    }
}
