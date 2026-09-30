<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use RuntimeException;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $email = trim(
            (string) config('seeding.admin.email')
        );

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

            throw new RuntimeException(
                'SEED_ADMIN_EMAIL chưa hợp lệ.'
            );
        }


        $existing = User::where(
            'email',
            $email
        )->first();


        if ($existing) {

            if ($existing->role !== 'admin') {

                throw new RuntimeException(
                    'Email SEED_ADMIN_EMAIL đang thuộc một tài khoản User.'
                );
            }


            $this->command?->info(
                'Admin đã tồn tại, giữ nguyên tài khoản hiện tại.'
            );

            return;
        }


        $password = (string) config(
            'seeding.admin.password'
        );


        if (strlen($password) < 12) {

            throw new RuntimeException(
                'SEED_ADMIN_PASSWORD phải có ít nhất 12 ký tự.'
            );
        }


        $admin = new User();

        $admin->forceFill([

            'name' =>
                config('seeding.admin.name')
                ?: 'GreenLife Admin',

            'email' =>
                $email,

            'password' =>
                Hash::make($password),

            'role' =>
                'admin',

            'email_verified_at' =>
                now(),

        ])->save();


        $this->command?->info(
            'Đã tạo tài khoản Admin GreenLife Flora.'
        );
    }
}