<?php

require_once( __DIR__ . '/../../../lib/EasyWebService.php' );

$myWebService = new class extends EasyWebService {

    public function get( array $input ) {
        return 'Welcome ' . ( isset( $input[ 'name' ] ) ? $input[ 'name' ] : 'stranger' ) . ' !';
    }

    public function post( array $input ) {
        return 'Welcome ' . ( isset( $input[ 'name' ] ) ? $input[ 'name' ] : 'stranger' ) . ' !';
    }

    public function put( array $input ) {
        return 'Welcome ' . ( isset( $input[ 'name' ] ) ? $input[ 'name' ] : 'stranger' ) . ' !';
    }

    public function delete( array $input ) {
        return 'Welcome ' . ( isset( $input[ 'name' ] ) ? $input[ 'name' ] : 'stranger' ) . ' !';
    }
    public function patch( array $input ) {
        return 'Welcome ' . ( isset( $input[ 'name' ] ) ? $input[ 'name' ] : 'stranger' ) . ' !';
    }
    public function head( array $input ) {
        return 'Welcome ' . ( isset( $input[ 'name' ] ) ? $input[ 'name' ] : 'stranger' ) . ' !';
    }

};

$myWebService->execute();

?>

