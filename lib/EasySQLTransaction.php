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
 * Class to make it easy to use SQL transactions, and then commit or rollback them.
 */
class EasySQLTransaction {

    private $connect;
    private $inTransaction;

    /**
     * Class constructor.
     * 
     * @param type $connect the database connection
     */
    public function __construct( $connect ) {
        $this->connect       = $connect;
        $this->inTransaction = FALSE;
    }

    /**
     * Class destructor.
     * 
     * @throws \ErrorException no need to catch them, they are produced for the main try/catch in ws.php
     */
    public function __destruct() {
        if ( $this->inTransaction ) {
            // That should never happen !
            // Even if we probably already are outside of ws.php, still produce an Exception to signal this bad news to developer
            throw new \ErrorException( "Transaction has not been correctly closed !", 666 /* very very bad error :-) */, 0, __FILE__, __LINE__ );
        }
    }

    /**
     * To start a new transaction on this database connection. Works only if we are not already in a transaction.
     */
    public function startTransaction() {
        if ( $this->inTransaction === FALSE ) {
            // Set autocommit to off (and hence start a new transaction)
            mysqli_autocommit( $this->connect, FALSE );
            $this->inTransaction = TRUE;
        } else {
            // That should never happen ! It is probably a BUG in the project code using it. Produce an Exception to signal this bad news to the developers.
            $this->inTransaction = FALSE;   // To avoid the destructor to trigger and supersede this exception
            throw new \ErrorException( "Cannot start a transaction within a transaction !", 666 /* very very bad error :-) */, 0, __FILE__, __LINE__ );            
        }
    }

    /**
     * To commit the current transaction if any.
     */
    public function endTransactionWithCommit() {
        self::endTransaction( TRUE );
    }

    /**
     * To rollback the current transaction if any.
     */
    public function endTransactionWithRollback() {
        self::endTransaction( FALSE );
    }

    /**
     * Internal method used to commit or rollback the current transaction if any.
     * 
     * @param boolean $shouldWeCommit indicating if COMMIT is wanted (ROLLBACK otherwise)
     */
    protected function endTransaction( bool $shouldWeCommit ) {
        if ( $this->inTransaction === TRUE ) {
            // Now check that everything went OK to know what to do with the transaction
            if ( $shouldWeCommit === TRUE ) {
                // Commit the transaction		
                mysqli_commit( $this->connect );
            } else {
                // Rollback the transaction
                mysqli_rollback( $this->connect );
            }

            // Set autocommit to on again
            mysqli_autocommit( $this->connect, TRUE );
            $this->inTransaction = FALSE;
        } else {
            // That should never happen ! It is probably a BUG in the project code using it. Produce an Exception to signal this bad news to the developers.
            $this->inTransaction = FALSE;   // To avoid the destructor to trigger and supersede this exception
            throw new \ErrorException( "Cannot end the current transaction as there is none !", 666 /* very very bad error :-) */, 0, __FILE__, __LINE__ );            
        }
    }
}
