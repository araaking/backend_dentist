<?php

namespace App\Providers;

use App\Models\Consultation;
use App\Models\User;
use App\Policies\ConsultationPolicy;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::define('admin', fn (User $user): bool => (bool) $user->is_admin);
        Gate::policy(Consultation::class, ConsultationPolicy::class);

        // Login: 5 percobaan/menit per kombinasi email + IP, DAN maks 20/menit per IP
        // (mencegah credential stuffing dengan banyak email berbeda dari satu IP).
        RateLimiter::for('login', function (Request $request) {
            return [
                Limit::perMinute(5)->by(Str::lower((string) $request->input('email')).'|'.$request->ip()),
                Limit::perMinute(20)->by('login-ip:'.$request->ip()),
            ];
        });

        // Registrasi: 10 per jam per IP.
        RateLimiter::for('register', function (Request $request) {
            return Limit::perHour(10)->by($request->ip());
        });

        // Aksi sensitif (mis. hapus akun yang memverifikasi password): 5/menit.
        RateLimiter::for('sensitive', function (Request $request) {
            return Limit::perMinute(5)->by($request->user()?->getAuthIdentifier() ?: $request->ip());
        });
    }
}
