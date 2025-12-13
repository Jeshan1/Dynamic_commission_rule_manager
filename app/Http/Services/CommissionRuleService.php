<?php

namespace App\Http\Services;

use App\Http\Repository\CommissionRuleRepository;
use Illuminate\Support\Facades\DB;
use App\Models\CommissionRule;

class CommissionRuleService{

    protected $commissionRuleRepository;
    public function __construct(CommissionRuleRepository $commissionRuleRepository)
    {
        $this->commissionRuleRepository = $commissionRuleRepository;
    }

    public function createCommissionRules(array $rulesData)
    {
        if (empty($rulesData)) {
            throw new Exception('No commission rules provided.');
        }

        DB::beginTransaction();
        try {
            $created = [];

            foreach ($rulesData as $index => $ruleData) {
                if (!isset($ruleData['rate_value'], $ruleData['rate_type'], $ruleData['origins'], $ruleData['destinations'])) {
                    throw new Exception("Invalid data at rule:" . ($index + 1));
                }

                $rule = $this->commissionRuleRepository->createCommissionRule($ruleData);
                $created[] = $rule;
            }

            DB::commit();
            return $created;
        } catch (\Exception $e) {
            DB::rollBack();
            throw new Exception('Failed to create commission rules: ' . $e->getMessage());
        }
    }

    public function updateCommissionRule(string $id, array $data){
        DB::beginTransaction();
        try {
            $rule = CommissionRule::findOrFail($id);
            $this->commissionRuleRepository->updateCommissionRule($rule, $data);
            DB::commit();
            return true;
        }
        catch(\Exception $e){
            DB::rollBack();
            throw new Exception('Failed to create commission rules: ' . $e->getMessage());
        }
    }
}