<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class MakeAdmin extends Command
{
    protected $signature = 'app:make-admin
        {email : Email user yang dijadikan admin}
        {--revoke : Cabut hak admin user yang sudah ada (set is_admin=false)}
        {--create : Buat user baru (beserta profil pasien) bila email belum terdaftar}
        {--password= : Password untuk user baru (bila kosong akan ditanyakan; hindari di shell bersama karena tersimpan di history)}
        {--name= : Nama untuk profil pasien user baru (default: Administrator)}';

    protected $description = 'Jadikan user sebagai admin (set is_admin), buat admin baru dengan --create, atau cabut dengan --revoke';

    public function handle(): int
    {
        $email = trim((string) $this->argument('email'));

        $user = User::where('email', $email)->first();

        if ($this->option('revoke')) {
            if (! $user) {
                $this->error("User dengan email {$email} tidak ditemukan.");

                return self::FAILURE;
            }

            $user->forceFill(['is_admin' => false])->save();
            $this->info("Hak admin user {$email} telah dicabut.");

            return self::SUCCESS;
        }

        if ($user) {
            if ($user->is_admin) {
                $this->info("User {$email} sudah menjadi admin.");

                return self::SUCCESS;
            }

            $user->forceFill(['is_admin' => true])->save();
            $this->info("User {$email} sekarang adalah admin.");

            return self::SUCCESS;
        }

        if (! $this->option('create')) {
            $this->error("User dengan email {$email} tidak ditemukan. Gunakan --create untuk membuat user baru.");

            return self::FAILURE;
        }

        $password = $this->option('password');
        if ($password === null || $password === '') {
            if (! $this->input->isInteractive()) {
                $this->error('Password diperlukan. Berikan --password= atau jalankan secara interaktif.');

                return self::FAILURE;
            }
            $password = $this->secret('Password (minimal 8 karakter)');
        }

        $validator = Validator::make(
            ['email' => $email, 'password' => $password],
            ['email' => 'required|email|max:255|unique:users,email', 'password' => 'required|string|min:8'],
        );

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $message) {
                $this->error($message);
            }

            return self::FAILURE;
        }

        $name = $this->option('name') ?: 'Administrator';

        DB::transaction(function () use ($email, $password, $name) {
            $user = User::create(['email' => $email, 'password' => $password]);
            $user->forceFill(['is_admin' => true])->save();
            $user->patient()->create(['name' => $name]);
        });

        $this->info("User admin {$email} berhasil dibuat.");

        return self::SUCCESS;
    }
}
