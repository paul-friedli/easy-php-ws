<?php

////////////////////////////////////////////////////////////////////////////////
//                _____                  ____   ___  _                        //
//               | ____|__ _ ___ _   _  / ___| / _ \| |                       //
//               |  _| / _` / __| | | | \___ \| | | | |                       //
//               | |__| (_| \__ \ |_| |  ___) | |_| | |___                    //
//               |_____\__,_|___/\__, | |____/ \__\_\_____|                   //
//                      _        |___/  _   _ ____                            //
//                     (_)_ __   |  _ \| | | |  _ \                           //
//                     | | '_ \  | |_) | |_| | |_) |                          //
//                     | | | | | |  __/|  _  |  __/                           //
//                     |_|_| |_| |_|   |_| |_|_|                              //
//                                                                            //
//                        written by Paul Friedli                             //
//                                                                            //
// -------------------------------------------------------------------------- //
//                                Part of                                     //
//      Easy and fast mini-framework for creating RESTfull web-services.      //
//                                                                            //
// ========================================================================== //
// HISTORY :                                                                  //
// ---------                                                                  //
// v1.0 / 12.04.2020                                                          //
// Initial implementation handling only SELECT requests.                      //
//                                                                            //
// v1.01 / 13.04.2020                                                         //
// Bug fixes.                                                                 //
//                                                                            //
// v1.02 / 17.04.2020                                                         //
// Another bug fix.                                                           //
//                                                                            //
// v1.03 / 22.04.2020                                                         //
// Added support for all 4 SELECT/INSERT/UPDATE/DELETE types of SQL requests  //
// through specific classes very easy to use.                                 //
//                                                                            //
// v1.04 / 24.04.2020                                                         //
// Added support for transactions.                                            //
//                                                                            //
////////////////////////////////////////////////////////////////////////////////

require_once( __DIR__ . '/EasySQLBase.php' );

/**
 * Class making it very easy to do "UPDATE" queries.
 */
class EasySQLUpdate extends EasySQLBase
{

    /**
     * Class constructor.
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * Class destructor.
     */
    public function __destruct() {
        parent::__destruct();
    }


    /**
     * "UPDATE" query execution the easy way :-).
     * 
     *     /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
     *     // Typical usage
     *     /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
     *     //    
     *     //    // Do the update query the easy way :-)
     *     //    $query = "UPDATE t_demo SET blabla = '###2###' WHERE id = ###1###";
     *     //    $jsonAnswer = SQLUpdate::execute( array(
     *     //                'request' => array(
     *     //                    'connect'            => $connect,                          // Required. The connection to the database that must be used.
     *     //                    'sql'                => $query,                            // Required. The SQL request to execute.
     *     //                    'sql-is-using-parms' => TRUE,                              // Required. Yes, we are using wildcards.
     *     //                    'parms'              => array(                             // Required only if using parms. An array of arrays, each one giving all details about the wildcard and remplacement to be done
     *     //                        array(
     *     //                            'to-find'     => '###1###',                        // What must be searched in the query
     *     //                            'replace-by'  => $jsonReceived[ 'id' ],            // Replacement value
     *     //                            'must-escape' => TRUE,                             // Should the replacement value be escaped to avoid SQL injection ?
     *     //                            'must-trim'   => FALSE ),                          // Should the replacement value be trimmed before being used ?
     *     //                        ),
     *     //                        array(
     *     //                            'to-find'     => '###2###',                        // What must be searched in the query
     *     //                            'replace-by'  => $jsonReceived[ 'new-blabla' ],    // Replacement value
     *     //                            'must-escape' => TRUE,                             // Should the replacement value be escaped to avoid SQL injection ?
     *     //                            'must-trim'   => TRUE ),                           // Should the replacement value be trimmed before being used ?
     *     //                        )
     *     //                    )
     *     //                )
     *     //        ) ) );
     *     //    
     *     //    
     *     //    // In case the UPDATE request execution succeeded
     *     //    return array(
     *     //        'succeeded'           => true,
     *     //        'affected-rows-count' => (int) the number of touched records,
     *     //        'error'               => null
     *     //    );
     *     //    
     *     //    // In case the UPDATE request execution failed
     *     //    return array(
     *     //        'succeeded'           => false,
     *     //        'affected-rows-count' => 0,
     *     //        'error'               => array(
     *     //            'sql-request'  => (string)The SQL request that failed
     *     //            'error-msg'    => (string)The error message from MySQL
     *     //            'error-code'   => (int)The numerical error code from MySQL
     *     //        )
     *     //    );
     *     //    
     *     /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
     * 
     * @param array $execParms all the SQL request parameters packaged within an associative array. Please look at example above for the details.
     * @return array|null the json answer containing the execution status and the extra information desired
     * @throws \ErrorException no need to catch them, they are produced for the main try/catch in ws.php
     */






    public static function execute( $execParms ) : array|null {

        $jsonAnswer = NULL;

        // Verify that all needed parameters are provided or produce an Exception !
        if (
            is_null( $execParms ) ||
            !is_array( $execParms ) ||
            !array_key_exists( 'request', $execParms ) ||
            !array_key_exists( 'connect', $execParms[ 'request' ] ) ||
            !array_key_exists( 'sql', $execParms[ 'request' ] ) ||
            !array_key_exists( 'sql-is-using-parms', $execParms[ 'request' ] ) ||
            ( ( $execParms[ 'request' ][ 'sql-is-using-parms' ] === TRUE ) && ( !array_key_exists( 'parms', $execParms[ 'request' ] ) || !is_array( $execParms[ 'request' ][ 'parms' ] ) ) ) ||
            ( array_key_exists( 'results', $execParms ) && !is_array( $execParms[ 'results' ] ) )
        ) {
            // Produce an Exception to signal this bad news to developer
            throw new \ErrorException( "Invalid parameters for the SQLInsert request !", 666 /* very very bad error :-) */, 0, __FILE__, __LINE__ );
        }

        // Do we have to replace wildcards with some 'parms' values in the SQL request ?
        $sqlRequest = $execParms[ 'request' ][ 'sql' ];
        if ( $execParms[ 'request' ][ 'sql-is-using-parms' ] === TRUE ) {
            // Replace all wildcards and avoid finding them within the remplacement data
            $sqlRequest = self::replaceWildcardsWithDesiredValues( $execParms[ 'request' ][ 'connect' ], $sqlRequest, $execParms[ 'request' ][ 'parms' ] );
        }

        // Execute SQL query
        if ( ( $result = mysqli_query( $execParms[ 'request' ][ 'connect' ], $sqlRequest ) ) ) {

            // Get number of touched records
            $affectedRowsCount = mysqli_affected_rows( $execParms[ 'request' ][ 'connect' ] );

            // Produce final result
            $jsonAnswer = array(
                'succeeded'           => true,
                'affected-rows-count' => $affectedRowsCount,
                'error'               => null
            );

        } else {
            // Houston we have a problem...
            // Most probably a 'duplicate keys' error that can easily happen and must be handled.
            // Typically it happens when there is a unique index that forbids that new value with the SQL UPDATE.

            $errorCode    = mysqli_errno( $execParms[ 'request' ][ 'connect' ] );
            $errorMessage = mysqli_error( $execParms[ 'request' ][ 'connect' ] );

            // Produce final result
            $jsonAnswer = array(
                'succeeded'           => false,
                'affected-rows-count' => 0,
                'error'               => array(
                    'sql-request'  => $sqlRequest,
                    'error-msg'  => $errorMessage,
                    'error-code' => $errorCode
                )
            );
        }

        return $jsonAnswer;
    }

}