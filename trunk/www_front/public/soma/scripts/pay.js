
/**总价计算并显示*/
function showCalcTotalHtml(price){
    var total = 0;
    if( price <= 0){
        total = 0;
    }else{
        total = price;
    }
    $('#grandTotal').html(total.toFixed(2));
}


/**总优惠计算*/
function calcTotalAmount(){
    pageloading('总价计算中，请稍后',0.2);
    /*卡券*/
    var mcid = $('#mcid').val();
    if(mcid == '' || mcid == undefined){
        mcid = 0;
    }

    $.ajax({
        type: 'POST',
        url: grandTotalCalcUrl,
        data: {
            product_id:10029,
            qty : parseInt($('.up').siblings('input').val()),
            mcid: '' ,
            bcnt: 1 , //储值
            pcnt: 1 //积分

        },
        success: function(data){
            $('.pageloading').remove();
            if(data.status == 1){
                if(data.coupon_title !='' && data.mcid !='' && $('#couponName') ){ //有优惠券
                    $('#couponName').html(data.coupon_title);
                    $('#mcid').val(data.mcid);
                }
                totalReduce = data.amount;
                grandTotalCalcShow();
            }else{
                $.MsgBox.Alert(data.message);
                return false;
            }
        } ,
        dataType: 'json'
    });
}

/**
 * 总价计算
 */
function grandTotalCalcShow(){
    var grandTotal = originalTotal - totalReduce;
    showCalcTotalHtml(grandTotal);
}

window.onload=function(){
    $('.pageloading').remove();
    couponItemText = $('#choose_coupon').html();
}