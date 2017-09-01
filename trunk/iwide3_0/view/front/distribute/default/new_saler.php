<link href="<?php echo base_url('public/distribute/default/styles/incom.css')?>" rel="stylesheet">
<link href="<?php echo base_url('public/distribute/default/styles/fill_in.css')?>" rel="stylesheet">
<title>填写信息</title>
</head>
<style>
    <!--
    .pull{ position:fixed; top:0; left:0; width:100%; height:100%;-webkit-overflow-scrolling:touch; overflow:scroll; background:#fff; color:#555; display:none;}
    .pull div{background:#e4e4e4; padding:2%; text-align:center;}
    .pull input{background:#fff; border-radius:0.2rem; border:1px solid #d3d3d3; text-align:center; padding:2%; width:90%;}
    .pull dt{border-bottom: 1px solid #e4e4e4; padding:3%;}
    .selsct span{display:inline-block}
    .selsct span:last-child{width:70%; padding-right:3%; }
    #sent_code{font-size:0.5rem; background:#F90; color:#fff; border-radius:0.3rem; padding:0.3rem 0; width:6em; text-align:center; display:inline-block;}
    #sent_code.disable{ background:#eee;}
    /*.floot p,#sent_code{background: #d40f20;}*/
    -->
</style>
<body>
<div class="nav">
    <div class="state">
        <div class="lis_sta">
            <div class="radi bg">1</div>
            <p class="col">填写信息</p>
        </div>
        <div class="lis_sta">
            <div>2</div>
            <p>酒店审核</p>
        </div>
        <div class="lis_sta">
            <div>3</div>
            <p>审核结果</p>
        </div>
        <div class="yello"></div>
        <div class="c_99"></div>
    </div>
</div>
<div class="titl">填写个人信息</div>
<div class="box">
    <div class="selsct">
        <span class="front">姓&nbsp;&nbsp;名</span>
        <span><input type="text" class="use" placeholder="请输入姓名" name="name" id="name" value="<?php if (isset($saler['name'])):echo $saler['name'];endif;?>" /></span>
    </div>
    <div class="selsct">
        <span class="front">身份证号</span>
        <span><input maxlength="18" type="text" class="identit" placeholder="请输入身份证号" name="idnum" id="idnum" value="<?php if (isset($saler['id_card'])):echo $saler['id_card'];endif;?>" /></span>
    </div>
    <div class="selsct">
        <span class="front">手机号码</span>
        <span><input maxlength="11" type="tel" class="phone" placeholder="请输入手机号码" name="cellphone" id="cellphone" value="<?php if (isset($saler['cellphone'])):echo $saler['cellphone'];endif;?>" /></span>
    </div>
    <div class="selsct">
        <span class="front">部门</span>
        <span>
        <?php if(is_null($depts)):?><input type="text" placeholder="部门" name="department" id="department" value="<?php if (isset($saler['master_dept'])):echo $saler['master_dept'];endif;?>" />
        <?php else: ?><select name="department" id="department">
        <?php foreach ($depts as $dept):?>
            <option value="<?php echo $dept->dept_name?>"<?php if (isset($saler['master_dept']) && $saler['master_dept'] == $dept->dept_name):echo ' selected';endif;?>><?php echo $dept->dept_name?></option>
        <?php endforeach;endif;?>
        </select>
        </span>
    </div>
    <div class="selsct s_h">
        <span class="front">所属酒店</span>
        <span class="s"><em style="color:#999;">请选择酒店</em></span>
    </div>
    <div class="pull">
        <div><input type="text" placeholder="搜索酒店名" id="selecthotel" value=''/></div>
        <?php foreach ($hotels as $hotel):?>
            <?php if($hotel['inter_id'] != 'a449675133' || (strpos($hotel['name'],'集团') !== false || $hotel['status'] == 1)):?>
                <dt tid="<?php echo $hotel['hotel_id']?>"><?php echo $hotel['name']?></dt>
            <?php endif;?>
        <?php endforeach;?>
    </div>
    <input type="hidden" name="hotel" id="hotel" value='' />
</div>
<!-- <div class="treaty"><span class="i_no">我同意</span><a href="">《分销协议》</a></div> -->
<div class="floot">
    <a href="javascript:;" onclick="submit()"><p>提交</p></a>
</div>
</body>
</html>
<script>
    var testval={
        name:/^[\u4E00-\u9FA5]{2,4}/g,
        identity:/(^\d{15}$)|(^\d{18}$)|(^\d{17}(\d|X|x)$)/,
        _phon:/^[1]\d{10}$/
    }
    function submit(){
        var sub = true;
        var name      = $('#name').val();
        var idnum     = $('#idnum').val();
        var cellphone = $('#cellphone').val();
        var department= $('#department').val();
        var rcode     = $('#rcode').val();
        if(name == undefined  || name == ''){
            alert('请输入姓名');return false;
        }
        if(idnum == undefined  || !testval.identity.test(idnum)){
            alert('请输入正确身份证');return false;
        }
        if(cellphone == undefined ||!testval._phon.test(cellphone)){
            alert('请输入正确手机号码');return false;
        }

        if(department == undefined || department == ''){
            alert('请输入部门');return false;
        }
        if($('#hotel').val() == ''){
            alert('请选择酒店');return false;
        }
        if(sub){
            sub = false;
//            pageloading('正在提交数据....',0.2);
            $.post(
                "<?php echo site_url('distribute/distribute/do_reg')?>?id=<?php echo $inter_id?>",
                {
                    <?php if(isset($saler['id'])):?>'id':<?php echo $saler['id'];?>,<?php endif;?>
                    "hotel":$('#hotel').val(),
                    "department":department,
                    "name":name,
                    "idnum":idnum,
                    "cellphone":cellphone,
                    "rcode":rcode,
                    '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
                },
                function(datas){
                    if(datas.errmsg == 'ok'){
                        alert(datas.message);
                        window.location.reload();
                    }else{
                        alert(datas.message);
                        sub=true;
                    }
                },'json');
        }else{
            alert('正在提交数据');
        }
    }
    $('.s_h').click(function(){
        toshow($('.pull'));
        $('.pull dt').stop().show();
    })
    $('.pull').scroll(function(e){	e.preventDefault(); });
    $('.pull dt').click(function(){
        $('#hotel').val($(this).attr('tid'));
        $('#selecthotel').val('');
        $('.s').html($(this).html()).removeClass('error').addClass('all_right');
        $('.pull').stop().hide();
    })
    $('#selecthotel').bind('input propertychange', function() {
        var val=$(this).val();
        if( val ==''){$('.pull dt').stop().show();}
        else{
            for( var i=0; i<$('.pull dt').length; i++){
                if ( $('.pull dt').eq(i).html().indexOf(val) >= 0)
                    $('.pull dt').eq(i).stop().show();
                else
                    $('.pull dt').eq(i).stop().hide();
            }
        }
    });
    $(document).on('blur','input[name]',function(){
        var _v =$(this).val();
        if(_v==''|| ($(this).hasClass('identit')&&!testval.identity.test(_v))||($(this).hasClass('phone')&&!testval._phon.test(_v))){
            $(this).parent().removeClass('all_right').addClass('error');
        }
        else{
            $(this).parent().removeClass('error').addClass('all_right');
        }
        //$('.pull').stop().hide();
    })

</script>