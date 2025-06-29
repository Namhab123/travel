<?php

namespace App\Providers;

use App\Models\admin\ContactModel;
use App\Models\admin\BookingModel;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        View::composer('admin.blocks.sidebar', function ($view) {
            $contactModel = new ContactModel();
            $unreadData = $contactModel->countContactsUnread();
            $view->with('contactUnreadCount', $unreadData['countUnread']);
            $view->with('unreadContacts', $unreadData['contacts']);
        });

        View::composer('admin.blocks.sidebar', function ($view) {
            $bookingModel = new BookingModel();
            $unreadData = $bookingModel->countBookingUnread();
            $view->with('bookingUnreadCount', $unreadData['countUnread']);
            $view->with('unreadBooking', $unreadData['booking']);
        });
    }
}
