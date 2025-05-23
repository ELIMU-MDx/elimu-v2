<?php

namespace Database\Seeders;

use App\Models\Study;
use App\Models\User;
use Domain\Study\Roles\Owner;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        User::query()->delete();
        Study::query()->delete();
        $user = User::create([
            'name' => 'Silvan',
            'email' => 'safe.wing2218@kbuhl.ch',
            'email_verified_at' => now(),
            'password' => Hash::make('bFAojbkDNokvPGRZP7e8'),
        ]);

        $study = Study::firstOrCreate([
            'name' => 'Test Study',
            'identifier' => 'testStudy',
        ]);
        $study->users()->attach($user->id, ['role' => new Owner()]);
        // \App\Models\User::factory(10)->create();
    }
}
