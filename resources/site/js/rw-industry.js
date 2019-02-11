// JavaScript Document


   <script type="text/javascript">  
        window.onload=function()  
        {  var hNode=document.getElementsByTagName("li");  
            var info=document.getElementById("div2").getElementsByTagName("div");  
            for(var i=0;i<hNode.length;i++)  
            {  
                hNode[i].index=i;  
               hNode[i].onmousemove=function()  
                {  
                   for(var j=0;j<hNode.length;j++){  
                        hNode[j].className="";  
                        info[j].className="kj-1"  
                    }  
                   hNode[this.index].className="li-1";  
                    info[this.index].className="";  
                }  
            }  
       }       
        
    </script>  
  
	