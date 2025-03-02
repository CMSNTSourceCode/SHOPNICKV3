<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
  return $request->user();
});

// Deposit Routes
Route::middleware('auth:sanctum')->prefix('/deposit')->group(function () {
  Route::post('/paypal-confirm', [App\Http\Controllers\Api\Deposit\PaypalController::class, 'index']);
});

// Withdraw Routes
Route::middleware('auth:sanctum')->prefix('/withdraws')->group(function () {
  Route::get('/', [App\Http\Controllers\Api\WithdrawController::class, 'index']);
  Route::get('/info', [App\Http\Controllers\Api\WithdrawController::class, 'info']);
  Route::post('/store', [App\Http\Controllers\Api\WithdrawController::class, 'store']);
});

// Admin Routes
Route::middleware(['auth:sanctum', 'admin'])->prefix('/admin')->group(function () {
  // User Routes
  Route::prefix('/users')->group(function () {
    Route::get('/', [App\Http\Controllers\Api\Admin\UserController::class, 'index']);
  });

  // Transaction Routes
  Route::prefix('/transactions')->group(function () {
    Route::get('/', [App\Http\Controllers\Api\Admin\TransactionController::class, 'index']);
  });
  // History Routes
  Route::prefix('/histories')->group(function () {
    Route::get('/', [App\Http\Controllers\Api\Admin\HistoryController::class, 'index']);
  });
  // Tools Routes
  Route::middleware('throttle:200,1')->prefix('/tools')->group(function () {
    Route::post('/upload', [App\Http\Controllers\Api\Tools\UploadController::class, 'index'])->name('admin.tools.upload');
  });

  // Data Routes
  Route::prefix('/data')->group(function () {
    Route::get('/accounts-v1', [App\Http\Controllers\Api\Admin\DataController::class, 'accountsV1']);
    Route::get('/accounts-v2', [App\Http\Controllers\Api\Admin\DataController::class, 'accountsV2']);

  });
});

Route::prefix('/staff')->middleware(['auth:sanctum', 'staff'])->group(function () {
  // Tools Routes
  Route::prefix('/tools')->group(function () {
    Route::post('/upload', [App\Http\Controllers\Api\Tools\UploadController::class, 'index']);
  });
});

// Games Routes
Route::middleware('auth:sanctum')->prefix('/games')->group(function () {
  Route::post('/spin-quest/turn', [App\Http\Controllers\Api\Game\SpinQuestController::class, 'turn']);
  Route::post('/spin-quest/turn-test', [App\Http\Controllers\Api\Game\SpinQuestController::class, 'turnTest']);

  // withdraws
  Route::get('/withdraws', [App\Http\Controllers\Api\Game\WithdrawController::class, 'index']);
  Route::post('/withdraws', [App\Http\Controllers\Api\Game\WithdrawController::class, 'store']);
});

// Accounts Routes
Route::middleware(['auth:sanctum'])->prefix('/accounts')->group(function () {
  // Profiles Routes
  Route::get('/histories', [App\Http\Controllers\Api\Account\HistoryController::class, 'index']);
  Route::get('/transactions', [App\Http\Controllers\Api\Account\TransactionController::class, 'index']);
  // Invoices Routes
  Route::get('/invoices', [App\Http\Controllers\Api\Account\InvoiceController::class, 'index']);
  Route::get('/invoices/{id}', [App\Http\Controllers\Api\Account\InvoiceController::class, 'show']);
  Route::post('/invoices', [App\Http\Controllers\Api\Account\InvoiceController::class, 'store']);
  // Deposits Routes
  Route::get('/card-list', [App\Http\Controllers\Api\Account\DepositController::class, 'cardList']);
  Route::post('/send-card', [App\Http\Controllers\Api\Account\DepositController::class, 'sendCard']);

});
// Users Routes
Route::middleware(['auth:sanctum'])->prefix('/users')->group(function () {
  // Affiliate Routes
  Route::prefix('/affiliates')->group(function () {
    Route::post('/withdraw', [App\Http\Controllers\Api\User\AffiliateController::class, 'withdraw']);
  });
  // Gift Rewards Routes
  Route::prefix('/gift-rewards')->group(function () {
    Route::get('/', [App\Http\Controllers\Api\User\GiftRewardController::class, 'index']);
    Route::post('/claim', [App\Http\Controllers\Api\User\GiftRewardController::class, 'claim']);
  });
  // Invoices Routes
  Route::get('/invoices', [App\Http\Controllers\Api\User\InvoiceController::class, 'index']);
  Route::get('/invoices/{id}', [App\Http\Controllers\Api\User\InvoiceController::class, 'show']);
  Route::post('/invoices', [App\Http\Controllers\Api\User\InvoiceController::class, 'store']);
  // Bank Accounts Routes
  Route::get('/banks', [App\Http\Controllers\Api\User\BankingController::class, 'index']);
});

