<?php

/**
 * websocket server
 * @author wxj
 */
define('DEBUG', 'on');
//开启服务器模式，而非web请求模式
define('SWOOLE_SERVER', true);
define("WEBPATH", str_replace("\\", "/", __DIR__));
require __DIR__ . '/libs/lib_config.php';

class wwebsocket {

    const PORT = 9502;

    private $ws;
    private $actions;

    public function __construct() {
        $this->init();
    }

    public function init() {
        $this->actions = [
            'fight' => ['run'],
        ];
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
        Swoole::$php->router(array($this, 'router'), $ws, $frame);
        Swoole::$php->runMVC();
        //$response = Swoole::$php->runMVC();
        //$this->ws->push($frame->fd, $response);
    }

    public function router($ws, $frame) {
        $get_data = json_decode($frame->data, true);
        $controller = $this->actions[$get_data['c']];
        if ($controller) {
            $mvc['controller'] = $get_data['c'];
            if (in_array($get_data['v'], $controller)) {
                $mvc['view'] = $get_data['v'];
            }
        }
        $mvc['param'] = [
            'ws' => $ws,
            'frame' => $frame,
            'data' => $get_data['data'],
        ];
        return $mvc;
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
