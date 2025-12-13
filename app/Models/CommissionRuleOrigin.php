<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CommissionRuleOrigin extends Model
{
    protected $table = 'commission_rule_origins';

    protected $fillable = ['commission_rule_id', 'airport_code'];

    public $timestamps = true;

    public function rule()
    {
        return $this->belongsTo(CommissionRule::class);
    }
}
