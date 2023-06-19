<?php

require_once( __DIR__ . '/../../../lib/EasyWebService.php' );
require_once( __DIR__ . '/../../../lib/EasySQLSelect.php' );
require_once( __DIR__ . '/../../../lib/EasySQLInsert.php' );
require_once( __DIR__ . '/../../../lib/EasySQLUpdate.php' );
require_once( __DIR__ . '/../../../lib/EasySQLDelete.php' );

$myWebService = new class extends EasyWebService {

    public function defaultDatabaseConnectionSettings() : array {
        return array(
            'host'     => 'localhost',
            'dbname'   => 'your-database-name-here',
            'username' => 'your-database-user-name-here',
            'password' => 'your-database-user-password-here'
        );
    }

    public function post( array $input ) {

        $query = "INSERT INTO t_demo ( msg_date, msg_content ) VALUES ( NOW(), '###1###' )";

        return EasySQLInsert::execute(
            array(
                'request' => array(
                    'connect'            => $this->getDBConnection(),
                    'sql'                => $query,
                    'sql-is-using-parms' => TRUE,
                    'parms'              => array(
                        array(
                            'to-find'     => '###1###',
                            'replace-by'  => $input[ 'msg_content' ],
                            'must-escape' => TRUE,
                            'must-trim'   => TRUE
                        )
                    )
                )
            )
        );
    }

    public function get( array $input ) {

        $query = "
            SELECT 
                id,
                DATE_FORMAT( msg_date, '%Y-%m-%d %H:%i:%s' ) as msg_date,
                msg_content
            FROM
                t_demo
            ORDER BY
                msg_date DESC";

        return EasySQLSelect::execute(
            array(
                'request' => array(
                    'connect'            => $this->getDBConnection(),
                    'sql'                => $query,
                    'sql-is-using-parms' => FALSE
                ),
                'results' => array(
                    'wanted-columns' => array(
                        'id'          => 'integer',
                        'msg_date'    => 'string',
                        'msg_content' => 'string'
                    )
                )
            )
        );
    }

    public function put( array $input ) {

        $query = "UPDATE t_demo SET msg_content = '###2###' WHERE id = ###1###";

        return EasySQLUpdate::execute(
            array(
                'request' => array(
                    'connect'            => $this->getDBConnection(),
                    'sql'                => $query,
                    'sql-is-using-parms' => TRUE,
                    'parms'              => array(
                        array(
                            'to-find'     => '###1###',
                            'replace-by'  => $input[ 'id' ],
                            'must-escape' => TRUE,
                            'must-trim'   => FALSE,
                        ),
                        array(
                            'to-find'     => '###2###',
                            'replace-by'  => $input[ 'msg_content' ],
                            'must-escape' => TRUE,
                            'must-trim'   => TRUE,
                        )
                    )
                )
            )
        );
    }

    public function delete( array $input ) {

        $query = "DELETE FROM t_demo WHERE id = ###1###";

        return EasySQLUpdate::execute(
            array(
                'request' => array(
                    'connect'            => $this->getDBConnection(),
                    'sql'                => $query,
                    'sql-is-using-parms' => TRUE,
                    'parms'              => array(
                        array(
                            'to-find'     => '###1###',
                            'replace-by'  => $input[ 'id' ],
                            'must-escape' => TRUE,
                            'must-trim'   => FALSE,
                        )
                    )
                )
            )
        );
    }
};

$myWebService->execute();

?>