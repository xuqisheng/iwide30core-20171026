<html>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta http-equiv="Pragma" content="no-cache">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, maximum-scale=1, shrink-to-fit=no">
	<meta name="Maker" content="Taoja" tel="13544425200">
	<meta name="format-detection" content="telephone=no">
	<title>直播</title>
	<link type="text/css" href="/public/zb/css/tao.css" rel="stylesheet">
    <style>
        body{
            background-color: white;
            overflow: auto;
        }
        .dq_xiabi{
            text-align: center;
            color:#bfbfbf;
            font-size: 1.17rem;
            margin-top: 60px;
        }
        .xiabi_num{
            margin-top: 15px;
            font-size: 3.33rem;
            color: #fea251;
            text-align: center;
            padding-bottom: 50px;
            border-bottom: 1px dashed #efebeb;
        }
        .mingxi_name{
            color:#666;
            font-size: 1.17rem;
        }
        .mingxi_time{
            color: #bfbfbf;
            font-size: 1rem;
        }
        .mingxi_num{
            font-size: 1.33rem;
        }
        .earn{
            color:#50ac3b;
        }
        .spend{
            color:#e63f6a;
        }
        .mingxi_body{
            margin: 20px;
            margin-top: 35px;
        }
        .mingxi_each{
            margin-top: 1rem;
            padding-bottom: 15px;
            border-bottom: 1px solid #eeeae9;
        }
        .mingxi_each>flex{
            margin-top: 10px;
        }
        @media screen and (min-width:375px){
            html{
                font-size: 14px;
            }
        }
        @media screen and (min-width:414px){
            html{
                font-size: 15px;
            }
        }
    </style>
</head>

<body>
	<div style="margin:0px 15px;">
        <div>
            <div class="dq_xiabi">当前虾币</div>
            <div class="xiabi_num"><?php echo $mibi?></div>
            <div class="mingxi_body">
                <?php foreach($record as $key => $r_data){?>
                <div class="mingxi_each">
                        <flex between>
                            <ib class="mingxi_name"><?php echo $r_data['record_type_name'];?></ib>
                            <ib></ib>
                        </flex>
                        <flex between>
                            <ib class="mingxi_time"><?php echo date("Y/m/d H:i",strtotime($r_data['create_time']));?></ib>
                           
                           
                            <ib class="mingxi_num <?php if($r_data['record_type'] == "give"){echo "spend";}else{echo "earn";}?>"><?php echo $r_data['mibi_change_num_name'];?></ib>
                        </flex>
                </div>
                <?php }?>
               
            </div>
        </div>
    </div>
</body>

</html>
