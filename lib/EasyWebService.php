<?php
////////////////////////////////////////////////////////////////////////////////
//                          _____                                             //
//                         | ____|__ _ ___ _   _                              //
//                         |  _| / _` / __| | | |                             //
//                         | |__| (_| \__ \ |_| |                             //
//                         |_____\__,_|___/\__, |                             //
//        __        __   _       ____      |___/       _                      //
//        \ \      / /__| |__   / ___|  ___ _ ____   _(_) ___ ___  ___        //
//         \ \ /\ / / _ \ '_ \  \___ \ / _ \ '__\ \ / / |/ __/ _ \/ __|       //
//          \ V  V /  __/ |_) |  ___) |  __/ |   \ V /| | (_|  __/\__ \       //
//           \_/\_/ \___|_.__/  |____/ \___|_|    \_/ |_|\___\___||___/       //
//                        _         ____  _   _ ____                          //
//                       (_)_ __   |  _ \| | | |  _ \                         //
//                       | | '_ \  | |_) | |_| | |_) |                        //
//                       | | | | | |  __/|  _  |  __/                         //
//                       |_|_| |_| |_|   |_| |_|_|                            //
//                                                                            //
//                          written by Paul Friedli                           //
//                                                                            //
// -------------------------------------------------------------------------- //
//                                                                            //
//      Easy and fast mini-framework for creating RESTfull web-services.      //
//                                                                            //
// ========================================================================== //
// HISTORY :                                                                  //
// ---------                                                                  //
// v0.1 / 2011                                                                //
// First functional version. Reingineered several times to ease its use.      //
//                                                                            //
// v1.01 / 2014                                                               //
// Initial 'framework' implementation.                                        //
//                                                                            //
// v1.02 / 2015                                                               //
// Only 'POST' protocol were supported.                                       //
// Added support for 'GET', 'PUT', 'PATCH', 'DELETE' and 'HEAD' protocols.    //
// Added cool way to reach parameters : always provided the same way to your  //
// methods implementing a web-service entry regardless of the protocol used.  //
//                                                                            //
// v1.03 / 2016                                                               //
// Added support for multiple web-service entries within the same script.     //
//                                                                            //
// v1.04 / 2016                                                               //
// Added support for easier SQL requests and transactions through ready made  //
// classes very easy to use. More declarative, less coding :-)                //
//                                                                            //
// v1.04 / 2018                                                               //
// Added transactions support.                                                //
//                                                                            //
// v2.01 / 2023                                                               //
// Rewrite and port to GitHub.                                                //
// Added "unit testing" script testing all EasyWebService functionalities.    //
// Added documentation and explanations that were missing.                    //
//                                                                            //
// v2.02 / 2023                                                               //
// Minor bug fix and added destructor.                                        //
//                                                                            //
////////////////////////////////////////////////////////////////////////////////

// To activate internal debugging 
define( 'EWS_DEBUGGING_ACTIVATED', FALSE );
define( 'EWS_DEBUGGING_FILENAME', 'ews_debug.txt' );

class EasyWebService
{
    /**
     * Holding the database connection if any (mixed type because not all implementations use objects)
     * @var mixed
     */
    private mixed $dbConnection;

    /**
     * Holding the http result code to send back to calling client
     * @var int
     */
    private int $httpResultCode;

    /**
     * Class constructor. Initialize a new EasyWebService instance.
     */
    public function __construct() {
        // For the moment we are not connected to any DB
        $this->dbConnection = null;

        // By default the result code will be 200/OK unless user changes it with setter
        $this->httpResultCode = 200;
    }

    /**
     * Class destructor.
     */
    public function __destruct() {
        
    }
    /**
     * Getter of getDBConnection, holder of the database connection.
     * 
     * @return mixed current database connection
     */
    public function getDBConnection() : mixed {
        return $this->dbConnection;
    }

    /**
     * Setter of the http result code that will be sent back to calling client.
     * @param int $newHttpResultCode the new http result code to send
     * @return void
     */
    public function setHttpResultCode( int $newHttpResultCode ) : void {
        $this->httpResultCode = $newHttpResultCode;
    }

    /**
     * Getter of the http result code that will be sent back to calling client.
     * @return int
     */
    public function getHttpResultCode() : int {
        return $this->httpResultCode;
    }

