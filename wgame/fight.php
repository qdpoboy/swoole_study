<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
function run($ws){
    $m1 = map1($ws);
    if($m1){
        $m2 = map2($ws);
        if($m2){
            return 1;
        }
    }
}
function map1($ws){
    $ws->push('张三进入到地图1');
    $ws->push('张三遇到了 小狐狸1');
    $ws->push('张三遇到了 小狐狸2');
    $ws->push('张三遇到了 小狐狸3');
    $ws->push('张三遇到了 小狐狸4');
    $ws->push('张三遇到了 小狐狸5');
    return 1;
}

function map2($ws){
    $ws->push('张三进入到地图2');
    $ws->push('张三遇到了 大狐狸2');
    $ws->push('张三遇到了 大狐狸3');
    $ws->push('张三遇到了 大狐狸4');
    $ws->push('张三遇到了 大狐狸5');
    return 1;
}

