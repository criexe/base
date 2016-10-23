/**
 * Created by Mustafa Aydemir on 14.11.15.
 */


function topAlert(params)
{
    if(typeof params === 'object')
    {
        var alert_message = params.message;
        var alert_class   = params.cssClass;

        var alert = '<div class="alert ' + alert_class + '">' + alert_message + '</div>';

        var topAlert = $(".topAlert");
        var speed    = "fast";
        var delay    = 3000;

        var doneFunction = params.done;

        topAlert.append(alert);
        topAlert.slideDown(speed).delay(delay).slideUp(speed, function(){

            topAlert.html("");
            doneFunction();
        });
    }
}


function _go(url)
{
    window.location = url;
}