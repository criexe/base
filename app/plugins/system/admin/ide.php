<?php if(!defined('CX')) exit; ?>

<style type="text/css">
    #editor-area{
        position: absolute;
        top:0;
        bottom:0;
        left:0;
        right:0;
        font-size: 12px;
    }
    .code{
        position: relative;
        width:100%;
        height:100%;
    }
    .preview{
        height:calc(100% - 65px);
        width:100%;
        border:none;
        margin:0 !important;
    }
    .btn-container{
        padding:15px;
        background: #fff;
        border-bottom:solid 1px #e0e0e0;
        width:100%;
    }
    .code-col{
        padding:0;
        height:100%;
    }
    .row{
        height:100%;
        max-width:100%;
        margin:0;
    }
</style>

<div class="row">
    <div class="col-sm-7 code-col">
        <div class="btn-container">
            <button class="btn btn-success pull-right"> <i class="fa fa-play"></i> Run</button>
            <div class="clearfix"></div>
        </div>
        <pre class="preview"></pre>
    </div>
    <div class="col-sm-5 code-col">
        <div class="code">
            <div id="editor-area"></div>
        </div>
    </div>
</div>

<script src="/assets/ace-editor/ace.js"></script>
<script>
    var editor = ace.edit("editor-area");

    editor.setTheme("ace/theme/monokai");
    editor.getSession().setMode("ace/mode/php");

    // Get from local storage
    editor.setValue( localStorage.getItem('editor_code') );

    $(".code-col").css("height", $("#admin-content").height() + "px");
</script>
<script>

        $(function(){

            $("body").on("click", "button", function(){

                var $code = editor.getValue();

                if($code != "")
                {
                    $.post("/admin/plugin_page/system/php_code_preview?layout=false", {
                            code : $code
                        })
                        .done(function(data){

                            $(".preview").html(data);
                        });


                    localStorage.setItem('editor_code', $code);
                }
            });

        });

</script>