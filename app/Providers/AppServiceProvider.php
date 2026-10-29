<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

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
        // null = tidak memutuskan, sehingga Policy/Gate lain tetap bisa dicek
        Gate::before(function ($user, string $ability) {
            return $user->hasPermission($ability) ?: null;
        });
        Paginator::useBootstrapFive();

        // @asset('css/admin.css') -> /css/admin.css?v=1730000000
        // Versi = waktu file terakhir diubah, jadi browser boleh cache lama (lihat public/.htaccess)
        // tapi langsung mengambil file baru begitu isinya berubah.
        Blade::directive('asset', function (string $path) {
            return "<?php echo e(asset({$path}) . (is_file(public_path({$path})) ? '?v=' . filemtime(public_path({$path})) : '')); ?>";
        });
    }
}
