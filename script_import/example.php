<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<link href="//netdna.bootstrapcdn.com/bootstrap/3.1.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<script src="//netdna.bootstrapcdn.com/bootstrap/3.1.0/js/bootstrap.min.js"></script>
<script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
<!------ Include the above in your HEAD tag ---------->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Untitled Document</title>
<style>
/* CSS REQUIRED */
.state-icon {
    left: -5px;
}
.list-group-item-primary {
    color: rgb(255, 255, 255);
    background-color: rgb(66, 139, 202);
}

/* DEMO ONLY - REMOVES UNWANTED MARGIN */
.well .list-group {
    margin-bottom: 0px;
}

</style>
<script>
$(function () {
    $('.list-group.checked-list-box .list-group-item').each(function () {
        
        // Settings
        var $widget = $(this),
            $checkbox = $('<input type="checkbox" class="hidden" />'),
            color = ($widget.data('color') ? $widget.data('color') : "primary"),
            style = ($widget.data('style') == "button" ? "btn-" : "list-group-item-"),
            settings = {
                on: {
                    icon: 'glyphicon glyphicon-check'
                },
                off: {
                    icon: 'glyphicon glyphicon-unchecked'
                }
            };
            
        $widget.css('cursor', 'pointer')
        $widget.append($checkbox);

        // Event Handlers
        $widget.on('click', function () {
            $checkbox.prop('checked', !$checkbox.is(':checked'));
            $checkbox.triggerHandler('change');
            updateDisplay();
        });
        $checkbox.on('change', function () {
            updateDisplay();
        });
          

        // Actions
        function updateDisplay() {
            var isChecked = $checkbox.is(':checked');

            // Set the button's state
            $widget.data('state', (isChecked) ? "on" : "off");

            // Set the button's icon
            $widget.find('.state-icon')
                .removeClass()
                .addClass('state-icon ' + settings[$widget.data('state')].icon);

            // Update the button's color
            if (isChecked) {
                $widget.addClass(style + color + ' active');
            } else {
                $widget.removeClass(style + color + ' active');
            }
        }

        // Initialization
        function init() {
            
            if ($widget.data('checked') == true) {
                $checkbox.prop('checked', !$checkbox.is(':checked'));
            }
            
            updateDisplay();

            // Inject the icon if applicable
            if ($widget.find('.state-icon').length == 0) {
                $widget.prepend('<span class="state-icon ' + settings[$widget.data('state')].icon + '"></span>');
            }
        }
        init();
    });
    
    $('#get-checked-data').on('click', function(event) {
        event.preventDefault(); 
        var checkedItems = {}, counter = 0;
        $("#check-list-box li.active").each(function(idx, li) {
            checkedItems[counter] = $(li).text();
            counter++;
        });
        $('#display-json').html(JSON.stringify(checkedItems, null, '\t'));
    });
});
</script>
</head>

<body>


<div class="container" style="margin-top:20px;">
	<div class="row">
        
        <div class="col-xs-6">
            <h3 class="text-center">Colorful Example</h3>
            <div class="well" style="max-height: 300px;overflow: auto;">
			<h3 class="text-center">Colorful Example</h3>
            	<ul id="check-list-box" class="list-group checked-list-box">
                  <li class="list-group-item">Cras justo odio</li>
                  <li class="list-group-item" data-color="success">Dapibus ac facilisis in</li>
                  <li class="list-group-item" data-color="info">Morbi leo risus</li>
                  <li class="list-group-item" data-color="warning">Porta ac consectetur ac</li>
                  <li class="list-group-item" data-color="danger">Vestibulum at eros</li>
				  <li class="list-group-item1">Vestibulum at eros</li>
                </ul>
				<h3 class="text-center">Colorful Example</h3>
            	<ul id="check-list-box" class="list-group checked-list-box">
                  <li class="list-group-item">Cras justo odio</li>
                  <li class="list-group-item" data-color="success">Dapibus ac facilisis in</li>
                  <li class="list-group-item" data-color="info">Morbi leo risus</li>
                  <li class="list-group-item" data-color="warning">Porta ac consectetur ac</li>
                  <li class="list-group-item" data-color="danger">Vestibulum at eros</li>
                </ul>
                <button class="btn btn-primary col-xs-12" id="get-checked-data">Get Checked Data</button>
            </div>
            <pre id="display-json"></pre>
        </div>
	</div>
    
</div>
</body>
</html>
