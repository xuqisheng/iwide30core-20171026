<script>
wx.ready(function(){
	wx.scanQRCode({
		needResult: 1,
		scanType: ["qrCode","barCode"],
		success: function (res) {
			var result = res.resultStr;
			$.post('/index.php/hoteladmin/comsume',{'dcardid':'111','dcode':result},function(d){
				if(d.errcode==0){
					alert('核销成功！');
				}
				else {
					alert('核销失败！失败信息为：'+d.errmsg);
				}
			},'json');
		}
	});
});
</script>


