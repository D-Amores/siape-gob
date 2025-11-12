<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\StatusController;
use App\Http\Controllers\AcceptAssignments\AssetsUniqueUserController;
use App\Http\Controllers\AcceptAssignments\AcceptAssignmentsController;
use App\Http\Controllers\Asset\AssetController;
use App\Http\Controllers\Asset\AssetReportController;
use App\Http\Controllers\Asset\AssetTrackingController;
use App\Http\Controllers\Assigner\BrandController;
use App\Http\Controllers\Assigner\CategoryController;
use App\Http\Controllers\Assigner\AssetAcceptedController;
use App\Http\Controllers\Assigner\AssetUnassigedController;
use App\Http\Controllers\Assigner\PersonnelAssetPendingController;
use App\Http\Controllers\Admin\PersonnelController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\AreaController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Historic\HistoricController;
use App\Http\Controllers\FormatoController;

Route::middleware('guest')->group(function () {
    // Login routes
    Route::get('/login', [LoginController::class, 'index'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
});

Route::middleware('auth')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Historic routes
    Route::get('history', [HistoricController::class, 'index'])->name('historic.index');
    Route::post('historic/api',[HistoricController::class, 'historicApi']);

    
    //Service routes
    Route::resource('assets/asset-reports', AssetReportController::class)->only(['store']);
    // Status routes
    Route::post('statuses/api', [StatusController::class, 'statusApi']);
    // Logout route
    Route::post('logout', [LoginController::class, 'logout'])->name('logout');

    Route::middleware('role:assigner|admin')->group(function () {
        Route::resource('assets', AssetController::class)->only(['index', 'store', 'update', 'destroy']);
        Route::resource('assets/asset-tracking', AssetTrackingController::class)->parameters(['asset-tracking' => 'report']);
        Route::resource('brands', BrandController::class)->only(['index', 'store', 'update', 'destroy']);
        Route::resource('categories', CategoryController::class)->only(['index', 'store', 'update', 'destroy']);
        Route::resource('personnel-asset-pending', PersonnelAssetPendingController::class)->only(['index', 'store', 'update', 'destroy']);
        Route::get('assignments', [AssetAcceptedController::class, 'index'])->name('assignments.index');
        
        Route::post('admin/personnel/api', [PersonnelController::class, 'personnelApi']); // Temporal no es mi ruta (iba en middleware('role:admin') )
        Route::post('brands/api', [BrandController::class, 'brandApi']);
        Route::post('categories/api', [CategoryController::class, 'categoryApi']);
        Route::post('assets/api', [AssetController::class, 'assetsApi'])->name('assets.api');
        Route::post('assets/asset-reports/api', [AssetReportController::class, 'reportApi']);
        Route::delete('assets/unassigned/{personnelAsset}', [AssetUnassigedController::class, 'destroy']);
        Route::post('assignments/api', [AssetAcceptedController::class, 'assignmentsAssetApi']);
        Route::post('admin/personnel/api', [PersonnelController::class, 'personnelApi']);
        
    });

    Route::middleware('role:user')->group(function () {
        Route::resource('accept-assignments', AcceptAssignmentsController::class)->only(['index']);
        Route::resource('assets-user', AssetsUniqueUserController::class)->only(['index']);

        // Ruta API para cargar asignaciones pendientes del usuario autenticado
        Route::post('accept-assignments/api', [AcceptAssignmentsController::class, 'pendingAssignmentsApi'])->name('accept-assignments.api');

        // Ruta API para cargar bienes del usuario autenticado 
        Route::post('assets-unique-user/api', [AssetsUniqueUserController::class, 'assetsUniqueUsuarioAPi'])->name('assets-user.api');
        
        // Ruta API para mostar el activo en el modal ver
        Route::post('/assets-unique-user/{id}', [AssetsUniqueUserController::class, 'show'])->name('assets-unique-user.show');

        // Ruta para descargar documento de respaldo
        Route::get('/assets-user/download-respaldo-document/{assignmentId}', [AssetsUniqueUserController::class, 'downloadRespaldoDocument'])->name('assets-user.download-respaldo-document');

        // Ruta API para Subir documento de aceptación
        Route::post('/assets-unique-user/{id}/upload-document', [AssetsUniqueUserController::class, 'update'])->name('assets-unique-user.upload-document');
        
        // Ruta API para Descargar documento de aceptación desde el modulo bienes asignados
        Route::get('download-document/{assignmentId}', [AssetsUniqueUserController::class, 'downloadDocument'])->name('assets-unique-user.download');
        
        // Rutas API para usar la generacion del pdf al aceptar una asignación
        Route::get('accept-assignments/pdf/{id}', [AcceptAssignmentsController::class, 'downloadAssignmentPdf'])->name('assignment.pdf.download');
        Route::get('/pdf/asignacion/{id}', [FormatoController::class, 'pdfAsignacion'])->name('pdf.asignacion');
        
        // Ruta API para aceptar los bienes asignados al usuario
        Route::post('accept-assignments/accept', [AcceptAssignmentsController::class, 'acceptAssignmentApi'])->name('accept-assignments.accept');
        
    });

    Route::middleware('role:admin')->group(function () {
        // Admin routes
        Route::resource('admin/users', UserController::class)->only([
            'index', 'show', 'store', 'update', 'destroy'
        ]);
        Route::post('admin/users/api', [UserController::class, 'userApi']);
        Route::resource('admin/personnel', PersonnelController::class)->only([
            'index', 'show', 'store', 'update', 'destroy'
        ]);
        Route::post('admin/areas/api', [AreaController::class, 'areaApi']);
    });
});


