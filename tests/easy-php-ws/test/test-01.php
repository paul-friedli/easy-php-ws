<?php

require_once( __DIR__ . '/../../../lib/EasyWebService.php' );

$myWebService = new class extends EasyWebService {

    public function get( array $input ) {
        $welcomeMsg = 'Welcome ' . ( isset( $input[ 'name' ] ) ? $input[ 'name' ] : 'stranger' ) . ' !';
        return array(
            'msg'         => $welcomeMsg,
            'isPHPCool'   => true,
            'age'         => 123,
            'weight'      => 88.88,
            'values'      => array( 1, 2, 3, 4, 5, 6, 7, 8, 9, 10 ),
            'sub-struct'  => array(
                'sub1' => array(
                    'sub11'    => array(
                        'sub111' => 'It is working !',
                        'age'    => 123
                    ),
                    'location' => 'Switzerland'
                ),
                'sub2' => 3.1415926
            ),
            'server-date' => date( 'd.m.Y' ),
            'server-time' => date( 'H:i' )
        );
    }
};

$myWebService->execute();

?>

