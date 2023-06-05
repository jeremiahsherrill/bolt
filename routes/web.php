<?php

use Illuminate\Support\Facades\Route;
use LaraZeus\Bolt\Http\Livewire\FillForms;
use LaraZeus\Bolt\Http\Livewire\ListEntries;
use LaraZeus\Bolt\Http\Livewire\ListForms;
use LaraZeus\Bolt\Http\Livewire\ShowEntry;
use LaraZeus\Bolt\Http\Livewire\Submitted;

Route::prefix(config('zeus-bolt.path'))
    ->name('bolt.')
    ->middleware(config('zeus-bolt.middleware'))
    ->group(function () {
        Route::get('/', ListForms::class)->name('forms.list');
        Route::get('submitted/{slug}', Submitted::class)->name('submitted');
        Route::get('/entries', ListEntries::class)->name('entries.list')->middleware('auth');
        Route::get('/entry/{responseID}', ShowEntry::class)->name('entry.show')->middleware('auth');
        Route::get('{slug}/{itemSlug?}', FillForms::class)->name('form.show');
    });
