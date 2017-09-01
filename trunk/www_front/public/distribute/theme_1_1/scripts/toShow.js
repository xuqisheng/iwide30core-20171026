// JavaScript Document
//作者：yituo
var iBase = {
	//设置元素透明度,透明度值按IE规则计,即0~100
	SetOpacity: function(ev, v){
		ev.filters ? ev.style.filter = 'alpha(opacity=' + v + ')' : ev.style.opacity = v / 100;
	}
}
function fadeOut(elem, speed, opacity){
    /*
     * 参数说明
     * elem==>需要淡入的元素
     * speed==>淡入速度,正整数(可选)
     * opacity==>淡入到指定的透明度,0~100(可选)
     */
    speed = speed || 100;
    opacity = opacity || 0;
    //初始化透明度变化值为0
    var val = 100;
    //循环将透明值以5递减,即淡出效果
    (function(){
        iBase.SetOpacity(elem, val);
        val -= 5;
        if (val >= opacity) {
            setTimeout(arguments.callee, speed);
        }else if (val < 0) {
            //元素透明度为0后隐藏元素
            elem.style.display = 'none';
        }
    })();
}
function fadeIn(elem, speed, opacity){    
	/*    
	* 参数说明    
	* elem==>需要淡入的元素    
	* speed==>淡入速度,正整数(可选)    
	* opacity==>淡入到指定的透明度,0~100(可选)    
	*/    
   speed = speed || 60;    
   opacity = opacity || 100;    
	//显示元素,并将元素值为0透明度(不可见)    
   elem.style.display = 'block';    
   iBase.SetOpacity(elem, 0);    
	//初始化透明度变化值为0    
   var val = 0;    
	//循环将透明值以5递增,即淡入效果    
   (function(){    
	   iBase.SetOpacity(elem, val);    
	   val += 5;    
	   if (val <= opacity) {    
		   setTimeout(arguments.callee, speed)    
	   }    
   })();    
}
function createDoc(doc,classNames,cssText,inHTML){
	var classNam=document.createElement(doc);
		classNam.innerHTML=inHTML||'';
		classNam.className=classNames;
		classNam.style.cssText=cssText;
		return classNam;
}
function toShows(title,content,btn_link){
		var flex=createDoc("div","flex","position:fixed;top:0px;left:0px;z-index:5;background:rgba(0,0,0,0.8);width:100%;height:100%;display:none;");
		var fle_box=createDoc("div","fle_box","width:90%;margin:24% auto;background:#fff;border-radius:15px;text-align:center;padding:5% 0");
		var title=createDoc("h2","title","font-size:0.9rem;padding:10px 0px;font-weight:700;",title);
		fle_box.appendChild(title);
		var fle_con=createDoc("div","fle_con","font-size:0.75rem;display:flex;display:-webkit-flex;align-items:center;justify-content:center;text-align:left;text-indent:2em;margin:10px 30px;box-sizing:border-box;overflow:hidden;text-overflow:ellipsis;display:-webkit-box;-webkit-line-clamp:3;line-clamp:3;-webkit-box-orient:vertical;box-orient:vertical;",content);
		fle_box.appendChild(fle_con);
		var fle_div=createDoc("div","btn__linst","width:100%;margin:auto;margin-top:7%;text-align:center;font-size:0.83rem;");
		var fle_btn=createDoc("div","fle_btn","width:20%;border:1px solid #E4E4E4;background:#fff;padding:0.4rem 1.2rem;border-radius:3px;display:inline-block","我知道了");
		var fle_a=createDoc("a","fle_href","width:20%;background:#FF9900;padding:0.4rem 1.2rem;border-radius:3px;color:#fff;display:inline-block;margin-right:9%;","查看详情");
		fle_a.href=btn_link;
		
		fle_div.appendChild(fle_a);
		fle_div.appendChild(fle_btn);
		fle_box.appendChild(fle_div);
		flex.appendChild(fle_box);
		document.body.appendChild(flex);
		var flexs=document.getElementsByClassName('flex')[0];
		fadeIn(flexs);
		fle_btn.addEventListener('click',function(){
			fadeOut(flexs);
			setTimeout(function(){
				flex.remove(flexs);	
			},1200)	
		})
}

//调用插件。。。
// window.onload=function(){
// 	toShows('亲爱的各位分销员，上午好！为提高全员分销产品的用户体验，现向大家征集改进建议，建议一旦采用，即可获得金。。。');
//	$('.fle_href').attr('href','http://www.baidu.com');
// }