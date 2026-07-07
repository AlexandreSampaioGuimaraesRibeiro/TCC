<?php

use App\Http\Controllers\Admin\ApprovalController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\VerificationController;
use App\Http\Controllers\Client\BookingController;
use App\Http\Controllers\Client\HomeController;
use App\Http\Controllers\LgpdController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\Professional\PanelController;
use App\Http\Controllers\Professional\ServiceController;
use Illuminate\Support\Facades\Route;

/* ---------- Público ---------- */
Route::get('/', [PageController::class, 'landing'])->name('landing');
Route::get('/politica-de-privacidade', [PageController::class, 'privacy'])->name('privacidade');
Route::get('/termos-de-uso', [PageController::class, 'terms'])->name('termos');

/* ---------- Autenticação ---------- */
Route::middleware('guest')->group(function () {
    Route::get('/cadastro/cliente', [RegisterController::class, 'showClient'])->name('cadastro.cliente');
    Route::get('/cadastro/profissional', [RegisterController::class, 'showProfessional'])->name('cadastro.profissional');
    Route::post('/cadastro/{tipo}', [RegisterController::class, 'store'])
        ->middleware('throttle:10,10')->name('cadastro.store');

    Route::get('/login', [LoginController::class, 'show'])->name('login');
    Route::post('/login', [LoginController::class, 'store'])
        ->middleware('throttle:5,1')->name('login.store');

    Route::get('/esqueci-senha', [PasswordResetController::class, 'showForgot'])->name('password.request');
    Route::post('/esqueci-senha', [PasswordResetController::class, 'sendLink'])
        ->middleware('throttle:3,10')->name('password.email');
    Route::get('/resetar-senha/{token}', [PasswordResetController::class, 'showReset'])->name('password.reset');
    Route::post('/resetar-senha', [PasswordResetController::class, 'reset'])
        ->middleware('throttle:5,10')->name('password.update');
});

Route::post('/logout', [LoginController::class, 'destroy'])->middleware('auth')->name('logout');

/* ---------- Verificação de e-mail ---------- */
Route::middleware('auth')->group(function () {
    Route::get('/verificar-email', [VerificationController::class, 'notice'])->name('verification.notice');
    Route::get('/verificar-email/{id}/{hash}', [VerificationController::class, 'verify'])
        ->middleware(['signed', 'throttle:6,1'])->name('verification.verify');
    Route::post('/verificar-email/reenviar', [VerificationController::class, 'resend'])
        ->middleware('throttle:3,10')->name('verification.send');
});

/* ---------- LGPD (qualquer usuário logado) ---------- */
Route::middleware(['auth'])->group(function () {
    Route::get('/perfil/meus-dados', [LgpdController::class, 'export'])->name('lgpd.export');
    Route::post('/perfil/excluir-conta', [LgpdController::class, 'destroy'])->name('lgpd.destroy');
});

/* ---------- Cliente ---------- */
Route::middleware(['auth', 'verified', 'role:client'])->group(function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::get('/servicos/{slug}', [HomeController::class, 'category'])->name('servicos.categoria');
    Route::get('/api/proximos', [HomeController::class, 'nearbyJson'])
        ->middleware('throttle:60,1')->name('api.proximos');

    Route::get('/profissional/{profile}', [BookingController::class, 'showProfessional'])->name('profissional.show');
    Route::post('/profissional/{profile}/solicitar', [BookingController::class, 'store'])
        ->middleware('throttle:20,1')->name('booking.store');
    Route::get('/meus-pedidos', [BookingController::class, 'myBookings'])->name('meus-pedidos');
});

/* ---------- Profissional ---------- */
Route::middleware(['auth', 'verified', 'role:professional'])->group(function () {
    Route::get('/painel/aguardando', [PanelController::class, 'waiting'])->name('painel.aguardando');

    Route::middleware('approved')->prefix('painel')->name('painel.')->group(function () {
        Route::get('/', [PanelController::class, 'agenda'])->name('agenda');
        Route::get('/agenda/eventos', [PanelController::class, 'events'])->name('agenda.eventos');
        Route::post('/bloqueio', [PanelController::class, 'storeBlock'])->name('bloqueio.store');
        Route::delete('/bloqueio/{block}', [PanelController::class, 'destroyBlock'])->name('bloqueio.destroy');

        Route::get('/perfil', [PanelController::class, 'editProfile'])->name('perfil');
        Route::post('/perfil', [PanelController::class, 'updateProfile'])->name('perfil.update');

        Route::get('/valores', [PanelController::class, 'editPrices'])->name('valores');
        Route::post('/valores', [PanelController::class, 'updatePrices'])->name('valores.update');

        Route::get('/servicos/{filtro}', [ServiceController::class, 'index'])->name('servicos');
        Route::post('/servico/{booking}/aceitar', [ServiceController::class, 'accept'])->name('servico.aceitar');
        Route::post('/servico/{booking}/rejeitar', [ServiceController::class, 'reject'])->name('servico.rejeitar');
        Route::post('/servico/{booking}/concluir', [ServiceController::class, 'complete'])->name('servico.concluir');
    });
});

/* ---------- Admin ---------- */
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/aprovacoes', [ApprovalController::class, 'index'])->name('aprovacoes');
    Route::get('/aprovacoes/{profile}', [ApprovalController::class, 'show'])->name('aprovacoes.show');
    Route::post('/aprovacoes/{profile}/aprovar', [ApprovalController::class, 'approve'])->name('aprovacoes.aprovar');
    Route::post('/aprovacoes/{profile}/rejeitar', [ApprovalController::class, 'reject'])->name('aprovacoes.rejeitar');
    Route::get('/documento/{qualification}', [ApprovalController::class, 'document'])->name('documento');

    Route::get('/categorias', [CategoryController::class, 'index'])->name('categorias');
    Route::post('/categorias', [CategoryController::class, 'store'])->name('categorias.store');
    Route::post('/categorias/{category}/toggle/{field}', [CategoryController::class, 'toggle'])->name('categorias.toggle');

    Route::get('/usuarios', [UserController::class, 'index'])->name('usuarios');
    Route::post('/usuarios/{user}/suspender', [UserController::class, 'suspend'])->name('usuarios.suspender');
});
