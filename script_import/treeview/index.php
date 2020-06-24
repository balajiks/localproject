<?php 
include('header.php');
?>
<title>phpzag.com : Demo Create Treeview with jsTree, PHP and MySQL</title>
<link rel="stylesheet" href="jstree/style.min.css" />
<script src="jstree/jstree.min.js"></script>
<?php include('container.php');?>
<div class="container">
	<h2>Create Treeview with jsTree, PHP and MySQL</h2>	
	<div class="row">	
		<div id="tree-data-container"></div>				
	</div>	
	<div style="margin:50px 0px 0px 0px;">
		<a class="btn btn-default read-more" style="background:#3399ff;color:white" href="http://www.phpzag.com/create-treeview-with-jstree-php-and-mysql" title="Create Treeview with jsTree, PHP and MySQL">Back to Tutorial</a>			
	</div>		
</div>
<script type="text/javascript">
$(document).ready(function(){ 
     $('#tree-data-container').jstree({
	'plugins': ["wholerow", "checkbox"],
        'core' : {
            'data' : {
                "url" : "tree_data.php",
                "dataType" : "json" 
            }
        }
    }) 
});
</script>
<?php include('footer.php');?>

