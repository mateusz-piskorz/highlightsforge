<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class CreateAdminUser extends Command
{

    protected $signature = 'app:create-admin-user';

    protected $description = 'Command description';

    public function handle()
    {
        $adminUser = User::query()->where('email', 'owcaofficial@yahoo.com')->first();
        if ($adminUser) {
            $adminUser->role = 'admin';
            $adminUser->save;
        } else {
            User::create(
                [
                    'email'     => 'owcaofficial@yahoo.com',
                    'user_name' => 'Admin',
                    'role'      => 'admin'
                ]
            );
        }

    }
}
