<?php

namespace Adrolli\FilamentJobManager\Resources\FailedJobsResource\Pages;

use Filament\Resources\Pages\ListRecords;
use Filament\Notifications\Notification;
use Filament\Pages\Actions\Action;
use Illuminate\Support\Facades\Artisan;
use Adrolli\FilamentJobManager\Resources\FailedJobsResource;
use Adrolli\FilamentJobManager\Models\FailedJob;

class ListFailedJobs extends ListRecords
{
    protected static string $resource = FailedJobsResource::class;

    public function getActions(): array
    {
        return [
            Action::make('retry_all')
                ->label('Retry all failed Jobs')
                ->requiresConfirmation()
                ->action(function (): void {
                    Artisan::call('queue:retry all');
                    Notification::make()
                        ->title('All failed jobs have been pushed back onto the queue.')
                        ->success()
                        ->send();
                }),

            Action::make('delete_all')
                ->label('Delete all failed Jobs')
                ->requiresConfirmation()
                ->color('danger')
                ->action(function (): void {
                    FailedJob::truncate();
                    Notification::make()
                        ->title('All failed jobs have been removed.')
                        ->success()
                        ->send();
                }),
        ];
    }
}
