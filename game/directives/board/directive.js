angular.module('game').directive('board', ['$http', 'piecesService', 'boardService', function($http, piecesService, boardService) {
    return {
        restrict: 'E',
        scope: {
            board: '@'
        },
        templateUrl: "directives/board/template.html",
        link: function(scope) {
        }
    }
}]);