    /**
     * Use this method to very easily specify all your desired web-services entries.
     * 
     * Each entry in the array must consist of an array of key/value parameters to define the desired web-service behaviour.
     * 
     * The supported keys/values are :
     * array(
     *     'request'           => 'getDateTime',                                            // Name of the REST request called
     *     'php-method'        => 'requestHandler_getDateTime',                             // Name of the method/handler to call to answer that request
     *     'needDB'            => FALSE,                                                    // Does this request need a database connection to do its job ?
     *     'protocols'         => array( 'GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'HEAD' )  // HTTP protocols that can be used to reach this request
     *  ) 
     *  
     * The key 'request' is the key name by default to identify the desired method. It can be changed by overriding the defaultRequestVerb() method.
     * 
     * @return array|null
     */
    public function getDesiredWebServiceEntries() : array|null {
        // By default we do not provided multiple web-service entries.
        return null;
    }

    /**
     * Sends the desired http header to caller.
     * 
     * @param mixed $contentType the content-type to be applied
     * @param mixed $statusCode the http status code to be applied
     * @return void
     */
    public function sendHttpHeaders( $contentType, $statusCode ) : void {
        http_response_code( $statusCode );
        $statusMessage = $this->getHttpStatusMessage( $statusCode );
        header( "HTTP/1.1 " . $statusCode . " " . $statusMessage );
        header( "Content-Type: " . $contentType );
    }

    /**
     * Returns the official text explanation of a valid http status code.
     * 
     * @param mixed $statusCode the http status code of interest
     * @return string the http status code textual explanation
     */
    public final function getHttpStatusMessage( $statusCode ) : string {
        $httpStatus = array(
            100 => 'Continue',
            101 => 'Switching Protocols',
            200 => 'OK',
            201 => 'Created',
            202 => 'Accepted',
            203 => 'Non-Authoritative Information',
            204 => 'No Content',
            205 => 'Reset Content',
            206 => 'Partial Content',
            300 => 'Multiple Choices',
            301 => 'Moved Permanently',
            302 => 'Found',
            303 => 'See Other',
            304 => 'Not Modified',
            305 => 'Use Proxy',
            306 => '(Unused)',
            307 => 'Temporary Redirect',
            400 => 'Bad Request',
            401 => 'Unauthorized',
            402 => 'Payment Required',
            403 => 'Forbidden',
            404 => 'Not Found',
            405 => 'Method Not Allowed',
            406 => 'Not Acceptable',
            407 => 'Proxy Authentication Required',
            408 => 'Request Timeout',
            409 => 'Conflict',
            410 => 'Gone',
            411 => 'Length Required',
            412 => 'Precondition Failed',
            413 => 'Request Entity Too Large',
            414 => 'Request-URI Too Long',
            415 => 'Unsupported Media Type',
            416 => 'Requested Range Not Satisfiable',
            417 => 'Expectation Failed',
            500 => 'Internal Server Error',
            501 => 'Not Implemented',
            502 => 'Bad Gateway',
            503 => 'Service Unavailable',
            504 => 'Gateway Timeout',
            505 => 'HTTP Version Not Supported'
        );
        return ( $httpStatus[ $statusCode ] ) ? $httpStatus[ $statusCode ] : 'INVALID http STATUS CODE';
    }

    /**
     * Provides the default http result code that will be applied to all requests. Feel free to override it.
     * 
     * @return int the defaul http result code that will be applied to all requests
     */
    public function defaultHttpResultCode() : int {
        // By default everything is fine 200/OK
        return 200;
    }

    /**
     * Provides the default request verb to be used to identify the desired web-service entry to be called. Feel free to override it to use another verb.
     * 
     * @return string the defaul request verb to be used to identify the desired web-service entry to be called
     */
    public function defaultRequestVerb() : string {
        return 'request';
    }

    /**
     * Provides the default content-type that will be sent back to caller. Feel free to override it to use another content-type.
     * 
     * @return string default content-type that will be sent back to caller
     */
    public function defaultAnswerContentType() : string {
        return 'application/json; charset=UTF-8';
    }

    // To access your own database, override this method
    /**
     * Must be overridden to supply the database connection settings to be used. You must return an array with the following informations :
     * array(
     *     'host' => 'your hostname',
     *     'dbname' => 'your database name',
     *     'username' => 'your username',
     *     'password' => 'your password'
     * )
     * 
     * @return array|null your database connection settings to be used
     */
    public function defaultDatabaseConnectionSettings() : array|null {
        // No database connection needed by default
        return null;
    }

