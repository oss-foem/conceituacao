use App\Http\Controllers\RoleController;
    <?php

    use Illuminate\Support\Facades\Route;
    use App\Http\Controllers\AuthController;
    use App\Http\Controllers\UserController;
    use App\Http\Controllers\RoleController;

    /*
    |--------------------------------------------------------------------------
    | API Routes
    |--------------------------------------------------------------------------
    |
    | Here is where you can register API routes for your application. These
    | routes are loaded by the RouteServiceProvider and all of them will
    | be assigned to the "api" middleware group. Make something great!
    |
    */

    Route::post('/register', [AuthController::class, 'register'])->name('register');
    Route::post('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::middleware('auth:api')->group(function () {

        Route::get('/user', [UserController::class, 'getUser']);
        Route::middleware('check-admin-role')->group(function () {

            Route::get('/users', [UserController::class, 'index']);
            Route::get('/users/{user}', [UserController::class, 'show']);
            Route::post('/users', [UserController::class, 'store']);
            Route::put('/users/{user}', [UserController::class, 'update']);
            Route::delete('/users/{user}', [UserController::class, 'destroy']);

            Route::post('/users/{user}/roles', [UserController::class, 'assignRoles']);
            Route::delete('/users/{user}/roles', [UserController::class, 'removeRoles']);

            Route::get('/is-admin/{user}', [UserController::class, 'isAdmin']);

            // Rotas dos perfis
            Route::get('/roles', [RoleController::class, 'index']); // Listar perfis
            Route::post('/roles', [RoleController::class, 'store']); // Criar perfil
            Route::put('/roles/{role}', [RoleController::class, 'update']); // Editar perfil
            Route::delete('/roles/{role}', [RoleController::class, 'destroy']); // Excluir perfil

        });
    });



