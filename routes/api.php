<?php

use App\Http\Controllers\EducationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\BuyingController;

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

/*
|--------------------------------------------------------------------------
| Users Management
|--------------------------------------------------------------------------
*/

Route::prefix('user_register')
    ->controller(AuthController::class)
    ->group(function () {
        Route::post('/', 'register');
        Route::post('/verify', 'verifyCode');
        Route::post('/resend', 'resendCode');
        Route::post('/login', 'login');
        Route::post('/child/login', 'loginChild');
    });

Route::middleware('auth:api')->post('/logout', [AuthController::class, 'logout']);
Route::middleware('auth:api')->post('/change/change_password', [AuthController::class, 'changePassword']);

Route::middleware('auth:api')->prefix('users')
    ->controller(UserController::class)
    ->group(function () {
        //Admin
        Route::post('/addEmployee', 'addEmployee');
        Route::post('/addRepresentative', 'addRepresentative');
        Route::post('/updateUser/{id}', 'updateUser');
        Route::delete('/deleteUser/{id}', 'deleteUser');
        Route::get('/showUserInfo/{id}', 'showUserInfo');
        Route::post('/addChild', 'addChild');
        Route::get('/getAllChildren', 'getAllChildren');
        Route::post('/filterChildren', 'filterChildren');



    });
/*
|--------------------------------------------------------------------------
| Posts Management
|--------------------------------------------------------------------------
*/
Route::middleware('auth:api')->prefix('posts')
    ->controller(PostController::class)
    ->group(function () {
        Route::get('/', 'index');
        Route::get('/all', 'getAll');
        Route::post('/', 'store');
        Route::get('/{id}', 'show');
        Route::post('/{id}', 'update');
        Route::delete('/{id}', 'destroy');
        Route::get('/post/filter', 'filter');
        Route::post('/accept/{id}', 'acceptPost');
        Route::post('/unaccept/{id}', 'unacceptPost');
        Route::get('/user/get_user_posts', 'getUserPosts');
        Route::post('/like/{postId}', 'addLike');
        Route::post('/remove/like/{postId}', 'removeLike');
    });


/*
|--------------------------------------------------------------------------
| Education Management
|--------------------------------------------------------------------------
*/

//middleware('auth:api')->child/donor
Route::middleware('auth:api')->prefix('education')
    ->controller(EducationController::class)
    ->group(function () {
        Route::get('/getAllClassroom', 'getAllClassroom');
        Route::get('/getSubjectsForClassroom/{id}', 'getSubjectsForClassroom');
        Route::get('/getTitlesForSubjectClass/{id}', 'getTitlesForSubjectClass');
        Route::get('/getExplanationsByTitle/{id}', 'getExplanationsByTitle');
        Route::get('/ExplanationDetails/{id}', 'ExplanationDetails');
        Route::post('/orderExplanations', 'orderExplanations');
        Route::get('/getAllOrderExplanations', 'getAllOrderExplanations');
        Route::post('/approveorderExplanation/{id}', 'approveorderExplanation');
        Route::get('/OrderExplanationDetails/{id}', 'OrderExplanationDetails');
        Route::get('/getChildOrderExplanations', 'getChildOrderExplanations');
        Route::get('/getUserPendingExplanations', 'getUserPendingExplanations');
        Route::get('/getUserUploadedExplanations', 'getUserUploadedExplanations');
        Route::get('/getUserRejectedExplanations', 'getUserRejectedExplanations');
        Route::get('/getUserApprovedExplanations', 'getUserApprovedExplanations');
        Route::get('/generateSignature', 'generateSignature');
        Route::POST('/saveExplanationUrl/{id}', 'saveExplanationUrl');
        ////JUST FOR TEASTING
        Route::POST('/uploadToCloudinary', 'uploadToCloudinary');
        Route::POST('/uploadToCloudinary1', 'uploadToCloudinary1');
        Route::POST('/uploadVideoToCloudinary', 'uploadVideoToCloudinary');
        Route::GET('/fetchVideoFromCloudinary', 'fetchVideoFromCloudinary');
        Route::GET('/fetchVideoFromCloudinary1', 'fetchVideoFromCloudinary1');


    });
Route::middleware('auth:api')->controller(EducationController::class)
    ->group(function () {
        Route::get('/getAllPendingExplanations', 'getAllPendingExplanations');
        Route::get('/getAllUploadedExplanations', 'getAllUploadedExplanations');
        Route::get('/getAllRejectedExplanations', 'getAllRejectedExplanations');
        Route::get('/getAllApprovedExplanations', 'getAllApprovedExplanations');
        Route::post('/approveExplanation/{id}', 'approveExplanation');
        Route::post('/rejectedExplanation/{id}', 'rejectedExplanation');
    });

/*
|--------------------------------------------------------------------------
| Products Management
|--------------------------------------------------------------------------
*/
Route::middleware('auth:api')->prefix('products')
    ->controller(ProductController::class)
    ->group(function () {
        Route::get('/', 'index');
        Route::get('/category/all', 'getAllCategories');
        Route::get('/category/{categoryId}', 'getProductsByCategory');
        Route::post('/', 'store');
        Route::get('/{id}', 'show');
        Route::post('/{id}', 'update');
        Route::delete('/{id}', 'destroy');
        Route::get('/product/filter', 'filter');
        Route::post('/accept/{id}', 'acceptProduct');
        Route::post('/unaccept/{id}', 'unacceptProduct');
        Route::get('/product/pending', 'getPendingProducts');
        Route::get('/product/rejected', 'getRejectedProducts');
        Route::get('/user/get_user_products', 'getUserProducts');
    });

/*
|--------------------------------------------------------------------------
| Buying Management
|--------------------------------------------------------------------------
*/

Route::middleware('auth:api')->prefix('baskets')
    ->controller(BuyingController::class)
    ->group(function () {
        Route::get('/', 'showUserBasket');
        Route::post('/{productId}', 'addToBasket');
        Route::delete('/remove/{productId}', 'removeFromBasket');
    });

Route::middleware('auth:api')->prefix('orders')
    ->controller(BuyingController::class)
    ->group(function () {
        Route::post('/', 'placeOrder');
        Route::get('/{orderId}', 'showOrder');
        Route::get('/pending', 'getPendingOrders');
        Route::get('/received', 'getReceivedOrders');
        Route::get('/unreceived', 'getUnreceivedOrders');
        Route::get('/done', 'getDoneOrders');
        Route::post('/order/received/{orderId}', 'updateOrderState');
        Route::post('/order/done/{orderId}', 'updateOrderStateToDone');
        Route::post('/order/unreceived/{orderId}', 'updateOrderStateToUnreceived');
        Route::get('/user/get_user_orders', 'getUserOrders');
    });
