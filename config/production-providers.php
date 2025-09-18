<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Service Providers de Production
    |--------------------------------------------------------------------------
    |
    | Cette configuration contient uniquement les service providers
    | nécessaires en production, évitant les erreurs de packages manquants.
    |
    */

    'providers' => [
        // Laravel Framework Service Providers
        Illuminate\Auth\AuthServiceProvider::class,
        Illuminate\Broadcasting\BroadcastServiceProvider::class,
        Illuminate\Bus\BusServiceProvider::class,
        Illuminate\Cache\CacheServiceProvider::class,
        Illuminate\Foundation\Providers\ConsoleSupportServiceProvider::class,
        Illuminate\Concurrency\ConcurrencyServiceProvider::class,
        Illuminate\Cookie\CookieServiceProvider::class,
        Illuminate\Database\DatabaseServiceProvider::class,
        Illuminate\Encryption\EncryptionServiceProvider::class,
        Illuminate\Filesystem\FilesystemServiceProvider::class,
        Illuminate\Foundation\Providers\FoundationServiceProvider::class,
        Illuminate\Hashing\HashServiceProvider::class,
        Illuminate\Mail\MailServiceProvider::class,
        Illuminate\Notifications\NotificationServiceProvider::class,
        Illuminate\Pagination\PaginationServiceProvider::class,
        Illuminate\Auth\Passwords\PasswordResetServiceProvider::class,
        Illuminate\Pipeline\PipelineServiceProvider::class,
        Illuminate\Queue\QueueServiceProvider::class,
        Illuminate\Redis\RedisServiceProvider::class,
        Illuminate\Session\SessionServiceProvider::class,
        Illuminate\Translation\TranslationServiceProvider::class,
        Illuminate\Validation\ValidationServiceProvider::class,
        Illuminate\View\ViewServiceProvider::class,

        // Package Service Providers (Production uniquement)
        Barryvdh\DomPDF\ServiceProvider::class,
        BladeUI\Heroicons\BladeHeroiconsServiceProvider::class,
        BladeUI\Icons\BladeIconsServiceProvider::class,
        Filament\Actions\ActionsServiceProvider::class,
        Filament\FilamentServiceProvider::class,
        Filament\Forms\FormsServiceProvider::class,
        Filament\Infolists\InfolistsServiceProvider::class,
        Filament\Notifications\NotificationsServiceProvider::class,
        Filament\Schemas\SchemasServiceProvider::class,
        Filament\Support\SupportServiceProvider::class,
        Filament\Tables\TablesServiceProvider::class,
        Filament\Widgets\WidgetsServiceProvider::class,
        Kirschbaum\PowerJoins\PowerJoinsServiceProvider::class,
        Laravel\Tinker\TinkerServiceProvider::class,
        Livewire\LivewireServiceProvider::class,
        Carbon\Laravel\ServiceProvider::class,
        RyanChandler\BladeCaptureDirective\BladeCaptureDirectiveServiceProvider::class,

        // Application Service Providers
        App\Providers\AppServiceProvider::class,
        App\Providers\Filament\AdminPanelProvider::class,
    ],
];
