angular.module('game').directive('tile', ['$http', 'boardService', 'piecesService', '$rootScope', 'activePlayerService', 'orderService', 'turnService',
	function($http, boardService, piecesService, $rootScope, activePlayerService, orderService, turnService) {
    return {
        restrict: 'E',
        scope: {
            coords: '=?',
			id: '=?'
        },
        templateUrl: "directives/tile/template.html",
        link: function(scope) {

			scope.$watch(function() {
				return turnService.getCurrentTurn();
			}, function() {
                if(scope.coords) {
                    scope.tile = boardService.getTileByCoordinatesAndTurn(scope.coords, turnService.getCurrentTurn());
                }
                else if(scope.id) {
                    scope.tile = boardService.getTileByIdAndTurn(scope.id, turnService.getCurrentTurn());
                }
                else {
                    throw Error("Tile directive should receive either coords or id.");
                }

                console.log(scope.tile);
                scope.planet = piecesService.getPlanetForTileAndTurn(scope.tile, turnService.getCurrentTurn());
                scope.currentOrder = orderService.getCurrentOrderForTileAndPlayer(scope.tile, 1);
                scope.previousOrders = orderService.getPreviousActivityForTile(scope.tile);
			});

            scope.clicked = function() {
                $rootScope.$broadcast('entity.clicked', 'tile', scope.tile);
            }
		}
    }
}]);