    /**
     * To return an error to caller.
     * 
     * @param int $desiredHttpResultCode the desired http result code
     * @param string $msg the message to send
     * @param array $details extra details to send is available (exception details)
     * @return array the overall answer to send back to caller
     */
    private function defaultErrorAnswerPackaging( int $desiredHttpResultCode, string $msg, array $details = NULL ) : array {

        $this->setHttpResultCode( $desiredHttpResultCode );

        if ( is_null( $details ) ) {
            return array(
                'status'           => 'KO',
                'http-result-code' => $desiredHttpResultCode,
                'http-result-msg'  => $this->getHttpStatusMessage( $desiredHttpResultCode ),
                'msg'              => $msg
            );
        } else {
            return array(
                'status'           => 'KO',
                'http-result-code' => $desiredHttpResultCode,
                'http-result-msg'  => $this->getHttpStatusMessage( $desiredHttpResultCode ),
                'msg'              => $msg,
                'details'          => $this->encodeJson( $details )
            );
        }
    }

    /**
     * Provides the default 'packaging' behaviour of the 'answer' just before it is sent back to caller. Feel free to override it to change default behaviour.
     * By default there is no packaging of the answer that is sent back to caller. It could be very handy as we could do stuff like :
     *   - adding a field depending on current http result code
     *   - encode/encrypt all/some data
     *   - ...
     * 
     * @param mixed $answerFromMethod the answer generated by the web-service entry that has been successfully called
     * @return string|int|float|bool|null|array the packaged answer we will send back to caller
     */
    public function defaultMethodAnswerPackaging( $answerFromMethod ) : string|int|float|bool|null|array {
        // By default no packaging
        return $answerFromMethod;
    }

    /**
     * Provides the default 'encoding' behaviour of the 'answer' just before it is sent back to caller. Feel free to override it to change default behaviour.
     * By default the answer will be JSON encoded before being sent back to caller.
     * 
     * @param mixed $answer the answer generated by the web-service entry that has been successfully called
     * @return string the encoded answer we will send back to caller
     */
    public function defaultEncodeAnswerSentBack( $answer ) : string {
        // By default data is sent back in JSON
        return $this->encodeJson( $answer );
    }

    /**
     * Internal method to control the way we JSON encode (json_encode parameters).
     * 
     * @param mixed $data data to JSON encode
     * @return string encoded data
     */
    private function encodeJson( $data ) : string {
        return json_encode( $data, JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR );
    }

    /**
     * Default implementation of database connection, here using MySQL code. Feel free to override it to change behaviour (other parameters or kind of database).
     * The DB channel will be set to UTF-8 and LC_TIME_NAMES configured to produce French results. Feel free to override.
     * 
     * @param string $host database host
     * @param string $dbname database name
     * @param string $username database username
     * @param string $password database password
     * @return bool true if successfully connected
     */
    public function databaseConnect( string $host, string $dbname, string $username, string $password ) : bool {
        $successfull = false;

        // Connect to database with default implementation relying on mysqli.
        // Simply ovverride this method if other database or settings are desired.
        if ( ( $this->dbConnection = mysqli_connect( $host, $username, $password, $dbname ) ) == true ) {
            // Force UTF8 database communication at all levels
            mysqli_query( $this->dbConnection, 'SET NAMES utf8' );
            mysqli_query( $this->dbConnection, 'SET CHARACTER SET utf8' );
            mysqli_set_charset( $this->dbConnection, 'utf8' );
            mysqli_query( $this->dbConnection, "SET LC_TIME_NAMES= 'fr_FR'" ); // To get day names in french with DATE_FORMAT()
            // Make sure we are using the correct database
            mysqli_query( $this->dbConnection, 'USE ' . $dbname );

            $successfull = true;
        }

        return $successfull;
    }

    /**
     * Default implementation of database disconnection, here using MySQL code. Feel free to override it to change behaviour (other parameters or kind of database).
     * 
     * @return void
     */
    public function databaseDisconnect() : void {
        if ( !is_null( $this->dbConnection ) ) {
            mysqli_close( $this->dbConnection );
            $this->dbConnection = null;
        }
    }

