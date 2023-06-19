<?php

require_once( __DIR__ . '/../../../lib/EasyWebService.php' );

$myWebService = new class extends EasyWebService {

    public function get( array $input ) {
        return array(
            'name'     => isset( $input[ 'name' ] ) ? $input[ 'name' ] : NULL,
            'forename' => isset( $input[ 'forename' ] ) ? $input[ 'forename' ] : NULL,
            'age'      => isset( $input[ 'age' ] ) ? $input[ 'age' ] : NULL,
            'ismale'   => isset( $input[ 'ismale' ] ) ? $input[ 'ismale' ] : NULL,
            'lookgood' => isset( $input[ 'lookgood' ] ) ? $input[ 'lookgood' ] : NULL,

        );
    }
};

$myWebService->execute();

?>

