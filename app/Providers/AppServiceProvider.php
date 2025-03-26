<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Mail;

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
        // Mail::extend('custom', function () {
        //     return new class {
        //         public function send($message)
        //         {
        //             try {
        //                 // Tenta SendGrid primeiro
        //                 Mail::mailer('sendgrid')->send($message);
        //             } catch (\Exception $e) {
        //                 // Se falhar, usa Mailgun
        //                 Mail::mailer('mailgun')->send($message);
        //             }
        //         }
        //     };
        // });
    }
}
