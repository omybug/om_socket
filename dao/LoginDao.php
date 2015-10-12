<?php
/**
 * User: omybug
 * Date: 2015/10/12
 * Time: 15:59
 */

class LoginDao extends \core\Dao{
    public function find($account){
        return  $this->db->row(
            'SELECT * FROM login WHERE account = :account',
            array("account"=>$account)
        );
    }

    public function create($account, $password){
        return $this->db->insert(
            'INSERT INTO login(account,password) VALUES ($account,$password)',
            array('account'=>$account, 'password'=>$password)
        );
    }
}