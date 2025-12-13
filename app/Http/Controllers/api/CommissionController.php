<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use App\Models\CommissionRule;
use App\Models\CommissionRuleOrigin;
use App\Models\CommissionRuleDestination;
use App\Http\Resources\CommissionResource;
use App\Http\Requests\CommissionRequest;
use App\Http\Services\CommissionRuleService;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;

class CommissionController extends Controller
{
    protected $commissionRuleService;
    public function __construct(CommissionRuleService $commissionRuleService)
    {
        $this->commissionRuleService = $commissionRuleService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $rules = CommissionRule::with('origins', 'destinations')->get();
        $data = new CommissionResource($rules);
        return $this->successResponse('Commission rules fetched successfully', $data);
        
    }

    /**
     * Store a newly created resource in storage.
     */
    
     public function store(CommissionRequest $request)
     {
         $rulesData = $request->validated()['rules'];
     
         try {
             $this->commissionRuleService->createCommissionRules($rulesData);
     
             return $this->successResponse('Commission rules created successfully', null, 201);
     
         } catch (ValidationException $e) {
             return $this->errorResponse('Validation failed', $e->errors(), 422);
     
         } catch (\Exception $e) {
             Log::error('Create commission failed', [
                 'data' => $rulesData,
                 'error' => $e->getMessage(),
                 'trace' => $e->getTraceAsString()
             ]);
     
             return $this->errorResponse('Failed to create commission rules', $e->getMessage(), 500);
         }
     }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CommissionRequest $request, string $id)
    {
        $ruleData = $request->validated()['rules'][0];

        if (!$ruleData) {
            return $this->errorResponse('No rule data provided', 400);
        }

        try {
            $this->commissionRuleService->updateCommissionRule($id, $ruleData);

            return $this->successResponse('Commission rule updated successfully', 200);

        } catch (ModelNotFoundException $e) {
            return $this->errorResponse('Commission rule not found', 404);

        } catch (\Exception $e) {
            Log::error('Update commission failed', [
                'id' => $id,
                'data' => $ruleData,
                'error' => $e->getMessage()
            ]);

            return $this->errorResponse('Failed to update commission rule', $e->getMessage(), 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            if (!is_numeric($id) || (int)$id <= 0) {
                return $this->errorResponse(
                    message: 'Invalid commission rule ID',
                    status: 400
                );
            }

            DB::transaction(function () use ($id) {
                $rule = CommissionRule::findOrFail($id);
                $rule->origins()->delete();
                $rule->destinations()->delete();
                $rule->delete();
            });

            return $this->successResponse(
                message: 'Commission rule deleted successfully',
                status: 200
            );

        } catch (ModelNotFoundException $e) {
            return $this->errorResponse(
                message: 'Commission rule not found',
                status: 404
            );

        } catch (\Throwable $th) {
            Log::error('Failed to delete commission rule', [
                'rule_id' => $id,
                'error'   => $th->getMessage(),
                'trace'   => $th->getTraceAsString(),
            ]);

            return $this->errorResponse('Failed to delete commission rule. Please try again later.',500);
        }
    }
}
