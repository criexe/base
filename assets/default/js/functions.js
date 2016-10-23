function set_store($this, key, value)
{
    if(value != "")
    {
        if(typeof(Storage) !== "undefined" & $this.value != "")
        {
            localStorage.setItem(key, $this.value);
        }
    }
    else
    {
        localStorage.setItem(key, value);
    }
}

function get_store($this, key)
{
    if(typeof(Storage) !== "undefined")
    {
        $this.value = localStorage.getItem(key);
    }
}

function include_js(file)
{
    var script  = document.createElement('script');
    script.src  = file;
    script.type = "text/javascript";

    document.body.appendChild(script);
}

$.fn.increase = function(count)
{
    count = typeof count !== 'undefined' ? count : 1;

    var num = parseInt(this.text());

    if(Number.isInteger(num))
    {
        this.text(num + 1);
    }
}

$.fn.decrease = function(count)
{
    count = typeof count !== 'undefined' ? count : 1;

    var num = parseInt(this.text());

    if(Number.isInteger(num))
    {
        this.text(num - 1);
    }
}


function set_ckeditor($name, $config)
{
    var $config = typeof $config !== 'undefined' ?  $config : {};
    var editor = CKEDITOR.replace($name, $config);

    editor.on('change', function( evt ) {
        $("[name='" + $name + "']").val(evt.editor.getData());
    });
}