    /**
     * The main entry point doing all the magic within EasyWebService. It all starts here...
     * 
     * @return void
     */
    public function execute() : void {

        EasyWebService::internalDebug( '---------------------------------------------------------------------------------------------------------' );

        // To make sure all PHP methods won't produce output as we test ourselves return values
        ini_set( 'display_errors', '0' );

        // Make sure we catch all PHP errors, notice, warnings, deprecated methods, ...
        error_reporting( E_ALL | E_STRICT );

        //error_reporting( E_ERROR | E_PARSE ); // In production would be better
        // Tell PHP that every error should be converted to a ErrorException so that we can handle all of them in the try/catch below
        function exception_error_handler( $errno, $errstr, $errfile, $errline ) {
            throw new \ErrorException( $errstr, $errno, 0, $errfile, $errline );
        }

        set_error_handler( "exception_error_handler" );

        // For the moment put the default http status code
        $this->setHttpResultCode( $this->defaultHttpResultCode() );

        // Content-type by default
        $contentType = $this->defaultAnswerContentType();

        // Handle request and potential raised errors
        try {

            //
            // Most of the magic happens within here...
            //
            EasyWebService::internalDebug( 'calling extractPayloadAccordingToHttpProtocolUsedAndProcessRequest()...' );
            $answer = $this->extractPayloadAccordingToHttpProtocolUsedAndProcessRequest();

        } catch ( \Throwable $e ) { // For PHP 7
            $this->setHttpResultCode( 500 );
            $answer = $this->defaultErrorAnswerPackaging(
                500,
                'Error or exception encountered !',
                array(
                    'code'               => $e->getCode(),
                    'file'               => $e->getFile(),
                    'line'               => $e->getLine(),
                    'message'            => $e->getMessage(),
                    'stacktraceAsString' => $e->getTraceAsString()
                )
            );
        } catch ( \Exception $e ) { // For PHP 5
            $this->setHttpResultCode( 500 );
            $answer = $this->defaultErrorAnswerPackaging(
                500,
                'Error or exception encountered !',
                array(
                    'code'               => $e->getCode(),
                    'file'               => $e->getFile(),
                    'line'               => $e->getLine(),
                    'message'            => $e->getMessage(),
                    'stacktraceAsString' => $e->getTraceAsString()
                )
            );
        }

        // Depending on the processing status, send the correct header
        EasyWebService::internalDebug( 'sending http headers for content type [' . $contentType . '] with result code [' . $this->getHttpResultCode() . ']...' );
        $this->sendHttpHeaders( $contentType, $this->getHttpResultCode() );

        // JSON encode the result and send it back to caller 
        EasyWebService::internalDebug( 'sending back the answer => ' . $this->defaultEncodeAnswerSentBack( $answer ) );
        echo $this->defaultEncodeAnswerSentBack( $answer );
    }

    /**
     * Process the received web-service request. All parameters are first assembled, either passed via the URL (GET) or through payload (POST), before calling the registered handler.
     * 
     * @return string|int|float|bool|null|array the JSON answer resulting from the called web-service entry
     */
    private function extractPayloadAccordingToHttpProtocolUsedAndProcessRequest() : string|int|float|bool|null|array {
        // What protocol has been used to contact us (can be GET, POST, PUT, PATCH, DELETE, HEAD) ?
        $protocol = strtoupper( $_SERVER[ 'REQUEST_METHOD' ] );
        EasyWebService::internalDebug( 'detected protocol is [' . $protocol . ']...' );

        // Our parameters for the moment
        $inputParameters = array();

        // Retrieve the GET parameters within URL and transform them all in an array of keys => values
        parse_str( $_SERVER[ 'QUERY_STRING' ], $inputParametersByGet );
        EasyWebService::internalDebug( 'detected GET parameters are ' . json_encode( $inputParametersByGet, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT ) );

        // Retrieve the POST parameters by decoding the received JSON payload (if any) 
        if ( ( $inputParametersByPayload = json_decode( file_get_contents( 'php://input' ), TRUE ) ) == NULL ) {
            $inputParametersByPayload = array();
        }
        EasyWebService::internalDebug( 'detected POST parameters are ' . json_encode( $inputParametersByPayload, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT ) );

        // Merge the two set of parameters with POST having priority and overriding GET ones
        $inputParameters = array_merge( $inputParametersByGet, $inputParametersByPayload );
        EasyWebService::internalDebug( 'computed overall parameters are ' . json_encode( $inputParameters, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT ) );

        // Process the request accordingly
        switch ($protocol) {
            case 'POST':
            case 'PUT':
            case 'PATCH':
            case 'DELETE':
            case 'HEAD':
            case 'GET':
                // Process this request
                EasyWebService::internalDebug( 'calling processRequestWithJSONInput()...' );
                $answer = $this->processRequestWithJSONInput( $protocol, $inputParameters );
                EasyWebService::internalDebug( 'processRequestWithJSONInput() is done' );
                break;

            default:
                // Unknown protocol
                EasyWebService::internalDebug( '[' . $protocol . '] is not a recognized protocol (must be GET, POST, PUT, PATCH, DELETE or HEAD)!' );
                $answer = $this->defaultErrorAnswerPackaging(
                    400,
                    'Error : the only http protocols supported are GET, POST, PUT, PATCH, DELETE and HEAD !'
                );
                break;
        }

        return $answer;
    }

