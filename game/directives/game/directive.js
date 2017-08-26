angular.module('game').directive('game', ['$rootScope', '$timeout', '$http', 'boardService', 'piecesService', 'pieceTypesService', 'playersService', 'historyService', 'orderService', 'activePlayerService', 'detailsCommand', 'tacticalCommand', 'turnService', 'objectiveService', 'gameService',
	function ($rootScope, $timeout, $http, boardService, piecesService, pieceTypesService, playersService, historyService, orderService, activePlayerService, detailsCommand, tacticalCommand, turnService, objectiveService, gameService) {
    return {
        restrict: 'E',
        scope: {

        },
        templateUrl: "directives/game/template.html",
        link: function(scope) {

            $http.get('/game/' + $rootScope.gameId).then(function(response) {

                $rootScope.playerId = response.data.myPlayerId;

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
				turnService.setTurns(response.data.turns);
                objectiveService.setObjectives(response.data.objectives, response.data.claimedObjectives);
                gameService.setGame(response.data);

                $http.get('/game/' + $rootScope.gameId + '/player/' + $rootScope.playerId).then(function(response) {
                    activePlayerService.setData(response.data);
                });

            }, function(errorResponse) {
                if(errorResponse.status === 401) {
                    window.location = '/game/login.html';
                }
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
