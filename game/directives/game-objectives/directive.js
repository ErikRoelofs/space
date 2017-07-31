angular.module('game').directive('gameObjectives', ['objectiveService', 'playersService', function(objectiveService, playersService) {
    return {
        restrict: 'E',
        scope: {
            game: '@'
        },
        templateUrl: "directives/game-objectives/template.html",
        link: function(scope) {
            scope.objectives = objectiveService.getObjectives();

            scope.players = playersService.getPlayers();
        }

    }
}]);
