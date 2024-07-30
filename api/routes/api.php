<?php

use App\Http\Controllers\EnforcerController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\VerificationController;
use App\Http\Controllers\PasswordController;
use App\Http\Controllers\PermissionsController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\ImagesController;
use App\Http\Controllers\LogsController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\AboutSectionController;
use App\Http\Controllers\QuitterController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\ReminderController;
use App\Http\Controllers\FilesController;

use App\Http\Controllers\AddressController;
use App\Http\Controllers\ChartController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\TestimonyController;
use App\Http\Controllers\TrackController;
use Brick\Math\Exception\RoundingNecessaryException;


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


#auth
$middleAuth = (bool)env('APP_AUTH_TOKEN', false) ? 'auth:api' : 'auth:sanctum';

Route::get('hatdog', [FilesController::class, 'upload']);



Route::middleware([$middleAuth, 'throttle:60,1', 'disabled'])->group(function () {
    Route::middleware('verified', 'throttle:60,1')->group(function () {

        #region Account Management
        Route::post('/user/image', [ProfileController::class, 'saveAvatar']);
        Route::patch('/user/image', [ProfileController::class, 'changeAvatar']);
        Route::patch('/user/password', [ProfileController::class, 'changePassword']);
        Route::post('/user/profile', [ProfileController::class, 'changeProfile']);
        Route::patch('/user/account', [ProfileController::class, 'changeAccount']);
        Route::delete('/user/image', [ProfileController::class, 'deleteImage']);
        #endregion

        #region Permissions

        Route::get('permissions', [PermissionsController::class, 'permissionSearch']);
        Route::get('all-permissions', [PermissionsController::class, 'getPermission']);
        Route::post('/permissions', [PermissionsController::class, 'createPermission']);
        Route::patch('/permissions/{id}', [PermissionsController::class, 'updatePermission']);
        Route::delete('/permissions/{id}', [PermissionsController::class, 'permissionDelete']);
        #endregion

        #region Roles
        Route::get('/roles', [PermissionsController::class, 'roleSearch']);
        Route::get('/roles/permissions', [PermissionsController::class, 'rolePermissionSearch']);
        Route::get('/roles/{id}', [PermissionsController::class, 'getRole']);
        Route::post('/roles', [PermissionsController::class, 'createRole']);
        Route::patch('/roles/{id}', [PermissionsController::class, 'updateRole']);
        Route::delete('/roles/{id}', [PermissionsController::class, 'deleteRole']);
        #endregion

        #region Users
        Route::get('/users', [UsersController::class, 'userSearch']);
        Route::get('/users/permissions', [UsersController::class, 'getPermissions']);
        Route::get('/users/roles', [UsersController::class, 'getRoles']);
        Route::get('/users/gender', [UsersController::class, 'getGenders']);
        Route::get('/users/i/{username}', [UsersController::class, 'getUserByUsername']);
        Route::get('/users/{id}', [UsersController::class, 'getUserById']);
        Route::get('/users/stathistory/{id}', [UsersController::class, 'getUserStatusHistory']);
        Route::get('/get-permissions/{id}',[UsersController::class, 'getUserPermissions']);

        Route::post('/users/create', [UsersController::class, 'addUser']);
        Route::post('/users/profile/{id}', [UsersController::class, 'updateUserProfile']);
        Route::post('/users/avatar/{id}', [UsersController::class, 'saveAvatar']);
        Route::patch('/users/avatar/{id}', [UsersController::class, 'changeAvatar']);
        Route::delete('/users/delete-avatar/{id}',[UsersController::class, 'deleteAvatar']);
        // Route::delete('/users/delete-avatar/{id}', [UsersController::class, 'deleteAvatar']);

        Route::post('/users/edit-profile/{hash}', [UsersController::class, 'createOrUpdateUserProfile']);
        Route::patch('/users/main-address/{id}', [UsersController::class, 'setAddressAsMain']);
        Route::patch('/users/address-update', [UsersController::class, 'updateAddress']);
        Route::delete('/users/address-delete', [UsersController::class, 'deleteAddress']);

        Route::patch('/users/permissions/{id}', [UsersController::class, 'setPermissions']);
        Route::patch('/users/disable/{id}', [UsersController::class, 'toggleUserActive']);
        Route::patch('/users/verify/{email}', [UsersController::class, 'verifyEmail']);
        Route::patch('/users/account/{id}', [UsersController::class, 'updateAccount']);
        Route::patch('/users/password/{id}', [UsersController::class, 'updatePassword']);

        #endregion

        #region Address
        Route::get('/address/initial/barangay/{Code}', [AddressController::class, 'getBarangayAddress']);
        Route::get('/address/initial/city/{Code}', [AddressController::class, 'getCityAddress']);
        Route::get('/address/regions', [AddressController::class, 'Regions']);
        Route::get('/address/provinces/{regionCode}', [AddressController::class, 'Provinces']);
        Route::get('/address/cities/{provinceCode}', [AddressController::class, 'Cities']);
        Route::get('/address/barangays/{barangayCode}', [AddressController::class, 'Barangays']);
        Route::get('/address/types', [AddressController::class, 'getAddressTypes']);
        #endregion

        #region Logs
        Route::get('/logsy/{year}/{month?}/{day?}', [LogsController::class, 'getLogs']);
        #endregion


    });

    Route::get('/auth/permissions', [AuthenticationController::class, 'getPermissions']);
    Route::post('/auth/logout', [AuthenticationController::class, 'Logout']);

    #region Authentication
    Route::post('/email/resend', [VerificationController::class, 'resend']);
    Route::get('/email/isVerified', [VerificationController::class, 'checkifverified']);
    #endregion

    #region Product
    Route::post('/product/create', [ProductController::class, 'create']);
    Route::get('/product/list', [ProductController::class, 'list']);
    Route::patch('/product/edit', [ProductController::class, 'update']);
    Route::delete('/product/delete', [ProductController::class, 'delete']);
    Route::get('/product/{hash}', [ProductController::class, 'show']);


    #endregion

    #region About Section
    Route::get('/admin/about', [AboutSectionController::class, 'getSectionList']);
    Route::get('/admin/about/section', [AboutSectionController::class, 'getSection']);
    Route::post('/admin/about/create', [AboutSectionController::class, 'createSection']);
    Route::post('/admin/about/arrange-sections', [AboutSectionController::class, 'arrangeSections']);
    Route::post('/admin/about/upload-photo', [AboutSectionController::class, 'uploadPhoto']);
    Route::post('/admin/about/section-show', [AboutSectionController::class, 'showInPublic']);
    Route::patch('/admin/about/update', [AboutSectionController::class, 'updateSection']);
    Route::delete('/admin/about/delete-images', [AboutSectionController::class, 'deleteImages']);
    Route::delete('/admin/about/delete-section', [AboutSectionController::class, 'deleteSection']);


    #endregion

    #region Quitter
    Route::get('/quitter/list', [QuitterController::class, 'list']);
    Route::patch('/quitter/reject', [QuitterController::class, 'reject']); //update the admin remarks
    Route::delete('/quitter/delete', [QuitterController::class, 'delete']);
    Route::patch('/quitter/update', [QuitterController::class, 'accept']);
    Route::patch('/quitter/reshedule',[QuitterController::class, 'reschedule']);
    Route::get('/quitter/filter',[QuitterController::class, 'filterData']);
    // Route::get('/quitter/accepted-list', [QuitterController::class, 'listAccepted']);
    // Route::get('/quitter/rejected-list', [QuitterController::class, 'listRejected']);
    #endregion

    #region Report
    Route::get('/report/list', [ReportController::class, 'list']);
    Route::patch('/report/reject', [ReportController::class, 'reject']);
    Route::delete('/report/delete', [ReportController::class, 'delete']);
    Route::patch('/report/update', [ReportController::class, 'resolve']);
    Route::get('/report/rejected-list', [ReportController::class, 'listReject']);
    Route::get('/report/resolved-list', [ReportController::class, 'listResolve']);
    Route::get('/report/filter', [ReportController::class, 'filterData']);
    #endregion

    #region Testimony
    Route::post('/testimony/create', [TestimonyController::class, 'create']);
    Route::get('/testimony/list', [TestimonyController::class, 'list']);
    Route::get('/testimony/deleted-list', [TestimonyController::class, 'listDeleted']);
    Route::get('/testimony/{hash}', [TestimonyController::class, 'show']);
    Route::get('testimony-deleted/{hash}', [TestimonyController::class, 'showDeleted']);
    Route::get('testimony-restore/{hash}',[TestimonyController::class, 'restoreTestimony']);
    Route::patch('/testimony/update', [TestimonyController::class, 'update']);
    Route::delete('/testimony/delete', [TestimonyController::class, 'delete']);
    Route::delete('/testimony/delete-photo', [TestimonyController::class, 'deleteImage']);
    #endregion

    #region EVENTS
    Route::post('/events/create', [EventController::class, 'createEvent']);
    Route::post('/events/create/photo', [EventController::class, 'uploadPhoto']);
    Route::post('EditEvent/create/photo', [EventController::class, 'uploadPhoto']);
    Route::delete('/events/delete', [EventController::class, 'delete']);
    Route::get('/events/list', [EventController::class, 'list']);
    Route::get('admin/events/deletedEvents', [EventController::class, 'listDeleted']);
    Route::patch('admin/events/EditEvent/', [EventController::class, 'update']);
    Route::delete('/EditEvent/delete/photo', [EventController::class, 'deleteImage']);
    Route::get('admin/events/{hash}', [EventController::class, 'show']);
    Route::get('admin/events/deletedEvents/{hash}', [EventController::class, 'showDeleted']);
    Route::get('event-restore/{hash}',[EventController::class, 'restoreEvent']);



    #endregion
    #region ARTICLE
    Route::post('admin/articles/AddArticle/create', [ArticleController::class, 'create']);
    Route::post('/articles/AddArticle/create/photo', [ArticleController::class, 'uploadPhoto']);
    Route::post('admin/articles/EditArticle/create/photo', [ArticleController::class, 'uploadPhoto']);
    Route::get('/articles/list', [ArticleController::class, 'list']);
    Route::get('/articles/deletedArticles', [ArticleController::class, 'listDeleted']);
    // Route::get('admin/articles/ViewArticle/',[ArticleController::class.'show']);
    Route::patch('admin/articles/EditArticle/', [ArticleController::class, 'update']);
    Route::delete('admin/articles/delete', [ArticleController::class, 'delete']);
    Route::delete('admin/articles/EditArticle/photo', [ArticleController::class, 'deleteImage']);
    Route::get('admin/articles/{hash}', [ArticleController::class, 'show']);
    Route::get('/admin/articles/ListOfDeletedArticles/{hash}', [ArticleController::class, 'showDeleted']);
    Route::get('article-restore/{hash}',[ArticleController::class, 'restoreArticle']);


    #endregion
    #region MESSAGE ADMIN
    Route::get('admin/messages/list', [MessageController::class, 'list']);
    Route::get('admin/message/get', [MessageController::class, 'show']);
    Route::get('admin/message/get-deleted', [MessageController::class, 'getDeletedMessages']);
    Route::delete('admin/messages/delete', [MessageController::class, 'delete']);
    Route::get('message-restore/{hash}',[MessageController::class, 'restoreMessage']);

    #endregion

    #region Enforcer Room
    Route::post('/admin/enforcer-room/upload-file', [FilesController::class,'uploadNewForm']);
    Route::get('/admin/enforcer-room/get-uploaded-form', [FilesController::class,'getUploadedForm']);
    Route::get('form-restore/{hash}',[FilesController::class, 'restoreForm']);

    #endregion

});

