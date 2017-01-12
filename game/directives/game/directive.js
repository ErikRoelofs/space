angular.module('game').directive('game', ['$timeout', '$http', 'boardService', 'piecesService', 'pieceTypesService', 'playersService', function ($timeout, $http, boardService, piecesService, pieceTypesService, playersService) {
    return {
        restrict: 'E',
        scope: {

        },
        templateUrl: "directives/game/template.html",
        link: function(scope) {

            $http.get('/board/1/tiles').then(function(response) {
                boardService.setBoard(response.data);
            });
            $http.get('/board/1/pieces').then(function(response) {
                piecesService.setAllPieces(response.data);
            });
            $http.get('/game/1/settings').then(function(response) {
                pieceTypesService.setAllPieceTypes(response.data.pieceTypes);
                playersService.setPlayers(response.data.players);
            });

            scope.ready = false;
            $timeout(function() {
                scope.ready = true;
            }, 500);
        }
    }
}]);
