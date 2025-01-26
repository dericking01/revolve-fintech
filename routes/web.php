<?php

use App\Http\Controllers\admin\AdminDashboardController;
use App\Http\Controllers\admin\AdminLoginController;
use App\Http\Controllers\admin\invoice\InvoiceController;
use App\Http\Controllers\admin\order\OrdersController;
use App\Http\Controllers\admin\products\ProductsController;
use App\Http\Controllers\admin\AdminsController;
use App\Http\Controllers\admin\BorrowersController;
use App\Http\Controllers\admin\loans\LoansController;
use App\Http\Controllers\admin\products\AdminProductExportController;
use App\Http\Controllers\admin\reports\ReportsController;
use App\Http\Controllers\admin\StaffsController;
use App\Http\Controllers\admin\transactions\TransactionsController;
use App\Http\Controllers\admin\UsersController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\SmsController;
use App\Http\Controllers\staff\customer\StaffCustomerController;
use App\Http\Controllers\staff\DeliveryController;
use App\Http\Controllers\staff\order\StaffOrdersController;
use App\Http\Controllers\staff\products\StaffProductsController;
use App\Http\Controllers\staff\StaffLoginController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('admin.auth.login');
});

// administrator authentication routes
Route::group(['prefix' => 'admin'], function () {
    Route::get('login', [AdminLoginController::class, 'login'])->name('admin.login');
    Route::post('login/submit', [AdminLoginController::class, 'submit'])->name('admin.submit.login');
    Route::post('logout', [AdminLoginController::class, 'logout'])->name('admin.logout');
});

//Reset Password Routes
Route::group(['prefix' => 'mypass'], function () {
    Route::get('forgotpassword', [ForgotPasswordController::class, 'index'])->name('forgotpassword');
    Route::post('forgotpwd', [ForgotPasswordController::class, 'forgotPasswordPost'])->name('forgotpwd');
    Route::get('reset-password/{token}', [ForgotPasswordController::class, 'resetPassword'])->name('reset.password');
    Route::post('reset-password}', [ForgotPasswordController::class, 'resetPasswordPost'])->name('resetpassword.post');
});

// SMS api routes
Route::get('/send-sms', [SmsController::class, 'sendSingleSms']);
Route::get('/send-bulk-sms', [SmsController::class, 'sendBulkSms']);

