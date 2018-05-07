<?php

namespace App\Controller;

//use Hoa\Core\Exception\Exception;
use Swoole;

//use App;

class Fight extends Swoole\Controller {

    private $frame;
    private $ws;
    private $userinfo;
    private $mapinfo;

    public function __construct($swoole) {
        parent::__construct($swoole);
    }

    private function vinit($param) {
        $this->frame = $param['frame'];
        $this->ws = $param['ws'];
        $this->init_user();
        $this->init_map();
    }

    private function init_user() {
        $user_arr = $this->db->query("select * from w_user where id = 1");
        $this->userinfo = $user_arr[0];
    }

    private function init_map() {
        $maps_arr = $this->db->query("select * from w_map where level_l >= " . $this->userinfo['level'] . " and level_h <= " . $this->userinfo['level']);
        $this->mapinfo = $maps_arr[array_rand($maps_arr)];
        $this->send($this->userinfo['nickname'] . '进入到' . $this->mapinfo['name']);
    }

    private function send($msg, $mtime = 500000) {
        if ($this->ws->exist($this->frame->fd)) {
            $this->ws->push($this->frame->fd, $msg);
            usleep($mtime);
            $closeFdArr = $this->ws->heartbeat();
            var_dump($closeFdArr);
        } else {
            return 1;
        }
    }

    public function run($param) {
        if ($param && !$this->frame && !$this->ws) {
            $this->vinit($param);
        }
        for($i = 0;$i < 100;$i++){
            $this->do_fight();
        }
    }

    private function do_fight() {
        $monsters_arr = $this->db->query("select * from w_monster where map_id = " . $this->mapinfo['id']);
        $one_monster = $monsters_arr[array_rand($monsters_arr)];
        $this->send($this->userinfo['nickname'] . '遇到了' . $one_monster['name']);
    }

}
