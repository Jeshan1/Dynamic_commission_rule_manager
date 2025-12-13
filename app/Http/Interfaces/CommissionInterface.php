<?php

namespace App\Http\Interfaces;

use App\Models\CommissionRule;

interface CommissionInterface
{
    public function createCommissionRule(array $data);
    public function updateCommissionRule(CommissionRule $rule, array $data);
}