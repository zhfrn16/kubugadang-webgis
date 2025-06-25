<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (is_file(SYSTEMPATH . 'Config/Routes.php')) {
    require SYSTEMPATH . 'Config/Routes.php';
}

/*
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
// The Auto Routing (Legacy) is very dangerous. It is easy to create vulnerable apps
// where controller filters or CSRF protection are bypassed.
// If you don't want to define all routes, please use the Auto Routing (Improved).
// Set `$autoRoutesImproved` to true in `app/Config/Feature.php` and set the following to true.
//$routes->setAutoRoute(false);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */
$routes->get('/api/dbCheck', 'Home::dbCheck');
$routes->get('/', 'Home::landingPage');
$routes->get('/403', 'Home::error403');
$routes->get('/login', 'Web\Admin::login');
$routes->get('/register', 'Web\Admin::register');
$routes->get('/failedlogin', 'Web\Admin::failedlogin');
// $routes->get('/login', 'Login::index');
// $routes->get('/.auth/login/google/callback', 'Login::callback');

$routes->group('web', function ($routes) {
    $routes->group('profile', function ($routes) {
        $routes->get('/', 'Home::profile');
        $routes->get('update', 'Home::update');
        $routes->post('save/(:any)', 'Home::save/$1');
        $routes->get('changePassword', 'Home::changePassword');
        $routes->post('changePassword', 'Home::changePassword');
    });
});

