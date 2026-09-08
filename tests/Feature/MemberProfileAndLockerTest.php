<?php

namespace Tests\Feature;

use App\Models\GymNotification;
use App\Models\Locker;
use App\Models\Member;
use App\Models\MembershipPlan;
use App\Models\User;
use App\Services\BirthdayNotificationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MemberProfileAndLockerTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    private MembershipPlan $plan;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();

        $this->user = User::factory()->create();
        $this->plan = MembershipPlan::create([
            'name' => 'Monthly',
            'duration_days' => 30,
            'price' => 1500,
            'status' => 'active',
        ]);
    }

    public function test_member_can_be_created_with_medical_report_and_instagram_id(): void
    {
        $locker = Locker::create(['locker_code' => 'L-101', 'status' => 'available']);

        $response = $this->actingAs($this->user)->post(route('members.store'), $this->memberPayload([
            'medical_report' => 'Allergic to peanuts. Previous shoulder injury.',
            'instagram_id' => 'https://instagram.com/john_doe',
            'locker_id' => $locker->id,
        ]));

        $response->assertRedirect(route('members.index'));

        $member = Member::first();
        $this->assertSame('Allergic to peanuts. Previous shoulder injury.', $member->medical_report);
        $this->assertSame('john_doe', $member->instagram_id);
        $this->assertSame($locker->id, $member->locker_id);
        $this->assertSame('assigned', $locker->fresh()->status);
    }

    public function test_member_can_be_edited_without_losing_existing_data(): void
    {
        $member = $this->createMember([
            'medical_report' => 'Knee pain',
            'instagram_id' => 'priya_fits',
            'notes' => 'Prefers morning slots',
        ]);

        $this->actingAs($this->user)->put(route('members.update', $member), $this->memberPayload([
            'first_name' => 'Priya',
            'last_name' => 'Patel',
            'medical_report' => 'Knee pain',
            'instagram_id' => 'priya_fits',
            'notes' => 'Prefers morning slots',
            'status' => 'active',
        ]))->assertRedirect(route('members.show', $member));

        $member->refresh();
        $this->assertSame('Priya', $member->first_name);
        $this->assertSame('Knee pain', $member->medical_report);
        $this->assertSame('priya_fits', $member->instagram_id);
        $this->assertSame('Prefers morning slots', $member->notes);
    }

    public function test_medical_report_instagram_and_locker_appear_on_detail_page(): void
    {
        $locker = Locker::create(['locker_code' => 'L-102', 'status' => 'available']);
        $member = $this->createMember([
            'first_name' => 'John',
            'last_name' => 'Doe',
            'medical_report' => 'Asthma. Keep inhaler nearby.',
            'instagram_id' => 'john_doe',
        ]);
        $locker->assignTo($member);

        $this->actingAs($this->user)
            ->get(route('members.show', $member))
            ->assertOk()
            ->assertSee('Asthma. Keep inhaler nearby.')
            ->assertSee('@john_doe')
            ->assertSee('L-102')
            ->assertSee('Assigned');
    }

    public function test_assigned_locker_cannot_be_assigned_to_another_member(): void
    {
        $locker = Locker::create(['locker_code' => 'L-103', 'status' => 'available']);
        $first = $this->createMember(['first_name' => 'Amit']);
        $second = $this->createMember(['first_name' => 'Rohan', 'phone' => '9999999999']);
        $locker->assignTo($first);

        $this->actingAs($this->user)
            ->put(route('members.update', $second), $this->memberPayload([
                'first_name' => 'Rohan',
                'phone' => '9999999999',
                'locker_id' => $locker->id,
                'status' => 'active',
            ]))
            ->assertSessionHasErrors('locker_id');

        $this->assertSame($first->id, $locker->fresh()->member->id);
        $this->assertNull($second->fresh()->locker_id);
    }

    public function test_locker_can_be_unassigned_and_becomes_available(): void
    {
        $locker = Locker::create(['locker_code' => 'L-104', 'status' => 'available']);
        $member = $this->createMember();
        $locker->assignTo($member);

        $this->actingAs($this->user)
            ->post(route('lockers.unassign', $locker))
            ->assertRedirect();

        $this->assertNull($member->fresh()->locker_id);
        $this->assertSame('available', $locker->fresh()->status);
    }

    public function test_assigned_locker_cannot_be_deleted(): void
    {
        $locker = Locker::create(['locker_code' => 'L-105', 'status' => 'available']);
        $member = $this->createMember();
        $locker->assignTo($member);

        $this->actingAs($this->user)
            ->delete(route('lockers.destroy', $locker))
            ->assertRedirect();

        $this->assertDatabaseHas('lockers', ['id' => $locker->id]);
    }

    public function test_birthday_notification_is_created_once_per_member_per_day(): void
    {
        $member = $this->createMember([
            'first_name' => 'John',
            'last_name' => 'Doe',
            'date_of_birth' => now()->subYears(25)->format('Y-m-d'),
        ]);

        $service = app(BirthdayNotificationService::class);

        $this->assertSame(1, $service->sendForToday());
        $this->assertSame(0, $service->sendForToday());

        $this->artisan('gym:send-birthday-notifications')->assertSuccessful();

        $this->assertSame(1, GymNotification::where('type', 'birthday')->count());

        $notification = GymNotification::where('type', 'birthday')->first();
        $this->assertSame('🎂 Birthday Today!', $notification->title);
        $this->assertSame("Today is {$member->full_name}'s birthday.", $notification->message);
        $this->assertSame(route('members.show', $member, false), $notification->link);
    }

    public function test_existing_member_create_still_works_without_optional_fields(): void
    {
        $this->actingAs($this->user)
            ->post(route('members.store'), $this->memberPayload())
            ->assertRedirect(route('members.index'));

        $this->assertDatabaseHas('members', [
            'first_name' => 'Rahul',
            'phone' => '9876543210',
            'medical_report' => null,
            'instagram_id' => null,
            'locker_id' => null,
        ]);
    }

    private function createMember(array $overrides = []): Member
    {
        return Member::create(array_merge([
            'member_code' => Member::generateMemberCode(),
            'first_name' => 'Rahul',
            'last_name' => 'Sharma',
            'phone' => '9876543210',
            'membership_plan_id' => $this->plan->id,
            'joining_date' => today(),
            'membership_start_date' => today(),
            'membership_expiry_date' => today()->addDays(30),
            'payment_status' => 'paid',
            'status' => 'active',
        ], $overrides));
    }

    private function memberPayload(array $overrides = []): array
    {
        return array_merge([
            'first_name' => 'Rahul',
            'last_name' => 'Sharma',
            'phone' => '9876543210',
            'membership_plan_id' => $this->plan->id,
            'joining_date' => today()->format('Y-m-d'),
            'payment_status' => 'paid',
        ], $overrides);
    }
}
