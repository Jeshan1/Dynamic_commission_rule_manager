<?php

namespace App\Http\Repository;

use App\Models\CommissionRule;
use App\Models\CommissionRuleOrigin;
use App\Models\CommissionRuleDestination;
use App\Http\Interfaces\CommissionInterface;

class CommissionRuleRepository implements CommissionInterface
{

    public function createCommissionRule(array $data)
    {
         $rule = CommissionRule::create([
            'rate_value' => $data['rate_value'],
            'rate_type' => $data['rate_type'],
        ]);

        $this->syncOrigins($rule, $data['origins']);
        $this->syncDestinations($rule, $data['destinations']);

        return $rule;
    }

    public function updateCommissionRule(CommissionRule $rule, array $data)
    {
        $rule->update([
            'rate_value' => $data['rate_value'],
            'rate_type'  => $data['rate_type'],
        ]);

        $this->syncOrigins($rule, $data['origins']);
        $this->syncDestinations($rule, $data['destinations']);

        return $rule;
    }

    //Helper Methods
    private function syncOrigins(CommissionRule $rule, array $origins): void
    {
        $rule->origins()->delete();
        $ruleId = $rule->id;
        $now = now();

        $originCodes = collect($origins)
            ->pluck('code')
            ->unique()
            ->map(fn ($code) => [
                'airport_code' => $code,
                'commission_rule_id' => $ruleId,
                'created_at' => $now,
                'updated_at' => $now
            ])
            ->toArray();

        $rule->origins()->insert($originCodes);
    }

    private function syncDestinations(CommissionRule $rule, array $destinations): void
    {
        $rule->destinations()->delete();
        $ruleId = $rule->id;
        $now = now();

        $destinationCodes = collect($destinations)
            ->pluck('code')
            ->unique()
            ->map(fn ($code) => [
                'airport_code' => $code,
                'commission_rule_id' => $ruleId,
                'created_at' => $now,
                'updated_at' => $now

            ])
            ->toArray();

        $rule->destinations()->insert($destinationCodes);
    }
    

}