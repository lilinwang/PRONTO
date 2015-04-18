<!DOCTYPE html>
<html lang="en" ng-app="radiology_protocol">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Radiology Protocols</title>
	
    <link href="css/bootstrap.css" rel="stylesheet">
    <link href="css/plugins/metisMenu/metisMenu.min.css" rel="stylesheet">    
    <link href="css/plugins/dataTables.bootstrap.css" rel="stylesheet">	
	<link href="font-awesome-4.2.0/css/font-awesome.min.css" rel="stylesheet" type="text/css">
	<link href="css/sb-admin-2.css" rel="stylesheet">
	<link rel="stylesheet" href="css/jquery-ui.css">
	<link rel="stylesheet" href="css/datepicker.css">
	<link href="css/style.css" rel="stylesheet">
    
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
	 <!-- jQuery -->
    <script src="js/jquery.js"></script>
    <script src="js/bootstrap.min.js"></script>		
	<script type="text/javascript" src="js/jquery-ui.js"></script>	
    <script type="text/javascript" src="js/bootstrap-filestyle.js"> </script>
    <script src="js/plugins/metisMenu/metisMenu.min.js"></script>
    <script src="js/sb-admin-2.js"></script>	
	<script src="js/bootbox.min.js"></script>	
	<script type="text/javascript" src="js/angular.js"></script>
	<script type="text/javascript" src="http://angular-ui.github.io/bootstrap/ui-bootstrap-tpls-0.11.0.js"></script>
	<script src="js/dirPagination.js"></script>
	<script type="text/javascript" src="js/alasql.min.js"></script>
	<script type="text/javascript" src="js/xlsx.core.min.js"></script>
	<script type="text/javascript" src="js/app2.js"></script>
	<script src="js/bootstrap-datepicker.js"></script>
	<script type="text/javascript">		   

	var opt={				
		height: 150,
        width: 250,
		autoOpen: false,		
	}
	function sentNotification(e){
		$(e).prop('disabled', true);
	}
	function upload(){						
		var file_data = $("#userfile").prop("files")[0];   		
		var fileName = $("#userfile").val();
		//var user_name = '@Session["user_name"]';
		
		if(fileName.lastIndexOf("csv")===fileName.length-3){										
			$('#upload-icon').html('<i class="fa fa-spin fa-spinner"></i>');
			var form_data = new FormData();                  
			form_data.append("file", file_data);  
			//form_data.append("username", user_name);  
			$.ajax({
                url: "ajax/upload",               
                cache: false,
                contentType: false,
                processData: false,
                data: form_data,                         
                type: 'post',
				enctype: 'multipart/form-data',
                complete: function(data){				
					console.log(data['responseText']);																                   									
					var response = JSON.parse(data['responseText']);
					//console.log(response);
					$('#upload-icon').html('<i class="fa fa-upload"></i>');					
					$("#dialog").html("<p>Import success!</p>");
					var theDialog = $("#dialog").dialog(opt);					
					var dialog = theDialog.dialog("open");
					setTimeout(function() { dialog.dialog("close"); }, 1000);
					//alert(response.length);
					//$('#import-result').html(response.length);
					$('#import-result').html("<h3>Imported protocols:</h3></br>");
					
					/*for (var i=0;i<response[0].length;i++){							
						
							$('#import-result').append("<div class='form-group row'><label class='col-md-3 control-label'>"+response[0][i]+"</label><label class='col-md-3 control-label'>"+response[1][i]+"</label><label class='col-sm-2'>"+response[2][i]+"</label><button class='btn btn-primary btn-sm' onclick='sentNotification(this)'>Send Notification</button></div>");						
					}*/
					var status=["new protocol","modified","no change"];
					for (var i=0;i<response[0].length;i++){							
						if (response[2][i]!=2){
							$('#import-result').append("<div class='form-group row'><label class='col-md-3 control-label'>"+response[0][i]+"</label><label class='col-md-3 control-label'>"+response[1][i]+"</label><label class='col-sm-2'>"+status[response[2][i]]+"</label><button class='btn btn-primary btn-sm' onclick='sentNotification(this)'>Send Notification</button></div>");
						}
					}	
					for (var i=0;i<response[0].length;i++){							
						if (response[2][i]==2){
							$('#import-result').append("<div class='form-group row'><label class='col-md-3 control-label'>"+response[0][i]+"</label><label class='col-md-3 control-label'>"+response[1][i]+"</label><label class='col-sm-2'>"+status[response[2][i]]+"</label></div>");
						}
					}
                }
			});				            			     	
		}else{
			$("#dialog").html("<p>Not csv file choosen!</p>");
			var theDialog = $("#dialog").dialog(opt);					
			var dialog = theDialog.dialog("open");
			setTimeout(function() { dialog.dialog("close"); }, 1000);
		}
	};			
	
	</script>
	
</head>

