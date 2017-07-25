angular.module('game').directive('gameHistory', ['$http', 'pieceTypesService', 'historyService', function($http, pieceTypesService, historyService) {
    return {
        restrict: 'E',
        scope: {
            game: '@'
        },
        templateUrl: "directives/game-history/template.html",
        link: function(scope) {
			scope.history = historyService.getHistory(6);
        }

    }
}]);
