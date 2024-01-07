<?php

use Illuminate\Support\Facades\Route;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::group(['middleware' =>'auth'] ,function() 
{
    Route::get('/', 'HomeController@index');
    // user
    Route::get('user', 'UserController@index')->name('user.index');
    Route::post('user/save', 'UserController@save')->name('user.store');
    Route::get('user/profile', 'UserController@profile')->name('user.profile');
    
    Route::post('user/profile/update', 'UserController@save_profile')->name('save.profile');
    Route::post('user/update', 'UserController@update')->name('user.update');
    Route::get('user/logout', 'UserController@logout')->name('user.logout');
    // role
    Route::get('role', 'RoleController@index')->name('role.index');
    Route::get('role/delete/{id}', 'RoleController@delete')->name('role.delete');
    Route::get('role/permission/{id}', 'PermissionController@index')->name('role.permission');
    Route::post('rolepermission/save', "PermissionController@save");
    Route::get('role/export', 'RoleController@export');
    // company
    Route::get('company', 'CompanyController@index');
    Route::get('company/edit/{id}', 'CompanyController@edit');
    Route::post('company/save', 'CompanyController@save');
    // category
    Route::get('category', 'CategoryController@index')->name('category.index');
    // position
    Route::get('position', 'PositionController@index')->name('position.index');
    // protocol
    Route::get('protocol', 'ProtocolController@index')->name('protocol.index');
    Route::get('protocol-category', 'ProtocolCategoryController@index')->name('protocol_category.index');
    Route::get('get/protocol/{id}', 'TechnicalController@get_protocol');
      // protocol
      Route::get('fil', 'FilController@index')->name('fil.index');
     // medicine library
     Route::get('medicine-library', 'MedicineLibraryController@index')->name('medicine_library.index');
    // department
    Route::get('department', 'DepartmentController@index')->name('department.index');
    Route::get('department/detail/{id}', 'DepartmentController@detail')->name('department.detail');
    Route::get('section/delete/{id}', 'DepartmentController@delete')->name('section.delete');

    //diagnosis-template
    Route::get('diagnosis-template', 'DiagnosisTemplateController@index')->name('diagnosis_template.index');
    Route::get('diagnosis-template/create', 'DiagnosisTemplateController@create')->name('diagnosis_template.create');
    Route::post('diagnosis-template/save', 'DiagnosisTemplateController@save');
    Route::get('diagnosis-template/edit/{id}', 'DiagnosisTemplateController@edit')->name('diagnosis_template.edit');
    Route::post('diagnosis-template/update/', 'DiagnosisTemplateController@update');
    Route::get('diagnosis-template/detail/{id}', 'DiagnosisTemplateController@detail')->name('diagnosis_template.detail');
    Route::get('diagnosis-template/delete', 'DiagnosisTemplateController@delete')->name('diagnosis_template.delete');

        //template
        Route::get('template', 'TemplateController@index')->name('template.index');
        Route::get('template/create', 'TemplateController@create')->name('template.create');
        Route::post('template/save', 'TemplateController@save');
        Route::get('template/edit/{id}', 'TemplateController@edit')->name('template.edit');
        Route::post('template/update/', 'TemplateController@update');
        Route::get('template/detail/{id}', 'TemplateController@detail')->name('template.detail');
        Route::get('template/delete', 'TemplateController@delete')->name('template.delete');
    // invoice
    Route::get('invoice', 'InvoiceController@index')->name('invoice.index');
    Route::get('invoice/get-request/{id}', 'InvoiceController@get_request');
    Route::get('invoice/delete/{id}', 'InvoiceController@delete');
    Route::get('invoice/detail/{id}', 'InvoiceController@detail')->name('invoice.detail');
    Route::resource('invoice', 'InvoiceController')->except(['show', 'destroy']);
    Route::post('payment/save', 'InvoiceController@save_payment');
    Route::post('invoice/medicine/save', 'InvoiceController@save_medicine');
    Route::post('invoice/request/save', 'InvoiceController@request_save');
    Route::post('invoice/request-update/', 'InvoiceController@invoice_update');
    Route::get('payment/delete/{id}', 'InvoiceController@delete_payment');
    Route::get('invoice/print/{id}', 'InvoiceController@print');
    Route::get('invoice/delete-request/{id}', 'InvoiceController@delete_request');
    Route::get('invoice/delete-medicine/{id}', 'InvoiceController@delete_medicine');
    Route::get('invoice/get-medicine/{id}', 'InvoiceController@get_medicine');
      // sections
      Route::resource('section', 'SectionController')->except(['show', 'destroy']);
      Route::get('section/get-section/{id}', 'SectionController@get_section');
      //diseases
      Route::get('disease', 'DiseaseController@index')->name('disease.index');
      

      //front officef
      Route::get('front-office', 'FrontOfficeController@index')->name('front_office.index');
      Route::get('front-office/detail/{id}', 'FrontOfficeController@detail');
      Route::get('front-office/request-delete/{id}', 'FrontOfficeController@request_delete');
      Route::get('front-office/edit/{id}', 'FrontOfficeController@edit');
      Route::post('front-office/update/', 'FrontOfficeController@update')->name('front_office.update');
      Route::get('front-office/send-technical/{id}', 'FrontOfficeController@send_technical');
      Route::get('front-office/send-doctor/{id}', 'FrontOfficeController@send_doctor');
      Route::get('front-office/confirm/{id}', 'FrontOfficeController@confirm')->name('front_office.confirm');
      Route::get('front-office/arrived/{id}', 'FrontOfficeController@arrived')->name('front_office.arrived');
      Route::get('front-office/canceled/{id}', 'FrontOfficeController@canceled')->name('front_office.canceled');
      Route::get('front-office/today/', 'FrontOfficeController@today')->name('front_office.today');
      Route::get('front-office/yesterday/', 'FrontOfficeController@yesterday')->name('front_office.yesterday');
      Route::get('front-office/week/', 'FrontOfficeController@week')->name('front_office.week');
      // item
      Route::resource('item', 'ItemController')->except(['show', 'destroy']);
      Route::get('item', 'ItemController@index')->name('item.index');
      Route::get('item/delete/{id}', 'ItemController@delete');
      Route::get('item/get-item/{id}', 'ItemController@get_item');
      // paraclinical
      Route::get('paraclinical', 'ParaclinicalController@index')->name('paraclinical.index');
      Route::get('paraclinical/delete/{id}', 'ParaclinicalController@delete');
      Route::get('paraclinical/result/{id}', 'ParaclinicalController@result')->name('paraclinical.result');
      Route::resource('paraclinical', 'ParaclinicalController')->except(['show', 'destroy']);
      Route::post('paraclinical/update', 'ParaclinicalController@update')->name('paraclinical.update');
      //request
      Route::resource('request', 'RequestcheckController')->except(['show', 'destroy']);
      Route::get('request', 'RequestcheckController@index')->name('request.index');
      Route::post('request/detail/save', 'RequestcheckController@save_detail');
      Route::post('request/update/', 'RequestcheckController@update')->name('request.update');
      Route::get('request/detail/{id}', 'RequestcheckController@detail')->name('request.detail');
      Route::post('request/detail/update', 'RequestcheckController@detail_update');
      Route::get('request/detail/get/{id}', 'RequestcheckController@get_request_detail');
      Route::get('request/delete/{id}', 'RequestcheckController@delete');
      Route::get('request/request-detail/delete/{id}', 'RequestcheckController@delete_detail');
      Route::get('request/today/', 'RequestcheckController@today')->name('request.today');
      Route::get('request/yesterday/', 'RequestcheckController@yesterday')->name('request.yesterday');
      Route::get('request/week/', 'RequestcheckController@week')->name('request.week');
      Route::get('request/send-technical/{id}', 'RequestcheckController@send_technical');
      Route::get('request/send-doctor/{id}', 'RequestcheckController@send_doctor');
      
      //technical check on request detail
      Route::get('technical', 'TechnicalController@index')->name('technical.index');
      Route::get('technical/confirm/{id}', 'TechnicalController@confirm')->name('technical.confirm');
      Route::get('technical/canceled/{id}', 'TechnicalController@canceled')->name('technical.canceled');
      Route::get('technical/today/', 'TechnicalController@today')->name('technical.today');
      Route::get('technical/yesterday/', 'TechnicalController@yesterday')->name('technical.yesterday');
      Route::get('technical/week/', 'TechnicalController@week')->name('technical.week');
      Route::get('technical/done/{id}', 'TechnicalController@done')->name('technical.done');
      Route::get('technical/confirm-finished/{id}', 'TechnicalController@confirm_finished')->name('technical.confirm_finished');
      Route::post('technical/save/{id}', 'TechnicalController@save');
      Route::get('technical/delete/{id}', 'TechnicalController@delete');
      Route::get('technical/request-delete/{id}', 'TechnicalController@delete_request');
      Route::get('technical/detail/{id}', 'TechnicalController@detail')->name('technical.detail');
      Route::get('technical/print/{id}', 'TechnicalController@print');

      // doctor check 
      Route::get('doctor-check/', 'DoctorCheckController@index')->name('doctor_check.index');
      Route::post('doctor-check/save/{id}', 'DoctorCheckController@save');
      Route::get('doctor-check/create/{id}', 'DoctorCheckController@create');
      Route::get('doctor-check/print/{id}', 'DoctorCheckController@print');
      Route::get('doctor-check/get-template/{id}', 'DoctorCheckController@get_template');
      Route::get('doctor-check/update-status/{id}', 'DoctorCheckController@update_status');
      Route::get('doctor-check/detail/edit/{id}', 'DoctorCheckController@edit_detail')->name('doctor_check.edit');
      Route::get('doctor-check/detail/{id}', 'DoctorCheckController@detail')->name('doctor_check.detail');
      Route::get('doctor-check/reviewing/{id}', 'DoctorCheckController@reviewing')->name('doctor_check.reviewing');
      Route::get('doctor-check/validated/{id}', 'DoctorCheckController@validated')->name('doctor_check.validated');
      Route::get('doctor-check/today/', 'DoctorCheckController@today')->name('doctor_check.today');
      Route::get('doctor-check/yesterday/', 'DoctorCheckController@yesterday')->name('doctor_check.yesterday');
      Route::get('doctor-check/week/', 'DoctorCheckController@week')->name('doctor_check.week');
    // expense
    Route::get('expense', 'ExpenseController@index')->name('expense.index');
    
    //exchange rate
    Route::resource('exchange', 'ExchangeController')->except(['destory', 'show']);

    // appointment
    Route::get('appointment', 'AppointmentController@index')->name('appointment.index');
    Route::get('appointment/schedule', 'AppointmentController@schedule');

    // hosiptal
    Route::get('hospital', 'HospitalController@index')->name('hospital.index');
    Route::post('hospital/save', 'HospitalController@save')->name('hospital.store');
    Route::post('hospital/update', 'HospitalController@update')->name('hospital.update');
    // patient
    Route::resource('patient', 'PatientController')->except(['show', 'destroy']);
    Route::get('patient/detail/{id}', 'PatientController@detail')->name('patient.detail');
    Route::get('patient/summary/{id}', 'PatientController@summary')->name('patient.summary');
    Route::get('patient/appointment/delete/{id}', 'PatientController@delete_appointment');
    Route::get('patient/treatment/delete/{id}', 'PatientController@delete_treatment');
    Route::get('patient/invoice/delete/{id}', 'PatientController@delete_invoice');
    Route::get('patient/request/delete/{id}', 'PatientController@delete_request');
    Route::get('patient/get-request/{id}', 'PatientController@get_request');
    Route::get('patient/invoice-unpaid/{id}', 'PatientController@get_invoice_unpaid');
    Route::get('patient/result/print/{id}', 'PatientController@result_print');
    Route::get('customer/get-disease/{id}', 'PatientController@get_disease');
    Route::post('disease/update', 'PatientController@update_disease')->name('disease.update');
    Route::get('customer/get-pregnancy/{id}', 'PatientController@get_pregnancy');
    Route::post('pregnancy/update', 'PatientController@update_pregnancy')->name('pregnancy.update');
    Route::get('patient/paraclinical/delete/{id}', 'PatientController@delete_paraclinical');
    Route::post('patient/appointment/save', 'PatientController@save_appointment');
    Route::get('patient/delete/{id}', 'PatientController@delete')->name('patient.delete');
    Route::post('pregnancy/save', 'PatientController@save_pregnancy')->name('pregnancy.save');
    Route::get('pregnancy/delete/{id}', 'PatientController@delete_pregnancy')->name('pregnancy.delete');
    Route::post('disease/save', 'PatientController@save_disease')->name('disease.save');
    Route::get('disease/delete/{id}', 'PatientController@delete_disease')->name('disease.delete');
    Route::get('pdisease/delete/{id}', 'PatientController@delete_pdisease')->name('pdisease.delete');
   // patient
   Route::resource('treatment', 'TreatmentController')->except(['show', 'destroy']);
   Route::get('treatment/get-template/{id}', 'TreatmentController@get_template');
   Route::post('treatment/update', 'TreatmentController@update');
   Route::post('treatment/medicine/save', 'TreatmentController@save_medicine');
   Route::post('treatment/medicine/update', 'TreatmentController@update_medicine');
   Route::get('treatment/detail/{id}', 'TreatmentController@detail')->name('treatment.detail');
   Route::get('treatment/delete/{id}', 'TreatmentController@delete')->name('treatment.delete');
   Route::get('treatment/print/{id}', 'TreatmentController@print');
   Route::get('treatment/print-no-letterhead/{id}', 'TreatmentController@print2');
   Route::get('treatment/print/{id}', 'TreatmentController@print3');
   Route::get('treatment/get-request/{id}', 'TreatmentController@get_request');
   Route::get('treatment/get-medicine-library/{id}', 'TreatmentController@get_medicine_library');
   Route::get('treatment/eget-medicine-library/{id}', 'TreatmentController@eget_medicine_library');
   Route::get('treatment-detail/delete/{id}', 'TreatmentController@delete_td');

    // bulk operation 
    Route::get('bulk/delete/{id}', 'BulkController@delete');
    Route::get('bulk/get/{id}', 'BulkController@get');
    Route::post('bulk/save', 'BulkController@save');
    Route::post('bulk/update', 'BulkController@update');
    Route::post('bulk/remove', 'BulkController@remove');
    // change language
    Route::get('lang/{id}', 'UserController@change_lang');
    Route::fallback('NotFoundController@index');

    // report income and expense
    Route::get('report/income', 'ReportController@income')->name('income.index');
    Route::get('report/income/print', 'ReportController@income_print');
    Route::get('report/due', 'ReportController@due')->name('due.index');
    Route::get('report/due/print', 'ReportController@due_print');
    Route::get('report/expense', 'ReportController@expense');
    Route::get('report/expense/search', 'ReportController@search_expense');
    Route::get('report/expense/print', 'ReportController@print_expense');
    Route::get('report/profit', 'ReportController@profit')->name('profit.index');
    Route::get('report/commission', 'ReportController@commission');
    Route::get('report/commission/search', 'ReportController@search_commission');
    Route::get('report/commission/print', 'ReportController@print_commission');
});

Auth::routes();

// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