// App
$routes->group('web', ['namespace' => 'App\Controllers\Web'], function ($routes) {

    $routes->get('/', 'Kubugadang::index');
    // $routes->get('getprice', 'Reservation::getprice');
    $routes->get('all', 'Dashboard::all', ['filter' => 'login']);
    $routes->get('package/extend/(:any)', 'Package::extend/$1', ['filter' => 'login']);
    $routes->get('package/custompackage/(:any)', 'Package::custompackage/$1', ['filter' => 'login']);
    $routes->post('detailreservation/addextend/(:any)', 'DetailReservation::addextend/$1', ['filter' => 'login']);
    $routes->post('detailreservation/addcustompackage/(:any)', 'DetailReservation::addcustompackage/$1', ['filter' => 'login']);
    $routes->get('package/custom/(:any)', 'Package::custom/$1', ['filter' => 'login']);
    
    $routes->get('detailpackage/(:any)', 'Package::detailpackage/$1', ['filter' => 'login']);

    $routes->presenter('attraction');
    $routes->presenter('event');
    $routes->presenter('package');
    $routes->presenter('kubugadang');
    // $routes->presenter('sumpu');
    // $routes->presenter('sumpur');
    $routes->presenter('explore');
    $routes->resource('explore');
    $routes->get('mypackage', 'Explore::exploremypackage', ['filter' => 'login']);
    $routes->get('listmobile', 'Package::listmobile');
    $routes->get('packagelistmobile', 'Explore::packagelistMobile');
    $routes->get('mypackageMobile', 'Explore::exploremypackageMobile', ['filter' => 'login']);

    $routes->post('package/updatecapacity', 'Package::updatecapacity');
    $routes->post('package/sendToEmailRequest', 'Package::sendToEmailRequest');

    $routes->group('silek', function ($routes) {
        $routes->presenter('silek');
        $routes->get('/', 'Unik::silek');
    });


    $routes->resource('homestay');
    $routes->presenter('homestay');
    $routes->presenter('culinaryPlace');
    $routes->presenter('traditionalHouse');
    $routes->presenter('souvenirPlace');
    $routes->presenter('worshipPlace');
    $routes->presenter('facility');
    $routes->presenter('facilitytype');
    $routes->resource('facilitytype');
    $routes->presenter('packagetype');
    $routes->resource('packagetype');
    $routes->presenter('servicepackage');
    $routes->resource('servicepackage');
    $routes->post('servicepackage/createservicepackage/(:segment)', 'Servicepackage::createservicepackage/$1');
    $routes->delete('servicepackage/delete/(:any)', 'Servicepackage::delete/$1');
    $routes->delete('package/deletepackage/(:any)', 'Package::delete/$1');
    
    


    $routes->get('homestayhomestay', 'Homestay::indexhomestay');
    $routes->post('homestay/createComment', 'Homestay::createComment');
    $routes->post('homestay/updateComment/(:segment)', 'Homestay::updateComment/$1');
    $routes->post('homestay/deleteComment/(:segment)', 'Homestay::deleteComment/$1');
    $routes->post('package/createComment', 'Package::createComment');
    $routes->post('package/updateComment/(:segment)', 'Package::updateComment/$1');
    $routes->post('package/deleteComment/(:segment)', 'Package::deleteComment/$1');


    $routes->presenter('cart', ['filter' => 'login']);
    $routes->resource('cart', ['filter' => 'login']);
    $routes->post('addCart', 'Cart::addCart', ['filter' => 'login']);
    $routes->get('showcart', 'Cart::showcart', ['filter' => 'login']);
    $routes->get('usercarttotal', 'Cart::usercarttotal', ['filter' => 'login']);

    $routes->get('reservation/custombookinghomestay/(:segment)', 'Reservation::custombookinghomestay/$1', ['filter' => 'login']);
    $routes->get('reservation/custombooking/(:segment)', 'Reservation::custombooking/$1', ['filter' => 'login']);
    $routes->post('reservation/uploaddeposit/(:any)', 'Reservation::uploaddeposit/$1', ['filter' => 'login']);
    $routes->post('reservation/uploadfullpayment/(:any)', 'Reservation::uploadfullpayment/$1', ['filter' => 'login']);


    $routes->presenter('reservation', ['filter' => 'login']);
    $routes->resource('reservation', ['filter' => 'login']);

    $routes->get('showreservation', 'DetailReservation::showreservation');
    $routes->post('detailreservation/addcustom', 'DetailReservation::addcustom', ['filter' => 'login']);
    $routes->post('detailreservation/createday/(:segment)', 'DetailReservation::createday/$1');
    $routes->post('detailreservation/updateday/(:segment)', 'DetailReservation::updateday/$1');
    $routes->post('detailreservation/createactivity/(:segment)', 'DetailReservation::createactivity/$1');
    $routes->delete('detailreservation/deleteunit/(:any)', 'DetailReservation::deleteunit/$1');
    $routes->delete('detailreservation/deleteday/(:any)', 'DetailReservation::deleteday/$1');
    $routes->delete('detailreservation/delete/(:any)', 'DetailReservation::delete/$1');

    $routes->get('detailreservation/review/(:any)', 'DetailReservation::review/$1'); //--------
    $routes->post('detailreservation/savereview/(:any)', 'DetailReservation::savereview/$1'); //--------
    $routes->post('detailreservation/savereviewunit/(:any)', 'DetailReservation::savereviewunit/$1'); //--------
    $routes->post('detailreservation/savedelete/(:any)', 'DetailReservation::savedelete/$1'); //--------
    $routes->post('detailreservation/savecancel/(:any)', 'DetailReservation::savecancel/$1'); //--------
    $routes->post('detailreservation/saveresponse/(:any)', 'DetailReservation::saveresponse/$1'); //--------
    $routes->post('detailreservation/saverefund/(:any)', 'DetailReservation::saverefund/$1'); //--------
    $routes->post('detailreservation/savecheckdeposit/(:any)', 'DetailReservation::savecheckdeposit/$1'); //--------
    $routes->post('detailreservation/savecheckpayment/(:any)', 'DetailReservation::savecheckpayment/$1'); //--------
    $routes->post('detailreservation/savecheckrefund/(:any)', 'DetailReservation::savecheckrefund/$1'); //--------
    $routes->presenter('detailreservation', ['filter' => 'login']);
    $routes->resource('detailreservation', ['filter' => 'login']);

    $routes->post('detailreservation/(:any)/updatedepositcheck', 'Reservation::updateDepositCheck');
    $routes->get('emailupdateDepositCheck', 'Reservation::emailupdateDepositCheck');
    $routes->post('detailreservation/(:any)/updatefullcheck', 'Reservation::updateFullCheck');

    $routes->get('generatepdf/(:any)', 'PdfController::generatePDF/$1');

    // Profile
    $routes->group('profile', function ($routes) {
        $routes->get('/', 'Profile::profile', ['filter' => 'login']);
        $routes->get('changePassword', 'Profile::changePassword', ['filter' => 'login']);
        $routes->post('changePassword', 'Profile::changePassword', ['filter' => 'login']);
        $routes->get('update', 'Profile::updateProfile', ['filter' => 'login']);
        $routes->post('update', 'Profile::update', ['filter' => 'login']);
    });
});

