<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\UserController;
use App\Http\Controllers\TrainingLibraryController;
use App\Http\Controllers\Admin\UsersController;
use App\Http\Controllers\Crm\CrmDashboard;
use App\Http\Controllers\Appointment\AppointmentController;
use App\Http\Controllers\Users\SettingsController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\Drip\DripCampaignsController;

use App\Models\User;

use Illuminate\Foundation\Auth\EmailVerificationRequest;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Events\MessageNotification;


// APIs for Authentication Only
Route::prefix('/auth')->group(function () {
    // Auth APIs
    Route::controller(AuthController::class)->group(function () {
        Route::post('login',                      'login')->name('user.login');
        Route::post('register',                   'register')->name('user.register');
    });

    Route::group(['middleware' => 'auth:sanctum'], function () {


        // Training Library Controller
        Route::controller(TrainingLibraryController::class)->group(function () {
            Route::post('save-training-library', 'saveTrainingLibrary');
            Route::delete('delete-training-library/{id}', 'deleteTrainingLibrary');
            Route::post('update-training-library/{id}', 'updateTrainingLibrary');
            Route::get('get-training-libraries', 'getTrainingLibraries');
            Route::get('get-training-libraries-with', 'getTrainingLibrariesWith');

            Route::post('save-training-library-topic', 'saveTrainingLibraryTopic');
            Route::get('get-training-library-topics', 'getTrainingLibraryTopics');
            Route::get('get-training-library-topics-all', 'getTrainingLibraryTopicsAll');
            Route::patch('/training-library-topic/{id}/display', 'toggleDisplay');
            Route::post('update-training-library-topic/{id}', 'updateTrainingLibraryTopic');
            Route::delete('delete-training-library-topic/{id}', 'deleteTrainingLibraryTopic');

            Route::patch('/training-library/{id}/favorite', 'toggleFavorite');
            Route::patch('/training-library/{id}/complete', 'markAsCompleted');
            Route::get('/training-library/favorites', 'getFavoriteVideos');
            Route::get('/training-library/completed', 'getCompletedVideos');
        });

        Route::controller(AuthController::class)->group(function () {
            // Logout
            Route::get('logout', 'logout');
            // Email Verification
            Route::post('/code/verification',     'code')->name('code.verification');
            // Password Reset Email Verification
            Route::post('/code/password',         'codepassword')->name('code.password');
        });

        // Email Verification Resend
        Route::post('/email/verification-notification', function (Request $request) {
            $request->user()->sendEmailVerificationNotification();
            return response('', 200);
        })->middleware(['throttle:6,1'])->name('verification.send');

        // Email Verification Pre-requisite
        Route::get('/{id}/{hash}', function (EmailVerificationRequest $request) {
            $request->fulfill();
            return response('', 200);
        })->middleware(['signed'])->name('verification.verify');

        // User Registration steps
        Route::controller(UserController::class)->group(function () {
            // Register - More Information
            Route::put('more-information',        'more_information')->name('user.more-information');
            // Register - Team Members
            Route::post('team-members',           'team_members')->name('user.team-members');
        });
    });


    // Password Reset Email Verification
    Route::post('/email/verification-notification/password', function (Request $request) {
        $request->validate([
            'email' => 'required|email'
        ]);

        try {
            $user = User::where('email', $request->email)->first();

            if (!$user) {
                return response()->json(['message' => 'User not found'], 404);
            }

            $user->sendEmailVerificationNotification();

            return response()->json(['message' => 'Verification email sent successfully.'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occurred while sending the verification email. Please try again later.'], 500);
        }
    })->middleware(['throttle:6,1'])->name('verification.send');




    // API Forgot Password 
    Route::post('/forgot-password', [ForgotPasswordController::class, 
    'sendResetLinkEmail']);

    Route::post('/reset-password', [ForgotPasswordController::class, 
    'passwordUpdate'])->name('password.update');
});

// APIs not related to Authentication
Route::group(['middleware' => 'auth:sanctum'], function () {
    // Appointments
    Route::controller(AppointmentController::class)->group(function () {
        Route::get('appointment',                  'index');
        Route::post('appointment',                 'store');
        Route::put('appointment',                  'update');
    });

    // CRM Dashboard
    Route::controller(CrmDashboard::class)->group(function () {
        Route::get('crm-dashboard-info/{page}/{itemPerPage}/{userType}',    'index');
        Route::post('add-contact/{page}/{itemPerPage}/{userType}',          'store');
        Route::post('search-contact/{page}/{itemPerPage}/{userType}',       'show');
        Route::put('edit-contact/{page}/{itemPerPage}/{userType}',          'edit');
    });

    // Drip Campaigns
    Route::controller(DripCampaignsController::class)->group(function () {
        Route::get('drip-campaigns-info/{page}/{itemPerPage}/{userType}',       'index');
        Route::post('add-drip-campaign/{page}/{itemPerPage}/{userType}',        'store');
        Route::post('search-drip-campaign/{page}/{itemPerPage}/{userType}',     'show');
        Route::put('update-drip-campaign/{page}/{itemPerPage}/{userType}',      'edit');
    });

    // Admin - Manage Users
    Route::controller(UsersController::class)->group(function () {
        Route::get('users',                        'index');
    });

    // User Profile
    Route::controller(UserController::class)->group(function () {
        Route::get('user',                                'index');
        Route::get('user/{id}',                           'show');
        Route::post('user/image/{id}',                    'image')->name('user.image');
        Route::put('user/update/firstname/{id}',          'firstname')->name('user.firstname');
        Route::put('user/update/lastname/{id}',           'lastname')->name('user.lastname');
        Route::put('user/update/username/{id}',           'username')->name('user.username');
        Route::put('user/update/phone/{id}',              'phone')->name('user.phone');
        Route::put('user/update/email/{id}',              'email')->name('user.email');
        Route::put('user/update/password/{id}',           'password')->name('user.password');
        Route::put('user/update/realtor_license_no/{id}', 'realtor_license_no')->name('user.realtor_license_no');
        Route::put('user/update/postal_zip/{id}',         'postal_zip')->name('user.postal_zip');
        Route::put('user/update/country/{id}',            'country')->name('user.country');
        Route::put('user/update/state_province/{id}',     'state_province')->name('user.state_province');
        Route::put('user/update/city_municipality/{id}',  'city_municipality')->name('user.city_municipality');
    });

    // Users - User Settings
    Route::controller(SettingsController::class)->group(function () {
        Route::put('update-timezone', 'update_timezone');
    });

    
});

// Independent route for password reset
Route::get('/reset-password/{token}', [ForgotPasswordController::class, 'passwordReset'])->name('password.reset');

// Route::get('/event', function() {
//     event(new MessageNotification('First message'));
// });
// Route::get('/listen', function() {
//     return view('listen');
// });

Route::post('/broadcast-event', function (Request $request) {
    $message = $request->input('message');
    broadcast(new MessageNotification($message));
    return response()->json(['status' => 'Event broadcasted']);
});

// Notification routes
Route::controller(NotificationController::class)->group(function () {
    Route::post('/save-notification', 'saveNotification');
    Route::get('/notifications/{userId}', 'getNotificationsByUserId');
    Route::delete('/notifications/{id}', 'deleteNotification');
    Route::patch('/notifications/{id}/seen', 'markAsSeen');
    Route::get('/public-notifications', 'getPublicNotifications');
});

Route::post('/public-notification', [NotificationController::class, 'savePublicNotification']);
