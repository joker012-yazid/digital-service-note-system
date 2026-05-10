<?php

use App\Http\Controllers\ServiceNoteController;
use App\Http\Controllers\SettingsController;
use Illuminate\Support\Facades\Route;

Route::get('/', [ServiceNoteController::class, 'index'])->name('home');

Route::post('/service-notes', [ServiceNoteController::class, 'store'])->name('service-notes.store');
Route::get('/service-notes/{serviceNote}/edit', [ServiceNoteController::class, 'edit'])->name('service-notes.edit');
Route::put('/service-notes/{serviceNote}', [ServiceNoteController::class, 'update'])->name('service-notes.update');
Route::delete('/service-notes/{serviceNote}', [ServiceNoteController::class, 'destroy'])->name('service-notes.destroy');
Route::get('/service-notes/{serviceNote}/pdf', [ServiceNoteController::class, 'pdf'])->name('service-notes.pdf');
Route::get('/service-notes/{serviceNote}', [ServiceNoteController::class, 'show'])->name('service-notes.show');
Route::get('/settings', [SettingsController::class, 'index'])->name('settings');
Route::put('/settings', [SettingsController::class, 'update'])->name('settings.update');
