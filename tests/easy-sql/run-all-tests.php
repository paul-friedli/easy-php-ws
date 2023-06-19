<?php

// Please specify here the location of the tests/test folder
$WSHOME = 'https://www.mywebsite.com/folder/folder/easy-php-ws/tests/easy-sql/test';

// TRUE => verbose output, you'll see all test details (like the returned JSON)
// FALSE => only the tests results
$verbose             = TRUE;
$verboseOnlyIfFailed = FALSE; // TRUE to turn off $verbose in case everything is fine...
$showURLUnderTitle   = TRUE;

// Inform our web browser that he will display HTML content
header( 'Content-Type: text/plain; charset=utf-8' );

// This is an array containing all tests to be done
$uniqueID1 = uniqid( 'ID', true );
$uniqueID2 = uniqid( 'ID', true );

//
// Test case - retrieve all actual records
//
$httpResultCode = NULL;
$selectTestCase = array(
    "Protocol" => "GET",
    "URL"      => "$WSHOME/test-multi.php",
    "Payload"  => NULL
);
echo ( '===========================================================================' . PHP_EOL );
echo ( '1) Testing SELECT...' . PHP_EOL );
echo ( '===========================================================================' . PHP_EOL );
$result = callWebservice( $selectTestCase, $httpResultCode );
echo ( ( ( $httpResultCode == 200 ) ? 'http result code 200 is OK !' : 'http result code ' . $httpResultCode . ' is KO !' ) . PHP_EOL );
echo ( 'Below is the returned JSON :' . PHP_EOL );
echo ( '---------------------------------------------------------------------------' . PHP_EOL );
echo ( json_encode( $result, JSON_PRETTY_PRINT ) . PHP_EOL );
echo ( '---------------------------------------------------------------------------' . PHP_EOL );

//
// Test case - inserting a new record
//
$httpResultCode = NULL;
$selectTestCase = array(
    "Protocol" => "POST",
    "URL"      => "$WSHOME/test-multi.php",
    "Payload"  => array(
        'msg_content' => "A new line with this unique identifier [$uniqueID1]"
    )
);
echo ( PHP_EOL );
echo ( PHP_EOL );
echo ( PHP_EOL );
echo ( '===========================================================================' . PHP_EOL );
echo ( '2) Testing INSERT...' . PHP_EOL );
echo ( '===========================================================================' . PHP_EOL );
echo ( "Let's create a new entry with this uniqueID = [$uniqueID1]" . PHP_EOL );
$result = callWebservice( $selectTestCase, $httpResultCode );
echo ( ( ( $httpResultCode == 200 ) ? 'http result code 200 is OK !' : 'http result code ' . $httpResultCode . ' is KO !' ) . PHP_EOL );
echo ( 'Below is the returned JSON :' . PHP_EOL );
echo ( '---------------------------------------------------------------------------' . PHP_EOL );
echo ( json_encode( $result, JSON_PRETTY_PRINT ) . PHP_EOL );
echo ( '---------------------------------------------------------------------------' . PHP_EOL );
if ( !is_null( $result ) && $result[ 'succeeded' ] === true ) {
    $pkCreated = $result[ 'created-pk' ];
    echo ( "The PK of the created record is : $pkCreated" . PHP_EOL );
}
echo ( '---------------------------------------------------------------------------' . PHP_EOL );

//
// Test case - retrieve all actual records
//
$httpResultCode = NULL;
$selectTestCase = array(
    "Protocol" => "GET",
    "URL"      => "$WSHOME/test-multi.php",
    "Payload"  => NULL
);
echo ( PHP_EOL );
echo ( PHP_EOL );
echo ( PHP_EOL );
echo ( '===========================================================================' . PHP_EOL );
echo ( '3) Testing SELECT...' . PHP_EOL );
echo ( '===========================================================================' . PHP_EOL );
$result = callWebservice( $selectTestCase, $httpResultCode );
echo ( ( ( $httpResultCode == 200 ) ? 'http result code 200 is OK !' : 'http result code ' . $httpResultCode . ' is KO !' ) . PHP_EOL );
echo ( 'Below is the returned JSON :' . PHP_EOL );
echo ( '---------------------------------------------------------------------------' . PHP_EOL );
echo ( json_encode( $result, JSON_PRETTY_PRINT ) . PHP_EOL );
echo ( '---------------------------------------------------------------------------' . PHP_EOL );

