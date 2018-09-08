<?php 
if ($_REQUEST) {
    print ("Il server ha ricevuto: ".$_POST['message']);
    exit();
}
?>
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>GP JS AJAX</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <style>
            .box-editor {height:400px; background:#333; overflow:hidden; overflow-y:auto}
            .box-response {width:100%; background:#333; color:#F2F2F2; padding:10px 10px 2px 10px; box-sizing: border-box; line-height:20px; margin-bottom: -1px; font-size:16px;}
            .boxsend {background:#333; display:flex; }
            .boxsend div {flex-grow:1}
            .flexgrow0 {flex-grow:0}
            .boxsend .gt { color: #F2F2F2; margin-left: 10px;  line-height: 20px; font-weight: bold;}
            .btn-send {color:#888; cursor:pointer; margin-right: 20px;}
            .btn-send:hover {text-decoration:underline}
            .input-msg {width:100%; background:#333; color:#F2F2F2; padding:0px 10px 5px 5px; box-sizing: border-box; border:none; outline:none; position:relative; font-size:16px;}
            .input-msg:focus {border:none; outline:none;}
        </style>
        <script>
            /** simple function to use ajax */
            function gpAjax(options) {
                this.options = options;
                if (!'url' in this.options ) {
                    console.log ("jpAjax: url is required");
                    return false;
                }
                if (!'method' in options) {
                    this.options.method = "post";
                }
            }
            gpAjax.prototype = { 
                send:function(dataToSend) {
                    var getQueryArray = [];
                    var formData = new FormData();
                    for (i in dataToSend) {
                        formData.append(i, dataToSend[i]);
                        getQueryArray.push(i+"="+dataToSend[i]);
                    }
                    var xmlHttp = new XMLHttpRequest();
                    xmlHttp.onreadystatechange = (function () {
                        if (xmlHttp.readyState == 4 || xmlHttp.readyState === XMLHttpRequest.DONE) {
                            if (xmlHttp.status == 200) {
                                if (typeof this.options.success === 'function') {
                                    this.options.success.call(this, xmlHttp.responseText);
                                }
                            } else {
                                if (typeof this.options.error === 'function') {
                                    this.options.error.call(this, xmlHttp.status);
                                }
                            }
                        }
                    }).bind(this);
                
                    if (this.options.method == "get") {
                        getQuery = getQueryArray.join('&');
                        xmlHttp.open(this.options.method, this.options.url+"?"+getQuery, true);
                        xmlHttp.send();
                    } else {
                        xmlHttp.open(this.options.method, this.options.url, true);
                        xmlHttp.send(formData);
                    }
                }
            }
        </script>
    </head>
    <body>
        <h1> Very simple ajax script </h1>
        <p>Send what you write at the server. The server response with the same data</p>
        <div class="box-editor">
            <div id="boxResponde" class="box-response"></div>
            <div class="boxsend"> 
                <div class="flexgrow0 gt">&gt;</div>
                <input id="msg" class="input-msg">
                <div id="btnSend" class="flexgrow0 btn-send">SEND</div>
            </div>
        </div>
        <script>
            /* TEST */
            var myAjax = new gpAjax({
                url:'test-jsajax.php',
                method:'post',
                success: function(response) {
                    var node = document.createElement("div");        
                    var text = document.createTextNode(response);
                    node.appendChild(text);
                    document.getElementById('boxResponde').appendChild(node);
                    document.getElementById('msg').value = "";
                    document.getElementById('msg').focus();
                },
              
                error: function(err) {
                    var node = document.createElement("div");        
                    var text = document.createTextNode("C'è stato un errore nell'invio di dati! "+err);
                    node.appendChild(text);
                    document.getElementById('boxResponde').appendChild(node);
                    document.getElementById('msg').value = "";
                    document.getElementById('msg').focus();
                }
            });
            document.getElementById('msg').addEventListener("keypress",function(e) {
                if (e.keyCode == 13) {
                    if (document.getElementById('msg').value != "") {
                        myAjax.send({message:document.getElementById('msg').value});
                    }
                    
                }
            });
            document.getElementById('btnSend').addEventListener("click", function() {
                if (document.getElementById('msg').value != "") {
                    myAjax.send({message:document.getElementById('msg').value});
                }
            });
            document.getElementById('msg').focus();
        </script>
    </body>
    </html>