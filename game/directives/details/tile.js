angular.module('game').controller('tileDetailsController', ['$http', 'activePlayerService', '$scope', 'turnService', '$rootScope', function($http, activePlayerService, $scope, turnService, $rootScope) {
	var controller = this;
	var tile = $scope.data;

	if( activePlayerService.tileHasOrder(tile) || !turnService.showingLastTurn() ) {
	}
	else {
		controller.showCreateOrder = true;
	}

	controller.createOrder = function() {
		$rootScope.$broadcast('game.mode', 'tactical', tile);
	}

}]);
