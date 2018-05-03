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

    public function vinit($param) {
        $this->frame = $param['frame'];
        $this->ws = $param['ws'];
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
        $this->ws->push($this->frame->fd, '张三进入到地图1');
        usleep(500000);
        $this->ws->push($this->frame->fd, '张三遇到了 小狐狸1');
        usleep(500000);
        $this->ws->push($this->frame->fd, '张三遇到了 小狐狸2');
        usleep(500000);
        $this->ws->push($this->frame->fd, '张三遇到了 小狐狸3');
        usleep(500000);
        $this->ws->push($this->frame->fd, '张三遇到了 小狐狸4');
        usleep(500000);
        $this->ws->push($this->frame->fd, '张三遇到了 小狐狸5');
        return 1;
    }

    public function map2() {
        $this->ws->push($this->frame->fd, '张三进入到地图2');
        usleep(500000);
        $this->ws->push($this->frame->fd, '张三遇到了 大狐狸2');
        usleep(500000);
        $this->ws->push($this->frame->fd, '张三遇到了 大狐狸3');
        usleep(500000);
        $this->ws->push($this->frame->fd, '张三遇到了 大狐狸4');
        usleep(500000);
        $this->ws->push($this->frame->fd, '张三遇到了 大狐狸5');
        return 1;
    }

}
