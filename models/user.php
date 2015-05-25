<?php
class User extends \Phalcon\Mvc\Model{

public function userAdd ()
    {
        if ( isset( $_POST[ 'login' ] ) && isset( $_POST[ 'password' ] ) ) {
            $login = $_POST[ 'login' ];
            $password = md5( md5( trim( $_POST[ 'password' ] ) ) );
            $role_id = $_POST[ 'role' ];
            $name = $_POST[ 'name' ];
            $email = $_POST[ 'email' ];
        }
        $query = <<<SQL
                INSERT INTO
                xyz_employee
                SET employee_login = :employee_login,
                employee_password = :employee_password,
                role_id = :role_id,
                employee_name = :employee_name,
                employee_email = :employee_email;
SQL;
        $this->dbh->insertRow( $query, array(
            'employee_login' => $login,
            'employee_password' => $password,
            'employee_name' => $name,
            'employee_email' => $email,
            'role_id' => $role_id ) );
        $lastInsId = $this->dbh->lastInsertedId();
        $this->dbh = null;
        if ( null !== $lastInsId ) {
            return true;
        }
        return false;
    }
    }
