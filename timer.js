var $cp         = require("child_process");
var period_time = 1000;
var command     = process.argv[2];

console.log("Timer Started !");

setInterval(function(){

    try{

        $cp.exec(command, function(err, stdout){

            if(err)
            {
                console.log("Timer Error : " + err);
            }
            else
            {
                console.log(stdout);
            }
        });

    }catch(err){
        console.log(err);
    }

}, period_time);