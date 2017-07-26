angular.module('game').directive('game', ['$rootScope', '$timeout', '$http', 'boardService', 'piecesService', 'pieceTypesService', 'playersService', 'historyService', 'orderService', 'activePlayerService', 'detailsCommand', 'tacticalCommand', 'turnService',
	function ($rootScope, $timeout, $http, boardService, piecesService, pieceTypesService, playersService, historyService, orderService, activePlayerService, detailsCommand, tacticalCommand, turnService) {
    return {
        restrict: 'E',
        scope: {

        },
        templateUrl: "directives/game/template.html",
        link: function(scope) {

            $http.get('/game/1').then(function(response) {

                boardService.setBoard(response.data.turns);

                var pieces = [];
                angular.forEach( response.data.turns, function( turn ) {
                    angular.forEach( turn.tiles, function(tile) {
                        angular.forEach( tile.pieces, function(piece) {
                            pieces.push(piece);
                        })
                    });
                } )
                piecesService.setAllPieces(pieces);

                pieceTypesService.setAllPieceTypes(response.data.pieceTypes);
                playersService.setPlayers(response.data.players);
				historyService.setHistory(response.data.turns.map(function(item) { return item.logs}));
				orderService.setOrders(response.data.turns.map(function(item) { return item.orders}));
				turnService.setLatestTurn(response.data.turns.length);
            });

			$http.get('/game/1/player/1').then(function(response) {
				activePlayerService.setData(response.data);
			});

            scope.ready = false;
            $timeout(function() {
                scope.ready = true;
            }, 500);

			var activeControl = detailsCommand;
			scope.$on('entity.clicked', function(event, toShow, data) {
				activeControl.entityClicked(toShow, data);
			});
			scope.$on('game.mode', function(event, mode, data) {
				activeControl.unload();
				if(mode=='details') {
					activeControl = detailsCommand;
				}
				else if(mode=='tactical') {
					activeControl = tacticalCommand;
				}
				activeControl.load(data);
			});

        }
    }
}]);
