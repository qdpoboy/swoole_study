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
    private $monsterinfo;
    private $goodsinfo;

    public function __construct($swoole) {
        parent::__construct($swoole);
    }

    private function vinit($param) {
        $this->frame = $param['frame'];
        $this->ws = $param['ws'];
        $data = $param['data'];
        $this->init_user();
        if ($data['map']) {
            $this->init_map($data['map']);
        } else {
            $this->init_map();
        }
        $this->init_monster();
        $this->init_goods();
    }

    private function init_user() {
        $result = $this->db->query("select * from w_user where id = 1");
        $user_arr = $result->fetch();
        $this->userinfo = $user_arr;
    }

    private function init_map($map_id = 0) {
        if ($map_id) {
            $result = $this->db->query("select * from w_map where id = " . $map_id);
            $map_arr = $result->fetch();
            $this->mapinfo = $map_arr;
        } else {
            $result = $this->db->query("select * from w_map where level_l <= " . $this->userinfo['level'] . " and level_h >= " . $this->userinfo['level']);
            $maps_arr = $result->fetchall();
            $this->mapinfo = $maps_arr[array_rand($maps_arr)];
            $this->send($this->userinfo['nickname'] . ' 进入到 ' . $this->mapinfo['name']);
        }
    }

    private function init_monster() {
        $result = $this->db->query("select * from w_monster where map_id = " . $this->mapinfo['id']);
        $monsters_arr = $result->fetchall();
        $this->monsterinfo = $monsters_arr[array_rand($monsters_arr)];
        $this->send($this->userinfo['nickname'] . ' 遇到了 ' . $this->monsterinfo['name']);
    }
    
    private function init_goods() {
        $result = $this->db->query("select * from w_goods where mon_id = " . $this->monsterinfo['id']);
        $goods_arr = $result->fetchall();
        $this->goodsinfo = $goods_arr[array_rand($goods_arr)];
        $this->send($this->userinfo['nickname'] . ' 获得了 ' . $this->goodsinfo['name']);
        $end_msg = [
            'type' => 'end',
            'map' => $this->mapinfo['id'],
            'msg' => '结束一轮战斗',
        ];
        $this->send($end_msg);
        return 1;
    }

    private function send($msg, $mtime = 500000) {
        if ($this->ws->exist($this->frame->fd)) {
            if (is_array($msg)) {
                $msg_obj = json_encode($msg);
            } else {
                $msg_arr['type'] = 'text';
                $msg_arr['msg'] = $msg;
                $msg_obj = json_encode($msg_arr);
            }
            $this->ws->push($this->frame->fd, $msg_obj);
            usleep($mtime);
            //$closeFdArr = $this->ws->heartbeat();
            //var_dump($closeFdArr);
        } else {
            return 1;
        }
    }

    public function run($param) {
        if ($param && !$this->frame && !$this->ws) {
            $this->vinit($param);
        }
    }

    private function do_fight() {
        $m_hp = $this->monsterinfo['hp'];
        $u_hp = $this->userinfo['hp'];
        if ($u_hp > 0) {
            $this->send($this->userinfo['nickname'] . ' 被 ' . $this->monsterinfo['name'] . '打败了');
        } else {
            $this->send($this->userinfo['nickname'] . ' 被 ' . $this->monsterinfo['name'] . '打败了');
            return 1;
        }
    }

}
