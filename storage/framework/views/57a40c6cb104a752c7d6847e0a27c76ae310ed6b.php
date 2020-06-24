<?php $__env->startSection('content'); ?>

<?php
$minorchecktags = DB::table('minorchecktags')->where('status', 1)->get();
$checktag = array();
foreach($minorchecktags as $minorchecktag){
	$checktag[] = $minorchecktag->name;
}

$mlfilepath = get_option('site_url').'ml_files/'.$jobsourceinfo[0]->ml_file.'.json';
function group_by($key, $data) {
    $result = array();
    foreach($data as $val) {
        if(array_key_exists($key, $val)){
            $result[$val[$key]][] = $val;
        }else{
            $result[""][] = $val;
        }
    }
    return $result;
}

// Get the contents of the JSON file 
$strJsonFileContents = file_get_contents($mlfilepath);

// Convert to array 
$strAry		 	= json_decode($strJsonFileContents, true);

$aryGroup 		= group_by("weight", $strAry['mainterms']);
asort($aryGroup);



$treeviewstruct = '';
foreach($aryGroup as $key => $aryval){
if($key == 'a'){
	$keyname	=	'Level_1';
	$keyval		=	'Level 1';
} else {
	$keyname	=	'Level_2';
	$keyval		=	'Level 2';
}

$treeviewstruct .= '<li id="'.$keyname.'">'.$keyval.'<ul>';
	foreach($aryval as $keysection => $treeval){
		if(!empty(@$treeval['sentences'])){ 
			$sen = base64_encode(json_encode(@$treeval['sentences']));
		} else { 
			$sen = 'null';
		}
		if (in_array($treeval['term'], $checktag)) {
   			$keytermtype	= 'Checktag';
			$classname		= 'chktag';
			$keyhintname	= '<span style="display:none;">'.$treeval['term'].'</span><div class="label label-warning btn-xxs">'.$keytermtype.'</div>';
		} else if ($treeval['termType'] == 'MED') {
			$keytermtype 	= $treeval['termType'];
			$classname		= $treeval['termType'];
			$keyhintname	= '<span style="display:none;">'.$treeval['term'].'</span><div class="label label-info btn-xxs">'.$keytermtype.'</div>';
		} else if ($treeval['termType'] == 'DIS') {
			$keytermtype 	= $treeval['termType'];
			$classname		= $treeval['termType'];
			$keyhintname	= '<span style="display:none;">'.$treeval['term'].'</span><div class="label label-success btn-xxs">'.$keytermtype.'</div>';
		}else if ($treeval['termType'] == 'DRG') {
			$keytermtype 	= $treeval['termType'];
			$classname		= $treeval['termType'];
			$keyhintname	= '<span style="display:none;">'.$treeval['term'].'</span><div class="label label-primary btn-xxs">'.$keytermtype.'</div>';
		}
			
	$treeviewstruct .='<li id="'.$key.'_'.$keysection.'" class="class'.$classname.'"><span onclick="passsentences(\''.$sen.'\',\''.$treeval['term'].'\',\''.$treeval['termType'].'\',\''.$treeval['score'].'\')" id="treevalterm">'.$treeval['term'].'&nbsp;&nbsp;'.$keyhintname.'</span>';
		 if(!empty($treeval['links'])){
		 	foreach($treeval['links'] as $keylink => $keyterm){
				$treeviewstruct .= '<ul>';
				if(!empty(@$keyterm['sentences'])){ 
					$sent = base64_encode(json_encode(@$keyterm['sentences']));
				} else { 
					$sent = 'null';
				}
				 $treeviewstruct .='<li id="'.$key.'_'.$keysection.'_'.$keylink.'"><span onclick="passsentences(\''.$sent.'\',\''.$keyterm['term'].'\',\''.$keyterm['termType'].'\',\''.$keyterm['score'].'\')">'.$keyterm['term'].'</span>';
				 	if(!empty($keyterm['sublinks'])){
						$treeviewstruct .= '<ul>';
		 				foreach($keyterm['sublinks'] as $keysublink => $keysubterm){
							if(!empty(@$keysubterm['sentences'])){ 
								$senten = base64_encode(json_encode(@$keysubterm['sentences']));
							} else { 
								$senten = 'null';
							}
				 			$treeviewstruct .='<li id="'.$key.'_'.$keysection.'_'.$keylink.'_'.$keysublink.'"><span onclick="passsentences(\''.$senten.'\',\''.$keysubterm['term'].'\',\''.$keysubterm['termType'].'\',\''.$keysubterm['score'].'\')">'.$keysubterm['term'].'</span></li>';
						}
						$treeviewstruct .= '</ul>';
					}
					$treeviewstruct .= '</li></ul>';
			}		 
		 }
		 $treeviewstruct .= '</li>';
	}
$treeviewstruct .='</ul></li>';
}
?>
<input type="hidden" class="form-control" placeholder="jobid" name="jobid" id="jobid" value="<?php echo e($jobsourceinfo[0]->id); ?>" />
<input type="hidden" class="form-control" placeholder="pui" name="pui" id="pui" value="<?php echo e($jobsourceinfo[0]->pui); ?>" />
<input type="hidden" class="form-control" placeholder="orderid" name="orderid" id="orderid" value="<?php echo e($jobsourceinfo[0]->orderid); ?>" />
<aside class="aside-xll b-l">
  <section class="vbox">
    <section class="scrollable" id="feeds">
      <div class="slim-scroll padder" data-color="#333333" data-disable-fade-out="true" data-distance="0" data-height="auto" data-size="3px"><br />
        <div class="panel-group m-b" id="accordion2">
          <ul class="list no-style" id="responses-list">
            <li class="panel btn-primary" id="tokenize">
              <div class="panel-heading"><?php echo e(svg_image('solid/cogs')); ?> <?php echo trans('app.'.'emtreemlheader'); ?> </div>
            </li>
          </ul>
        </div>
        <div id="tree">
          <ul>
            <?php echo $treeviewstruct; ?>
          </ul>
        </div>
      </div>
    </section>
  </section>
