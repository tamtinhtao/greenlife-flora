<?php

namespace App\Providers;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\ServiceProvider;
use Symfony\Component\Mailer\Bridge\Brevo\Transport\BrevoTransportFactory;
use Symfony\Component\Mailer\Transport\Dsn;

class AppServiceProvider extends ServiceProvider
{
    /*
    |--------------------------------------------------------------------------
    | REGISTER
    |--------------------------------------------------------------------------
    */

    public function register(): void
    {
        //
    }


    /*
    |--------------------------------------------------------------------------
    | BOOT
    |--------------------------------------------------------------------------
    */

    public function boot(): void
    {
        /*
        |--------------------------------------------------------------------------
        | BREVO MAIL TRANSPORT
        |--------------------------------------------------------------------------
        |
        | Brevo sử dụng HTTPS API thay vì SMTP.
        | Vì vậy hoạt động được trên Render Free.
        |
        */

        Mail::extend(
            'brevo',
            function () {

                $apiKey =
                    config(
                        'services.brevo.key'
                    );


                if (empty($apiKey)) {

                    throw new \RuntimeException(
                        'BREVO_API_KEY chưa được cấu hình.'
                    );
                }


                return (
                    new BrevoTransportFactory()
                )->create(

                    new Dsn(
                        'brevo+api',
                        'default',
                        $apiKey
                    )

                );
            }
        );
    }
}