// administrator routes
Route::group(['prefix' => 'admin'], function () {


    // dashboard routes
    Route::group(['prefix' => 'dashboard', 'middleware' => ['admin']], function () {
        Route::get('home', [AdminDashboardController::class, 'home'])->name('admin.dashboard.home');

    });


    //Management routes.
    Route::group(['middleware' => ['admin']], function () {

        //users routes

        //admin routes
         Route::group(['prefix' => 'admin'], function () {
            Route::get('admin', [AdminsController::class, 'index'])->name('admin.listadmins');
            Route::post('storeadmin', [AdminsController::class, 'store'])->name('admin.storeadmin');
            Route::put('update/admin{id}', [AdminsController::class, 'update'])->name('admin.update');
            Route::delete('destroy/admin', [AdminsController::class, 'destroy'])->name('admin.destroyadmin');

            Route::get('staff', [StaffsController::class, 'index'])->name('admin.liststaffs');
            Route::post('storestaff', [StaffsController::class, 'store'])->name('admin.storestaff');
            Route::put('update/staff{id}', [StaffsController::class, 'update'])->name('admin.updatestaff');
            Route::delete('destroy/staff', [StaffsController::class, 'destroy'])->name('admin.destroystaff');

         });


         //vetlink-users routes
        Route::group(['prefix' => 'revinfo'], function () {
            Route::get('users', [UsersController::class, 'index'])->name('admin.listusers');
            Route::delete('destroy-buyer', [UsersController::class, 'destroyBuyer'])->name('agent.destroybuyer');
            Route::get('sellers', [UsersController::class, 'sellers'])->name('admin.listsellers');
            Route::put('update-seller/{id}', [UsersController::class, 'updateSeller'])->name('seller.update');
            Route::delete('destroy-seller', [UsersController::class, 'destroySeller'])->name('seller.destroyseller');
            Route::get('view-seller/{id}', [UsersController::class, 'viewSeller'])->name('seller.view');
            Route::get('agents', [UsersController::class, 'agents'])->name('admin.listagents');
            Route::get('borrowers', [BorrowersController::class, 'borrowers'])->name('admin.listborrowers');
            Route::post('storeborrower', [BorrowersController::class, 'storeBorrower'])->name('admin.storeborrower');
            Route::put('update-borrower/{id}', [BorrowersController::class, 'updateBorrower'])->name('admin.borrowerupdate');
            Route::delete('destroy-borrower', [BorrowersController::class, 'destroy'])->name('borrower.destroy');
            Route::get('view-borrower/{id}', [BorrowersController::class, 'viewBorrower'])->name('agent.view-borrower');
            Route::get('view-loan/{id}', [BorrowersController::class, 'viewLoan'])->name('borrower.view-loan');


            Route::post('storeagent', [UsersController::class, 'storeAgent'])->name('admin.storeagent');
            Route::put('update/{id}', [UsersController::class, 'updateAgent'])->name('agent.update');
            Route::delete('destroy', [UsersController::class, 'destroy'])->name('agent.destroyagent');
            Route::get('view-agent/{id}', [UsersController::class, 'viewAgent'])->name('agent.view-agent');
            Route::get('view-agentcard/{id}', [UsersController::class, 'viewAgentCard'])->name('agent.view-agent_card');
            Route::get('view-user/{id}', [UsersController::class, 'viewUser'])->name('admin.view-user');
            Route::get('branches', [UsersController::class, 'branches'])->name('admin.listbranches');
            Route::post('storebranch', [UsersController::class, 'storeBranch'])->name('admin.storebranch');
            Route::put('update-branch/{id}', [UsersController::class, 'updateBranch'])->name('branch.update');
            Route::delete('destroy-branch', [UsersController::class, 'destroyBranch'])->name('branch.destroybranch');
            Route::get('branch-details/{id}',[UsersController::class, 'viewBranch'])->name('admin.branch.details');
            Route::get('branch-stock/{id}',[UsersController::class, 'viewBranchStock'])->name('admin.branch.stock');
            Route::put('branchproduct-update/{id}', [UsersController::class, 'updateBranchProduct'])->name('branchproduct.update');


        });

        //Products routes

        //  admin products
        Route::group(['prefix' => 'admin-products'], function () {
            Route::get('list', [ProductsController::class, 'index'])->name('admin.products.listproducts');
            Route::get('warehouse', [ProductsController::class, 'warehouseIndex'])->name('admin.warehouse.products');
            Route::post('distribute-product', [ProductsController::class, 'distributeProduct'])->name('admin.products.distributeProduct');
            Route::put('update-distribution/{id}', [ProductsController::class, 'updateDistribution'])->name('admin.distribution.update');
            Route::delete('destroy-distribution', [ProductsController::class, 'destroyDistribution'])->name('admin.distribution.destroy');
            Route::post('store', [ProductsController::class, 'store'])->name('admin.products.store');
            Route::put('update/{id}', [ProductsController::class, 'update'])->name('admin.products.update');
            Route::delete('destroy', [ProductsController::class, 'destroy'])->name('admin.products.destroy');
            Route::get('product-details/{id}',[ProductsController::class, 'details'])->name('admin.products.details');
            Route::get('export-admin-products', [AdminProductExportController::class, 'export'])->name('admin.products.export');
            Route::post('import-products', [AdminProductExportController::class, 'import'])->name('admin.products.import');
        });


        //  Orders Routes
        Route::group(['prefix' => 'orders'], function () {
            Route::get('create-order', [OrdersController::class, 'orderForm'])->name('admin.createOrder');
            Route::get('create-loan', [LoansController::class, 'LoanForm'])->name('admin.createLoan');
            Route::post('store-loan', [LoansController::class, 'storeLoan'])->name('admin.storeLoan');
            Route::get('pending-loans', [LoansController::class, 'pendingLoansindex'])->name('admin.pendingLoans');
            Route::put('update-loan/{id}', [LoansController::class, 'updateLoan'])->name('admin.loan.update');
            Route::delete('destroy-loan', [LoansController::class, 'deleteLoan'])->name('admin.loan.delete');

            Route::post('store', [OrdersController::class, 'store'])->name('admin.storeOrder');
            Route::get('pendingorder', [OrdersController::class, 'pendingOrderindex'])->name('admin.pendingOrder');
            Route::put('update/{id}', [OrdersController::class, 'updateOrder'])->name('admin.pendingOrder.update');
            Route::delete('destroy', [OrdersController::class, 'destroyOrder'])->name('admin.order.destroy');
            Route::get('rejectedorder', [OrdersController::class, 'rejectedOrderindex'])->name('admin.rejectedorder');
            Route::get('partialorder', [OrdersController::class, 'partialOrderindex'])->name('admin.partialorder');
            Route::put('update-partialorder/{id}', [OrdersController::class, 'updatePartialOrder'])->name('admin.partialOrder.update');
            Route::get('paypointorder', [OrdersController::class, 'payPointOrderindex'])->name('admin.paypointorder');
            Route::get('completedorder', [OrdersController::class, 'completedOrderindex'])->name('admin.completedorder');
            Route::get('view-order/{id}', [OrdersController::class, 'viewOrder'])->name('admin.orders.vieworder');
        });

        // Reports routes
        Route::group(['prefix' => 'reports'], function () {
            Route::get('sales', [ReportsController::class, 'salesReport'])->name('reports.sales');
            Route::get('prod-distributions', [ReportsController::class, 'DistributionReport'])->name('reports.product-distributions');
            Route::get('stock-report', [ReportsController::class, 'StockReport'])->name('reports.stock');
            Route::get('/export-product-distributions', [ReportsController::class, 'exportProductDistributions'])->name('export.product-distributions');
            Route::get('/export-stock', [ReportsController::class, 'exportStock'])->name('export.stock-report');
            Route::get('/export-salesrpt', [ReportsController::class, 'exportSales'])->name('export.sales-report');

        });

        // Invoice routes
        Route::group(['prefix' => 'invoices'], function () {
            Route::get('pendinginvoices', [InvoiceController::class, 'pendingInvoiceindex'])->name('admin.pendingInvoice');
            Route::delete('destroy', [InvoiceController::class, 'destroyInvoice'])->name('admin.pendingInvoice.destroy');
            Route::get('paidinvoices', [InvoiceController::class, 'paidInvoices'])->name('admin.paidInvoice');
            Route::get('overdueinvoices', [InvoiceController::class, 'overDueInvoices'])->name('admin.overDueInvoice');

        });

        // Transactions routes
        Route::group(['prefix' => 'transactions'], function () {
            Route::get('withdrawalReq', [TransactionsController::class, 'index'])->name('admin.withdrawalReq');
            Route::get('settledTrans', [TransactionsController::class, 'settledTrans'])->name('admin.settledTrans');
            Route::put('update/{id}', [TransactionsController::class, 'approve'])->name('admin.settledTrans.approve');
            Route::put('reject/{id}', [TransactionsController::class, 'reject'])->name('admin.settledTrans.reject');

        });

    });
});