</aside>
<aside class="b-l bg" id="">
  <section class="vbox"><br />
    <div class="panel-group m-b" id="accordion2">
      <ul class="list no-style" id="responses-list">
        <li class="panel btn-primary" id="tokenize">
          <div class="panel-heading"><?php echo e(svg_image('solid/cogs')); ?> <?php echo trans('app.'.'pdfviewer'); ?> <span class="btn btn-warning btn-xs">JobID: <?php echo e($jobsourceinfo[0]->id); ?></span> &nbsp;&nbsp; <span class="btn btn-warning btn-xs">PUI: <?php echo e($jobsourceinfo[0]->pui); ?></span> </div>
        </li>
      </ul>
    </div>
    <div id="results" class="hidden"></div>
    <div id="pdf"></div>
    <script src="<?php echo e(getAsset('js/pdfobject.min.js')); ?>"></script>
    <script>
		var options = {
			pdfOpenParams: {
				navpanes: 0,
				toolbar: 1,
				statusbar: 0,
				view: "FitV",
				search: 'Acceptability',
				page: 1
			},
			forcePDFJS: true,
			PDFJS_URL: "<?php echo e(get_option('site_url')); ?>js/pdfjs/web/viewer.html"
		};
		
		var myPDF = PDFObject.embed("<?php echo e(get_option('site_url')); ?>../storage/app/uploads/projects/<?php echo e($jobsourceinfo[0]->filename); ?>", "#pdf", options);
		var el = document.querySelector("#results");
	</script>
    <br />
    <br />
    <br />
  </section>
</aside>

<aside class="aside-lg b-l">
<section class="vbox">
<section class="scrollable">
<div class="slim-scroll padder" data-color="#333333" data-disable-fade-out="true" data-distance="0" data-height="auto" data-size="3px">
<br />
<div class="panel-group m-b" id="accordion2">
  <ul class="list no-style" id="responses-list">
    <li class="panel btn-primary" id="tokenize">
      <div class="panel-heading"><?php echo e(svg_image('solid/cogs')); ?> <?php echo trans('app.'.'sentences'); ?> </div>
    </li>
  </ul>
</div>
<div id="sentencesfeeds">
  <div id="preloader"><i class="fas fa-spin fa-spinner"></i> Loading...</div>
</div>
</section>
</section>
</aside>


<?php $__env->startPush('pagescript'); ?>
<script>
$("#preloader").hide();		
function passsentences(selectedval,term,termType,score){
   if(selectedval !='') {
		$("#preloader").show();	
		axios.post('<?php echo e(get_option('site_url')); ?>api/v1/indexings/ajax/esvsentences', {
			selectterm: selectedval,
			term: term,
			termType: termType,
			score: score
			
		})
		.then(function (response) {
			$('#sentencesfeeds').html(response.data.message); 
			$("#preloader").hide();	
			<!--toastr.success(response.data.message, '<?php echo trans('app.'.'success'); ?> ');-->
		})
		.catch(function (error) {
			var errors = error.response.data.errors;
			var errorsHtml= '';
			$.each( errors, function( key, value ) {
				errorsHtml += '<li>' + value[0] + '</li>';
			});
			toastr.error( errorsHtml , '<?php echo trans('app.'.'response_status'); ?> ');
		});
	}
}
jQuery(document).ready(function(){	

});

jQuery(window).on('load', function() {
	var open = false;
	toggle(open);
});

function toggle(open){
   if(open){
    $("#tree").jstree('close_all');
    open = false;
   } else{
    $("#tree").jstree('open_all');
    open = true;
   }
}
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.indexersourceapp', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>