<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command( 'app:reset', function() {
    try {
        $this->info( 'Wiping database...' );
        Artisan::call( 'db:wipe' );
        $this->info( 'Database wiped!' );
        $this->info( 'Running migrations...' );
        Artisan::call( 'migrate:refresh' );
        $this->info( 'Finished!' );
        $this->info( 'Seeding Database...' );
        Artisan::call( 'db:seed' );
        $this->info( 'Enjoy!' );
    } catch (\Exception $e) {
        \Log::error( $e->getMessage() );
    }
});

Artisan::command( 'app:insert', function() {

});