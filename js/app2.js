(function(){
	var app = angular.module('radiology_protocol',['angularUtils.directives.dirPagination']).config(function($locationProvider) {
		$locationProvider.html5Mode(true).hashPrefix('!');
	});	
	
	app.controller("PanelController", ['$http','$scope','$window','$location',function($http,$scope,$window,$location){
		$scope.currentPage = 1;
		$scope.pageSize = 10;
		$scope.pageChangeHandler = function(num) {
			console.log('meals page changed to ' + num);
		};
		//var pdata=this;
		$scope.all_series_button="Hide All Series";
		
		$scope.protocols=[];
		$scope.protocol_number = $location.search()['protocolID'];
		$scope.protocol_number_category=$location.search()['Category'];
		$scope.series=[];
		
		this.showAllSeries=function(){
			if ($scope.all_series_button=="Show All Series"){
				$scope.all_series_button="Hide All Series";
				for (i = 0; i < $scope.series.length; i++) { 
					$scope.series[i].show=true;
				}			
			} else {
				$scope.all_series_button="Show All Series";
				for (i = 0; i < $scope.series.length; i++) { 
					$scope.series[i].show=false;
				}	
			}
		}
		this.showSeries=function(serie){
			serie.show=!serie.show;
		}
		
		this.showDetailedProtocol=function(protocol_number){	
			console.log($location);
			console.log($scope.protocol_number);
			$scope.all_series_button="Hide All Series";
			
			$http({
				url: 'detailed_ajax/get_protocol',
				method: "POST",
				data : {number:protocol_number}
			}).success(function (data) {
				console.log(data);
				if (angular.isObject(data)){					
					$scope.protocols=data.slice(0);
				}
				else{
					//console.log(data);
					$scope.protocols=[];
				}
			}).error(
				function (data) {
				console.log(data);				
			});				
			
			$http({
				url: 'detailed_ajax/get_series',
				method: "POST",
				data : {number:protocol_number,category:'CT'}
			}).success(function (data) {
				console.log(data);
				if (angular.isObject(data)){					
					$scope.series=data.slice(0);
					//console.log($scope.series);
					for (i = 0; i < $scope.series.length; i++) { 
						$scope.series[i].show=true;
					}
					//console.log($scope.series);
				}
				else{
					//console.log(data);
					$scope.series=[];
				}
			}).error(
				function (data) {
				console.log(data);				
			});	
		};
	}]);		
	
	var base_url="radiology";
	
})();