<body ng-controller="PanelController as panel" ng-init="panel.showDetailedProtocol(protocol_number)">
    <div id="wrapper">

       
        <!-- Page Content -->
        <div id="page-wrapper" name="home">
			
						
		 <div class="row">
							 
			<div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Detailed Protocol</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>       
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Protocol
                        </div>
						
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="table-responsive">
								<table class="table table-striped table-bordered table-hover" id="dataTables-detailed">
                                    <thead>
                                        <tr>
											<th>protocol Name</th>
											<th>protocol Category</th>
											<th>Indication</th>									
										</tr>
                                    </thead>
                                    <tbody>										 																			
                                        <tr class="odd gradeX" ng-repeat="protocol in protocols">										
											<td>{{protocol.protocol_name}}</td>
											<td>{{protocol.protocol_category}}</td>                                            											
											<td>{{protocol.indication}}</td>                                          									 
                                        </tr>                                       
                                    </tbody>
                                </table>
								</div>
                            <!-- /.table-responsive -->                           
                        </div>
						
                        <!-- /.panel-body -->
                    </div>
                    
						
					<div class="panel-body">					
						<p style="font-size:20px">Series
							<span class="btn btn-default" ng-click="panel.showAllSeries(this)" type="button">
								{{all_series_button}}
							</span>
						<p>
							<ul class="nav" >                                                                                            									 
								<li ng-repeat="serie in series">
									<a style="font-size:18px" ng-click="panel.showSeries(serie)"> {{serie.series_name}}<span class="fa arrow"></span></a>									
									<ul class="nav series" ng-show="serie.show && protocol_number_category[0]=='C'" >																				                                                                                                                                                                                                                                                                    
										<li><h4>Patient Orientation</h4>{{serie.patient_orientation}}</li>																				
                                        										
										<li><h4>Intravenous Contrast</h4>{{serie.intravenous_contrast}}</li>
										<li><h4>Oral Contrast</h4>{{serie.oral_contrast}}</li>	
										<li><h4>Scout</h4>{{serie.scout}}</li>
										
										<li><h4>Scanning Mode</h4>{{serie.scanning_mode}}</li>
										
										<li><h4>Range/Direction</h4>{{serie.range_direction}}</li>
										
										<li><h4>Gantry Angle</h4>{{serie.gantry_angle}}</li>
										
										<li><h4>Algorithm</h4>{{serie.algorithm}}</li>
										
										<li><h4>Beam Collimation / Detector Configuration</h4>{{serie.beam_collimation_detector_configuration}}</li>    
										
										<li><h4>Slice Thickness</h4>{{serie.slice_thickness}}</li>
										
										<li><h4>Interval</h4>{{serie.interval}}</li>
										
										<li><h4>Table Speed (mm/rotation)</h4>{{serie.table_speed}}</li>
										
										<li><h4>Pitch</h4>{{serie.pitch}}</li>  
										
										<li><h4>kVp</h4>{{serie.kvp}}</li>
																				
										<li><h4>mA</h4>{{serie.ma}}</li>

<li><h4>Noise Index</h4>{{serie.noise_index}}</li>				
										<li><h4>Noise Reduction</h4>{{serie.noise_reduction}}</li>
										
										<li><h4>Rotation Time</h4>{{serie.rotation_time}}</li>
										
										<li><h4>Scan FOV</h4>{{serie.scan_fov}}</li>  
										
										<li><h4>Display FOV</h4>{{serie.display_fov}}</li>
										<li><h4>Scan Delay</h4>{{serie.scan_delay}}</li>
										<li><h4>Post Processing</h4>{{serie.post_processing}}</li>
										
										<li><h4>Transfer Images</h4>{{serie.transfer_images}}</li>
										
										<li><h4>Notes</h4>{{serie.notes}}</li>  
										<li><h4>CTDI</h4>{{serie.CTDI}}</li>
									</ul>
									<ul class="nav series" ng-show="serie.show && detail_protocol_category[0]=='M'" >																				                                                                                                                                                                                                                                                                    
										<li><h4>Pulse Sequence</h4>{{serie.pulse_sequence}}</li>
										
										<li><h4>Plane</h4>{{serie.plane}}</li>
                                        										
										<li><h4>Imaging Mode</h4>{{serie.imaging_mode}}</li>
										
										<li><h4>Sequence Description</h4>{{serie.sequence_description}}</li>
										
										<li><h4>Fov</h4>{{serie.fov}}</li>
										
										<li><h4>Matrix 1.5t</h4>{{serie.matrix_15t}}</li>
										
										<li><h4>Matrix 3t</h4>{{serie.matrix_3t}}</li>
										
										<li><h4>Thk/Space</h4>{{serie.thk_space}}</li>
										
										<li><h4>Time</h4>{{serie.time}}</li>    										
									</ul>
                            <!-- /.nav-second-level -->
								</li>
							</ul>
					</div>
					<!--/.panel-body-->                   
                </div>
                <!-- /.col-lg-12 -->
			</div>
						
		</div>	
		
		
			
		</div>									
    </div>
    <!-- /#wrapper -->

	
</body>

</html>
