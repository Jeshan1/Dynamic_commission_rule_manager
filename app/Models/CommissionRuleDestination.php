<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CommissionRuleDestination extends Model
{
    protected $table = 'commission_rule_destinations';

    protected $fillable = ['commission_rule_id', 'airport_code'];

    public $timestamps = true;

    public function rule()
    {
        return $this->belongsTo(CommissionRule::class);
    }
}
