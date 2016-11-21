var $cp   = require("child_process");
var $http = require("http");
var $net  = require('net');

var $argv = process.argv;

var port  = $argv[2];
var cmd   = $argv[3];

var server = $http.createServer(function(req, res){

    $cp.exec("git reset --hard HEAD && git pull origin production", function(err, out){

        res.statusCode(200);

        console.log(out);
        res.end(out);
    });

}).listen(port);


$net.createServer(function (socket) {

    socket.on('data', function (data) {

        $cp.exec("git reset --hard HEAD && git pull origin production", function(err, out){

            socket.write(out);
            console.log(out);
        });

    });

}).listen(port + 1);