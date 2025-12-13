<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\CommissionRule;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

class CommissionRuleTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create([
            'role' => 'admin'
        ]);
        Sanctum::actingAs($this->admin, ['*']);
    }

    public function test_can_create_commission_rules()
    {
        $payload = [
            'rules' => [
                [
                    'origins' => [['code' => 'KTM'], ['code' => 'DEL']],
                    'destinations' => [['code' => 'DXB']],
                    'rate_value' => 10.5,
                    'rate_type' => 'percentage'
                ]
            ]
        ];

        $response = $this->postJson('/api/commission', $payload);

        $response->assertStatus(200)
                 ->assertJsonStructure(['message']);

        $this->assertDatabaseCount('commission_rules', 1);
        $this->assertDatabaseCount('commission_rule_origins', 2);
        $this->assertDatabaseCount('commission_rule_destinations', 1);
    }

    public function test_can_update_a_commission_rule()
    {
        $rule = CommissionRule::factory()
            ->hasOrigins(2)
            ->hasDestinations(1)
            ->create();

        $payload = [
            'rules' => [[
                'origins' => [['code' => 'JFK']],
                'destinations' => [['code' => 'LHR'], ['code' => 'CDG']],
                'rate_value' => 50,
                'rate_type' => 'flat'
            ]]
        ];

        $response = $this->putJson("/api/commission/{$rule->id}", $payload);

        $response->assertStatus(200);

        $this->assertDatabaseHas('commission_rules', [
            'id' => $rule->id,
            'rate_value' => 50,
            'rate_type' => 'flat'
        ]);

        $this->assertDatabaseCount('commission_rule_origins', 1);
        $this->assertDatabaseHas('commission_rule_origins', ['airport_code' => 'JFK']);
    }

    public function test_can_delete_a_commission_rule()
    {
        $rule = CommissionRule::factory()
            ->hasOrigins(1)
            ->hasDestinations(1)
            ->create();

        $response = $this->deleteJson("/api/commission/{$rule->id}");

        $response->assertStatus(200);
        $this->assertDatabaseMissing('commission_rules', ['id' => $rule->id]);
        $this->assertDatabaseCount('commission_rule_origins', 0);
        $this->assertDatabaseCount('commission_rule_destinations', 0);
    }
}