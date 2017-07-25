angular.module('game').directive('tile', ['$http', 'boardService', 'piecesService', '$rootScope', 'activePlayerService', 'orderService', function($http, boardService, piecesService, $rootScope, activePlayerService, orderService) {
    return {
        restrict: 'E',
        scope: {
            coords: '=?',
			id: '=?'
        },
        templateUrl: "directives/tile/template.html",
        link: function(scope) {
			if(scope.coords) {
				scope.tile = boardService.getTileByCoordinates(scope.coords);
			}
			else if(scope.id) {
				scope.tile = boardService.getTileById(scope.id);
			}
			else {
				throw Error("Tile directive should receive either coords or id.");
			}

			scope.currentOrder = orderService.getCurrentOrderForTileAndPlayer(scope.tile, 1);
			scope.previousOrders = orderService.getPreviousActivityForTile(scope.tile);
            scope.planet = piecesService.getPlanetForTile(scope.tile);
            scope.clicked = function() {
                $rootScope.$broadcast('entity.clicked', 'tile', scope.tile);
            }
		}
    }
}]);