// Dashboard
$routes->group('dashboard', ['namespace' => 'App\Controllers\Web', 'filter' => 'role:admin, master'], function ($routes) {
    $routes->get('/', 'Dashboard::index');
    $routes->get('sumpu', 'Dashboard::sumpu');
    $routes->get('announcement', 'Dashboard::announcement');
    $routes->get('users', 'Dashboard::users');
    $routes->get('attraction', 'Dashboard::attraction');
    $routes->get('event', 'Dashboard::event');
    $routes->get('package', 'Dashboard::package');
    $routes->get('facility', 'Dashboard::facility');
    $routes->get('souvenirplace', 'Dashboard::souvenirplace');
    $routes->get('worshipplace', 'Dashboard::worshipplace');
    $routes->resource('worshipplace', ['controller' => 'WorshipPlace']);
    $routes->get('culinaryplace', 'Dashboard::culinaryplace');
    $routes->get('traditionalhouse', 'Dashboard::traditionalhouse');
    $routes->get('servicepackage', 'Dashboard::servicepackage');
    $routes->post('package/updatecustom/(:any)', 'Package::updatecustom/$1');
    $routes->get('facilitytype', 'Dashboard::facilitytype');
    $routes->get('packagetype', 'Dashboard::packagetype');

    // $routes->get('packageday/(:segment)', 'Packageday::newday/$1');
    // $routes->post('packageday/createday/(:segment)', 'Packageday::createday/$1');
    // $routes->post('packageday/createactivity/(:segment)', 'Packageday::createactivity/$1');
    // $routes->delete('packageday/delete/(:any)', 'Packageday::delete/$1');
    // $routes->delete('packageday/deleteday/(:any)', 'Packageday::deleteday/$1');
    
    $routes->get('package/packageday/(:segment)', 'Packageday::newday/$1');
    $routes->post('package/packageday/createday/(:segment)', 'Packageday::createday/$1');
    $routes->post('package/packageday/createactivity/(:segment)', 'Packageday::createactivity/$1');
    $routes->delete('package/edit/packageday/delete/(:any)', 'Packageday::delete/$1');
    $routes->delete('package/edit/packageday/deleteday/(:any)', 'Packageday::deleteday/$1');

    $routes->post('facilityhomestay/createfacility/(:segment)', 'Facilityhomestay::createfacility/$1');
    $routes->post('facilityhomestay/createfacilityhomestay/(:segment)', 'Facilityhomestay::createfacilityhomestay/$1');
    $routes->delete('facilityhomestay/delete/(:any)', 'Facilityhomestay::delete/$1');
    $routes->get('homestay', 'Dashboard::homestay');
    $routes->resource('homestay');
    $routes->presenter('homestay');
    $routes->post('homestay/createfacility/', 'Homestay::createfacility');
    $routes->get('unithomestay/new/(:segment)', 'UnitHomestay::new/$1');
    $routes->delete('unithomestay/delete/(:any)', 'UnitHomestay::delete/$1');
    $routes->delete('unithomestay/deletefacilityunit/(:any)', 'UnitHomestay::deletefacilityunit/$1');
    $routes->resource('unit');
    $routes->presenter('unit');

    // $routes->get('admin', 'Adminuser::index');
    // $routes->get('admin/index', 'Adminuser::index');
    // $routes->get('admin/(:num)', 'Adminuser::show/$1', ['filter' => 'role:admin']);
    $routes->post('users/admin/register', 'Users::adminregister');

    $routes->resource('village');
    $routes->resource('users');
    $routes->presenter('sumpu');
    $routes->presenter('attraction');
    $routes->presenter('event');
    $routes->presenter('package');
    $routes->presenter('facility');
    $routes->presenter('culinaryplace');
    $routes->presenter('traditionalhouse');
    $routes->presenter('worshipplace');
    $routes->presenter('souvenirplace');
    $routes->presenter('packageday');

    $routes->presenter('packagetype');
    $routes->resource('packagetype');
    $routes->presenter('facilitytype');
    $routes->resource('facilitytype');
    $routes->presenter('servicepackage');
    $routes->resource('servicepackage');
    $routes->post('servicepackage/createservicepackage/(:segment)', 'Servicepackage::createservicepackage/$1');
    $routes->delete('servicepackage/delete/(:any)', 'Servicepackage::delete/$1');

    $routes->post('announcement/add', 'Sumpu::createannouncement');
    $routes->post('announcement/update/(:any)', 'Sumpu::updateannouncement/$1');

    $routes->presenter('unithomestay');
    $routes->post('unithomestay/createunit/(:segment)', 'UnitHomestay::createunit/$1');
    $routes->post('unithomestay/createfacility/(:segment)', 'UnitHomestay::createfacility/$1');
    $routes->post('unithomestay/createfacilityunit/(:segment)', 'UnitHomestay::createfacilityunit/$1');

    $routes->get('reservation/report', 'Reservation::report');
    $routes->presenter('reservation');
    $routes->resource('reservation');
    $routes->presenter('managereservation');
    $routes->get('detailreservation/confirm/(:any)', 'DetailReservation::confirm/$1');
    $routes->post('detailreservation/saveconfirm/(:any)', 'DetailReservation::saveconfirm/$1');
    $routes->post('reservation/uploadrefund/(:any)', 'Reservation::uploadrefund/$1');
    $routes->get('detailreservation/review/(:any)', 'DetailReservation::review/$1');
    $routes->presenter('detailreservation', ['filter' => 'login']);
    $routes->resource('detailreservation', ['filter' => 'login']);

    $routes->post('users/deleteobject/(:any)', 'Users::deleteobject/$1');
    $routes->post('announcement/deleteobject/(:any)', 'Sumpu::deleteobject/$1');
    $routes->post('package/deleteobject/(:any)', 'Package::deleteobject/$1');
    $routes->post('packagetype/deleteobject/(:any)', 'PackageType::deleteobject/$1');
    $routes->post('homestay/deleteobject/(:any)', 'Homestay::deleteobject/$1');
    $routes->post('servicepackage/deleteobject/(:any)', 'Servicepackage::deleteobject/$1');


});

