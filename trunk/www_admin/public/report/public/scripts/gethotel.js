$.get('/index.php/report/report/getpublic',{},function(d){	 
	$('#gethotel').html('选择酒店<select name="showpublic" id="showpublic" style="width:100px"></select>');
	$('#gethotel select').append('<option value="">全部</option>');
	for(var i=0;i<d.length;i++){
		var oldcheck = '';
		if($('#gethotel').attr('showpublic') == d[i].inter_id){
			oldcheck = ' selected="selected"';
			$.get('/index.php/report/report/gethotel?interid='+$('#gethotel').attr('showpublic'),{},function(d){
				$('#gethotel1').html('<select name="showhotel" id="showhotel" style="width:150px"><option value="">全部</option></select>');
				for(var i=0;i<d.length;i++){
					if($('#gethotel').attr('showhotel') == d[i].hotel_id){
						oldcheck = ' selected="selected"';
					}
					else {oldcheck = '';}
					$('#showhotel').append('<option value="'+d[i].hotel_id+'"'+oldcheck+'>'+d[i].name+'</option>');
				}
			},'json');
		} else {oldcheck = '';}
		$('#gethotel select').append('<option value="'+d[i].inter_id+'"'+oldcheck+'>'+d[i].name+'</option>');
	}
	
	$('#showpublic').change(function(d){
  		$.get('/index.php/report/report/gethotel?interid='+$('#gethotel select').val(),{},function(d){
        	$('#gethotel1').html('<select name="showhotel" id="showhotel" style="width:150px"><option value="">全部</option></select>');
			for(var i=0;i<d.length;i++){
				$('#showhotel').append('<option value="'+d[i].hotel_id+'">'+d[i].name+'</option>');
			}
		},'json');
    });
},'json');

