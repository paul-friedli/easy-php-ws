<?php

// Please specify here the location of the tests/test folder
$WSHOME = 'https://www.mywebsite.com/folder/folder/easy-php-ws/tests/easy-php-ws/test';

// TRUE => verbose output, you'll see all test details (like the returned JSON)
// FALSE => only the tests results
$verbose             = TRUE;
$verboseOnlyIfFailed = FALSE; // TRUE to turn off $verbose in case everything is fine...
$showURLUnderTitle   = TRUE;

// Inform our web browser that he will display HTML content
header( 'Content-Type: text/html; charset=utf-8' );

// This is an array containing all tests to be done
$testsToDo = array(
    array(
        "Nr"            => "1a",
        "Title"         => "Testing folder URL without parameters",
        "Protocol"      => "GET",
        "URL"           => "$WSHOME/",
        "Payload"       => NULL,
        "expected-json" => "Welcome stranger !",
        "expected-code" => 200,
    ),
    array(
        "Nr"            => "1b",
        "Title"         => "Testing folder URL with parameters",
        "Protocol"      => "GET",
        "URL"           => "$WSHOME/?name=Chewbaca",
        "Payload"       => NULL,
        "expected-json" => "Welcome Chewbaca !",
        "expected-code" => 200,
    ),
    array(
        "Nr"            => "2a",
        "Title"         => "Testing script URL without any parameters",
        "Protocol"      => "GET",
        "URL"           => "$WSHOME/index.php",
        "Payload"       => NULL,
        "expected-json" => "Welcome stranger !",
        "expected-code" => 200,
    ),
    array(
        "Nr"            => "2b",
        "Title"         => "Testing script URL with parameters",
        "Protocol"      => "GET",
        "URL"           => "$WSHOME/index.php?name=Chewbaca",
        "Payload"       => NULL,
        "expected-json" => "Welcome Chewbaca !",
        "expected-code" => 200,
    ),
    array(
        "Nr"            => "3",
        "Title"         => "Testing script URL with parameters returning complex JSON object",
        "Protocol"      => "GET",
        "URL"           => "$WSHOME/test-01.php?name=excellent%20programmer",
        "Payload"       => NULL,
        "expected-json" => array(
            'msg'         => 'Welcome excellent programmer !',
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
        ),
        "expected-code" => 200,
    ),
    array(
        "Nr"            => "4a",
        "Title"         => "Testing script URL returning a boolean",
        "Protocol"      => "GET",
        "URL"           => "$WSHOME/test-02.php?desired-return-type=boolean",
        "Payload"       => NULL,
        "expected-json" => TRUE,
        "expected-code" => 200,
    ),
    array(
        "Nr"            => "4b",
        "Title"         => "Testing script URL returning an integer",
        "Protocol"      => "GET",
        "URL"           => "$WSHOME/test-02.php?desired-return-type=integer",
        "Payload"       => NULL,
        "expected-json" => -1234567890,
        "expected-code" => 200,
    ),
    array(
        "Nr"            => "4c",
        "Title"         => "Testing script URL returning a double",
        "Protocol"      => "GET",
        "URL"           => "$WSHOME/test-02.php?desired-return-type=double",
        "Payload"       => NULL,
        "expected-json" => 3.1415927,
        "expected-code" => 200,
    ),
    array(
        "Nr"            => "4d",
        "Title"         => "Testing script URL returning a string",
        "Protocol"      => "GET",
        "URL"           => "$WSHOME/test-02.php?desired-return-type=string",
        "Payload"       => NULL,
        "expected-json" => 'Today is a great day !',
        "expected-code" => 200,
    ),
    array(
        "Nr"            => "4e",
        "Title"         => "Testing script URL returning a NULL",
        "Protocol"      => "GET",
        "URL"           => "$WSHOME/test-02.php?desired-return-type=null",
        "Payload"       => NULL,
        "expected-json" => NULL,
        "expected-code" => 200,
    ),
    array(
        "Nr"            => "4f",
        "Title"         => "Testing script URL returning an array",
        "Protocol"      => "GET",
        "URL"           => "$WSHOME/test-02.php?desired-return-type=array",
        "Payload"       => NULL,
        "expected-json" => array( 'red', 'green', 'blue' ),
        "expected-code" => 200,
    ),
    array(
        "Nr"            => "4g",
        "Title"         => "Testing script URL returning an unknown JSON type",
        "Protocol"      => "GET",
        "URL"           => "$WSHOME/test-02.php?desired-return-type=whatever",
        "Payload"       => NULL,
        "expected-json" => 'ERROR : Invalid parameters provided !',
        "expected-code" => 400,
    ),
    array(
        "Nr"            => "4h",
        "Title"         => "Testing script URL returning a double and http result code 402 ('Payment Required')",
        "Protocol"      => "GET",
        "URL"           => "$WSHOME/test-02.php?desired-return-type=double&desired-result-code=402",
        "Payload"       => NULL,
        "expected-json" => 3.1415927,
        "expected-code" => 402,
    ),
    array(
        "Nr"            => "5",
        "Title"         => "Testing script URL that does NOT provide any implementation of the desired method",
        "Protocol"      => "GET",
        "URL"           => "$WSHOME/test-03.php",
        "Payload"       => NULL,
        "expected-json" => array(
            'status'           => 'KO',
            'http-result-code' => 501,
            'http-result-msg'  => 'Not Implemented',
            'msg'              => 'Web-service entry get() does not exist. Either implement that method or override getDesiredWebServiceEntries() and defaultRequestVerb() to define your multiple entries.'
        ),
        "expected-code" => 501
    ),
    array(
        "Nr"            => "6",
        "Title"         => "Testing script URL called with GET protocol that also receives a payload like with POST",
        "Protocol"      => "GET",
        "URL"           => "$WSHOME/test-04.php?forename=Paul1&lookgood=yes",
        "Payload"       => array( 'forename' => 'Paul2', 'name' => 'Friedli2', 'age' => 123, 'ismale' => TRUE ),
        "expected-json" => array(
            'name'     => 'Friedli2',
            'forename' => 'Paul2',
            'age'      => 123,
            'ismale'   => TRUE,
            'lookgood' => 'yes'
        ),
        "expected-code" => 200,
    ),
    array(
        "Nr"            => "7a",
        "Title"         => "Testing folder URL without parameters",
        "Protocol"      => "POST",
        "URL"           => "$WSHOME/",
        "Payload"       => NULL,
        "expected-json" => "Welcome stranger !",
        "expected-code" => 200,
    ),
    array(
        "Nr"            => "7b",
        "Title"         => "Testing folder URL with parameters sent through payload",
        "Protocol"      => "POST",
        "URL"           => "$WSHOME/",
        "Payload"       => array( 'name' => 'Chewbaca' ),
        "expected-json" => "Welcome Chewbaca !",
        "expected-code" => 200,
    ),
    array(
        "Nr"            => "7c",
        "Title"         => "Testing folder URL with parameters sent through URL and through payload (payload has priority and overrides any GET parms)",
        "Protocol"      => "POST",
        "URL"           => "$WSHOME/?name=Chewbaca",
        "Payload"       => array( 'name' => 'Hansolo' ),
        "expected-json" => "Welcome Hansolo !",
        "expected-code" => 200,
    ),
    array(
        "Nr"            => "8a",
        "Title"         => "Testing folder URL without parameters",
        "Protocol"      => "PUT",
        "URL"           => "$WSHOME/",
        "Payload"       => NULL,
        "expected-json" => "Welcome stranger !",
        "expected-code" => 200,
    ),
    array(
        "Nr"            => "8b",
        "Title"         => "Testing folder URL with parameters sent through payload",
        "Protocol"      => "PUT",
        "URL"           => "$WSHOME/",
        "Payload"       => array( 'name' => 'Chewbaca' ),
        "expected-json" => "Welcome Chewbaca !",
        "expected-code" => 200,
    ),
    array(
        "Nr"            => "8c",
        "Title"         => "Testing folder URL with parameters sent through URL and through payload (payload has priority and overrides any GET parms)",
        "Protocol"      => "PUT",
        "URL"           => "$WSHOME/?name=Chewbaca",
        "Payload"       => array( 'name' => 'Hansolo' ),
        "expected-json" => "Welcome Hansolo !",
        "expected-code" => 200,
    ),
    array(
        "Nr"            => "9a",
        "Title"         => "Testing folder URL without parameters",
        "Protocol"      => "DELETE",
        "URL"           => "$WSHOME/",
        "Payload"       => NULL,
        "expected-json" => "Welcome stranger !",
        "expected-code" => 200,
    ),
    array(
        "Nr"            => "9b",
        "Title"         => "Testing folder URL with parameters sent through payload",
        "Protocol"      => "DELETE",
        "URL"           => "$WSHOME/",
        "Payload"       => array( 'name' => 'Chewbaca' ),
        "expected-json" => "Welcome Chewbaca !",
        "expected-code" => 200,
    ),
    array(
        "Nr"            => "9c",
        "Title"         => "Testing folder URL with parameters sent through URL and through payload (payload has priority and overrides any GET parms)",
        "Protocol"      => "DELETE",
        "URL"           => "$WSHOME/?name=Chewbaca",
        "Payload"       => array( 'name' => 'Hansolo' ),
        "expected-json" => "Welcome Hansolo !",
        "expected-code" => 200,
    ),

    array(
        "Nr"            => "10a",
        "Title"         => "Testing folder URL without parameters",
        "Protocol"      => "PATCH",
        "URL"           => "$WSHOME/",
        "Payload"       => NULL,
        "expected-json" => "Welcome stranger !",
        "expected-code" => 200,
    ),
    array(
        "Nr"            => "10b",
        "Title"         => "Testing folder URL with parameters sent through payload",
        "Protocol"      => "PATCH",
        "URL"           => "$WSHOME/",
        "Payload"       => array( 'name' => 'Chewbaca' ),
        "expected-json" => "Welcome Chewbaca !",
        "expected-code" => 200,
    ),
    array(
        "Nr"            => "10c",
        "Title"         => "Testing folder URL with parameters sent through URL and through payload (payload has priority and overrides any GET parms)",
        "Protocol"      => "PATCH",
        "URL"           => "$WSHOME/?name=Chewbaca",
        "Payload"       => array( 'name' => 'Hansolo' ),
        "expected-json" => "Welcome Hansolo !",
        "expected-code" => 200,
    ),

    array(
        "Nr"            => "11",
        "Title"         => "Testing script having multiple web-service entries defined through getDesiredWebServiceEntries() overrides => greeting()",
        "Protocol"      => "GET",
        "URL"           => "$WSHOME/test-05-multi.php?request=greeting&name=Chewbaca",
        "Payload"       => NULL,
        "expected-json" => "Welcome Chewbaca !",
        "expected-code" => 200,
    ),
    array(
        "Nr"            => "12",
        "Title"         => "Testing script having multiple web-service entries defined through getDesiredWebServiceEntries() overrides => greeting()",
        "Protocol"      => "POST",
        "URL"           => "$WSHOME/test-05-multi.php",
        "Payload"       => array(
            'request' => 'greeting',
            'name'    => 'Chewbaca'
        ),
        "expected-json" => "Welcome Chewbaca !",
        "expected-code" => 200,
    ),
    array(
        "Nr"            => "13",
        "Title"         => "Testing script having multiple web-service entries defined through getDesiredWebServiceEntries() overrides => greeting()",
        "Protocol"      => "DELETE",
        "URL"           => "$WSHOME/test-05-multi.php",
        "Payload"       => array(
            'request' => 'greeting',
            'name'    => 'Chewbaca'
        ),
        "expected-json" => array(
            'status'           => 'KO',
            'http-result-code' => 403,
            'http-result-msg'  => 'Forbidden',
            'msg'              => 'The called web-service greeting() does not support the desired [DELETE] protocol. Please check your getDesiredWebServiceEntries() override.'
        ),
        "expected-code" => 403,
    ),
    array(
        "Nr"            => "14",
        "Title"         => "Testing script having multiple web-service entries defined through getDesiredWebServiceEntries() overrides => serverDate()",
        "Protocol"      => "POST",
        "URL"           => "$WSHOME/test-05-multi.php",
        "Payload"       => array(
            'request' => 'serverDate'
        ),
        "expected-json" => array(
            'server-date' => date( 'd.m.Y' )
        ),
        "expected-code" => 200,
    ),
    array(
        "Nr"            => "15a",
        "Title"         => "Testing script having multiple web-service entries defined through getDesiredWebServiceEntries() overrides => Crud Person",
        "Protocol"      => "POST",
        "URL"           => "$WSHOME/test-05-multi.php",
        "Payload"       => array(
            'request'  => 'create_Person',
            'name'     => 'Disney',
            'forename' => 'Walt',
        ),
        "expected-json" => array(
            'status' => 'OK',
            'PK'     => 123
        ),
        "expected-code" => 200,
    ),
    array(
        "Nr"            => "15b",
        "Title"         => "Testing script having multiple web-service entries defined through getDesiredWebServiceEntries() overrides => cRud Person",
        "Protocol"      => "GET",
        "URL"           => "$WSHOME/test-05-multi.php?request=read_Person&PK=123",
        "Payload"       => NULL,
        "expected-json" => array(
            'status' => 'OK',
            'person' => array(
                'PK'       => 123,
                'name'     => 'Disney',
                'forename' => 'Walt',
            )
        ),
        "expected-code" => 200,
    ),
    array(
        "Nr"            => "15c",
        "Title"         => "Testing script having multiple web-service entries defined through getDesiredWebServiceEntries() overrides => crUd Person",
        "Protocol"      => "PUT",
        "URL"           => "$WSHOME/test-05-multi.php",
        "Payload"       => array(
            'request' => 'update_Person',
            'person'  => array(
                'PK'       => 123,
                'name'     => 'Disney',
                'forename' => 'Walt',
            )
        ),
        "expected-json" => array(
            'status' => 'OK',
        ),
        "expected-code" => 200,
    ),
    array(
        "Nr"            => "15d",
        "Title"         => "Testing script having multiple web-service entries defined through getDesiredWebServiceEntries() overrides => cruD Person",
        "Protocol"      => "DELETE",
        "URL"           => "$WSHOME/test-05-multi.php",
        "Payload"       => array(
            'request' => 'delete_Person',
            'PK'      => 123
        ),
        "expected-json" => array(
            'status' => 'OK',
        ),
        "expected-code" => 200,
    ),
);

