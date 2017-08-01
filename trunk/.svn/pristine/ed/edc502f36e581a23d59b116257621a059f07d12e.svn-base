<?php 
/**
 * 清风原创分页代码
 * $this->load->helper('qfpages');
 * $pageurl = '/index.php/chat/superform/suform?page={p}';
 * $totals = intval($ret['0']['count']);
 * $perpage = 5;
 * $page = intval($this->input->get("page"));
 * $nowpage = $page>1?$page:1;
 * $pages = qfpages($totals,$perpage,$nowpage,$pageurl);
 * $limit = $pages['limit'];	
 */

function qfpages($totals,$perpage,$nowpage,$pageurl) {
	$floatpage = $totals/$perpage;$intpage = intval($floatpage);
	$allpage = $floatpage>=$intpage?$intpage+1:$intpage;
	
	$pagehtml = '<div class="qfpages">';
	if ($nowpage!=1) {$pagehtml .= '<a href="'.str_replace('{p}', $nowpage-1, $pageurl).'" title="上一页">上一页</a>';	}
	

	for ($i = $nowpage-2; $i < $nowpage+3; $i++) {
		
		if ($i >= 1 && $i <= $allpage) {
			if ($i == $nowpage) {
				$pagehtml .= '<strong>&nbsp;'.$nowpage.'&nbsp;</strong>';
			}
			else {
				$pagehtml .= '<a href="'.str_replace('{p}', $i, $pageurl).'">&nbsp;'.$i.'&nbsp;</a>';
			}
		}
		
	}
	
	
	if ($nowpage!=$allpage) {$pagehtml .= '<a href="'.str_replace('{p}', $nowpage+1, $pageurl).'" title="下一页">下一页</a>';	}
	$pagehtml .= '<cite>共'.$totals.'条/'.$allpage.'页</cite>';
	$pagehtml .= '</div>';
	
	$limit = "";
	$nowpage = intval($nowpage);
	if ($nowpage>=1) {
		$limit = ($nowpage-1)*$perpage.",".$perpage;
	}
	else {
		$limit = "0,".$perpage;
	}
	$data['limit'] = $limit;
	$data['html'] = $pagehtml;
	return $data;
}

function qflimit($nowpage,$perpage) {
	$limit = "";
	$nowpage = intval($nowpage);
	if ($nowpage>=1) {
		$limit = ($nowpage-1)*$perpage.",".$perpage;
	}
	else {
		$limit = "0,".$perpage;
	}
	return $limit;
}

function qfpage3($totals,$perpage,$nowpage,$pageurl) {
	$floatpage = $totals/$perpage;$intpage = intval($floatpage);
	$allpage = $floatpage>=$intpage?$intpage+1:$intpage;
	
	$pagehtml = '<ul class="pagination">';
	if ($nowpage!=1) {
		$pagehtml .= '<li class="paginate_button previous" id="data-grid_previous"><a href="'.str_replace('{p}', $nowpage-1, $pageurl).'" aria-controls="data-grid" data-dt-idx="0" tabindex="0">上一页</a></li>';
	}
	
	
	for ($i = $nowpage-2; $i < $nowpage+3; $i++) {
	
		if ($i >= 1 && $i <= $allpage) {
			if ($i == $nowpage) {
				//$pagehtml .= '<strong>&nbsp;'.$nowpage.'&nbsp;</strong>';
				$pagehtml .= '<li class="paginate_button active"><a href="#" aria-controls="data-grid" data-dt-idx="'.$nowpage.'" tabindex="0">'.$nowpage.'</a></li>';
			}
			else {
				$pagehtml .= '<li class="paginate_button "><a href="'.str_replace('{p}', $i, $pageurl).'" aria-controls="data-grid" data-dt-idx="'.$i.'" tabindex="0">'.$i.'</a></li>';
			}
		}
	
	}
	
	
	if ($nowpage!=$allpage) {
		$pagehtml .= '<li class="paginate_button next" id="data-grid_next"><a href="'.str_replace('{p}', $nowpage+1, $pageurl).'" aria-controls="data-grid" data-dt-idx="8" tabindex="0">下一页</a></li>';
	}
	
	$limit = "";
	$nowpage = intval($nowpage);
	if ($nowpage>=1) {
		$limit = ($nowpage-1)*$perpage.",".$perpage;
	}
	else {
		$limit = "0,".$perpage;
	}
	
	$pageitem = '当前显示第 '.$nowpage.' / '.$allpage.' 页，共 '.$totals.' 条';
	
	$data['limit'] = $limit;
	$data['html'] = $pagehtml;
	$data['item'] = $pageitem;
	return $data;	
}
?>