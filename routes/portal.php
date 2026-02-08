<?php

use App\Http\Controllers\Portal\SalesmanController;
use App\Http\Controllers\Portal\ProductController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Portal\Auth\LoginController;
use App\Http\Controllers\Portal\Auth\Auth0LoginController;
use App\Http\Controllers\Portal\Auth\ForgotPasswordController;
use App\Http\Controllers\Portal\Auth\ResetPasswordController;
use App\Http\Controllers\Portal\DashboardController;
use App\Http\Controllers\Portal\CompanyController;
use App\Http\Controllers\Portal\CmsRoleController;
use App\Http\Controllers\Portal\ApplicationSettingController;
use App\Http\Controllers\Portal\UserController;
use App\Http\Controllers\Portal\CompanyAdminController;
use App\Http\Controllers\Portal\ManagerController;
use App\Http\Controllers\Portal\ClientController;
use App\Http\Controllers\Portal\ProductCategoryController;
use App\Http\Controllers\Portal\OrganizationTypeController;
use App\Http\Controllers\Portal\EventController;
use App\Http\Controllers\Portal\OrganizationController;
use App\Http\Controllers\Portal\EstimateController;
use App\Http\Controllers\Portal\InvoiceController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\Portal\ContractController;
use App\Http\Controllers\Portal\ReportingController;
use App\Http\Controllers\Portal\ContactActivityLogController;
use App\Http\Controllers\Portal\EstimateItemController;
use App\Http\Controllers\Portal\EstimateTaxController;
use App\Http\Controllers\Portal\EstimateDiscountController;
use App\Http\Controllers\Portal\EstimateInstallmentController;
use Auth0\Laravel\Facade\Auth0;


/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
*/

// Route::middleware(['guest:web'])->group(function () {

    //Route::match(['get', 'post'], 'login', [LoginController::class, 'login'])->name('admin.login');
//     Route::get('/portal/login', function () {
//     $query = http_build_query([
//         'client_id' => env('AUTH0_CLIENT_ID'),
//         'redirect_uri' => env('AUTH0_REDIRECT_URI'),
//         'response_type' => 'code',
//         'scope' => 'openid profile email',
//     ]);
//     return redirect('https://' . env('AUTH0_DOMAIN') . '/authorize?' . $query);
// })->name('admin.login');
// Route::get('/portal/login', function () {
//     return redirect('/auth0/login');
// })->name('admin.login');
// Route::get('/portal/callback', function () {
//     // Handle the Auth0 callback

//     dd([
//         'domain' => env('AUTH0_DOMAIN'),
//         'client_id' => env('AUTH0_CLIENT_ID'),
//         'client_secret' => env('AUTH0_CLIENT_SECRET'),
//         'redirect_uri' => env('AUTH0_REDIRECT_URI'),
//     ]);

//     $user = Auth0::getUser();

//     dd($user);

//     // if (!$user) {
//     //     return redirect()->route('admin.login')->with('error', 'Login failed.');
//     // }

//     // // Log in the user locally (optional)
//     // auth()->loginUsingId($user['sub']); // or map $user['email'] to local DB

//     // return redirect()->intended('/portal/dashboard'); 
// });

// Route::get('/portal/logout', function () {
//     auth()->logout();
//     return redirect('https://' . env('AUTH0_DOMAIN') . '/v2/logout?' . http_build_query([
//         'client_id' => env('AUTH0_CLIENT_ID'),
//         'returnTo' => url('/portal/login'),
//     ]));
// });

    // Route::get('/portal/login', [Auth0LoginController::class, 'login'])->name('admin.login');
    // Route::get('/portal/callback', [Auth0LoginController::class, 'callback'])->name('portal.callback');
    // Route::get('/portal/logout', [Auth0LoginController::class, 'logout'])->name('portal.logout');

    Route::match(['get', 'post'], 'forgot-password', [ForgotPasswordController::class, 'forgotPassword'])->name('admin.forgot-password');
    Route::match(['get', 'post'], 'reset-password/{any}', [ResetPasswordController::class, 'resetPassword'])->name('admin.reset-password');
    Route::match(['get', 'post'], 'create-password/{any}', [ResetPasswordController::class, 'createPassword'])->name('admin.create-password');

// });

//Route::middleware(['custom_auth:web'])->group(function () {

