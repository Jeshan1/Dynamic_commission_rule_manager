<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class CommissionRuleOrigin extends Model
{
    use HasFactory;
    protected $table = 'commission_rule_origins';

    protected $fillable = ['commission_rule_id', 'airport_code'];

    public $timestamps = true;

    public function rule()
    {
        return $this->belongsTo(CommissionRule::class);
    }
}