// staff authentication routes
Route::group(['prefix' => 'staff'], function () {
    Route::get('login', [StaffLoginController::class, 'login'])->name('staff.login');
    Route::post('login/submit', [StaffLoginController::class, 'submit'])->name('staff.submit.login');
    Route::post('logout', [StaffLoginController::class, 'logout'])->name('staff.logout')->middleware('staff');
});

// Routes accessible only to delivery staff
Route::middleware(['auth:staff', 'role:delivery'])->group(function () {

    Route::group(['prefix' => 'staff'], function () {

        Route::get('/deliveries', [DeliveryController::class, 'index'])->name('staff.deliveries');
        Route::put('deliveryorder/update/{id}', [DeliveryController::class, 'UpdateDeliveryStatus'])->name('staff.deliverystatus.update');

        // Add other delivery-specific routes here

    });

});

// Routes accessible only to orderman staff
Route::middleware(['auth:staff', 'role:orderman'])->group(function () {

    Route::group(['prefix' => 'staff'], function () {

        Route::get('orderman-create', [DeliveryController::class, 'orderForm'])->name('orderman.orders');
        Route::post('orderman-store', [DeliveryController::class, 'store'])->name('orderman.storeOrder');
        Route::put('order/update/{id}', [DeliveryController::class, 'updateOrder'])->name('staff.orderstatus.update');

    });

});

