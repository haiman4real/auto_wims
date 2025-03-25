<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Middleware\CustomerService;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CoporateUser;
use App\Http\Controllers\MasterAdmin;
use App\Http\Controllers\FrontDesk;
use App\Http\Middleware\JobController;
use App\Http\Middleware\MasterAdminMiddleware;
use App\Http\Middleware\ServiceAdvisor;
use App\Http\Middleware\SuperAdmin;
use App\Http\Middleware\Technician;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Route::get('/', function () {
//     return view('welcome');
// });

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');
Route::get('/', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');
Route::get('/get-chart-data', [DashboardController::class, 'getChartData'])->middleware(['auth', 'verified'])->name('getchartData');
Route::get('/get-transaction-data', [DashboardController::class, 'getTransactionData'])->middleware(['auth', 'verified'])->name('getTransactionData');
Route::get('/customer-lga-data', [DashboardController::class, 'getCustomerLgaData'])->middleware(['auth', 'verified'])->name('getCustomerLgaData');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::get('/manage-profile', [ProfileController::class, 'manage'])->name('profile.manage');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

Route::middleware(['auth', 'verified'])->middleware(MasterAdminMiddleware::class)->namespace('MasterAdmin')->group(function(){
    Route::get('/ma/home', 'HomeController@index');


});

Route::middleware(['auth', 'verified'])->middleware(SuperAdmin::class)->namespace('SuperAdmin')->group(function(){
    Route::get('/sa/home', 'HomeController@index');
    Route::get('/ma/users', [MasterAdmin\UserController::class, 'index'])->name('users.index');
    Route::get('/ma/users/add', [MasterAdmin\UserController::class, 'create'])->name('users.add');
    Route::post('/ma/users', [MasterAdmin\UserController::class, 'store'])->name('user.store');
    Route::get('/ma/users/{user}/edit', [MasterAdmin\UserController::class, 'edit'])->name('user.edit');
    Route::patch('/ma/users/{user}', [MasterAdmin\UserController::class, 'update'])->name('user.update');
    Route::delete('/ma/users/{user}', [MasterAdmin\UserController::class, 'destroy'])->name('user.destroy');
    Route::get('/ma/user/enable/{user}', [MasterAdmin\UserController::class, 'enableUser'])->name('user.enable');
    Route::get('/ma/user/disable/{user}', [MasterAdmin\UserController::class, 'disableUser'])->name('user.disable');

    Route::get('/self-service/appointments', [FrontDesk\SelfServiceController::class, 'index'])->name('self-service.index');
    Route::post('/self-service/appointments', [FrontDesk\SelfServiceController::class, 'store'])->name('self-service.store');
    Route::get('/self-service/appointments/{appointment}', [FrontDesk\SelfServiceController::class, 'showBooking'])->name('self-service.show');
});

Route::middleware(['auth','AdminOne','verified'])->namespace('AdminOne')->group(function(){
    Route::get('/ao/home', 'HomeController@index');
});

Route::middleware(['auth','AdminTwo','verified'])->namespace('AdminTwo')->group(function(){
    Route::get('/aw/home', 'HomeController@index');
});

Route::middleware(['auth','AdminThree', 'verified'])->namespace('AdminThree')->group(function(){
    Route::get('/ah/home', 'HomeController@index');
});

Route::middleware(['auth','CustomerService','verified'])->namespace('CustomerService')->group(function(){
    Route::get('/cs/home', 'HomeController@index');
});

Route::middleware(['auth','verified'])->middleware(\App\Http\Middleware\FrontDesk::class)->namespace('FrontDesk')->group(function(){
    Route::get('/fd/home', 'HomeController@index');
    // Route::get('/workshop/customers', [FrontDesk\CustomersController::class, 'index'])->name('customers.index');
    // Route::get('/workshop/customers/add', [FrontDesk\CustomersController::class, 'create'])->name('customers.add');
    // Route::post('/workshop/customers', [FrontDesk\CustomersController::class,'store'])->name('customers.store');
    // Route::get('/workshop/customers/{customer}/edit', [FrontDesk\CustomersController::class, 'edit'])->name('customers.edit');
    // Route::put('/workshop/customers/{customer}', [FrontDesk\CustomersController::class, 'update'])->name('customers.update');
    // Route::delete('/workshop/customers/{customer}', [FrontDesk\CustomersController::class, 'destroy'])->name('customers.destroy');
    // Route::get('/workshop/customers/{customer}/delete', [FrontDesk\CustomersController::class, 'deleteCustomer'])->name('customers.delete');

    // Route::get('/workshop/vehicles', [FrontDesk\VehicleController::class, 'index'])->name('vehicles.index');
    // Route::get('/workshop/vehicles/add', [FrontDesk\VehicleController::class, 'create'])->name('vehicles.add');
    // Route::post('/workshop/vehicles', [FrontDesk\VehicleController::class,'store'])->name('vehicles.store');
    // Route::get('/workshop/vehicles/{customer}/edit', [FrontDesk\VehicleController::class, 'edit'])->name('vehicles.edit');
    // Route::put('/workshop/vehicles/{customer}', [FrontDesk\VehicleController::class, 'update'])->name('vehicles.update');
    // Route::delete('/workshop/vehicles/{customer}', [FrontDesk\VehicleController::class, 'destroy'])->name('vehicles.destroy');
    // Route::get('/workshop/vehicles/{customer}/delete', [FrontDesk\VehicleController::class, 'deleteCustomer'])->name('vehicles.delete');

    // Route::get('/self-service/appointments', [FrontDesk\SelfServiceController::class, 'index'])->name('self-service.index');


    // Route::get('/workshop/service-order', [FrontDesk\ServiceBookingController::class, 'index'])->name('service_booking.index');
    // Route::post('/workshop/service-order', [FrontDesk\ServiceBookingController::class, 'createJob'])->name('service_booking.create');
    // Route::get('/api/jobs/count', [FrontDesk\ServiceBookingController::class, 'generateOrderNumber'])->name('service_booking.genOrderNo');


    // Route::get('/workshop/job-controller', [FrontDesk\ServiceBookingController::class, 'returnJobController'])->name('service_booking.jobcontroller');
    // Route::post('/workshop/assign-technician', [FrontDesk\ServiceBookingController::class, 'assignTechnician'])->name('service_booking.assign_technician');
    // Route::get('/workshop/technician', [FrontDesk\ServiceBookingController::class, 'returnTechnicianAdmin'])->name('service_booking.technician.admin');
    // Route::post('/workshop/technician', [FrontDesk\ServiceBookingController::class, 'updateTechnicianJobAdmin'])->name('service_booking.technician.admin.updateJob');

    // Route::get('/workshop/service-advisor', [FrontDesk\ServiceBookingController::class, 'returnServiceAdvisor'])->name('service_booking.service_advisor.admin');
    // Route::post('/workshop/service-advisor', [FrontDesk\ServiceBookingController::class, 'updateServiceAdvisor'])->name('service_booking.service_advisor.admin.updateJob');

    // Route::get('/workshop/job-bank', [FrontDesk\ServiceBookingController::class, 'returnJobBank'])->name('service_booking.job_bank.admin');


    // Route::get('/workshop/bookings', [FrontDesk\ServiceBookingController::class, 'returnBookings'])->name('service_booking.bookings');
    // Route::get('/workshop/estimate/generate/{job_id}', [FrontDesk\ServiceBookingController::class, 'showEstimationPage'])->name('service_booking.estimate.generate');
    // Route::post('/workshop/estimate/save', [FrontDesk\ServiceBookingController::class, 'storeEstimation'])->name('service_booking.estimate.save');
    // Route::get('/workshop/estimate/edit/{job_id}', [FrontDesk\ServiceBookingController::class, 'editEstimate'])->name('service_booking.estimate.edit');
    // Route::post('/workshop/estimate/update', [FrontDesk\ServiceBookingController::class, 'updateEstimate'])->name('service_booking.estimate.update');

    // Route::get('/workshop/estimate/invoice/{job_id}', [FrontDesk\ServiceBookingController::class, 'returnInvoice'])->name('service_booking.estimate.invoice');

    // Route::get('/workshop/services', [FrontDesk\JobServicesController::class, 'returnServices'])->name('job.services');
    // Route::get('/workshop/services/add', [FrontDesk\JobServicesController::class, 'addService'])->name('job.services.add');
    // Route::post('/workshop/services', [FrontDesk\JobServicesController::class, 'storeService'])->name('job.services.store');
    // Route::get('/workshop/services/{service}/edit', [FrontDesk\JobServicesController::class, 'editService'])->name('job.services.edit');
    // Route::put('/workshop/services/{service}', [FrontDesk\JobServicesController::class, 'updateService'])->name('job.services.update');
    // Route::delete('/workshop/services/{service}', [FrontDesk\JobServicesController::class, 'destroyService'])->name('job.services.destroy');



    // Route::get('/api/lgas/{state}', [FrontDesk\CustomersController::class, 'getLga'])->name('customers.getLga');
    // Route::get('/api/customers', [FrontDesk\CustomersController::class, 'searchCustomers'])->name('customers.search');
    // Route::post('/api/vehicles', [FrontDesk\VehicleController::class, 'addVehicle'])->name('vehicles.addOrder');
    // Route::get('/api/vehicles', [FrontDesk\VehicleController::class, 'getVehiclesByCustomer'])->name('vehicles.byCustomer');



});

Route::middleware(['auth', 'verified'])->middleware(Technician::class)->namespace('Technician')->group(function(){
    Route::get('/tc/home', 'HomeController@index');
    Route::get('/tc/workshop/technician', [FrontDesk\ServiceBookingController::class, 'returnTechnicianUser'])->name('service_booking.technician.user');
    Route::post('/tc/workshop/technician', [FrontDesk\ServiceBookingController::class, 'updateTechnicianJobUser'])->name('service_booking.technician.user.updateJob');
});

Route::middleware(['auth', 'verified'])->middleware(ServiceAdvisor::class)->namespace('ServiceAdvisor')->group(function(){
    Route::get('/sa/home', 'HomeController@index');
    Route::get('/sa/workshop/service-advisor', [FrontDesk\ServiceBookingController::class, 'returnServiceAdvisorUser'])->name('service_booking.service_advisor.user');
    Route::post('/sa/workshop/service-advisor', [FrontDesk\ServiceBookingController::class, 'updateServiceAdvisorUser'])->name('service_booking.service_advisor.user.updateJob');

    // Route::get('/sa/workshop/job-controller', [FrontDesk\ServiceBookingController::class, 'returnJobController'])->name('sa.service_booking.jobcontroller');

});

Route::middleware(['auth','JobController','verified'])->namespace('JobController')->group(function(){
    Route::get('/jc/home', 'HomeController@index');
});

Route::middleware(['auth','AccountsAdmin','verified'])->namespace('AccountsAdmin')->group(function(){
    Route::get('/aa/home', 'HomeController@index');
});

Route::middleware(['auth','BusinessView', 'verified'])->namespace('BusinessView')->group(function(){
    Route::get('/bv/home', 'HomeController@index');
});

Route::middleware(['auth','GuestUser','verified'])->namespace('GuestUser')->group(function(){
    Route::get('/gu/home', 'HomeController@index');
});

Route::middleware(['auth','verified'])->middleware(\App\Http\Middleware\CoporateUser::class)->namespace('CoporateUser')->group(function(){
    Route::get('/cu/home', 'HomeController@index');
    Route::get('/cu/test', [CoporateUser\HomeController::class, 'testNonLaravelDb'])->name('testdb');


    //Trackers
    Route::get('/product/trackers', [CoporateUser\TrackerController::class, 'index'])->name('trackers.index');
    Route::get('/product/trackers/create', [CoporateUser\TrackerController::class, 'create'])->name('trackers.create');
    Route::post('/product/trackers', [CoporateUser\TrackerController::class,'store'])->name('trackers.store');
    Route::get('/product/trackers/{tracker}', [CoporateUser\TrackerController::class, 'show'])->name('trackers.show');
    Route::get('/product/trackers/{tracker}/edit', [CoporateUser\TrackerController::class, 'edit'])->name('trackers.edit');
    Route::put('/product/trackers/update/{tracker}', [CoporateUser\TrackerController::class, 'update'])->name('trackers.update');
    Route::get('/product/trackers/completed/{tracker}', [CoporateUser\TrackerController::class, 'completed'])->name('trackers.completed');
    Route::post('/trackers/complete', [CoporateUser\TrackerController::class, 'complete'])->name('trackers.complete');
    Route::get('/product/trackers/delete/{tracker}', [CoporateUser\TrackerController::class, 'destroy'])->name('trackers.destroy');
});

//General Routes

Route::middleware(['auth','verified'])->group(function(){
    Route::get('/api/jobs/count', [FrontDesk\ServiceBookingController::class, 'generateOrderNumber'])->name('service_booking.genOrderNo');
    Route::get('/api/lgas/{state}', [FrontDesk\CustomersController::class, 'getLga'])->name('customers.getLga');
    Route::get('/api/customers', [FrontDesk\CustomersController::class, 'searchCustomers'])->name('customers.search');
    Route::post('/api/vehicles', [FrontDesk\VehicleController::class, 'addVehicle'])->name('vehicles.addOrder');
    Route::get('/api/vehicles', [FrontDesk\VehicleController::class, 'getVehiclesByCustomer'])->name('vehicles.byCustomer');
});


Route::middleware(['auth','verified'])->middleware(['role:MasterAdmin, SuperAdmin, Technician, CustomerService, JobController'])->group(function(){
    //CUSTOMERS
    Route::get('/workshop/customers', [FrontDesk\CustomersController::class, 'index'])->name('customers.index');
    Route::get('/workshop/customers/add', [FrontDesk\CustomersController::class, 'create'])->name('customers.add');
    Route::post('/workshop/customers', [FrontDesk\CustomersController::class,'store'])->name('customers.store');
    Route::get('/workshop/customers/{customer}/edit', [FrontDesk\CustomersController::class, 'edit'])->name('customers.edit');
    Route::put('/workshop/customers/{customer}', [FrontDesk\CustomersController::class, 'update'])->name('customers.update');
    Route::delete('/workshop/customers/{customer}', [FrontDesk\CustomersController::class, 'destroy'])->name('customers.destroy');
    Route::get('/workshop/customers/{customer}/delete', [FrontDesk\CustomersController::class, 'deleteCustomer'])->name('customers.delete');

    //VEHICLES
    Route::get('/workshop/vehicles', [FrontDesk\VehicleController::class, 'index'])->name('vehicles.index');
    Route::get('/workshop/vehicles/add', [FrontDesk\VehicleController::class, 'create'])->name('vehicles.add');
    Route::post('/workshop/vehicles', [FrontDesk\VehicleController::class,'store'])->name('vehicles.store');
    Route::get('/workshop/vehicles/{customer}/edit', [FrontDesk\VehicleController::class, 'edit'])->name('vehicles.edit');
    Route::put('/workshop/vehicles/{customer}', [FrontDesk\VehicleController::class, 'update'])->name('vehicles.update');
    Route::delete('/workshop/vehicles/{customer}', [FrontDesk\VehicleController::class, 'destroy'])->name('vehicles.destroy');
    Route::get('/workshop/vehicles/{customer}/delete', [FrontDesk\VehicleController::class, 'deleteCustomer'])->name('vehicles.delete');

    //SERVICE ORDER
    Route::get('/workshop/service-order', [FrontDesk\ServiceBookingController::class, 'index'])->name('service_booking.index');
    Route::post('/workshop/service-order', [FrontDesk\ServiceBookingController::class, 'createJob'])->name('service_booking.create');

});

Route::middleware(['auth','verified'])->middleware(['role:MasterAdmin, SuperAdmin, CustomerService'])->group(function(){
    //BOOKINGS
    Route::get('/workshop/bookings', [FrontDesk\ServiceBookingController::class, 'returnBookings'])->name('service_booking.bookings');
    Route::get('/workshop/estimate/generate/{job_id}', [FrontDesk\ServiceBookingController::class, 'showEstimationPage'])->name('service_booking.estimate.generate');
    Route::post('/workshop/estimate/save', [FrontDesk\ServiceBookingController::class, 'storeEstimation'])->name('service_booking.estimate.save');
    Route::get('/workshop/estimate/edit/{job_id}', [FrontDesk\ServiceBookingController::class, 'editEstimate'])->name('service_booking.estimate.edit');
    Route::post('/workshop/estimate/update', [FrontDesk\ServiceBookingController::class, 'updateEstimate'])->name('service_booking.estimate.update');
    Route::get('/workshop/estimate/invoice/{job_id}', [FrontDesk\ServiceBookingController::class, 'returnInvoice'])->name('service_booking.estimate.invoice');


    //JOB SERVICES
    Route::get('/workshop/services', [FrontDesk\JobServicesController::class, 'returnServices'])->name('job.services');
    Route::get('/workshop/services/add', [FrontDesk\JobServicesController::class, 'addService'])->name('job.services.add');
    Route::post('/workshop/services', [FrontDesk\JobServicesController::class, 'storeService'])->name('job.services.store');
    Route::get('/workshop/services/{service}/edit', [FrontDesk\JobServicesController::class, 'editService'])->name('job.services.edit');
    Route::put('/workshop/services/{service}', [FrontDesk\JobServicesController::class, 'updateService'])->name('job.services.update');
    Route::delete('/workshop/services/{service}', [FrontDesk\JobServicesController::class, 'destroyService'])->name('job.services.destroy');

    //JOB BANK
    Route::get('/workshop/job-bank', [FrontDesk\ServiceBookingController::class, 'returnJobBank'])->name('service_booking.job_bank.admin');

});

Route::middleware(['auth','verified'])->middleware(['role:MasterAdmin, SuperAdmin, JobController'])->group(function(){
    //JOB CONTROLLER
    Route::get('/workshop/job-controller', [FrontDesk\ServiceBookingController::class, 'returnJobController'])->name('service_booking.jobcontroller');
    Route::post('/workshop/assign-technician', [FrontDesk\ServiceBookingController::class, 'assignTechnician'])->name('service_booking.assign_technician');

    //TECHNICIAN
    Route::get('/workshop/technician', [FrontDesk\ServiceBookingController::class, 'returnTechnicianAdmin'])->name('service_booking.technician.admin');
    Route::post('/workshop/technician', [FrontDesk\ServiceBookingController::class, 'updateTechnicianJobAdmin'])->name('service_booking.technician.admin.updateJob');

});


Route::middleware(['auth','verified'])->middleware(['role:MasterAdmin, SuperAdmin, ServiceAdvisor'])->group(function(){
    //SERVICE ADVISOR
    Route::get('/workshop/service-advisor', [FrontDesk\ServiceBookingController::class, 'returnServiceAdvisor'])->name('service_booking.service_advisor.admin');
    Route::post('/workshop/service-advisor', [FrontDesk\ServiceBookingController::class, 'updateServiceAdvisor'])->name('service_booking.service_advisor.admin.updateJob');
});
