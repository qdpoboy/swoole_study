<?php ?>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <meta name="description" content="">
        <meta name="keywords" content="">
    </head>
    <script>
        var wsServer = 'wss://23.105.213.196:9502';
        var websocket = new WebSocket(wsServer);
        websocket.onopen = function (evt) {
            console.log("Connected to WebSocket server.");
        };

        websocket.onclose = function (evt) {
            console.log("Disconnected");
        };

        websocket.onmessage = function (evt) {
            console.log('Retrieved data from server: ' + evt.data);
        };

        websocket.onerror = function (evt, e) {
            console.log('Error occured: ' + evt.data);
        };
    </script>
    <body>

    </body>
</html>
