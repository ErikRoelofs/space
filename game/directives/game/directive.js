angular.module('game').directive('game', ['$timeout', '$http', 'boardService', 'piecesService', 'pieceTypesService', 'playersService', 'historyService', 'orderService', function ($timeout, $http, boardService, piecesService, pieceTypesService, playersService, historyService, orderService) {
    return {
        restrict: 'E',
        scope: {

        },
        templateUrl: "directives/game/template.html",
        link: function(scope) {

            $http.get('/game/1').then(function(response) {

                var currentTurn = response.data.turns.length - 1;

                boardService.setBoard(response.data.turns[currentTurn]);

                var pieces = [];
                angular.forEach( response.data.turns[currentTurn].tiles, function(tile) {
                    angular.forEach( tile.pieces, function(piece) {
                        pieces.push(piece);
                    })
                });
                piecesService.setAllPieces(pieces);
                pieceTypesService.setAllPieceTypes(response.data.pieceTypes);
                playersService.setPlayers(response.data.players);
				historyService.setHistory(response.data.turns.map(function(item) { return item.logs}));
				orderService.setOrders(response.data.turns.map(function(item) { return item.orders}));
            });

            scope.ready = false;
            $timeout(function() {
                scope.ready = true;
            }, 500);
        }
    }
}]);
