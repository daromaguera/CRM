<?php

namespace App\Providers;

use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;
use Wotz\VerificationCode\Models\VerificationCode;

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
        ini_set('upload_max_filesize', '100M');
        ini_set('post_max_size', '100M');
        ini_set('memory_limit', '256M');

        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }

        VerifyEmail::toMailUsing(function (object $notifiable, string $url) {
            $code = VerificationCode::createFor($notifiable->email);
            
            return (new MailMessage)
                ->greeting('Welcome ' . $notifiable->firstname . ' ' . $notifiable->lastname . '!')
                ->subject('Verify Email Address')
                ->line('To continue with your email verification, please enter the following code: ' . $code)
                ->line('The code will expire in 60 minutes.')
                ->line('If you did not create an account, no further action is required.');
        });
    }
}
