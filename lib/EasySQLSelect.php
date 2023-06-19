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
 * Class making it very easy to do "SELECT" queries.
 */
class EasySQLSelect extends EasySQLBase
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
     * "SELECT" query execution the easy way :-).
     * 
     *     /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
     *     // Typical usage N°1 - SQL to be executed directly, no use of wildcards
     *     /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
     *     //    
     *     //    $query = "SELECT id, DATE_FORMAT( quand, '%Y-%m-%d %H:%i:%s' ) as quand, blabla, TRUE AS isAdmin FROM t_demo ORDER BY quand DESC";
     *     //    
     *     //    $jsonAnswer = SQLSelect::execute( array(
     *     //                'request' => array(
     *     //                    'connect'            => $connect,                         // Required. The connection to the database that must be used.
     *     //                    'sql'                => $query,                           // Required. The SQL request to execute.
     *     //                    'sql-is-using-parms' => FALSE,                            // Required. Indicate if 'sql' request is using wildcards that must be replaced with some 'parms' values.
     *     //                    'parms'              => NULL,                             // Optional if 'sql-is-using-parms' is FALSE.
     *     //                ),
     *     //                'results' => array(
     *     //                    'wanted-columns'    => array(                             // Required. The expected data in the result set. Names must match a returned column name from SQL request. Type MUST be either 'string' or 'integer' or 'boolean'
     *     //                        'id' => 'integer',
     *     //                        'quand' => 'string',
     *     //                        'blabla' => 'string',
     *     //                        'isAdmin' => 'boolean'
     *     //                    )
     *     //                ) )
     *     //    );
     *     //    
     *     /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
     *     // Typical usage N°2 - SQL needing some wildcards replacements
     *     /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
     *     //    
     *     //    $query = "SELECT id, DATE_FORMAT( quand, '%Y-%m-%d %H:%i:%s' ) as quand, blabla, FALSE AS isAdmin FROM t_demo WHERE ( blabla LIKE '%###1###%' ) OR ( blabla LIKE '%###2###%' ) ORDER BY quand DESC";
     *     //    
     *     //    $jsonAnswer = SQLSelect::execute( array(
     *     //                'request' => array(
     *     //                    'connect'            => $connect,                         // Required. The connection to the database that must be used.
     *     //                    'sql'                => $query,                           // Required. The SQL request to execute.
     *     //                    'sql-is-using-parms' => TRUE,                             // Required. Yes, we are using wildcards.
     *     //                    'parms'              => array(                            // An array of arrays, each one giving all details about the wildcard and remplacement to be done
     *     //                        array(
     *     //                            'to-find'     => '###1###',                       // What must be searched in the query
     *     //                            'replace-by'  => 'comment',                       // Replacement value
     *     //                            'must-escape' => TRUE,                            // Should the replacement value be escaped to avoid SQL injection ?
     *     //                            'must-trim'   => TRUE ),                          // Should the replacement value be trimmed before being used ?
     *     //                        array(
     *     //                            'to-find'     => '###2###',                       // What must be searched in the query
     *     //                            'replace-by'  => 'ça',                            // Replacement value
     *     //                            'must-escape' => TRUE,                            // Should the replacement value be escaped to avoid SQL injection ?
     *     //                            'must-trim'   => TRUE )                           // Should the replacement value be trimmed before being used ?
     *     //                    ),
     *     //                ),
     *     //                'results' => array(
     *     //                    'wanted-columns'    => array(                             // Required. The expected data in the result set. Names must match a returned column name from SQL request. Type MUST be either 'string' or 'integer' or 'boolean'
     *     //                        'id' => 'integer',
     *     //                        'quand' => 'string',
     *     //                        'blabla' => 'string',
     *     //                        'isAdmin' => 'boolean'
     *     //                    )
     *     //                ) )
     *     //    );    
     *     //    
     *     /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
     * 
     * @param array $execParms all the SQL request parameters packaged within an associative array. Please look at examples above for the details.
     * @return array|null the desired result set
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
            !array_key_exists( 'results', $execParms ) ||
            !array_key_exists( 'wanted-columns', $execParms[ 'results' ] )
        ) {
            // Produce an Exception to signal this bad news to developer
            throw new \ErrorException( "Invalid parameters for the SQLSelect request !", 666 /* very very bad error :-) */, 0, __FILE__, __LINE__ );
        }

        // Do we have to replace wildcards with some 'parms' values in the SQL request ?
        $sqlRequest = $execParms[ 'request' ][ 'sql' ];
        if ( $execParms[ 'request' ][ 'sql-is-using-parms' ] === TRUE ) {
            // Replace all wildcards and avoid finding them within the remplacement data
            $sqlRequest = self::replaceWildcardsWithDesiredValues( $execParms[ 'request' ][ 'connect' ], $sqlRequest, $execParms[ 'request' ][ 'parms' ] );
        }

        // Execute SQL query
        if ( ( $result = mysqli_query( $execParms[ 'request' ][ 'connect' ], $sqlRequest ) ) ) {

            // Read all data returned
            $returnedData = array();
            while ( $row = mysqli_fetch_array( $result, MYSQLI_ASSOC ) ) {
                // Store in returned data all the desired fields that have been asked
                $rowData = array();
                foreach ( $execParms[ 'results' ][ 'wanted-columns' ] as $columnName => $columnType ) {
                    // Make sure we use the right JSON type to store the data
                    if ( $columnType === 'boolean' ) {
                        $rowData[ $columnName ] = ( intval( $row[ $columnName ] ) != 0 ) ? TRUE : FALSE;
                    } else if ( $columnType === 'integer' ) {
                        $rowData[ $columnName ] = intval( $row[ $columnName ] );
                    } else if ( $columnType === 'double' ) {
                        $rowData[ $columnName ] = doubleval( $row[ $columnName ] );
                    } else if ( $columnType === 'string' ) {
                        $rowData[ $columnName ] = $row[ $columnName ];
                    }
                }
                $returnedData[] = $rowData;
            }

            // Everything is fine => produce final desired JSON
            $jsonAnswer = $returnedData;
        } else {
            // Houston we have a problem...
            // Produce an Exception to signal this bad news to developer
            throw new \ErrorException( "SQL query error. The query ($sqlRequest) produced the SQL engine error (" . mysqli_error( $execParms[ 'request' ][ 'connect' ] ) . ')', 666 /* very very bad error :-) */, 0, __FILE__, __LINE__ );
        }

        return $jsonAnswer;
    }

}