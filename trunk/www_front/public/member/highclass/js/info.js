//个人信息
$("#birthday").mobiscroll('destroy').date({  
    theme: "android-holo light",  
    lang: "zh",  
    display: 'bottom',  
    dateFormat: 'yy-mm-dd', //返回结果格式化为年月格式  
    // wheels:[], 设置此属性可以只显示年月，此处演示，就用下面的onBeforeShow方法,另外也可以用treelist去实现  
    //onBeforeShow: function (inst) { inst.settings.wheels[0].length>2?inst.settings.wheels[0].pop():null; }, //弹掉“日”滚轮  
    headerText: function (valueText) { //自定义弹出框头部格式  
        array = valueText.split('-');  
        return array[0] + "年" + array[1] + "月" + array[2] + "日";  
    }
});