<?php

namespace Database\Seeders;

use App\Models\Email;
use App\Models\EmailAttachment;
use App\Models\EmailRecipient;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class EmailSeeder extends Seeder
{
    protected function truncatePreviousData()
    {
        Schema::disableForeignKeyConstraints();
        Email::truncate();
        EmailAttachment::truncate();
        EmailRecipient::truncate();
        Schema::enableForeignKeyConstraints();
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->truncatePreviousData();
        Email::factory()
            ->count(20)
            ->create()
            ->map(function ($email) {
                $recipients = [];
                $maxRecipients = rand(1, 8);
                for ($start = 1; $start <= $maxRecipients; $start++) {
                    $recipients[] = [
                        'email' => fake()->unique->safeEmail(),
                        'type' => 1,
                    ];
                }
                for ($start = 1; $start <= $maxRecipients; $start++) {
                    $recipients[] = [
                        'email' => fake()->unique->safeEmail(),
                        'type' => rand(2,3),
                    ];
                }
                $email->recipients()->createMany($recipients);
            });
    }
}
