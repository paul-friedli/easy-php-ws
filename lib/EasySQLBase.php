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

/**
 * Base class for SQL classes. Cannot be used directly (abstract).
 */
abstract class EasySQLBase {

    /**
     * Class constructor.
     */
    public function __construct() {
        
    }

    /**
     * Class destructor.
     */
    public function __destruct() {
        
    }

    /**
     * Used internally to process SQL strings that are using wildcards.
     * It will search and replace in $str all wildcards defined in $parms and allow for extra processing like string 
     * escapement (to avoid SQL injection) and trimming (to avoid leading and ending spaces).
     * This method does the necessary to avoid the potential collision that could happen when wildcard text is found
     * within replacement values. It is therefore allowed to have replacement values containing the used wildcards as 
     * this algorithm is insensitive to that and will not replace them twice.
     * 
     * @param mixed $connect database connection, used only if the replacement value has to be escaped to avoid SQL injection
     * @param string $str the string to be searched for wildcards in $parms
     * @param array $parms an array of wildcards, each one defining what should be searched, the replacement value, the desired extra processing needed
     * @return string a string based on the original string with all wildcards replaced
     */
    protected static function replaceWildcardsWithDesiredValues( mixed $connect, string $str, array $parms ) : string {

        // Did we receive all needed parameters to do replacement ?
        if ( is_null( $connect ) || is_null( $str ) || is_null( $parms ) || !is_array( $parms ) || ( count( $parms ) < 1 ) ) {
            return $str;
        }

        // Extract the last needed replacement
        $parm = array_pop( $parms );

        // Check if valid replacement parameters
        if ( is_null( $parm ) || !is_array( $parm ) ) {
            // Continue processing with remaining parameters
            return self::replaceWildcardsWithDesiredValues( $connect, $str, $parms );
        }

        // Do the asked replacement
        $find       = $parm[ 'to-find' ];
        $replace    = $parm[ 'replace-by' ];
        $mustEscape = $parm[ 'must-escape' ];
        $mustTrim   = $parm[ 'must-trim' ];
        $parts      = explode( $find, $str );
        if ( !is_array( $parts ) || ( count( $parts ) < 2 ) ) {
            // Continue processing with remaining parameters
            return self::replaceWildcardsWithDesiredValues( $connect, $str, $parms );
        }

        // Ok. We found $find at least one time.
        // Now continue to process recursively the remaining chunks with the remaining replacement parameters to avoid 
        // the risk of finding the tag within the replacement data itself.
        foreach ( $parts as $index => $part ) {
            $parts[ $index ] = self::replaceWildcardsWithDesiredValues( $connect, $part, $parms );
        }

        // Do we need to trim ?        
        if ( $mustTrim ) {
            $replace = trim( $replace );
        }

        // Do we need to escape the replacement ?
        if ( $mustEscape ) {
            $replace = mysqli_real_escape_string( $connect, $replace );
        }

        // Now return a string containing the desired replacement
        return implode( $replace, $parts );
    }

}
