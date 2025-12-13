<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class CommissionRule extends Model
{
    use HasFactory;
    protected $table = 'commission_rules';

    protected $fillable = ['rate_value', 'rate_type'];

    public function origins()
    {
        return $this->hasMany(CommissionRuleOrigin::class, 'commission_rule_id');
    }

    public function destinations()
    {
        return $this->hasMany(CommissionRuleDestination::class, 'commission_rule_id');
    }
}
