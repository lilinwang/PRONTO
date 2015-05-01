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
	<script type="text/javascript" src="js/app.js"></script>
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
					angular.element(document.getElementById('wholepage')).scope().construct_categories();
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

<body ng-controller="PanelController as panel" ng-init="panel.construct_categories()" id="wholepage">
<script>

</script>
    <div id="wrapper">

        <!-- Navigation -->
        <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="home">Radiology Protocols</a>
            </div>
            <!-- /.navbar-header -->
			<ul class="nav navbar-top-links navbar-left">
				<li ng-repeat="section in sections" ng-class="{ active: panel.isSelected(section)}">
					<a href ng-click="panel.select(section)">{{section}}</a> 
				</li>					
			</ul>
			
            <ul class="nav navbar-top-links navbar-right">                      			
				<button class="btn btn-default" ng-click="panel.selectAllProtocols()" type="button">
					Show all Protocols
				</button>
				<button class="btn btn-default" ng-click="panel.deleteAllProtocol()" type="button">
					Delete all Protocols
				</button>
				<li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                        <i class="fa fa-user fa-fw"></i>  <i class="fa fa-caret-down"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-user">                        
                        <li><a href="/radiology/logout"><i class="fa fa-sign-out fa-fw"></i> Logout</a>
                        </li>
                    </ul>
                    <!-- /.dropdown-user -->
                </li>
                <!-- /.dropdown -->
            </ul>
            <!-- /.navbar-top-links -->
					
            <div class="navbar-default sidebar" role="navigation">				
                <div class="sidebar-nav navbar-collapse">					
                    <ul class="nav" id="side-menu">
											
                        <li class="sidebar-search">
                            <div class="input-group custom-search-form">
                                <input type="text" ng-model="search_key" ng-keyup="$event.keyCode == 13 && panel.searchprotocols()" class="form-control" placeholder="Search...">
                                <span class="input-group-btn">
                                    <button class="btn btn-default" ng-click="panel.searchprotocols()" type="button">
                                        <i class="fa fa-search"></i>
                                    </button>
                                </span>
                            </div>
                            <!-- /input-group -->
                        </li>						
						
                        <li ng-repeat="category in categories" >
							<a href ng-show="category.level==0" ng-click="panel.selectprotocols(category.name)" ><i class="fa fa-wrench fa-fw"></i> {{category.show_name}}</a>
							<a href ng-show="category.level==1" ng-click="panel.selectprotocols(category.name)" style="padding-left:60px;">{{category.show_name}}</a>
							<a href ng-show="category.level==2" ng-click="panel.selectprotocols(category.name)" style="padding-left:80px;">{{category.show_name}}</a>
							<a href ng-show="category.level==3" ng-click="panel.selectprotocols(category.name)" style="padding-left:100px;">{{category.show_name}}</a>
						</li>
              
                    </ul>
                </div>
                <!-- /.sidebar-collapse -->
            </div>
            <!-- /.navbar-static-side -->
        </nav>

        <!-- Page Content -->
        <div id="page-wrapper" name="home">
			<div ng-show="panel.isSelected('Home')">				 
			<div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Home</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>            
            <!-- /.row -->
			<div class="row">
                <div class="col-lg-12">
				Welcome to the radiology.com web site. This is the world’s first site that can convert MR & CT scanning sequence parameters in machine language directly into an easily readable format, and allow you to download a file to import directly into your own scanner without the time consuming practice of entering every sequence manually. Please visit our about page for more information. We are currently adding new protocols to this site in every category.
				</div>
			</div>
			</div>
					
			
			<div ng-show="panel.isSelected('Import')">	
				<div class="row">
					<div class="col-lg-12">
						<h1 class="page-header">Import</h1>
					</div>
					<!-- /.col-lg-12 -->
				</div>            
				<!-- /.row -->
				
				<div class="row">
					<div class="col-lg-12">
						<div  class="input-group" style="width:500px;margin-left:15px;height:50px;">
							<span class="input-group-btn">
								<span class="input-group-btn">
								<input type="file" class="filestyle" data-buttonText="Choose csv file" id="userfile" name='userfile' >
								</span>
                                <span class="input-group-btn">
									
                                    <button class="btn btn-default"  onclick="upload()" name="submit" type="button" id="upload-icon">
                                        <i class="fa fa-upload"></i>
                                    </button>
                                </span>
							</span>
                        </div>									
						<div id='import-result'></div>
					</div>
				</div>
			</div>
			
			
			<div ng-show="panel.isSelected('Export')">	
				<div class="row">
					<div class="col-lg-12">
						<h1 class="page-header">Export</h1>
					</div>
					<!-- /.col-lg-12 -->
				</div>            
				<!-- /.row -->
				
				<div class="row">
                <div class="col-lg-6 export">
				<div class="checkbox exportbox" ng-repeat="option in categories">	
					<div ng-show="option.name[0]=='C'">
					<label ng-show="option.level==0" >
						<input type="checkbox" ng-model="option.checked"> 
						{{option.show_name}}
					</label>									
					<label ng-show="option.level==1" style="padding-left:60px;">
						<input type="checkbox" ng-model="option.checked"> 
						{{option.show_name}}
					</label>
					<label ng-show="option.level==2" style="padding-left:100px;">
						<input type="checkbox" ng-model="option.checked"> 
						{{option.show_name}}
					</label>
					<label ng-show="option.level==3" style="padding-left:140px;">
						<input type="checkbox" ng-model="option.checked"> 
						{{option.show_name}}
					</label>
					</div>
				</div>
				
				<div id='export-result'></div>
				<button class="btn btn-default" type="button" ng-click="export_data('CT')">
					Export CT
				</button>	
				<button class="btn btn-default" type="button" ng-click="check_all('CT',true)">
					Check all CT
				</button>	
				<button class="btn btn-default" type="button" ng-click="check_all('CT',false)">
					Uncheck all CT
				</button>	
				</div>
				
				
				<div class="col-lg-6 export">
				<div class="checkbox exportbox" ng-repeat="option in categories">	
					<div ng-show="option.name[0]=='M'">
					<label ng-show="option.level==0" >
						<input type="checkbox" ng-model="option.checked"> 
						{{option.show_name}}
					</label>									
					<label ng-show="option.level==1" style="padding-left:60px;">
						<input type="checkbox" ng-model="option.checked"> 
						{{option.show_name}}
					</label>
					<label ng-show="option.level==2" style="padding-left:100px;">
						<input type="checkbox" ng-model="option.checked"> 
						{{option.show_name}}
					</label>
					<label ng-show="option.level==3" style="padding-left:140px;">
						<input type="checkbox" ng-model="option.checked"> 
						{{option.show_name}}
					</label>
					</div>
				</div>
				<div id='export-result'></div>
				<button class="btn btn-default" type="button" ng-click="export_data('MR')">
					Export MR
				</button>
				<button class="btn btn-default" type="button" ng-click="check_all('MR',true)">
					Check all MR
				</button>	
				<button class="btn btn-default" type="button" ng-click="check_all('MR',false)">
					Uncheck all MR
				</button>	
				</div>
				</div>
			</div>
			
				
			
			<div ng-show="panel.isSelected('Protocols')">				 
			<div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Protocols</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>       
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Selected protocols
                        </div>
						
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover" id="dataTables-protocol">
                                    <thead>
                                        <tr>											
                                            <th>Protocol Name</th>
											<th>Protocol Category</th>
											<th>Indications</th>		
											
										</tr>
                                    </thead>
                                    <tbody>										 																			
                                        <tr class="odd gradeX showmore" ng-repeat="protocol in protocols" >											
                                            <td style="cursor: pointer" ng-click="panel.showDetailedProtocol(protocol['Protocol ID'],protocol['Protocol Category'])" >{{protocol['Protocol Name']}}</td>
											<td style="cursor: pointer" ng-click="panel.showDetailedProtocol(protocol['Protocol ID'],protocol['Protocol Category'])" >{{protocol['Protocol Category']}}</td>                                            											
											<td style="cursor: pointer" ng-click="panel.showDetailedProtocol(protocol['Protocol ID'],protocol['Protocol Category'])" >{{protocol['Indications']}}</td>
                                        </tr>                                       
                                    </tbody>
                                </table>
                            </div>
                            <!-- /.table-responsive -->  							
                        </div>
						
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
			</div>	
						
		 <div class="row" ng-show="panel.isSelected('DetailedProtocol')">
							 
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
											<th>Permalink</th>
										</tr>
                                    </thead>
                                    <tbody>										 																			
                                        <tr class="odd gradeX" ng-repeat="protocol in protocols">										
											<td>{{protocol['Protocol Name']}}</td>
											<td>{{protocol['Protocol Category']}}</td>                                            											
											<td>{{protocol['Indications']}}</td>  
											<td><a target="_blank" href="mylist?protocolID={{protocol['Protocol ID']}}&Category={{protocol['Protocol Category']}}"><?php echo base_url();?>mylist?protocolID={{protocol['Protocol ID']}}&Category={{protocol['Protocol Category']}}</a></td>                          								                                       
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
							<span class="btn btn-default" ng-click="panel.goBack()" type="button">
								<< Go Back
							</span>
						<p>
							<ul class="nav" >                                                                                            									 
								<li ng-repeat="serie in series">
									<a style="font-size:18px" ng-click="panel.showSeries(serie)"> {{serie['Series']}}<span class="fa arrow"></span></a>									
									<ul class="nav series" ng-show="serie.show && detail_protocol_category[0]=='C'" >																				                                                                                                                                                                                                                                                                    
										<li><h4>Orientation</h4>{{serie['Orientation']}}</li>																				
                                        										
										<li><h4>Intravenous Contrast</h4>{{serie['Intravenous Contrast']}}</li>
										<li><h4>Oral Contrast</h4>{{serie['Oral Contrast']}}</li>	
										<li><h4>Scout</h4>{{serie['Scout']}}</li>
										
										<li><h4>Scanning Mode</h4>{{serie['Scanning Mode']}}</li>
										
										<li><h4>Range/Direction</h4>{{serie['Range/Direction']}}</li>
										
										<li><h4>Gantry Angle</h4>{{serie['Gantry Angle']}}</li>
										
										<li><h4>Algorithm</h4>{{serie['Algorithm']}}</li>
										
										<li><h4>Beam Collimation / Detector Configuration</h4>{{serie['Beam Collimation / Detector Configuration']}}</li>    
										
										<li><h4>Slice Thickness</h4>{{serie['Slice Thickness']}}</li>
										
										<li><h4>Interval</h4>{{serie['Interval']}}</li>
										
										<li><h4>Table Speed (mm/rotation)</h4>{{serie['Table Speed (mm/rotation)']}}</li>
										
										<li><h4>Pitch</h4>{{serie['Pitch']}}</li>  
										
										<li><h4>kVp</h4>{{serie['kVp']}}</li>
																				
										<li><h4>mA</h4>{{serie['mA']}}</li>

										<li><h4>Noise Index</h4>{{serie['Noise Index']}}</li>				
										<li><h4>Noise Reduction</h4>{{serie['Noise Reduction']}}</li>
										
										<li><h4>Rotation Time</h4>{{serie['Rotation Time']}}</li>
										
										<li><h4>Scan FOV</h4>{{serie['Scan FOV']}}</li>  
										
										<li><h4>Display FOV</h4>{{serie['Display FOV']}}</li>
										<li><h4>Scan Delay</h4>{{serie['Scan Delay']}}</li>
										<li><h4>Post Processing</h4>{{serie['Post Processing']}}</li>
										
										<li><h4>Transfer Images</h4>{{serie['Transfer Images']}}</li>
										
										<li><h4>Notes</h4>{{serie['Notes']}}</li>  
										<li><h4>CTDI</h4>{{serie['CTDI']}}</li>
									</ul>
									<ul class="nav series" ng-show="serie.show && detail_protocol_category[0]=='M'" >																				                                                                                                                                                                                                                                                                    
										<li><h4>Pulse Sequence</h4>{{serie['Pulse Sequence']}}</li>
										
										<li><h4>Plane</h4>{{serie['Plane']}}</li>
                                        										
										<li><h4>Imaging Mode</h4>{{serie['Imaging Mode']}}</li>
										
										<li><h4>Sequence Description</h4>{{serie['Sequence Description']}}</li>
										
										<li><h4>Localization</h4>{{serie['Localization']}}</li>																				
										
										<li><h4>FOV</h4>{{serie['FOV']}}</li>
										
										<li><h4>MATRIX (1.5T)</h4>{{serie['MATRIX (1.5T)']}}</li>
										
										<li><h4>MATRIX (3T)</h4>{{serie['MATRIX (3T)']}}</li>
										
										<li><h4>NEX</h4>{{serie['NEX']}}</li>
										
										<li><h4>Bandwidth</h4>{{serie['Bandwidth']}}</li>
										
										<li><h4>THK/SPACE</h4>{{serie['THK/SPACE']}}</li>
										
										<li><h4>Sequence options</h4>{{serie['Sequence options']}}</li>
										
										<li><h4>Injection options</h4>{{serie['Injection options']}}</li>
										
										<li><h4>Time</h4>{{serie['Time']}}</li>   
										
									</ul>
                            <!-- /.nav-second-level -->
								</li>
							</ul>
					</div>
					<!--/.panel-body-->                   
                </div>
                <!-- /.col-lg-12 -->
			</div>
			
			<button class="btn btn-default" ng-click="panel.deleteprotocol()" type="button">
				DELETE PROTOCOL
            </button>
			<button class="btn btn-default" ng-click="panel.export_one_protocol()" type="button">
				EXPORT PROTOCOL
            </button>
			<p id='result'></p>
		</div>	
		
		
		<div class="row" ng-show="panel.isSelected('History')">
			<div class="row" style="margin-top:15px; margin-bottom:15px;">
                <div class="span5 col-md-5">
					<div class="input-daterange input-group" id="datepicker">
						<input id="datepicker_start" data-date-format="yyyy-mm-dd" ng-model="history_start" type="text" class="input-sm form-control" name="start" value="02/08/2015">
						<script>
						$("#datepicker_start").datepicker("setDate", new Date());
						</script>
						<span class="input-group-addon">to</span>
						<input id="datepicker_end" data-date-format="yyyy-mm-dd" ng-model="history_end" type="text" class="input-sm form-control" name="end" >
						<script>
						$("#datepicker_end").datepicker("setDate", new Date());
						</script>																	
					</div>					
				</div>
				<button class="btn btn-default"  type="button" ng-click="panel.showHistory()">Show History</button>
            </div>    
			
			<div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            History of protocols
                        </div>
						<div class="form-group row" style="margin-top:15px;margin-left:15px;margin-bottom:-15px;">							
							<div >
								<div class="form-group row col-md-10" style="float:right;">
									<label class="col-md-1 control-label">Search:</label>
									<div class="col-md-3">
										<input ng-model="q" type="text" class="form-control" placeholder="Filter text">
									</div>
									
									<label class="col-md-2 control-label" >items per page:</label>
									<div class="col-md-2">
										<input type="number" min="1" max="100" type="text" class="form-control" ng-model="pageSize">
									</div>
									<button class="btn btn-default"  type="button" ng-click="panel.showAllHistory()">{{all_history_button}}</button>
								</div>
							</div>
						</div>
						
			
						<div class="panel-body">							
                            <div class="table-responsive">
								<table class="table table-striped table-bordered table-hover" id="dataTables-history">
									<thead>
                                        <tr>
											<th>Status</th>
                                            <th>Protocol ID</th>
                                            <th>Protocol Name</th>
                                            <th>Created By</th>											                                          									
											<th>Created At</th>
										</tr>
									</thead>
									<tbody>										 																			
                                        <tr class="odd gradeX" dir-paginate="record in records | filter:q | itemsPerPage: pageSize" current-page="currentPage" >												
											<td>{{record.status}}</td>
                                            <td>{{record['Protocol ID']}}</td>											
                                            <td>{{record['Protocol Name']}}</td>
                                            <td>{{record.created_by}}</td>
											<td>{{record.created_at}}</td>                                                                                    							
                                        </tr>                                       
									</tbody>
								</table>								
							</div>							
						</div>
						              
						<div ng-controller="OtherController" >         
							<div class="text-center">
								<dir-pagination-controls boundary-links="true" on-page-change="pageChangeHandler(newPageNumber)" template-url="dirPagination.tpl.html"></dir-pagination-controls>
							</div>
						</div>
					</div>
				</div>
			</div>
			
		</div>
			
		</div>									
    </div>
    <!-- /#wrapper -->

	
	<div id="dialog" title="Alerts">
</body>

</html>