Route::middleware(['auth:web', 'third_party_api'])->group(function () {

    Route::match(['get', 'post'], 'user-profile', [CompanyAdminController::class, 'profile'])->name('admin.profile');
    Route::match(['get', 'post'], 'update-stripe-key', [CompanyAdminController::class, 'stripeKey'])->name('portal.update-stripe-key');
    Route::get('terms-and-conditions', [CompanyAdminController::class, 'termsAndConditions'])->name('portal.terms-and-conditions');
    Route::post('update-terms-and-conditions', [CompanyAdminController::class, 'updateTermsAndConditions'])->name('portal.update-terms-and-conditions');

    Route::match(['get', 'post'], 'change-password', [CompanyAdminController::class, 'changePassword'])->name('admin.change-password');
    //Route::get('logout', [CompanyAdminController::class, 'logout'])->name('admin.logout');

    Route::get('dashboard', [DashboardController::class, 'adminIndex'])->name('admin.dashboard');
    Route::get('company/dashboard', [DashboardController::class, 'companyIndex'])->name('company.dashboard');

    Route::get('company-management/ajax-listing', [CompanyController::class, 'ajaxListing'])->name('company-management.ajax-listing');
    Route::resource('company-management', CompanyController::class);

    Route::match(['get', 'post'], 'application-setting', [ApplicationSettingController::class, 'index'])->name('admin.application-setting');

    Route::get('manager/dashboard', [DashboardController::class, 'managerIndex'])->name('manager.dashboard');
    Route::get('manager-management/ajax-listing', [ManagerController::class, 'ajaxListing'])->name('manager-management.ajax-listing');
    Route::resource('manager-management', ManagerController::class);


    Route::get('salesman/dashboard', [DashboardController::class, 'salesmanIndex'])->name('salesman.dashboard');
    Route::get('salesman-management/ajax-listing', [SalesmanController::class, 'ajaxListing'])->name('salesman-management.ajax-listing');
    Route::resource('salesman-management', SalesmanController::class);


    Route::get('client/dashboard', [DashboardController::class, 'clientIndex'])->name('client.dashboard');
    Route::get('client-management/ajax-listing', [ClientController::class, 'ajaxListing'])->name('client-management.ajax-listing');
    Route::resource('client-management', ClientController::class);
    Route::get('/organization/fetch/{id}', [ClientController::class, 'fetch'])
        ->name('organization.fetch');


    Route::get('organization-type/ajax-listing', [OrganizationTypeController::class, 'ajaxListing'])->name('organization-type.ajax-listing');
    Route::resource('organization-type', OrganizationTypeController::class);

    Route::get('event-type/ajax-listing', [EventController::class, 'ajaxListing'])->name('event-type.ajax-listing');
    Route::resource('event-type', EventController::class);

    Route::get('organization/ajax-listing', [OrganizationController::class, 'ajaxListing'])->name('organization.ajax-listing');
    Route::resource('organization', OrganizationController::class);

    Route::get('product-category/ajax-listing', [ProductCategoryController::class, 'ajaxListing'])->name('product-category.ajax-listing');
    Route::resource('product-category', ProductCategoryController::class);

    Route::get('product/ajax-listing', [ProductController::class, 'ajaxListing'])
    ->name('product.ajax-listing');

    Route::get('product/api/create', [ProductController::class, 'createApi'])->name('product.api.create');
    Route::post('product/api/store', [ProductController::class, 'storeApi'])->name('product.api.store');

    Route::get('product/api/{id}/edit', [ProductController::class, 'editApi'])
        ->name('product.api.edit');

    Route::post('product/api/{id}', [ProductController::class, 'updateApi'])
        ->name('product.api.update');

    Route::resource('product', ProductController::class);


    Route::get('estimate/ajax-listing', [EstimateController::class, 'ajaxListing'])->name('estimate.ajax-listing');
    Route::post('estimate-save', [EstimateController::class, 'saveEstimate'])->name('estimate.save');
    Route::post('estimate-accept', [EstimateController::class, 'acceptEstimate'])->name('estimates.accept');
    Route::post('estimate-reject', [EstimateController::class, 'rejectEstimate'])->name('estimates.reject');
    Route::resource('estimate', EstimateController::class);


    Route::get('invoice/ajax-listing', [InvoiceController::class, 'ajaxListing'])->name('invoice.ajax-listing');
    Route::get('invoice-convert-to-installment/{slug}', [InvoiceController::class, 'convertToInstallment'])->name('invoice.convert-to-installment');
    Route::resource('invoice', InvoiceController::class);

    Route::get('/invoice/{invoice}/payment', [PaymentController::class, 'pay'])->name('invoice.pay');
    Route::get('/payment/success/{invoice}', [PaymentController::class, 'paymentSuccess'])->name('payment.success');
    Route::get('/payment/cancel/{invoice}', [PaymentController::class, 'paymentCancel'])->name('payment.cancel');

    Route::get('/invoice-installment/{invoice}/payment', [PaymentController::class, 'payInstallment'])->name('invoice-installment.pay');
    Route::get('/invoice-installment/success/{invoice}/{installmentPayment}/{installmentPlan}', [PaymentController::class, 'installmentPaymentSuccess'])->name('payment-installment.success');
    Route::get('/invoice-installment/cancel/{invoice}/{installmentPayment}/{installmentPlan}', [PaymentController::class, 'paymentCancel'])->name('payment-installment.cancel');

    Route::get('contract/ajax-listing', [ContractController::class, 'ajaxListing'])->name('contract.ajax-listing');
    Route::resource('contract', ContractController::class)->only(['index', 'show']);
    Route::post('contract-accept/{slug}', [ContractController::class, 'acceptContract'])->name('contract.accept');
    Route::post('contract-reject/{slug}', [ContractController::class, 'rejectContract'])->name('contract.reject');
    Route::post('contract-update/{slug}', [ContractController::class, 'updateContract'])->name('contract.update-contract');
    Route::post('contract-modify', [ContractController::class, 'modifyContract'])->name('contract.modify');


    Route::get('event-calander', [ContractController::class, 'eventCalander'])->name('event-calander');
    Route::get('credit-note/{slug}', [ContractController::class, 'addCreditNote'])->name('contract.add-credit-note');
    Route::post('credit-note', [ContractController::class, 'saveCreditNote'])->name('contract.save-credit-note');

    Route::post('update-invoice-status', [InvoiceController::class, 'updateInvoiceStatus'])->name('update-invoice-status');
    Route::post('update-installment-status', [InvoiceController::class, 'updateInstallmentStatus'])->name('update-installment-status');

    Route::get('report-company', [ReportingController::class, 'getAllCompanies'])->name('get=all-company');
    Route::get('company/ajax-listing', [ReportingController::class, 'ajaxListing'])->name('company.ajax-listing');
    Route::post('/organization/notes/save', [ContactActivityLogController::class, 'saveNotes'])
        ->name('organization.notes.save');


    Route::post('/estimate-products/add', [EstimateItemController::class, 'productAdd'])
    ->name('estimate.products.add');
    Route::post('/estimate-products/update', [EstimateItemController::class, 'updateItem'])
    ->name('estimate.products.update');
    Route::post('/estimate-products/delete', [EstimateItemController::class, 'deleteItem'])
    ->name('estimate.products.delete');
    Route::get('/estimate-products/get', [EstimateItemController::class, 'getItem'])->name('estimate.products.get');
    Route::post('/estimate-tax/add', [EstimateTaxController::class, 'productTaxAdd'])
     ->name('estimate.tax.add');
     Route::post('/estimate-tax/update', [EstimateTaxController::class, 'productTaxUpdate'])
    ->name('estimate.tax.update');
    Route::get('/get-estimate-tax/get-item', [EstimateTaxController::class, 'editGetItem'])
     ->name('estimate.tax.get');
    Route::delete('/estimate-tax/{tax}', [EstimateTaxController::class, 'deleteTax'])
    ->name('estimate.tax.delete');
    Route::post('/estimate-product-discount/add', [EstimateDiscountController::class, 'productDiscountAdd'])
     ->name('estimate.product.discount.add');
    Route::get('/get-product-discount/get-item', [EstimateDiscountController::class, 'getItem'])
     ->name('estimate.product.discount.get');
    Route::post(
    '/get-product-discount/update',
    [EstimateDiscountController::class, 'updateDiscount']
)->name('estimate.product.discount.update');
    Route::delete(
    '/get-product-discount/{id}/delete',
    [EstimateDiscountController::class, 'deleteDiscount']
)->name('estimate.product.discount.delete');
    Route::post('/estimate-installment/{estimate}/payment-save', [EstimateInstallmentController::class, 'savePaymentSchedule'])
    ->name('estimate.installments.save');

    Route::post('/estimate/note/save', [EstimateController::class, 'saveNote'])
    ->name('estimate.note.save');

    Route::post('/estimates-send-to-client/{estimate}', [EstimateController::class, 'sendToClient'])->name('estimates.send.to.client');

    Route::get('/no-permission', [UserController::class, 'noPermission'])
    ->name('no-permission');
    Route::get('/check-permission', [UserController::class, 'checkPermission'])
    ->name('check-permission');


});
