$(function () {
  window.addEventListener('load', function () {
    var section = parseUrl(location.href)
    if (section) {
      $('.page-' + section).show()
    }
  })
  window.addEventListener('hashchange', function(e){
    var section = parseUrl(e.newURL)
    var oldsection = parseUrl(e.oldURL)
    if (section) {
      $('.page-' + section).show()
    }
    if (oldsection) {
      $('.page-' + oldsection).hide()
    }

  }, false)
  var parseUrl = function (url) {
    var hash = url.split('#')[1]
    return hash
  }
  var toshow = function (hash) {
    location.hash = '#' + hash
  }
  $('.page').on('click','.btn-back', function () {
    history.back(-1);
  })
  $('.page').on('click','.btn-index', function () {
    location.href = pageIndex;
  })
  var $pageIndex = $('.page-index');
  var $pageAmity = $('.page-amity')
  var $pageRun = $('.page-run')
  var $pageInfo = $('.page-info')
  var $pageInfoProtocol = $pageInfo.find('.protocol')
  var $pageTip = $('.page-tip')
  var $pageApply = $('.page-apply')
  var $pageUser = $('.page-user')
  var $pageLoading = $pageUser.find('.loading')
  var $pageUserCont = $pageUser.find('.page-box')
  var $pageSuccess = $('.page-success')
  var pageInfoHasLayout = false
  var pageUserHasLayout = false
  $pageIndex.on('click', '.btn', function () {
    var type = $(this).data('type')
    if (type === 2) {
      toshow('amity');
    } else {
      toshow('run')
    }
  })
  $pageIndex.on('click', '.arrow', function () {
    if (!pageInfoHasLayout) {
      $pageInfo.css('z-index', -1).show()
      var bh = $pageInfo.find('.page-box').height()
      var rh = $pageInfo.find('.rules').height()
      var h = bh - 32 - rh - 8 - 44 - 31 - 15
      $pageInfoProtocol.css('max-height', h + 'px')
      pageInfoHasLayout = true
      $pageInfo.css('z-index', 13)
    }
    toshow('info')
  })
  var timer = null
  var $a = $pageInfo.find('input[name="a"]')
  $pageInfo.on('click', '.btn-next', function () {
    if($a.filter(':checked').val() == 0) {
      toshow('tip')
      clearTimeout(timer)
      timer = setTimeout(function () {
        history.back(-1)
        clearTimeout(timer)
      },2000)
    } else {
      toshow('apply')
    }
  })
  var $city = $pageApply.find('input[name="city"]')
  $pageApply.on('click', '.btn-next', function () {
    if (!pageUserHasLayout) {
      $pageUser.find('.box').css('height', $(window).height())
      $pageUser.css('z-index', -1).show()
      $pageUserCont.css('height', $pageUserCont.height())
      pageUserHasLayout = true
       $pageUser.css('z-index', 16)
    }
    toshow('user')
  })
  var $form = $('.form')
  var ageReg = /^\d{2}$/
  var phoneReg = /^1\d{10}$/
  var emailReg = /^[a-z0-9]+([._\\-]*[a-z0-9])*@([a-z0-9]+[-a-z0-9]*[a-z0-9]+.){1,63}[a-z0-9]+$/
  var age = 0
  var gender = 1
  var vcity={ 11:"北京",12:"天津",13:"河北",14:"山西",15:"内蒙古",
    21:"辽宁",22:"吉林",23:"黑龙江",31:"上海",32:"江苏",
    33:"浙江",34:"安徽",35:"福建",36:"江西",37:"山东",41:"河南",
    42:"湖北",43:"湖南",44:"广东",45:"广西",46:"海南",50:"重庆",
    51:"四川",52:"贵州",53:"云南",54:"西藏",61:"陕西",62:"甘肃",
    63:"青海",64:"宁夏",65:"新疆",71:"台湾",81:"香港",82:"澳门",91:"国外"
   };


  //检查号码是否符合规范，包括长度，类型
  var isCardNo = function(card)
  {
    //身份证号码为15位或者18位，15位时全为数字，18位前17位为数字，最后一位是校验位，可能为数字或字符X
    var reg = /(^\d{15}$)|(^\d{17}(\d|X)$)/;
    if(reg.test(card) === false)
    {
        return false;
    }
    return true;
  };
  //取身份证前两位,校验省份
  var checkProvince = function(card)
  {
    var province = card.substr(0,2);
    if(vcity[province] == undefined)
    {
        return false;
    }
    return true;
  };
  //检查生日是否正确
  var checkBirthday = function(card)
  {
    var len = card.length;
    //身份证15位时，次序为省（3位）市（3位）年（2位）月（2位）日（2位）校验位（3位），皆为数字
    if(len == '15')
    {
        var re_fifteen = /^(\d{6})(\d{2})(\d{2})(\d{2})(\d{3})$/; 
        var arr_data = card.match(re_fifteen);
        var year = arr_data[2];
        var month = arr_data[3];
        var day = arr_data[4];
        var birthday = new Date('19'+year+'/'+month+'/'+day);
        return verifyBirthday('19'+year,month,day,birthday);
    }
    //身份证18位时，次序为省（3位）市（3位）年（4位）月（2位）日（2位）校验位（4位），校验位末尾可能为X
    if(len == '18')
    {
        var re_eighteen = /^(\d{6})(\d{4})(\d{2})(\d{2})(\d{3})([0-9]|x|X)$/;
        var arr_data = card.match(re_eighteen);
        var year = arr_data[2];
        var month = arr_data[3];
        var day = arr_data[4];
        var birthday = new Date(year+'/'+month+'/'+day);
        return verifyBirthday(year,month,day,birthday);
    }
    return false;
  };
  //校验日期
  var verifyBirthday = function(year,month,day,birthday)
  {
      var now = new Date();
      var now_year = now.getFullYear();
      //年月日是否合理
      if(birthday.getFullYear() == year && (birthday.getMonth() + 1) == month && birthday.getDate() == day)
      {
        //判断年份的范围（18岁到70岁之间)
        var time = now_year - year;
        if(time >= 18 && time <= 70)
        {
          age = time
          return true;
        }
        return false;
      }
      return false;
  };
  //校验位的检测
  var checkParity = function(card)
  {
      //15位转18位
      card = changeFivteenToEighteen(card);
      var len = card.length;
      if(len == '18')
      {
          var arrInt = new Array(7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2); 
          var arrCh = new Array('1', '0', 'X', '9', '8', '7', '6', '5', '4', '3', '2'); 
          var cardTemp = 0, i, valnum; 
          for(i = 0; i < 17; i ++) 
          { 
              cardTemp += card.substr(i, 1) * arrInt[i]; 
          } 
          valnum = arrCh[cardTemp % 11]; 
          if (valnum == card.substr(17, 1)) 
          {
            gender = card.substr(16, 1) % 2
            return true;
          }
          return false;
      }
      return false;
  };
  //15位转18位身份证号
  var changeFivteenToEighteen = function(card)
  {
      if(card.length == '15')
      {
          var arrInt = new Array(7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2); 
          var arrCh = new Array('1', '0', 'X', '9', '8', '7', '6', '5', '4', '3', '2'); 
          var cardTemp = 0, i;   
          card = card.substr(0, 6) + '19' + card.substr(6, card.length - 6);
          for(i = 0; i < 17; i ++) 
          { 
              cardTemp += card.substr(i, 1) * arrInt[i]; 
          } 
          card += arrCh[cardTemp % 11]; 
          return card;
      }
      return card;
  };
  var words = {
    username: '姓名',
    gender: '性别',
    'age': '年龄',
    'phone': '电话',
    'idcard': '身份证号',
    'email': '邮箱',
    'urgent_phone': '紧急联系人电话',
    'urgent_username': '紧急联系人姓名'
  }
  var genderwords = ['女', '男']
  var rules = {
    age: function (val) {
      if (!ageReg.test(val)) {
        return '年龄必须在18至70周岁之间'
      }
      else if (val < 18) {
        return '年龄必须大于18周岁'
      }
      else if (val > 70) {
        return '年龄必须小于70周岁'
      }
    },
    phone: function (val) {
      if (!phoneReg.test(val)) {
        return '输入正确的电话'
      }
    },
    idcard: function (val) {
      //校验长度，类型
      if(isCardNo(val) === false){
        return '您输入的身份证号码不正确';
      }
      //检查省份
      if(checkProvince(val) === false){
        return '您输入的身份证号码不正确';
      }
      //校验生日
      if(checkBirthday(val) === false){
        return '您输入的身份证号码不在18到70周岁之间';
      }
      //检验位的检测
      if(checkParity(val) === false){
        return '您的身份证号码不正确';
      }
    },
    email: function (val) {
      if (!emailReg.test(val)) {
        return '输入正确的邮箱地址'
      }
    },
    urgent_phone: function (val) {
      if (!phoneReg.test(val)) {
        return '输入正确的紧急联系人电话'
      }
    }
  }
  var $error = $pageUser.find('.error')
  $pageUser.on('click', '.btn-next', function () {
    $error.hide()
    var result = {}
    var data = $form.serializeArray();
    var len = data.length;
    var i = 0;
    while (i < len) {
      var name = data[i].name;
      var value = $.trim(data[i].value) || '';
      if (name === 'idcard') {
        value = value.toUpperCase()
      }
      if (!value) {
        $error.text('请输入' + words[name]).show()
        return
      } else if (rules[name]) {
        var msg = rules[name](value);
        if (msg) {
          $error.text(msg).show()
          return
        }
      }
      result[name] = value
      i++;
    }
    // 根据身份证判断年龄是不是正确
    if (result.age != age) {
      $error.text('身份证年龄与输入年龄不符').show()
      return
    }
    if (genderwords[gender] !== result.gender) {
      $error.text('身份证性别与选择性别不符').show()
      return
    }
    result.type = '个人赛'
    result.city = $city.filter(':checked').val()
    result['csrf_token'] = csrfToken;
    $pageLoading.addClass('Ldf').removeClass('Ldn');
    $.ajax({
      url: applyUrl,
      data: result,
      type: 'post',
      dataType: 'json',
      success: function (data) {
        $pageLoading.removeClass('Ldf').addClass('Ldn');
        if (data.status === 1000) {
          location.href = data.data.pay_url
        } else {
          alert(data.msg);
        }
      },
      error: function () {
        $pageLoading.removeClass('Ldf').addClass('Ldn');
        alert('报名失败')
      }
    })
  })
  $pageSuccess.on('click', '.btn-wechat', function () {
    toshow('wechat');
  })
})