#public
Route::middleware('api', 'throttle:60,1')->group(function () {
    #region Authentication
    Route::post('/auth/login', [AuthenticationController::class, 'login']);
    // Route::post('/auth/register', [AuthenticationController::class, 'register']);
    Route::post('/password/forgot', [PasswordController::class, 'forgot_password'])->name('password.email');
    Route::post('/password/reset', [PasswordController::class, 'change_password'])->name('password.update');
    #endregion

    #region Public
    Route::get('/logo/{size?}', [ImagesController::class, 'logo'])->name('app.logo');
    #endregion

    #region Email Verification
    Route::get('/email/verify/{id}', [VerificationController::class, 'verify'])->name('verification.verify');
    #endregion

    #region Barangay
    Route::get('/address/get-barangays/{cityCode}', [AddressController::class, 'listBarangay']);
    #endregion

    #region Quitter
    Route::post('/quitter/create', [QuitterController::class, 'create']);
    #endregion

    #region Report
    Route::post('report/create', [ReportController::class, 'create']);
    #endregion

    #region Track
    Route::get('/track',[TrackController::class, 'track']);
    #endregion

    #region Testimony
    Route::get('/testimony-public/list', [TestimonyController::class, 'list']);
    Route::get('/testimony-public/{hash}', [TestimonyController::class, 'show']);
    #endregion

    #region About
    Route::get('/public/about', [AboutSectionController::class, 'sectionList']);
    #end region

    #region Enforcers
    Route::get('/public/enforcement/get-reminders',[EnforcerController::class,'getReminders']);
    Route::delete('/public/enforcement/delete-form',[FilesController::class,'deleteForm']);
    Route::get('/public/enforcement/get-deleted-forms',[FilesController::class,'getDeleteForms']);
    Route::get('/public/enforcement/get-enforcement-forms',[FilesController::class,'getPublicEnforcementForms']);
    Route::get('/admin/enforcer-room/get-uploaded-forms', [FilesController::class,'getUploadedForms']);
    Route::get('/admin/enforcer-room/get-submitted-forms', [FilesController::class,'getSubmittedForms']);
    Route::post('/admin/enforcer-room/add-new-reminder', [EnforcerController::class,'addNewReminder']);
    Route::get('/admin/enforcer-room/get-specific-reminder', [EnforcerController::class,'getSpecificReminder']);
    Route::patch('/admin/enforcer-room/edit-specific-reminder', [EnforcerController::class,'editSpecificReminder']);
    Route::delete('/admin/enforcer-room/delete-reminder', [EnforcerController::class,'deleteSpecificReminder']);

    #end region

    #region chart
    Route::get('/public/enforcement/get-quitter-report',[ChartController::class,'getQuitterReport']);
    #end region
});

