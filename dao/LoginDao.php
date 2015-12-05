<?php
/**
 * User: omybug
 * Date: 15-10-12 15:59
 */

namespace dao;

class LoginDao extends Dao{
    public function find($account){
        return  $this->db->row(
            'SELECT * FROM login WHERE account = :account',
            array("account"=>$account)
        );
    }

    public function create($account, $password){
        return $this->db->insert(
            'INSERT INTO login(account,password) VALUES (:account,:password)',
            array('account'=>$account, 'password'=>$password)
        );
    }
}