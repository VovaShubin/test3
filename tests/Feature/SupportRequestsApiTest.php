<?php

namespace Tests\Feature;

use App\Mail\SupportRequestResolvedMail;
use App\Models\SupportRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class SupportRequestsApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_can_create_support_request(): void
    {
        $payload = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'message' => 'Help me please',
        ];

        $response = $this->postJson('/api/requests', $payload);

        $response->assertCreated()
            ->assertJsonFragment([
                'name' => 'John Doe',
                'email' => 'john@example.com',
                'status' => SupportRequest::STATUS_ACTIVE,
            ]);

        $this->assertDatabaseHas('support_requests', [
            'email' => 'john@example.com',
            'status' => SupportRequest::STATUS_ACTIVE,
        ]);
    }

    public function test_admin_can_list_with_filters(): void
    {
        SupportRequest::factory()->count(3)->create(['status' => SupportRequest::STATUS_ACTIVE]);
        SupportRequest::factory()->count(2)->create(['status' => SupportRequest::STATUS_RESOLVED]);

        $response = $this->withHeader('X-Admin-Token', 'secret')
            ->getJson('/api/requests?status=Resolved');

        $response->assertOk();
        $this->assertSame(2, $response->json('total'));
    }

    public function test_admin_update_requires_comment_on_resolve_and_sends_mail(): void
    {
        Mail::fake();

        $req = SupportRequest::factory()->create([
            'status' => SupportRequest::STATUS_ACTIVE,
            'email' => 'jane@example.com',
        ]);

        $bad = $this->withHeader('X-Admin-Token', 'secret')
            ->putJson("/api/requests/{$req->id}", [
                'status' => SupportRequest::STATUS_RESOLVED,
            ]);
        $bad->assertUnprocessable();

        $good = $this->withHeader('X-Admin-Token', 'secret')
            ->putJson("/api/requests/{$req->id}", [
                'status' => SupportRequest::STATUS_RESOLVED,
                'comment' => 'Done',
            ]);
        $good->assertOk()->assertJsonFragment([
            'status' => SupportRequest::STATUS_RESOLVED,
            'comment' => 'Done',
        ]);

        Mail::assertQueued(SupportRequestResolvedMail::class);
    }
}


