<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Master data pertanyaan (aman untuk production, idempotent).
        $this->call(QuestionSeeder::class);

        // User uji hanya untuk lingkungan lokal/testing. JANGAN dibuat di production.
        if (app()->environment(['local', 'testing'])) {
            $user = User::firstOrCreate(
                ['email' => 'test@example.com'],
                ['password' => 'password'],
            );

            if (! $user->patient) {
                $user->patient()->create(['name' => 'Test User']);
            }
        }
    }
}
