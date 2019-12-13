<!DOCTYPE html>
<title聊天室</title>
<script src="https://cdn.bootcss.com/jquery/1.7.2/jquery.min.js"></script>



<meta charset="utf-8">
<style>
    .mesbox{
        width: 300px;height:40px;background: #a6d4f2 ;border-radius: 5px;line-height: 40px;padding-left: 20px;
        font-family: "微软雅黑";
        box-shadow: 5px 5px 3px #ccc;
    }
    .insert{
        width: 236px;height: 20px;background: #e1e2e3;border-radius: 7px;color:#9c779d;text-align: center;line-height: 20px;margin-left: 50px
    }
    #inputMessage{
        width:400px;height: 50px
    }
    button{
        width: 67px;height:23px;background:#a6d4f2
    }
    .left{float: left;}
    .right{float: left;}
    .right .grid{width: 200px;height: 30px;color:#9c779d;line-height: 30px}
</style>
<script>
    var name='';
    var log = function(s) {
        if (document.readyState !== "complete") {
            log.buffer.push(s);
        } else {
            document.getElementById("output").textContent += (s + "\n")
        }
    }
    log.buffer = [];
    if (this.MozWebSocket) {
        WebSocket = MozWebSocket;
    }
    url = "ws://127.0.0.1:8282";
    w = new WebSocket(url);
    w.onopen = function() {
        log("open");
        // set the type of binary data messages to ArrayBuffer
        w.binaryType = "arraybuffer";
        var mes="thank you for using this";
        // send one string and one binary message when the socket opens
        w.send('{"message": "'+mes+'", "name": "'+name+'","type":"open"}');
        //var a = new Uint8Array([8,6,7,5,3,0,9]);
        //w.send(a.buffer);
    }
    w.onmessage = function(e) {
        console.log(e.data);
        var data1 = eval('('+e.data+')');
        //alert(data1['name']);
        if(data1['type']){
            if(data1['type']=='img'){
                $('#output').append(data1['name']+"说："+'<img src="'+data1['path']+'" alt="" class="bq">');
                $("#output").append("<br />");
            } else {
                $("#output").append('<div class="insert">'+data1['mes']+'</div>');
                $("#output").append("<br />");
                if(data1['type'] == open){
                    $(".right").append('<div id="'+data1['name']+'">'+data1['name']+"</div>");
                    $(".right").append("<hr />");
                }
            }
        } else {
            $("#output").append('<div class="mesbox">'+data1['name']+"说："+data1['mes']+'</div>');
            $("#output").append("<br />");
        }
        //ajaxFileUpload();

    }
    w.onclose = function(e) {
        var data='{"name": "'+name+'"}';

        w.send(data);
        var data1 = eval('('+e.data+')');
        if(data1['type']){
            $("#output").append('<div class="insert">'+data1['mes']+'</div>');
            $("#output").append("<br />");
            alert(data1['name']);
            $("."+data1['name']).remove()
        }
    }
    w.onerror = function(e) {
        log("error");
    }



    window.onload = function() {
        show_prompt();
        log(log.buffer.join("\n"));

        document.getElementById("sendButton").onclick = function() {
            var mes=document.getElementById("inputMessage").value;
            var data='{"message": "'+mes+'", "name": "'+name+'"}';

            w.send(data);
            document.getElementById("inputMessage").value='';
        }

        if(event.keyCode==13){
            var mes=document.getElementById("inputMessage").value;
            var data='{"message": "'+mes+'", "name": "'+name+'"}';

            w.send(data);
        }

    }

    function show_prompt(){
        name = prompt('输入你的名字：', '');
        if(!name || name=='null'){
            name = '游客';
        }
    }

    function ajaxFileUpload() {
        $.ajaxFileUpload
        (
            {
                url: '/upload.php', //用于文件上传的服务器端请求地址
                secureuri: false, //是否需要安全协议，一般设置为false
                fileElementId: 'file', //文件上传域的ID
                dataType: 'json', //返回值类型 一般设置为json
                success: function (data, status)  //服务器成功响应处理函数
                {
                    alert(data.msg);
                    $("#img1").attr("src", data.msg);
                    if (typeof (data.error) != 'undefined') {
                        if (data.error != '') {
                            alert(data.error);
                        } else {

                            alert(data.msg);
                        }
                    }
                },
                error: function (data, status, e)//服务器响应失败处理函数
                {
                    alert(e);
                }
            }
        )
        return false;
    }

    $(document).ready(function(){
        $('.bq img').click(function () {
            path = $(this).attr('src');
            var person=new Object();
            person.path=path;
            person.name="Gates";
            person.type='img';
            var data=JSON.stringify(person);

            w.send(data);
        })
    });


</script>
<div class="left">
    <pre id="output">

    </pre>
    <input type="textarea" id="inputMessage" value="">
    <br />
    <button
        id="sendButton">发送</button>
</div>
<img id="img1" src="" />
<form action="" id="content1">
    <input type="file" name='file' id='file'>
</form>

<div class="bq">
    <p>表情</p>
    <img src="/images/a.jpg" alt="" class="bq">
    <img src="/images/b.jpg" alt="" class="bq">
    <img src="/images/c.jpg" alt="" class="bq">
    <img src="/images/d.jpg" alt="" class="bq">
</div>


<div class="right">
    <div class="title">当前在线人员</div>

</div>

<style>
    .bq{width:100px;
        height:100px;}
</style>



