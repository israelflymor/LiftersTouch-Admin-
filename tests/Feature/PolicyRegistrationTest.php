<?php

namespace Tests\Feature;

use App\Models\AuditLog;
use App\Models\ImportBatch;
use App\Models\ImportRow;
use App\Models\ImportSheetSnapshot;
use App\Models\ReconciliationItem;
use App\Models\ReconciliationRun;
use App\Policies\AuditLogPolicy;
use App\Policies\ImportPolicy;
use App\Policies\ReconciliationPolicy;
use Illuminate\Contracts\Auth\Access\Gate;
use Tests\TestCase;

class PolicyRegistrationTest extends TestCase
{
    public function test_explicit_policy_mappings_are_registered_for_non_conventional_models(): void
    {
        $gate = app(Gate::class);

        $expectedPolicies = [
            ReconciliationRun::class => ReconciliationPolicy::class,
            ReconciliationItem::class => ReconciliationPolicy::class,
            ImportBatch::class => ImportPolicy::class,
            ImportSheetSnapshot::class => ImportPolicy::class,
            ImportRow::class => ImportPolicy::class,
            AuditLog::class => AuditLogPolicy::class,
        ];

        foreach ($expectedPolicies as $model => $policy) {
            $this->assertInstanceOf(
                $policy,
                $gate->getPolicyFor($model),
                "Expected {$model} to resolve to {$policy}."
            );
        }
    }
}
