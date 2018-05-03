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
    private $frame;
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
            'task_worker_num' => 1
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
        $this->frame = $frame;
        print_r($this->ws->connections);
        print_r($ws->connections);
        //Swoole::$php->router(array($this, 'router'));
        //Swoole::$php->runMVC();
        //$response = Swoole::$php->runMVC();
        //$this->ws->push($frame->fd, $response);
    }

    public function router() {
        $get_data = json_decode($this->frame->data, true);
        $controller = $this->actions[$get_data['c']];
        if ($controller) {
            $mvc['controller'] = $get_data['c'];
            if (in_array($get_data['v'], $controller)) {
                $mvc['view'] = $get_data['v'];
            }
        }
        $mvc['param'] = [
            'ws' => $this->ws,
            'frame' => $this->frame,
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
