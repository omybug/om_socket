<?php
/**
 * User: omybug
 * Date: 15-10-12 15:55
 */

namespace service;

use core\BaseController;
use core\Redis;
use core\UserManager;
use core\Zone;
use dao\LoginDao;
use dao\UserDao;

class UserService extends Service{

    /**
     * @var UserManager
     */
    private $userManager;

    function __construct(BaseController $controller = null){
        parent::__construct($controller);
        $this->userManager = UserManager::instance();
    }

    /**
     * @param $account
     * @param $password
     * @param $fd
     * @return bool|int -2:账户不存在，-1：密码错误
     */
    public function login($account, $password, $fd){
        $loginDao = new LoginDao();
        $result = $loginDao->find($account);
        if(!$result){
            return -2;
        }
        if($result['password'] == md5($password)){
            $uid = $result['id'];
            if($this->userManager->isOnline($uid)){
                $oldFd = $this->logout($uid);
                $this->offline($oldFd);
                //多地同时登录处理
                if($this->controller->exist($oldFd)) {
//                    $this->controller->sendToUser($oldFd);
                    $this->controller->close($oldFd);
                }
            }
            $this->userManager->bindUid($fd, $result['id']);
            unset($result['password']);
            return $result;
        }
        return -1;
    }

    /**
     * 注销
     * @param $uid
     * @return string
     */
    public function logout($uid){
        $oldFd = $this->userManager->getBindFd($uid);
        return $oldFd;
    }

    public function offline($fd){
        $uid = $this->userManager->getBindUid($fd);
        $this->userManager->unBind($fd);
        $zone = new Zone();
        $rooms = $zone->getRooms();
        foreach($rooms as $room){
            $room->leave($uid);
        }
    }

    /**
     * @param $account
     * @param $password
     * @return int|mixed -1:账户已存在
     */
    public function register($account, $password){
        $loginDao = new LoginDao();
        $result = $loginDao->find($account);
        if($result){
            return -1;
        }
        return $loginDao->create($account, md5($password));
    }

    /**
     * @param $uid
     * @param $name
     * @return mixed
     */
    public function createRole($uid, $name){
        $duser = new UserDao();
        if(empty($duser->findByName($name))){
            return $duser->create($uid, $name);
        }else{
            return false;
        }
    }
}