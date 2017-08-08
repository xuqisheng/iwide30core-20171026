/**
 * 设置label 颜色
 * */
function setLabel () {
  var list = $('#list li');
  for (var i = 0; i < list.length; i++) {
    if ((i + 1) % 3 === 0) {
      list.eq(i).find('.label').addClass('label2');
    } else {
      list.eq(i).find('.label').addClass('label1');
    }
  }
  list.eq(1).find('.label').removeClass('label1').addClass('label2');
}

function setHeight () {
    var height = $('#list .img').width();
    console.log(height)
    $('#list .img').css({'height': height + 'px'});
}
/*将图宽高尺寸改为1:1*/
function setWH_1(){
  var aImg = $('#list .img img');
  var oWidth = $('#list .img').width();
  aImg.height(oWidth);

}
//setHeight();
setLabel();
setWH_1();