    /**
     * Internal debug tool to verbosely add lines to a text file. Can be (de)activated through the EWS_DEBUGGING_ACTIVATED constant.
     * 
     * @param string $msg the line of text to add
     * @return void
     */
    public static function internalDebug( string $msg ) {
        if ( EWS_DEBUGGING_ACTIVATED ) {
            $now    = DateTime::createFromFormat( 'U.u', number_format( microtime( true ), 6, '.', '' ) );
            $myfile = file_put_contents( EWS_DEBUGGING_FILENAME, $now->format( "Y-m-d H:i:s.u" ) . ' : ' . $msg . PHP_EOL, FILE_APPEND | LOCK_EX );
        }
    }

    /**
     * Process the received web-service request call, now that the protocol has been determined and the parameters have been collected.
     * 
     * @param string $protocol the protocol used
     * @param array $jsonReceived the received parameters
     * @return string|int|float|bool|null|array the JSON answer
     */
    private function processRequestWithJSONInput( string $protocol, array $jsonReceived ) : string|int|float|bool|null|array {

        // Do we need database connection for this call ?
        $dbSettings       = $this->defaultDatabaseConnectionSettings();
        $needDBConnection = !is_null( $dbSettings );

        // Then find out the name of the method that we should call...
        $wsName     = NULL;
        $protocolLC = strtolower( $protocol );
        if ( method_exists( $this, $protocolLC ) ) {
            $wsName = $protocolLC;
            EasyWebService::internalDebug( 'The method ' . $wsName . '() has been detected as present and will be called...' );
        } else {
            // None of the above. Probably using single/multiple entry-point(s) specified through getDesiredWebServiceEntries() override, and 
            // called with a 'verb' to select the desired web-service method to be executed.
            // Let's find out.
            $registeredHandlers = $this->getDesiredWebServiceEntries();
            if ( !is_null( $registeredHandlers ) && is_array( $registeredHandlers ) ) {

                // There are handlers defined, but did we receive the 'verb' specifying the web-service entry to be executed ?
                $requestVerb = $this->defaultRequestVerb();
                if ( isset( $jsonReceived[ $requestVerb ] ) ) {

                    // Verb has been specified and we have web-service entries.
                    EasyWebService::internalDebug( 'The method ' . $jsonReceived[ $requestVerb ] . '() is the one that should be called...' );

                    // Process each specific web-service entries to find a matching one for this protocol
                    EasyWebService::internalDebug( 'Scanning each defined web-service entries to find a matching one...' );
                    foreach ( $registeredHandlers as $handlerParms ) {

                        // Valid web-service entry ?
                        if ( !is_null( $handlerParms ) && is_array( $handlerParms ) ) {

                            // Check web-service entry name to see if request verb matches the one that has been called
                            if ( isset( $handlerParms[ $requestVerb ] ) && ( $jsonReceived[ $requestVerb ] == $handlerParms[ $requestVerb ] ) ) {

                                EasyWebService::internalDebug( 'We found a matching web-service entry !' );

                                // Do we need DB connection ?
                                $needDBConnection = isset( $handlerParms[ 'needDB' ] ) ? $handlerParms[ 'needDB' ] : FALSE;
                                EasyWebService::internalDebug( 'The method does need DB access [' . ( $needDBConnection ? 'YES' : 'NO' ) . ']...' );

                                // What are the protocols we support ?
                                $acceptedProtocols = isset( $handlerParms[ 'protocols' ] ) ? $handlerParms[ 'protocols' ] : array();

                                // Check if the protocol we have been called with is supported ?
                                if ( !in_array( $protocol, $acceptedProtocols ) ) {
                                    $error = "The called web-service " . $jsonReceived[ $requestVerb ] . "() does not support the desired [$protocol] protocol. Please check your getDesiredWebServiceEntries() override.";
                                    EasyWebService::internalDebug( $error );
                                    return $this->defaultErrorAnswerPackaging( 403, $error );
                                }

                                // Everything is fine. Store the name of the method that must be called as we found it !
                                // If the entry redefines the name of the php-method, take that one
                                if ( isset( $handlerParms[ 'php-method' ] ) ) {
                                    $wsName = $handlerParms[ 'php-method' ];
                                    EasyWebService::internalDebug( 'The web-service entry definition has redefined the method name that is now ' . $wsName . '()' );
                                } else {
                                    $wsName = $jsonReceived[ $requestVerb ];
                                    EasyWebService::internalDebug( 'The web-service entry definition does not redefine the method name that remains ' . $wsName . '()' );
                                }
                                break;
                            }
                        }
                    }

                    // Did we find it ?
                    if ( is_null( $wsName ) ) {
                        $error = "The called web-service " . $jsonReceived[ $requestVerb ] . "() does not exist in your getDesiredWebServiceEntries() override. Please check it.";
                        EasyWebService::internalDebug( $error );
                        return $this->defaultErrorAnswerPackaging( 405, $error );
                    }
                } else {
                    // No, then return a corresponding error
                    $error = "Unable to determine the web-service entry to be executed as the '$requestVerb' parameter has not been specified. Please check your getDesiredWebServiceEntries() and defaultRequestVerb() overrides.";
                    EasyWebService::internalDebug( $error );
                    return $this->defaultErrorAnswerPackaging( 501, $error );
                }

            } else {
                // No handlers defined, return corresponding error
                $error = 'Web-service entry ' . $protocolLC . '() does not exist. Either implement that method or override getDesiredWebServiceEntries() and defaultRequestVerb() to define your multiple entries.';
                EasyWebService::internalDebug( $error );
                return $this->defaultErrorAnswerPackaging( 501, $error );
            }
        }

        //
        // If we are here, $wsName contains the name of the desired web-service entry to call. Process it.
        //

        // Check if DB connection settings are available
        if ( $needDBConnection && is_null( $dbSettings ) ) {
            $error = "This web-service entry $wsName() needs database access but your overridden defaultDatabaseConnectionSettings() implementation does not specify 'host', 'dbname', 'username' and 'password' settings !";
            EasyWebService::internalDebug( $error );
            return $this->defaultErrorAnswerPackaging( 500, $error );
        }

        EasyWebService::internalDebug( 'JSON input follows => ' . json_encode( $jsonReceived, JSON_PRETTY_PRINT ) );

        // Connect to the database if needed
        if ( $needDBConnection ) {
            // Try to connect to the database and report if it fails
            EasyWebService::internalDebug( 'Trying to connect to the database...' );
            if ( !$this->databaseConnect( $dbSettings[ 'host' ], $dbSettings[ 'dbname' ], $dbSettings[ 'username' ], $dbSettings[ 'password' ] ) ) {
                $error = "Connection to the database failed ! Please check the 'host', 'dbname', 'username' and 'password' settings in your overridden defaultDatabaseConnectionSettings() implementation !";
                EasyWebService::internalDebug( $error );
                return $this->defaultErrorAnswerPackaging( 500, $error );
            }

            EasyWebService::internalDebug( 'Successfully connected to the database...' );
        }

        // Make sure the method implementing this web-service exists
        if ( !method_exists( $this, $wsName ) ) {
            $error = "The web-service entry $wsName() does not exist !";
            EasyWebService::internalDebug( $error );
            return $this->defaultErrorAnswerPackaging( 500, $error );
        }

        EasyWebService::internalDebug( "The web-service entry $wsName() has been detected as existing and will be called..." );

        // Call the method implementing this web-service and return the resulting JSON to caller                
        $jsonProduced = $this->$wsName( $jsonReceived );

        EasyWebService::internalDebug( 'The following JSON has been returned => ' . json_encode( $jsonProduced, JSON_PRETTY_PRINT ) );

        // Do the packaging of the web-service returned data
        $answer = $this->defaultMethodAnswerPackaging( $jsonProduced );

        EasyWebService::internalDebug( 'The packaged answer that will be sent back is => ' . json_encode( $answer, JSON_PRETTY_PRINT ) );

        // Disconnect from the database if previously connected
        if ( $needDBConnection ) {
            // Disconnect from database if connected
            EasyWebService::internalDebug( 'Trying to disconnect from database...' );
            $this->databaseDisconnect();
        }

        return $answer;
    }

}

?>