//
// Start the global report
//
echo "<H1>Running all test cases for 'easy-php-ws'...</H1>";
echo "<table border='1' cellspacing='0' cellpadding='5'>";
echo "<tr bgcolor='#D0D0D0'><td><b>Nr</b></td><td><b>Protocol</b></td><td><b>Title</b></td><td><b>Result</b></td></tr>";
$bgColorDetailedReportCell = '#FCFCE0';
$bgColorDetailedReportJSON = '#FCE0FC';
foreach ( $testsToDo as $testToDo ) {
    //
    // Prepare individual report lines
    //
    $tdNr       = $testToDo[ "Nr" ];
    $tdProtocol = $testToDo[ "Protocol" ];
    $tdTitle    = $testToDo[ "Title" ];
    $tdURL      = $testToDo[ "URL" ];
    $report     = "<font face='Inconsolata, Consolas' size='2'>";
    $report .= "URL = <a href='$tdURL'>$tdURL</a><br/>";
    $report .= "<hr/>";

    //
    // Call the specified web-service
    //
    $receivedData = callWebservice( $testToDo, $httpResultCode );

    //
    // Verify http resulting code with expected one and show in report
    //
    $isHttpResultCodeOK = ( isset( $testToDo[ "expected-code" ] ) && ( $testToDo[ "expected-code" ] == $httpResultCode ) ) ||
        ( !isset( $testToDo[ "expected-code" ] ) && ( $httpResultCode == 200 ) );
    if ( !$isHttpResultCodeOK ) {
        $tdResult = "<font color=red>KO</font>";
        $report .= "<font color=red size='+1'><b>Returned http code is different than expected !</b></font><br/>";
        $report .= "<font color=red>Expected : " . $testToDo[ "expected-code" ] . "</font><br/>";
        $report .= "<font color=red>Received : $httpResultCode</font><br/>";
    } else {
        $tdResult = "<font color=green>OK</font>";
        $report .= "<font color=green size='+1'><b>Returned http code is same as expected !</b></font><br/>";
    }

    //
    // Verify received JSON with expected one
    //
    $isDataOK = compareExpectedReceivedData( $receivedData, $testToDo[ "expected-json" ] );
    if ( !$isDataOK ) {
        $tdResult = "<font color=red>KO</font>";
        $report .= "<font color=red size='+1'><b>Returned JSON is different than expected !</b></font><br/>";
        $report .= "<font color=red>Expected : " . explainDataTypeAndContent( $testToDo[ "expected-json" ] ) . "</font><br/>";
        $report .= "<font color=red>Received : " . explainDataTypeAndContent( $receivedData ) . "</font><br/>";
    } else {
        $tdResult = "<font color=green>OK</font>";
        $report .= "<font color=green size='+1'><b>Returned JSON is same as expected !</b></font><br/>";
    }

    //
    // Add expected / received JSON to report
    //
    $report .= "<hr/>";
    $report .= "Received JSON is below<br/>";
    $report .= "<div style='background-color:$bgColorDetailedReportJSON;'><code><pre>";
    $report .= json_encode( $receivedData, JSON_PRETTY_PRINT );
    $report .= "</pre></code></div>";
    $report .= "<hr/>";
    $report .= "Expected JSON is below<br/>";
    $report .= "<div style='background-color:$bgColorDetailedReportJSON;'><code><pre>";
    $report .= json_encode( $testToDo[ "expected-json" ], JSON_PRETTY_PRINT );
    $report .= "</pre></code></div>";

    // 
    // Produce the report for this test
    //
    $tdTitleExtras = '';
    if ( $showURLUnderTitle ) {
        $tdTitleExtras = "<br><font face='Inconsolata, Consolas' size='1'><a href='$tdURL'>$tdURL</a></font>";
    }
    echo "<tr><td>$tdNr</td><td>$tdProtocol</td><td>$tdTitle$tdTitleExtras</td><td>$tdResult</td></tr>";
    $report .= "</font>";
    if ( ( $verbose && !$verboseOnlyIfFailed ) || ( $verbose && $verboseOnlyIfFailed && !$isDataOK ) ) {
        echo "<tr><td></td><td colspan='3' bgcolor='$bgColorDetailedReportCell'>";
        echo "$report";
        echo "</td></tr>";
    }
}
echo "</table>";

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