//
// Test case - updating the created record
//
$httpResultCode = NULL;
$selectTestCase = array(
    "Protocol" => "PUT",
    "URL"      => "$WSHOME/test-multi.php",
    "Payload"  => array(
        'id'          => $pkCreated,
        'msg_content' => "A new line with this unique identifier [$uniqueID2]"
    )
);
echo ( PHP_EOL );
echo ( PHP_EOL );
echo ( PHP_EOL );
echo ( '===========================================================================' . PHP_EOL );
echo ( '4) Testing UPDATE...' . PHP_EOL );
echo ( '===========================================================================' . PHP_EOL );
echo ( "Let's update the previously created entry (the one with id=$pkCreated) with this uniqueID = [$uniqueID2]" . PHP_EOL );
$result = callWebservice( $selectTestCase, $httpResultCode );
echo ( ( ( $httpResultCode == 200 ) ? 'http result code 200 is OK !' : 'http result code ' . $httpResultCode . ' is KO !' ) . PHP_EOL );
echo ( 'Below is the returned JSON :' . PHP_EOL );
echo ( '---------------------------------------------------------------------------' . PHP_EOL );
echo ( json_encode( $result, JSON_PRETTY_PRINT ) . PHP_EOL );
echo ( '---------------------------------------------------------------------------' . PHP_EOL );

//
// Test case - retrieve all actual records
//
$httpResultCode = NULL;
$selectTestCase = array(
    "Protocol" => "GET",
    "URL"      => "$WSHOME/test-multi.php",
    "Payload"  => NULL
);
echo ( PHP_EOL );
echo ( PHP_EOL );
echo ( PHP_EOL );
echo ( '===========================================================================' . PHP_EOL );
echo ( '5) Testing SELECT...' . PHP_EOL );
echo ( '===========================================================================' . PHP_EOL );
$result = callWebservice( $selectTestCase, $httpResultCode );
echo ( ( ( $httpResultCode == 200 ) ? 'http result code 200 is OK !' : 'http result code ' . $httpResultCode . ' is KO !' ) . PHP_EOL );
echo ( 'Below is the returned JSON :' . PHP_EOL );
echo ( '---------------------------------------------------------------------------' . PHP_EOL );
echo ( json_encode( $result, JSON_PRETTY_PRINT ) . PHP_EOL );
echo ( '---------------------------------------------------------------------------' . PHP_EOL );

//
// Test case - deleting the created record
//
$httpResultCode = NULL;
$selectTestCase = array(
    "Protocol" => "DELETE",
    "URL"      => "$WSHOME/test-multi.php",
    "Payload"  => array(
        'id' => $pkCreated
    )
);
echo ( PHP_EOL );
echo ( PHP_EOL );
echo ( PHP_EOL );
echo ( '===========================================================================' . PHP_EOL );
echo ( '6) Testing DELETE...' . PHP_EOL );
echo ( '===========================================================================' . PHP_EOL );
echo ( "Let's delete the previously created entry (the one with id=$pkCreated)" . PHP_EOL );
$result = callWebservice( $selectTestCase, $httpResultCode );
echo ( ( ( $httpResultCode == 200 ) ? 'http result code 200 is OK !' : 'http result code ' . $httpResultCode . ' is KO !' ) . PHP_EOL );
echo ( 'Below is the returned JSON :' . PHP_EOL );
echo ( '---------------------------------------------------------------------------' . PHP_EOL );
echo ( json_encode( $result, JSON_PRETTY_PRINT ) . PHP_EOL );
echo ( '---------------------------------------------------------------------------' . PHP_EOL );

//
// Test case - retrieve all actual records
//
$httpResultCode = NULL;
$selectTestCase = array(
    "Protocol" => "GET",
    "URL"      => "$WSHOME/test-multi.php",
    "Payload"  => NULL
);
echo ( PHP_EOL );
echo ( PHP_EOL );
echo ( PHP_EOL );
echo ( '===========================================================================' . PHP_EOL );
echo ( '7) Testing SELECT...' . PHP_EOL );
echo ( '===========================================================================' . PHP_EOL );
$result = callWebservice( $selectTestCase, $httpResultCode );
echo ( ( ( $httpResultCode == 200 ) ? 'http result code 200 is OK !' : 'http result code ' . $httpResultCode . ' is KO !' ) . PHP_EOL );
echo ( 'Below is the returned JSON :' . PHP_EOL );
echo ( '---------------------------------------------------------------------------' . PHP_EOL );
echo ( json_encode( $result, JSON_PRETTY_PRINT ) . PHP_EOL );
echo ( '---------------------------------------------------------------------------' . PHP_EOL );

