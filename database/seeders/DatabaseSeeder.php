<?php

namespace Database\Seeders;

use App\Models\Attendance;
use App\Models\DietPlan;
use App\Models\Enquiry;
use App\Models\Invoice;
use App\Models\Member;
use App\Models\MembershipPlan;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@gym.com',
            'password' => Hash::make('password'),
        ]);

        $plans = collect([
            ['name' => 'Monthly', 'duration_days' => 30, 'price' => 1500, 'description' => 'Basic monthly membership'],
            ['name' => 'Quarterly', 'duration_days' => 90, 'price' => 4000, 'description' => '3-month membership with savings'],
            ['name' => 'Half-Yearly', 'duration_days' => 180, 'price' => 7500, 'description' => '6-month premium membership'],
            ['name' => 'Yearly', 'duration_days' => 365, 'price' => 14000, 'description' => 'Full year premium access'],
            ['name' => 'Premium', 'duration_days' => 30, 'price' => 2500, 'description' => 'Premium monthly with personal trainer'],
        ])->map(fn ($plan) => MembershipPlan::create($plan));

        $membersData = [
            ['Rahul', 'Sharma', 'rahul@email.com', '9876543210', 'male', 0, 'paid', 'active', 0],
            ['Priya', 'Patel', 'priya@email.com', '9876543211', 'female', 1, 'paid', 'active', 3],
            ['Amit', 'Kumar', 'amit@email.com', '9876543212', 'male', 2, 'pending', 'active', 5],
            ['Sneha', 'Reddy', 'sneha@email.com', '9876543213', 'female', 0, 'paid', 'active', -2],
            ['Vikram', 'Singh', 'vikram@email.com', '9876543214', 'male', 4, 'overdue', 'expired', -15],
            ['Ananya', 'Gupta', 'ananya@email.com', '9876543215', 'female', 1, 'paid', 'active', 10],
            ['Rohan', 'Mehta', 'rohan@email.com', '9876543216', 'male', 3, 'paid', 'active', 1],
            ['Kavya', 'Nair', 'kavya@email.com', '9876543217', 'female', 0, 'pending', 'active', 7],
        ];

        $members = collect();
        foreach ($membersData as $index => [$first, $last, $email, $phone, $gender, $planIdx, $payment, $status, $expiryDays]) {
            $joinDate = now()->subDays(rand(30, 365));
            $startDate = $joinDate->copy();
            $expiryDate = now()->addDays($expiryDays);
            $plan = $plans[$planIdx];

            $member = Member::create([
                'member_code' => Member::generateMemberCode(),
                'first_name' => $first,
                'last_name' => $last,
                'email' => $email,
                'phone' => $phone,
                'date_of_birth' => now()->subYears(rand(20, 45))->format('Y-m-d'),
                'gender' => $gender,
                'address' => '123 Fitness Street, City',
                'emergency_contact_name' => 'Emergency Contact',
                'emergency_contact_phone' => '9876500000',
                'membership_plan_id' => $plan->id,
                'joining_date' => $joinDate,
                'membership_start_date' => $startDate,
                'membership_expiry_date' => $expiryDate,
                'payment_status' => $payment,
                'status' => $status === 'expired' ? 'expired' : ($expiryDays < 0 ? 'expired' : 'active'),
                'checked_in_at' => $index < 3 ? now()->subHours(rand(1, 3)) : null,
            ]);
            $members->push($member);
        }

        foreach ($members->take(5) as $member) {
            Invoice::create([
                'invoice_number' => Invoice::generateInvoiceNumber(),
                'member_id' => $member->id,
                'membership_plan_id' => $member->membership_plan_id,
                'amount' => $member->membershipPlan->price,
                'discount' => 0,
                'tax' => 0,
                'final_amount' => $member->membershipPlan->price,
                'payment_method' => 'cash',
                'payment_status' => $member->payment_status === 'paid' ? 'paid' : 'pending',
                'invoice_date' => now()->subDays(rand(1, 30)),
                'due_date' => now()->addDays(7),
            ]);
        }

        foreach ($members->take(6) as $member) {
            Attendance::create([
                'member_id' => $member->id,
                'date' => today(),
                'check_in' => '08:'.str_pad((string) rand(0, 59), 2, '0', STR_PAD_LEFT).':00',
                'status' => rand(0, 1) ? 'present' : 'absent',
            ]);
        }

        $dietPlan = DietPlan::create([
            'name' => 'Weight Loss Plan',
            'goal' => 'Fat Loss',
            'calories' => 1800,
            'breakfast' => 'Oats with fruits, 2 egg whites',
            'lunch' => 'Grilled chicken, brown rice, salad',
            'dinner' => 'Fish, steamed vegetables',
            'snacks' => 'Protein shake, nuts',
            'supplements' => 'Whey protein, Multivitamin',
            'notes' => 'Drink 3L water daily',
        ]);

        DietPlan::create([
            'name' => 'Muscle Gain Plan',
            'goal' => 'Muscle Building',
            'calories' => 2800,
            'breakfast' => '4 eggs, toast, banana',
            'lunch' => 'Chicken breast, rice, broccoli',
            'dinner' => 'Steak, sweet potato',
            'snacks' => 'Peanut butter sandwich',
            'supplements' => 'Creatine, BCAA',
        ]);

        Enquiry::create([
            'name' => 'Deepak Verma',
            'phone' => '9988776655',
            'email' => 'deepak@email.com',
            'interested_membership' => 'Monthly',
            'source' => 'Walk-in',
            'enquiry_date' => today(),
            'follow_up_date' => today()->addDays(2),
            'status' => 'new',
            'notes' => 'Interested in morning slots',
        ]);

        Enquiry::create([
            'name' => 'Meera Joshi',
            'phone' => '9988776656',
            'email' => 'meera@email.com',
            'interested_membership' => 'Yearly',
            'source' => 'Instagram',
            'enquiry_date' => today()->subDays(3),
            'follow_up_date' => today(),
            'status' => 'follow_up',
        ]);
    }
}
