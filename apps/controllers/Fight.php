<?php

namespace App\Controller;

//use Hoa\Core\Exception\Exception;
use Swoole;

//use App;

class Fight extends Swoole\Controller {

    private $frame;
    private $ws;

    public function __construct($swoole) {
        parent::__construct($swoole);
    }

    private function vinit($param) {
        $this->frame = $param['frame'];
        $this->ws = $param['ws'];
    }

    private function send($msg, $mtime = 500000) {
        //var_dump($this->ws->exist($this->frame->fd));
        foreach ($this->ws->connections as $clid => $info) {
            var_dump($clid);
            var_dump($info);
        }
        if ($this->ws->exist($this->frame->fd)) {
            $this->ws->push($this->frame->fd, $msg);
            //usleep($mtime);
        } else {
            return 1;
        }
    }

    public function run($param) {
        if ($param && !$this->frame && !$this->ws) {
            $this->vinit($param);
        }
        $m1 = $this->map1();
        if ($m1) {
            $m2 = $this->map2();
            if ($m2) {
                $this->run([]);
            }
        }
    }

    public function map1() {
        $this->send('张三进入到地图1');
        $this->send('张三遇到了 小狐狸1');
        $this->send('张三遇到了 小狐狸2');
        $this->send('张三遇到了 小狐狸3');
        $this->send('张三遇到了 小狐狸4');
        $this->send('张三遇到了 小狐狸5');
        $this->send('张三遇到了 小狐狸6');
        return 1;
    }

    public function map2() {
        $this->send('张三进入到地图2');
        $this->send('张三遇到了 大狐狸1');
        $this->send('张三遇到了 大狐狸2');
        $this->send('张三遇到了 大狐狸3');
        $this->send('张三遇到了 大狐狸4');
        $this->send('张三遇到了 大狐狸5');
        $this->send('张三遇到了 大狐狸6');
        $this->send('张三遇到了 大狐狸7');
        return 1;
    }

}