// unthrottled routes
Route::middleware([$middleAuth, 'disabled'])->group(function () {
    Route::middleware('verified')->group(function () {
        // Photo Upload
        Route::post('/product/create/photo', [ProductController::class, 'uploadPhoto']);
        Route::post('/testimony/create-photo', [TestimonyController::class, 'uploadPhoto']);
    });
});

// Unthrottled public routes
Route::middleware('api')->group(function () {
    # Images
    Route::get('/image/{thumbsize}/{hash}', [ImagesController::class, 'display_thumb'])->name('image.thumb');
    Route::get('/image/{hash}', [ImagesController::class, 'display'])->name('image.display');
    # End - Images
    #Upload Image
    Route::post('report/create-photo', [ReportController::class, 'uploadPhoto']);

    #regionend


    Route::get('/admin/enforcer-room/get-uploaded-form-preview/{id}', [FilesController::class,'getUploadedFormPreview'])->name('file.display');
    Route::get('/public/enforcer-room/download-form', [FilesController::class,'downloadForm']);
    Route::post('/public/enforcer-room/submit-file', [FilesController::class,'submitForm']);
    Route::patch('/public/set-submitted-by', [FilesController::class,'setSubmitBy']);
});


#region EVENTS
Route::post('/events/create', [EventController::class, 'createEvent']);
Route::delete('/events/delete', [EventController::class, 'delete']);
Route::get('/events/list', [EventController::class, 'list']);
Route::patch('admin/events/EditEvent/', [EventController::class, 'update']);
Route::get('admin/events/{hash}', [EventController::class, 'show']);
Route::post('events/create-photo', [EventController::class, 'uploadPhoto']);

#endregion

#region EVENTS PUBLIC
Route::get('/events/list', [EventController::class, 'list']);
Route::get('/events/{hash}', [EventController::class, 'show']);

#endregion

#region ARTICLE
Route::get('/articles/list', [ArticleController::class, 'list']);
Route::get('/articles/{hash}', [ArticleController::class, 'show']);

#endregion

#region MESSAGE PUBLIC
Route::post('public/home/create', [MessageController::class, 'create']);
    #endregion
