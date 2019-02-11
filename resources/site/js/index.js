    var index=2;        //图片下标
    var indexBox=2;     //文字显示下标
    var count="";       //定时器
    var cgalIndex=2;    //成功案例切换index



    //切换图片
    function chanageImg(){
        var img=document.getElementById("img-op");
        if(index==1){
            img.src="img/diyi.jpg";
        } 
        if(index==2){
            img.src="img/dier.jpg";
        } 
        if(index==3){
            img.src="img/disan.jpg";
        } 
        index++;
        if(index==4){
            index=1;
        }
    }
    
    //成功案例模块 动态切换
    function chanageSuccessfulCase(){
        if(cgalIndex==1){
            $(".one").css("display","block");
            $(".tow").css("display","none");
            $(".three").css("display","none");
            $(".four").css("display","none");
        } 
        if(cgalIndex==2){
            $(".one").css("display","none");
            $(".tow").css("display","block");
            $(".three").css("display","none");
            $(".four").css("display","none");
        } 
        if(cgalIndex==3){
            $(".one").css("display","none");
            $(".tow").css("display","none");
            $(".three").css("display","block");
            $(".four").css("display","none");
        }
        if(cgalIndex==4){
            $(".one").css("display","none");
            $(".tow").css("display","none");
            $(".three").css("display","none");
            $(".four").css("display","block");
        } 
        
        cgalIndex++;
        if(cgalIndex==5){
            cgalIndex=1;
        }
    }

    //鼠标移入时切换显示文字
    function chanageBox(){
        var arr=document.getElementsByClassName("on");
        $(arr[0]).mousemove(function(){
            $(".bdqn_box").css("display","block");
            $(".hqys_box").css("display","none");
            $(".asdf_box").css("display","none");
            clearInterval(count);
        })
        $(arr).mouseout(function(){
            count= setInterval("chanageBox2()","2000");
        })
        $(arr[1]).mousemove(function(){
            $(".bdqn_box").css("display","none");
            $(".hqys_box").css("display","block");
            $(".asdf_box").css("display","none");
            clearInterval(count);
        })
        $(arr[2]).mousemove(function(){
            $(".bdqn_box").css("display","none");
            $(".hqys_box").css("display","none");
            $(".asdf_box").css("display","block");
            clearInterval(count);
        })
    }

    //自动切换显示文字
    function chanageBox2(){
        if(indexBox==1){
            $(".bdqn_box").css("display","block");
            $(".hqys_box").css("display","none");
            $(".asdf_box").css("display","none");
        } 
        if(indexBox==2){
            $(".bdqn_box").css("display","none");
            $(".hqys_box").css("display","block");
            $(".asdf_box").css("display","none");
        } 
        if(indexBox==3){
            $(".bdqn_box").css("display","none");
            $(".hqys_box").css("display","none");
            $(".asdf_box").css("display","block");
        } 
        
        indexBox++;
        if(indexBox==4){
            indexBox=1;
        }
    }
    
    function buttonHover(){
        var one=document.getElementById("btn").firstElementChild;//获取第一个li
        var tow=document.getElementById("btn").firstElementChild.nextElementSibling;//获取第二个li
        var three=document.getElementById("btn").firstElementChild.nextElementSibling.nextElementSibling;//获取第三个li
        var four=document.getElementById("btn").lastElementChild;//获取第四个li
        
        $("#btn").mouseout(function(){
            count=setInterval("chanageSuccessfulCase()","2000");
        })

        $(one).mousemove(function(){
            $(".one").css("display","block");
            $(".tow").css("display","none");
            $(".three").css("display","none");
            $(".four").css("display","none");
            //cgalIndex=1;
            clearInterval(count);
        })
        $(tow).mousemove(function(){
            $(".one").css("display","none");
            $(".tow").css("display","block");
            $(".three").css("display","none");
            $(".four").css("display","none");
            //cgalIndex=2;
            clearInterval(count);
        })
        $(three).mousemove(function(){
            //cgalIndex=3;
            $(".one").css("display","none");
            $(".tow").css("display","none");
            $(".three").css("display","block");
            $(".four").css("display","none");
            clearInterval(count);
        })
        $(four).mousemove(function(){
            //cgalIndex=4;
            $(".one").css("display","none");
            $(".tow").css("display","none");
            $(".three").css("display","none");
            $(".four").css("display","block");
            clearInterval(count);
        })
    }

    $(function(){

        buttonHover();
        chanageBox();
        setInterval("chanageImg()","2000");         //图片切换定时器
        count = setInterval("chanageBox2()","2000"); //文字切换定时器
        count = setInterval("chanageSuccessfulCase()","2000");//成功案例切换定时器




        //鼠标移入时暂停 定时器
        $(".jy-f").mousemove(function(){
            clearInterval(count);
        })

        //鼠标移出时 启动定时器
        $(".jy-f").mouseout(function(){
            count= setInterval("chanageSuccessfulCase()","2000");
        })

        //当页面加载时生成验证码
        getShuiJiShu();

        // 当用户点击输入框时 提示信息消失
        $("#userid").focus(function(){
            $(this).prop("placeholder","")
        })

        $("#pwd").focus(function(){
            $(this).prop("placeholder","")
        })
        //当文本框失去焦点时 显示提示信息
        $("#userid").blur(function(){
            $(this).prop("placeholder", "用户名")
        });
        $("#pwd").blur(function(){
            $(this).prop("placeholder", "密码")
        });
       
        //随机生成验证码
        function getShuiJiShu(){
            var str="";

            var chars=['A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z'];

           // 生成四位随机数
            for(var i=0;i<4;i++){
                var sjs=parseInt(Math.random()*26)
                //alert(sjs);
                str+=chars[sjs];
            }
            //将验证码 赋值给div
            $(".yanzhengma").html(str);
        }
        
        //点击更换验证码
        $(".yanzhengma").click(getShuiJiShu)
    })