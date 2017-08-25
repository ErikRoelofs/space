angular.module('game').directive('gameHistory', ['$http', 'historyService', 'turnService', function($http, historyService, turnService) {
    return {
        restrict: 'E',
        scope: {
            game: '@'
        },
        templateUrl: "directives/game-history/template.html",
        link: function(scope) {
            scope.$watch(function() {
                return turnService.getCurrentTurn();
            }, function() {
                scope.history = historyService.getHistory(turnService.getCurrentTurn() - 1);
            });

        }

    }
}]);