//
// The method that calls the web-service and displays what has been returned
//
function callWebservice( &$testCase, &$httpResultCode ) {
    $result = NULL;

    switch ($testCase[ "Protocol" ]) {
        case "GET":
            // Call it through CURL
            $ch = curl_init();
            curl_setopt( $ch, CURLOPT_URL, $testCase[ "URL" ] );
            curl_setopt( $ch, CURLOPT_POST, 0 ); // GET
            curl_setopt( $ch, CURLOPT_ENCODING, 'UTF-8' );
            curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
            // payload to send ?
            if ( isset( $testCase[ "Payload" ] ) && !is_null( $testCase[ "Payload" ] ) ) {
                curl_setopt( $ch, CURLOPT_HTTPHEADER, array( 'Content-Type:application/json' ) );
                curl_setopt( $ch, CURLOPT_POSTFIELDS, json_encode( $testCase[ "Payload" ] ) );
                curl_setopt( $ch, CURLOPT_CUSTOMREQUEST, 'GET' );
            }
            $result = curl_exec( $ch );
            $httpResultCode = curl_getinfo( $ch, CURLINFO_HTTP_CODE );
            curl_close( $ch );
            $result = json_decode( $result, true );
            break;

        case "POST":
            // Call it through CURL
            $ch = curl_init();
            curl_setopt( $ch, CURLOPT_URL, $testCase[ "URL" ] );
            curl_setopt( $ch, CURLOPT_ENCODING, 'UTF-8' );
            curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
            curl_setopt( $ch, CURLOPT_HTTPHEADER, array( 'Content-Type:application/json' ) );
            curl_setopt( $ch, CURLOPT_POSTFIELDS, json_encode( $testCase[ "Payload" ] ) );
            $result = curl_exec( $ch );
            $httpResultCode = curl_getinfo( $ch, CURLINFO_HTTP_CODE );
            curl_close( $ch );
            $result = json_decode( $result, true );
            break;

        case "PUT":
            // Call it through CURL
            $ch = curl_init();
            curl_setopt( $ch, CURLOPT_URL, $testCase[ "URL" ] );
            curl_setopt( $ch, CURLOPT_ENCODING, 'UTF-8' );
            curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
            curl_setopt( $ch, CURLOPT_CUSTOMREQUEST, "PUT" );
            curl_setopt( $ch, CURLOPT_HTTPHEADER, array( 'Content-Type:application/json' ) );
            curl_setopt( $ch, CURLOPT_POSTFIELDS, json_encode( $testCase[ "Payload" ] ) );
            $result = curl_exec( $ch );
            $httpResultCode = curl_getinfo( $ch, CURLINFO_HTTP_CODE );
            curl_close( $ch );
            $result = json_decode( $result, true );
            break;

        case "DELETE":
            // Call it through CURL
            $ch = curl_init();
            curl_setopt( $ch, CURLOPT_URL, $testCase[ "URL" ] );
            curl_setopt( $ch, CURLOPT_ENCODING, 'UTF-8' );
            curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
            curl_setopt( $ch, CURLOPT_CUSTOMREQUEST, "DELETE" );
            curl_setopt( $ch, CURLOPT_HTTPHEADER, array( 'Content-Type:application/json' ) );
            curl_setopt( $ch, CURLOPT_POSTFIELDS, json_encode( $testCase[ "Payload" ] ) );
            $result = curl_exec( $ch );
            $httpResultCode = curl_getinfo( $ch, CURLINFO_HTTP_CODE );
            curl_close( $ch );
            $result = json_decode( $result, true );
            break;

        case "PATCH":
            // Call it through CURL
            $ch = curl_init();
            curl_setopt( $ch, CURLOPT_URL, $testCase[ "URL" ] );
            curl_setopt( $ch, CURLOPT_ENCODING, 'UTF-8' );
            curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
            curl_setopt( $ch, CURLOPT_CUSTOMREQUEST, "PATCH" );
            curl_setopt( $ch, CURLOPT_HTTPHEADER, array( 'Content-Type:application/json' ) );
            curl_setopt( $ch, CURLOPT_POSTFIELDS, json_encode( $testCase[ "Payload" ] ) );
            $result = curl_exec( $ch );
            $httpResultCode = curl_getinfo( $ch, CURLINFO_HTTP_CODE );
            curl_close( $ch );
            $result = json_decode( $result, true );
            break;

        default:
            $result = NULL;
            break;
    }

    return $result;
}

?>