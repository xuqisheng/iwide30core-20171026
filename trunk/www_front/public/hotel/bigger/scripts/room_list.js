$(function(){

    var swiper = new Swiper('.swiper-container',{
        pagination: '.swiper-pagination',
        paginationType: 'fraction',
        loop : true
        });

    $(".close").on("click",function(){
        cloheight();
        $(".whole_eject").hide();
    });

    $(".room_list_choose").on("click",function(){
        $(".room_list_choose").removeClass("color1").find(".shadow_b").hide();
        $(this).addClass("color1").find(".shadow_b").show();
    });
    $("#j-reservation").on("click",function(){
      $(".room_list").hide().eq(0).show();  
    })
    $("#j-swim").on("click",function(){
      $(".room_list").hide().eq(1).show();  
    })
    $("#business_portal").on("click",function(){
        $("#comment_verification").show();
    });
    $(document).on("click",".room_collect",function(){
        $(this).parents(".item").toggleClass("room_collect_hide")
    })

    $("#room_package_wrap").on("click","#room_details",function(){
        $(this).addClass("color1").find(".shadow_b").show();
        $("#package_details").removeClass("color1").find(".shadow_b").hide();
        $("#room_details_contents").show().siblings().hide()
    })
    $("#room_package_wrap").on("click","#package_details",function(){
        $(this).addClass("color1").find(".shadow_b").show();
        $("#room_details").removeClass("color1").find(".shadow_b").hide();
        $("#package_details_contents").show().siblings().hide();
    })
})