// Static Routes
Route::prefix('/static')->group(function () {
  Route::get('/skins/{id}', [App\Http\Controllers\Api\Store\AccountController::class, 'skins']);
  Route::get('/champions/{id}', [App\Http\Controllers\Api\Store\AccountController::class, 'champions']);
});

// Categories
Route::prefix('/categories')->group(function () {
  Route::get('/', [App\Http\Controllers\Api\CategoryController::class, 'index']);
});
Route::prefix('/groups')->group(function () {
  Route::get('/', [App\Http\Controllers\Api\GroupController::class, 'index']);
});

// Store Routes
Route::prefix('/stores')->group(function () {
  Route::prefix('/accounts')->group(function () {
    Route::get('/', [App\Http\Controllers\Api\Store\AccountController::class, 'index']);
    Route::get('/{code}', [App\Http\Controllers\Api\Store\AccountController::class, 'show']);
    Route::post('/{code}/buy', [App\Http\Controllers\Api\Store\AccountController::class, 'buy'])->middleware('auth:sanctum');
  });
  Route::prefix('/accounts-v2')->group(function () {
    Route::get('/', [App\Http\Controllers\Api\Store\AccountV2Controller::class, 'index']);
    Route::get('/{code}', [App\Http\Controllers\Api\Store\AccountV2Controller::class, 'show']);
    Route::post('/{code}/buy', [App\Http\Controllers\Api\Store\AccountV2Controller::class, 'buy'])->middleware('auth:sanctum');
  });
  Route::prefix('/items')->group(function () {
    Route::get('/', [App\Http\Controllers\Api\Store\ItemController::class, 'index']);
    Route::get('/{code}', [App\Http\Controllers\Api\Store\ItemController::class, 'show']);
    Route::post('/{slug}/buy', [App\Http\Controllers\Api\Store\ItemController::class, 'buy'])->middleware('auth:sanctum');
  });
  Route::prefix('/list-ingame')->group(function () {
    Route::get('/', [App\Http\Controllers\Api\Store\ListIngameController::class, 'index']);
  });
  Route::prefix('/boosting-game')->group(function () {
    Route::get('/', [App\Http\Controllers\Api\Store\BoostingGameController::class, 'index']);
    Route::get('/{slug}', [App\Http\Controllers\Api\Store\BoostingGameController::class, 'show']);
    Route::post('/{slug}/buy', [App\Http\Controllers\Api\Store\BoostingGameController::class, 'buy'])->middleware('auth:sanctum');
  });
});


Route::prefix('/tools')->group(function () {
  Route::post('/get-current-otp', function (Request $request) {
    $payload = $request->validate([
      'secret' => 'required|string',
    ]);

    if (!is_valid_2fa_secret($payload['secret'])) {
      return response()->json([
        'status'  => 400,
        'message' => 'Mã secret two factor không hợp lệ',
      ], 400);
    }

    return response()->json([
      'data'    => generate_code_2fa($payload['secret']),
      'status'  => 200,
      'message' => 'Lấy mã OTP thành công',
    ]);
  });
});
