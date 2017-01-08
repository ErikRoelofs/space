angular.module('game').directive('board', ['$http', 'piecesService', function($http, piecesService) {
    return {
        restrict: 'E',
        scope: {
            board: '@'
        },
        templateUrl: "directives/board/template.html",
        link: function(scope) {
            $http.get('/board/' + scope.board + '/tiles').then(function(response) {
                scope.board = response.data;
                console.log(scope.board);
            });
            $http.get('/board/' + scope.board + '/pieces').then(function(response) {
                scope.pieces = response.data;
                piecesService.setAllPieces(scope.pieces);
            })
        }
    }
}]);