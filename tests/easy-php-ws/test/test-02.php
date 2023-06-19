<?php

require_once( __DIR__ . '/../../../lib/EasyWebService.php' );

$myWebService = new class extends EasyWebService {

    public function get( array $input ) {

        $wantedType = null;
        $wantedResultCode = 200;

        if ( isset( $input[ 'desired-return-type' ] ) ) {
            $wantedType = strtolower( $input[ 'desired-return-type' ] );
        }
        
        if ( isset( $input[ 'desired-result-code' ] ) ) {
            $wantedResultCode = $input[ 'desired-result-code' ];
        }
        
        if ( $wantedResultCode != 200) {
            $this->setHttpResultCode($wantedResultCode);
        }

        switch ( $wantedType) {
            case "boolean":
                return TRUE;
    
            case "integer":
                return -1234567890;
    
            case "double":
                return 3.1415927;
    
            case "string":
                return 'Today is a great day !';
    
            case "null":
                return NULL;
    
            case "array":
                return array( 'red', 'green', 'blue' );
        }
        
        $this->setHttpResultCode(400);  // Bad Request
        return 'ERROR : Invalid parameters provided !';
    }

};

$myWebService->execute();

?>

