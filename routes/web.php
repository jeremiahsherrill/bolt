<?php

use Illuminate\Support\Facades\Route;
use LaraZeus\Bolt\Livewire\FillForms;
use LaraZeus\Bolt\Livewire\ListEntries;
use LaraZeus\Bolt\Livewire\ListForms;
use LaraZeus\Bolt\Livewire\ShowEntry;

Route::domain(config('zeus-bolt.domain'))
    ->prefix(config('zeus-bolt.prefix'))
    ->name('bolt.')
    ->middleware(config('zeus-bolt.middleware'))
    ->group(function () {
        Route::get('/', ListForms::class)
            ->name('forms.list');

        Route::get('/entries', ListEntries::class)->name('entries.list')
            ->middleware('auth');

        Route::get('/entry/{responseID}', ShowEntry::class)
            ->name('entry.show')
            ->middleware('auth');

        if (class_exists(\LaraZeus\BoltPro\BoltProServiceProvider::class)) {
            Route::get('embed/{slug}', \LaraZeus\BoltPro\Livewire\EmbedForm::class)
                ->name('form.embed');
        }

        Route::get('{slug}/{extensionSlug?}', FillForms::class)
            ->name('form.show');
    });