function compareExpectedReceivedData( $receivedData, $expectedData ) {

    switch (gettype( $expectedData )) {

        case "boolean":
        case "integer":
        case "double":
        case "string":
        case "NULL":
            return $receivedData === $expectedData;

        case "array":
            // Quick and dirty way to compare the two arrays (as they should be encoded the same way in a JSON string)
            $jsonStr1 = json_encode( $receivedData );
            $jsonStr2 = json_encode( $expectedData );
            return $jsonStr1 === $jsonStr2;
    }

    return false; // Not JSON accepted type anyway...
}

function explainDataTypeAndContent( &$data ) {

    switch (gettype( $data )) {
        case "boolean":
            return "(boolean) " . ( $data ? 'TRUE' : 'FALSE' );

        case "integer":
            return "(integer) $data";

        case "double":
            return "(double) $data";

        case "string":
            return "(string)(length=" . strlen( $data ) . ")[$data]";

        case "NULL":
            return "(NULL)NULL";

        case "array":
            $nKeys = sizeof( array_keys( $data ) );
            $encodedData = json_encode( $data );
            return "(hash)(nkeys=$nKeys)[$encodedData]";
    }

    return "Not a JSON valid type (the type is '" . gettype( $data ) . "')"; // Not JSON accepted type
}

?>
