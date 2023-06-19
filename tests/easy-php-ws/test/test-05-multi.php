<?php

require_once( __DIR__ . '/../../../lib/EasyWebService.php' );

$myWebService = new class extends EasyWebService {

    public function defaultRequestVerb() : string {
        return 'request';
    }

    public function getDesiredWebServiceEntries() : array {
        return array(
            // To get welcomed
            array( 'request' => 'greeting', 'protocols' => array( 'GET', 'POST' ) ),
            // To get server date
            array( 'request' => 'serverDate', 'protocols' => array( 'POST' ) ),
            // CRUD Person
            array( 'request' => 'create_Person', 'protocols' => array( 'POST' ) ),
            array( 'request' => 'read_Person', 'protocols' => array( 'GET' ), 'php-method' => 'read_Person666' ),
            array( 'request' => 'update_Person', 'protocols' => array( 'PUT' ) ),
            array( 'request' => 'delete_Person', 'protocols' => array( 'DELETE' ) ),
        );
    }

    public function greeting( array $input ) {
        return 'Welcome ' . ( isset( $input[ 'name' ] ) ? $input[ 'name' ] : 'stranger' ) . ' !';
    }

    public function serverDate( array $input ) {
        return array( 'server-date' => date( 'd.m.Y' ) );
    }

    public function create_Person( array $input ) {
        // We should create the person... Let's say it has been done ;-)
        // Now return its newly created PK
        return array( 'status' => 'OK', 'PK' => 123 );
    }

    public function read_Person666( array $input ) {
        // We should read the person having the provided PK... Let's say it has been done ;-)
        // Now return that fresh data
        return array(
            'status' => 'OK',
            'person' => array( 'PK' => 123, 'name' => 'Disney', 'forename' => 'Walt' )
        );
    }

    public function update_Person( array $input ) {
        // We should update the person having the provided PK... Let's say it has been done ;-)
        // Now return the status
        return array( 'status' => 'OK' );
    }

    public function delete_Person( array $input ) {
        // We should permanently delete the person having the provided PK... Let's say it has been done ;-)
        // Now return the status
        return array( 'status' => 'OK' );
    }
};

$myWebService->execute();

?>

