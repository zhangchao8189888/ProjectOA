<?php
echo "OK!!!!!!!!!!!!";
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"  
        "http://www.w3.org/TR/html4/loose.dtd">   
<html>   
<head>   
  <title></title>   
  <script type="text/javascript" src="js/jquery.js"></script>   
  <script type="text/javascript" src="js/table.js"></script>   
   <style type="text/css">
table{   
    border:1px solid black;   
    border-collapse:collapse;   
    width:500px;   
}   
 table th{   
     border:1px solid black;   
     text-align:center;   
     width:50%;   
 }   
 table td{   
     border:1px solid black;   
     text-align:center;   
     width:50%;   
 }   
  
  thead tr{   
      background-color:red;    
  }   
 tbody td{   
     background-color:dimgray;   
}  

</style>
</head>   
 <script language="javascript" type="text/javascript" src="common/js/jquery_last.js" charset="utf-8"></script>
        <script language="javascript" type="text/javascript" src="common/js/jquery.pagination.js" charset="utf-8"></script>
        <script language="javascript" type="text/javascript" src="common/js/jquery.checkbox.js" charset="utf-8"></script>
        <script language="javascript" type="text/javascript">
        $(function(){   
      	  //tbody中tr为奇数的背景颜色为darkgray   
      	  $("tbody tr:odd").css("backgroundColor","darkgray");   
      	  //为tbody中th为偶数创建单击事件     
      	  $("tbody th:even").click(function(){   
      	      //当前单击为偶数th对象   
      	      var thObj=$(this);   
      	      //如果当前对象中包含子结点input不创建   
      	      if(thObj.children("input").length>0){   
      	          return false;   
      	      }   
      	      //创建input对象   
      	      var  inputObj=$("<input type='text'/>");   
      	      //把thObj中的内容赋值给text变量   
      	      var text=thObj.html();   
      	       //清空thObj对象中的内容   
      	      thObj.html("");   
      	      //设置input对象中的样式与thObj对象一样   
      	      //后面把input对象增加到thObj父结点中   
      	      inputObj.css("borderWidth","0")   
      	              .width(thObj.width())   
      	              .height(thObj.height())   
      	              .css("backgroundColor",thObj.css("backgroundColor"))   
      	              .val(text)   
      	              .appendTo(thObj);   
      	      //全选input中的内容   
      	     // inputObj.select(); 或 二种实现支持所有的浏览器   
      	      inputObj.trigger("focus").trigger("select");   
      	      //取消input中的单击事件   
      	     /* inputObj.click(function(){  
      	         return false;  
      	      });*/  
      	      //如果是如果按ENTER就修改,ESC就取消   
      	      inputObj.keyup(function(event){   
      	           var keycode=event.which;   
      	            if(keycode==13){   
      	                if(inputObj.val()!='100'){   
      	                      thObj.html(inputObj.val());   
      	                        }else{   
      	                        alert("not 100 error");   
      	                      }   
      	            }   
      	            if(keycode==27){   
      	  
      	                 thObj.html(text);   
      	            }   
      	        });   
      	    });   
      	  
      	  /*---------------------------------------------------------------------*/  
      	     //为tbody中th为奇数创建单击事件   
      	  $("tbody th:odd").click(function(){   
      	      //当前单击为奇数th对象   
      	      var thObj=$(this);   
      	      //如果当前对象中包含子结点input不创建   
      	      if(thObj.children("input").length>0){   
      	          return false;   
      	      }   
      	      //创建input对象   
      	      var  inputObj=$("<input type='text'/>");   
      	      //把thObj中的内容赋值给text变量   
      	      var text=thObj.html();   
      	       //清空thObj对象中的内容   
      	      thObj.html("");   
      	      //设置input对象中的样式与thObj对象样式一样   
      	      //后面把input对象增加到thObj父结点中   
      	      inputObj.css("borderWidth","0")   
      	              .width(thObj.width())   
      	              .height(thObj.height())   
      	              .css("backgroundColor",thObj.css("backgroundColor"))   
      	              .val(text)   
      	              .appendTo(thObj);   
      	      //全选input中的内容   
      	     // inputObj.select(); 或 二种实现支持所有的浏览器   
      	      inputObj.trigger("focus").trigger("select");   
      	      //取消input中的单击事件   
      	     /* inputObj.click(function(){  
      	         return false;  
      	      });*/  
      	      //如果是如果按ENTER就修改,ESC就取消   
      	      inputObj.keyup(function(event){   
      	           var keycode=event.which;   
      	            if(keycode==13){   
      	            thObj.html(inputObj.val());   
      	            }   
      	            if(keycode==27){   
      	             thObj.html(text);   
      	            }   
      	        });   
      	    });   
      	       
      	});  
      	        
        </script>
<body>   
  
     <table>   
         <thead>   
             <tr> <th colspan="2"> 信息栏 </th></tr>   
         </thead>   
         <tbody>   
               <tr>   <td> id </td>   
                      <td> 标题 </td>   
               </tr>   
                 <tr>   
                       <th > 1</th>   
                       <th> rrrr</th>   
                 </tr>   
                <tr>   
                       <th >2</th>   
                       <th> eeee</th>   
                 </tr>   
             <tr>   
                       <th > 3</th>   
                       <th> nnnn</th>   
                 </tr>   
             <tr>   
                       <th > 4</th>   
                       <th> cccc</th>   
                 </tr>   
             <tr>   
                       <th > 5</th>   
                       <th> hhhh</th>   
                 </tr>   
         </tbody>   
     </table>   
  
</body>   
</html>  

