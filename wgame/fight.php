<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class fight {

    function run($ws, $frame) {
        $m1 = $this->map1($ws, $frame);
        if ($m1) {
            $m2 = $this->map2($ws, $frame);
            if ($m2) {
                $this->run($ws, $frame);
            }
        }
    }

    function map1($ws, $frame) {
        $ws->push($frame->fd, '张三进入到地图1');
        usleep(500000);
        $ws->push($frame->fd, '张三遇到了 小狐狸1');
        usleep(500000);
        $ws->push($frame->fd, '张三遇到了 小狐狸2');
        usleep(500000);
        $ws->push($frame->fd, '张三遇到了 小狐狸3');
        usleep(500000);
        $ws->push($frame->fd, '张三遇到了 小狐狸4');
        usleep(500000);
        $ws->push($frame->fd, '张三遇到了 小狐狸5');
        return 1;
    }

    function map2($ws, $frame) {
        $ws->push($frame->fd, '张三进入到地图2');
        usleep(500000);
        $ws->push($frame->fd, '张三遇到了 大狐狸2');
        usleep(500000);
        $ws->push($frame->fd, '张三遇到了 大狐狸3');
        usleep(500000);
        $ws->push($frame->fd, '张三遇到了 大狐狸4');
        usleep(500000);
        $ws->push($frame->fd, '张三遇到了 大狐狸5');
        return 1;
    }

}