// Routes accessible only to Stock Auditor staff
Route::middleware(['auth:staff', 'role:auditor'])->group(function () {

    Route::group(['prefix' => 'auditor'], function () {

        Route::get('distributions', [ProductsController::class, 'auditDistributions'])->name('audit.warehouse.products');

    });

});

// A route for access denied page
Route::get('/access-denied', function () {
    return response()->view('errors.access_denied', [], 403);
})->name('access.denied');

Route::get('/error', function () {
    abort(500);
});



// Routes accessible only to regular staff
Route::middleware(['auth:staff', 'role:staff'])->group(function () {
// Route::group(['middleware' => ['staff']], function () {

    // staff management routes
    Route::group(['prefix' => 'staff'], function () {

        // orders routes
        Route::get('create-order', [StaffOrdersController::class,'orderForm' ] )->name('staff.createorder');
        Route::post('store', [StaffOrdersController::class, 'store'])->name('staff.storeOrder');
        Route::get('branches', [StaffOrdersController::class, 'branches'])->name('staff.listbranches');
        Route::get('pendingorder', [StaffOrdersController::class, 'pendingOrderindex'])->name('staff.pendingOrder');
        Route::put('order/update/{id}', [StaffOrdersController::class, 'updateOrder'])->name('staff.pendingOrder.update');
        Route::get('partialorder', [StaffOrdersController::class, 'partialOrderindex'])->name('staff.partialorder');
        Route::put('update-partialorder/{id}', [StaffOrdersController::class, 'updatePartialOrder'])->name('staff.partialOrder.update');
        Route::delete('destroyorder', [StaffOrdersController::class, 'destroyOrder'])->name('staff.order.destroy');
        Route::get('rejectedorder', [StaffOrdersController::class, 'rejectedOrderindex'])->name('staff.rejectedorder');
        Route::get('completedorder', [StaffOrdersController::class, 'completedOrderindex'])->name('staff.completedorder');
        Route::get('view-order/{id}', [StaffOrdersController::class, 'viewOrder'])->name('staff.orders.vieworder');

        // customers routes
        Route::get('agents', [StaffCustomerController::class, 'agents'])->name('staff.listagents');
        Route::post('storeagent', [StaffCustomerController::class, 'storeAgent'])->name('staff.storeagent');
        Route::put('agent/update/{id}', [StaffCustomerController::class, 'updateAgent'])->name('staff.agent.update');
        Route::delete('destroyagent', [StaffCustomerController::class, 'destroy'])->name('staff.agent.destroyagent');
        Route::get('view-agent/{id}', [StaffCustomerController::class, 'viewAgent'])->name('staff.agent.view-agent');
        Route::get('view-agentcard/{id}', [StaffCustomerController::class, 'viewAgentCard'])->name('staff.agent.view-agent_card');

        // Products routes
        Route::get('list', [StaffProductsController::class, 'index'])->name('staff.products.listproducts');

    });


});

