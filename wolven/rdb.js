myApp = angular.module('myApp', ['ngSanitize']);

function MyCtrl($scope, $http) {
	$http.get('data.json').success(function(response) {
		// roles is an object, due to its irregular keys
		angular.forEach( response, function(role) {
			$scope.roles.push(role);
		});
		$scope.extractKeywords();
	});
    $scope.roles = [];
    $scope.keywords = {};
    $scope.extractKeywords = function() {
        angular.forEach($scope.roles, function(item) {
          angular.forEach(item.keywords, function(keyword) {
              $scope.keywords[keyword] = keyword;
          })
      });
    }    
    $scope.keywordFilter = {};

    $scope.filterOnKeywords = function(item) {
      if( $scope.noKeywords() ) {
        return true;
      }
	  var all = true;
	  angular.forEach($scope.keywordFilter, function(active, activeKeyword) {
		if(!active) return;
		var thisOne = false;  
		angular.forEach(item.keywords, function(keyword) {			
			if(keyword == activeKeyword) {
				thisOne = true;
			}
		});
		all = all && thisOne;
	  });
	
      return all;
    }

    $scope.noKeywords = function() {
      for (var key in $scope.keywordFilter) {
          if ($scope.keywordFilter[key]) {
              return false;
          }
      }
      return true;
    }
	
	$scope.clean = function() {
		$scope.filterbox = "";
		$scope.keywordFilter = {};
	}
};