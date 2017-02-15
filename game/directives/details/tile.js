angular.module('game').controller('tileDetailsController', ['$http', 'activePlayerService', '$scope', function($http, activePlayerService, $scope) {
	var controller = this;
	var tile = $scope.data;

	if( activePlayerService.tileHasOrder(tile) ) {
	}
	else {
		controller.showCreateOrder = true;
	}

	controller.createOrder = function() {
		console.log('emitting');
		$scope.$emit('game.mode', 'tactical', tile);
	}

}]);