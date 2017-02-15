angular.module('game').directive('tacticalPane', ['$http', 'piecesService', 'boardService', function($http, piecesService, boardService) {
    return {
        restrict: 'E',
        scope: {
            board: '@'
        },
        templateUrl: "directives/tactical-pane/template.html",
        link: function(scope) {
            scope.open = false;
			scope.tile = null;
			scope.$on('tactical.show', function(event, tile) {
				scope.open = true;
				scope.tile = tile;
			});

			scope.$on('tactical.add', function(event, piece) {
				if(scope.piecesToMove.indexOf(piece) == -1) {
					scope.piecesToMove.push(piece);
				}
			});

			scope.createOrder = function() {
				var order = {
					tile: scope.tile.id,
					pieces: scope.piecesToMove.map(function(item) { return item.id}),
					newPieces: scope.piecesToBuild.map(function(item) { return item.id}),
				};

				$http.post('/order/1/place/tactical', order).then(function(response) {
					console.log(response);
				},function(error) {
					console.log(error);
				})
			}

			scope.piecesToMove = [];
			scope.piecesToBuild = [];
			scope.buildablePieces = [];
        }
    }
}]);