// Upload files
$routes->group('upload', ['namespace' => 'App\Controllers\Web'], function ($routes) {
    $routes->post('photo', 'Upload::photo');
    $routes->post('video', 'Upload::video');
    $routes->post('qr_url', 'Upload::qr_url');
    $routes->delete('photo', 'Upload::remove');
    $routes->delete('video', 'Upload::remove');
    $routes->delete('qr_url', 'Upload::remove');
});


// API
$routes->group('api', ['namespace' => 'App\Controllers\Api'], function ($routes) {

    $routes->resource('kubugadang');
    $routes->get('custombooking/(:segment)', 'Reservation::custombooking/$1');
    $routes->get('tersedia', 'Reservation::tersedia');
    $routes->get('chooseHomeLama', 'Reservation::chooseHomeLama');
    $routes->get('chooseHome', 'Reservation::chooseHome');
    $routes->get('statistictersedia', 'Reservation::statistictersedia');
    $routes->get('statistictersedia2', 'Reservation::statistictersedia2');
    $routes->get('statisticbooking', 'Reservation::statisticbooking');
    $routes->get('statisticbookingcustom', 'Reservation::statisticbookingcustom');
    $routes->get('cart', 'Cart::index');
    $routes->get('usercart', 'Cart::usercart');
    $routes->get('usercarttotal', 'Cart::usercarttotal');
    $routes->get('newday', 'Packageday::newday');
    $routes->post('deleteservicepackage', 'Package::deleteservicepackage');
    $routes->post('chooseHome', 'Reservation::chooseHome');
    $routes->post('chooseCustomHome', 'Reservation::chooseCustomHome');
    $routes->post('reservation/create', 'Reservation::create');
    $routes->get('tourismVillageInfo', 'Sumpu::tourismVillageInfo');
    $routes->get('getIdProvince', 'Sumpu::getIdProvince');

    $routes->get('cobaexploremypackage', 'Explore::cobaexploremypackage');

    $routes->get('payMidtrans/(:any)', 'Reservation::payMidtrans/$1');
    $routes->get('payMidtransFull/(:any)', 'Reservation::payMidtransFull/$1');
    // $routes->post('payMidtrans', 'Reservation::payMidtrans');

    // $routes->get('packagelistmobile', 'Explore::packagelistMobile');
    $routes->get('listmobile', 'Package::listmobile');

    $routes->post('village', 'Village::getData');
    $routes->post('villages', 'Village::getDataKK');
    $routes->post('rumah', 'Homestay::getData');
    $routes->post('home', 'Homestay::getData');
    $routes->post('attraction', 'Attraction::getData');
    $routes->post('culinary', 'CulinaryPlace::getData');
    $routes->post('traditional', 'TraditionalHouse::getData');
    $routes->post('souvenir', 'SouvenirPlace::getData');
    $routes->post('worship', 'WorshipPlace::getData');
    $routes->post('facility', 'Facility::getData');
    $routes->post('facilitytype', 'FacilityType::getData');

    // $routes->get('mypackageMobile', 'Explore::exploremypackageMobile');
    // $routes->get('mypackageMobile', 'Explore::exploremypackageMobile', ['filter' => 'login']);


    $routes->resource('users');
    $routes->resource('connection');
    $routes->resource('tracking');
    $routes->get('attraction/maps', 'Attraction::maps');
    $routes->get('attraction/detail/(:any)', 'Attraction::detail/$1');
    $routes->resource('attraction');
    $routes->resource('servicepackage');
    $routes->resource('facility');
    $routes->post('facility/findByRadius', 'Facility::findByRadius');
    $routes->post('facility/findByTrack', 'Facility::findByTrack');
    $routes->resource('event');

    $routes->get('package/detail/(:any)', 'Package::detail/$1');
    $routes->get('package/type', 'Package::type');
    $routes->get('explorePackage', 'Package::explorePackage');
    $routes->get('exploreMyPackage', 'Package::exploreMyPackage');
    $routes->resource('package');
    $routes->get('packageday/(:any)', 'PackageDay::getDay/$1');
    $routes->get('packagedaylist/(:any)', 'PackageDay::getDayList/$1');
    $routes->post('package/findByName', 'Package::findByName');
    $routes->post('package/findByType', 'Package::findByType');

    $routes->get('homestay/detail/(:any)', 'Homestay::detail/$1');
    $routes->get('homestay/maps', 'Homestay::maps');
    $routes->resource('homestay');
    $routes->post('homestay/findByRadius', 'Homestay::findByRadius');
    $routes->get('homestayhomestay', 'Homestay::indexhomestay');


    $routes->get('reservation/custombooking/(:segment)', 'Package::detailapi/$1');
    $routes->post('reservation/findhome', 'Homestay::findhome');


    $routes->resource('cart');
    $routes->presenter('cart');
    $routes->post('addCart', 'Cart::addCart', ['filter' => 'login']);
    $routes->post('deleteCart', 'Cart::deleteCart');

    $routes->delete('announcement/(:any)', 'Sumpu::deleteannouncement/$1');


    $routes->resource('packagetype');
    $routes->presenter('packagetype');
    $routes->resource('facilitytype');
    $routes->presenter('facilitytype');
    $routes->resource('culinaryPlace');
    $routes->presenter('culinaryplace');
    $routes->get('attractionLSA', 'Attraction::attractionLSA');
    $routes->get('attractionNT', 'Attraction::attractionNT');
    $routes->get('attractionCT', 'Attraction::attractionCT');
    $routes->get('attractionET', 'Attraction::attractionET');
    $routes->post('attractionlsa/findByRadius', 'Attraction::findByRadiuslsa');
    $routes->post('culinaryPlace/findAll', 'CulinaryPlace::findAll');
    $routes->post('worshipPlace/findAll', 'WorshipPlace::findAll');
    $routes->post('attraction/findAll', 'Attraction::findAll');
    $routes->get('attraction/findAll', 'Attraction::findAll');
    $routes->post('attractionlsa/findlsaAll', 'Attraction::findlsaAll');
    $routes->post('homestay/findAll', 'Homestay::findAll');
    $routes->post('traditionalHouse/findAll', 'TraditionalHouse::findAll');
    $routes->post('souvenirPlace/findAll', 'SouvenirPlace::findAll');
    $routes->post('culinaryPlace/findByRadius', 'CulinaryPlace::findByRadius');
    $routes->resource('traditionalHouse');
    $routes->presenter('traditionalhouse');
    $routes->post('traditionalHouse/findByRadius', 'TraditionalHouse::findByRadius');
    $routes->resource('souvenirPlace');
    $routes->presenter('souvenirplace');
    $routes->post('souvenirPlace/findByRadius', 'SouvenirPlace::findByRadius');
    $routes->resource('worshipPlace');
    $routes->presenter('worshipplace');
    $routes->post('worshipPlace/findByRadius', 'WorshipPlace::findByRadius');
});

/*
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (is_file(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
