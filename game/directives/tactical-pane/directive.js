angular.module('game').directive('tacticalPane', ['$http', 'piecesService', 'boardService', '$rootScope', function($http, piecesService, boardService, $rootScope) {
    return {
        restrict: 'E',
        scope: {
            board: '@'
        },
        templateUrl: "directives/tactical-pane/template.html",
        link: function(scope) {
        	var player = 1;

            scope.open = false;
			scope.tile = null;
			scope.color = '#ff0000';
			scope.$on('tactical.show', function(event, tile) {
				scope.open = true;
				scope.tile = tile;

				$http.get('/order/tactical/' + player + '/' + scope.tile.id + '/moveable').then(function(response) {
					scope.moveablePieces = response.data;
				});
                $http.get('/order/tactical/' + player + '/' + scope.tile.id + '/buildable').then(function(response) {
                	scope.buildablePieces = response.data;
                });
			});

			scope.$on('tactical.add', function(event, piece) {
				if(scope.piecesToMove.indexOf(piece) == -1) {
					scope.piecesToMove.push(piece);
				}
			});

			scope.$on('tactical.cancel', function(event) {
				scope.open = false;
				scope.tile = undefined;
			});

			scope.$on('tactical.produce', function(event, pieceType) {
				scope.piecesToBuild.push(pieceType);
			});

			scope.createOrder = function() {
				var order = {
					tile: scope.tile.id,
					pieces: scope.piecesToMove.map(function(item) { return item.id}),
					newPieces: scope.piecesToBuild.map(function(item) { return item.id}),
				};

				$http.post('/order/' + player + '/place/tactical', order).then(function(response) {
					console.log(response);
				},function(error) {
					console.log(error);
				})
			};

			// ugly. this should not know about rootscope / game.mode
			scope.close = function() {
				$rootScope.$broadcast('game.mode', 'details');
                $rootScope.$broadcast('tactical.cancel');
			}

			scope.piecesToMove = [];
			scope.piecesToBuild = [];
			scope.buildablePieces = [];
        }
    }
}]);
