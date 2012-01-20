<?php
class Normalization extends CI_Controller {
	
	public function index()
	{
		
	}

	public function down_collapse_lonlat($scope = false)
	{
		if(!$scope) die('You must specify a scope. A scope is the table to down-collapse on geo table.');
		
		echo $scope.PHP_EOL;
	}
}
