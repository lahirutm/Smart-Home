<?php
require('class.db.php');
require('session.php');

$datavalues = new dataValues();
$datavalues->user_id = $iot_user_id;
$results = $db->oauth_tokens($datavalues);
if(isset($results[0]['api_key'])){
    $api_key =  $results[0]['api_key'];
    $client_id =  $results[0]['client_id'];
    $client_secret =  $results[0]['client_secret'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
        <title>IoT | webservice.lk</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
<!--===============================================================================================-->  
        <link rel="icon" type="image/png" href="images/icons/favicon.ico"/>
<!--===============================================================================================-->
        <link rel="stylesheet" type="text/css" href="vendor/bootstrap/css/bootstrap.min.css">
<!--===============================================================================================-->
        <link rel="stylesheet" type="text/css" href="vendor/animate/animate.css">
<!--===============================================================================================-->  
        <link rel="stylesheet" type="text/css" href="vendor/animsition/css/animsition.min.css">
<!--===============================================================================================-->
        <link rel="stylesheet" type="text/css" href="css/util.css">
        <link rel="stylesheet" type="text/css" href="css/main.css">
<!--===============================================================================================-->
</head>
<body>
<?php include('top_navigation.php');?>        

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="alert alert-success">
            <?php
            $datavalues = new dataValues();
            $datavalues->id = $iot_user_id;
            $results = $db->get_users($datavalues);
            if(isset($results[0]['user_name'])){
                echo "Welcome ".$results[0]['user_name']." ( ".$results[0]['email']." )";
            }
            ?>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4">
            <div class="alert alert-warning">
                <div class="panel panel-default">
                    <div class="panel-heading">API KEY</div>
                    <div class="panel-body">
                        <?php if(isset($api_key)) echo $api_key;?>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="alert alert-warning">
                <div class="panel panel-default">
                    <div class="panel-heading">CLIENT ID</div>
                    <div class="panel-body">
                        <?php if(isset($client_id)) echo $client_id;?>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="alert alert-warning">
                <div class="panel panel-default">
                    <div class="panel-heading">CLIENT SECRET</div>
                    <div class="panel-body">
                        <?php if(isset($client_secret)) echo $client_secret;?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Devices List
                    <a class="btn btn-success float-right" onclick="add_new_device();" role="button">
					<span class="glyphicon glyphicon-plus"></span> ADD NEW DEVICE
					</a>    
                </div>
                <div class="panel-body" id="devices_list">
                    
                </div>
            </div>
        </div>
    </div>
</div>



<div id="add_device_modal" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
<div class="modal-dialog">
<div class="modal-content">
	<div class="modal-header"><button type="button" class="close" data-dismiss="modal">&times;</button>
		<h4 class="modal-title"><span class="glyphicon glyphicon-retweet"></span> EDIT DEVICES</h4>
	</div>
	<div class="modal-body">
		<div class="row">
			<div class="col-md-6">
				<div class="form-group">
					<label for="name">Device Name</label>
				    <input type="text" class="form-control" name="name" id="name">
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					<label for="nickname">Nickname</label>
				    <input type="text" class="form-control" name="nickname" id="nickname">
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12">
				<div class="form-group">
					<label for="type">Device Type</label>
					<select class="form-control" name="type" id="type">
					    <option value="">- Select Type -</option>
					    <?php
					    $datavalues = new dataValues();
					    $data = $db->get_device_types($datavalues);
					    if(is_array($data) && count($data)>0){
					        foreach($data as $row){
					            echo '<option value="'.$row['id'].'">'.$row['type'].'</option>';
					        }
					    }
					    ?>
					</select>
				</div>				
			</div>
		</div>
		<div class="row">
			<div class="col-md-6">
				<div class="form-group">
					<button type="button" class="btn btn-default form-control" data-dismiss="modal"> CANCEL</button>
				</div>				
			</div>
			<div class="col-md-6">
				<div class="form-group">
					<button type="submit" class="btn btn-primary form-control" onclick="save_device();">SAVE</button>
					<input type="hidden" name="device_id" id="device_id" />
				</div>			
			</div>
		</div>
	</div>
</div>
</div>
</div>



<div id="delete_modal" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
<div class="modal-dialog">
<div class="modal-content">
	<div class="modal-header"><button type="button" class="close" data-dismiss="modal">&times;</button>
		<h4 class="modal-title"><span class="glyphicon glyphicon-retweet"></span> DELETE DEVICE</h4>
	</div>
	<div class="modal-body"></div>
	<div class="modal-footer"></div>
</div>
</div>
</div>
        
<!--===============================================================================================-->
        <script src="vendor/jquery/jquery-3.2.1.min.js"></script>
<!--===============================================================================================-->
        <script src="vendor/animsition/js/animsition.min.js"></script>
<!--===============================================================================================-->
        <script src="vendor/bootstrap/js/popper.js"></script>
        <script src="vendor/bootstrap/js/bootstrap.min.js"></script>
<!--===============================================================================================-->
        <script src="js/main.js"></script>
<script type="text/javascript">
    function add_new_device(){
        $('#device_id').val("");
        $('#name').val("");
    	$('#nickname').val("");
        $('#type').val("").change();
        $('#add_device_modal').modal();
    }
    
    function save_device(){
        var device_id = $('#device_id').val();
        var name = $('#name').val();
        var nickname = $('#nickname').val();
        var type = $('#type').val();
        $.ajax({
    		url : "ajax/save_device.php",
    		cache: false,
    		data: { 'device_id':device_id,'name':name, 'nickname':nickname, 'type':type },
    		type: 'POST',                  
    		success : function(data) {
    		    var arr = data.split("^");
    		    if(arr[0]=="success"){
    		        alert(arr[1]);
        			$('#add_device_modal').modal(hide);
        			get_devices_list();
    		    }
    		    else {
    		        console.log('Error fetching edit device ! \n' + arr[1]);
    		    }
    		},
    		error: function(data) {
    			console.log('Error fetching edit device ! \n');
    		}
    	});
    }

    function get_devices_list(){
        $.ajax({
    		url : "ajax/get_devices_list.php",
    		cache: false,
    		data: { },
    		type: 'POST',                  
    		success : function(data) {
    			$('#devices_list').html(data);
    		},
    		error: function(data) {
    			console.log('Error fetching devices list ! \n');
    		}
    	});
    }
    
    function edit_device(id){
        $.ajax({
    		url : "ajax/get_edit_device.php",
    		cache: false,
    		data: { 'id':id },
    		type: 'POST',                  
    		success : function(data) {
    		    var arr = data.split("^");
    		    if(arr[0]=="success"){
    		        $('#device_id').val(id);
        			$('#name').val(arr[1]);
        			$('#nickname').val(arr[2]);
        			$('#type').val(arr[3]).change();
        			$('#add_device_modal').modal();
    		    }
    		    else {
    		        console.log('Error fetching edit device ! \n');
    		    }
    		},
    		error: function(data) {
    			console.log('Error fetching edit device ! \n');
    		}
    	});
    }
    
    function delete_device(id){
        var msg = '<p>Are you sure, You want to delete this ?</p>';
		var btns = '<button type="button" class="btn btn-default" data-dismiss="modal">No</button>';
		btns += '<button type="button" class="btn btn-primary" data-dismiss="modal" onClick="delete_device_confirmed(\''+ id +'\')">Yes</button>';

		$('#delete_modal .modal-body').html(msg);
		$('#delete_modal .modal-footer').html(btns);
		$('#delete_modal').modal();
    }
    
    function delete_device_confirmed(id){
        $.ajax({
    		url : "ajax/delete_device.php",
    		cache: false,
    		data: { 'id':id },
    		type: 'POST',                  
    		success : function(data) {
    		    var arr = data.split("^");
    		    if(arr[0]=="success"){
    		        alert(arr[1]);
    		        get_devices_list();
    		    }
    		    else {
    		        alert('Delete device failed ! \n'+arr[1]);
    		    }
    		},
    		error: function(data) {
    			console.log('Delete device error ! \n');
    		}
    	});
    }


$(document).ready(function(){
 setInterval(get_devices_list,10000);
});

get_devices_list();
</script>
</body>
</html>
