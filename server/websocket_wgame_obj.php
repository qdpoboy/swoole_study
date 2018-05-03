<?php

/**
 * websocket server
 * @author wxj
 */
define('DEBUG', 'on');
define("WEBPATH", str_replace("\\", "/", __DIR__));
require __DIR__ . '/libs/lib_config.php';

class wwebsocket {

    const PORT = 9502;

    private $ws;

    public function __construct() {
        $this->init();
    }

    public function init() {
        $this->ws = new swoole_websocket_server("0.0.0.0", self::PORT);
        $this->ws->set([
            'task_worker_num' => 4
        ]);
        $this->ws->on('open', [$this, 'open']);
        $this->ws->on('message', [$this, 'message']);
        $this->ws->on('close', [$this, 'close']);
        $this->ws->on('task', [$this, 'task']);
        $this->ws->on('finish', [$this, 'finish']);
        $this->ws->start();
    }

    public function open($ws, $request) {
        echo "client-{$request->fd} is open\n";
        //var_dump($request);
    }

    public function message($ws, $frame) {
        $get_data = json_decode($frame->data, true);
//        $model = $get_data['m'];
//        $control = $get_data['c'];
//        include_once '../wgame/' . $model . '.php';
//        $obj = new $model();
//        $obj->$control($ws, $frame);
        Swoole::$php->router(array('page', 'index'));
        $response = Swoole::$php->runMVC();
        $this->ws->push($frame->fd, $response);
    }

    public function close($ws, $fd) {
        echo "client-{$fd} is closed\n";
    }

    public function task() {
        
    }

    public function finish() {
        
    }

}

new wwebsocket();
