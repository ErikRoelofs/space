angular.module('game').directive('gameObjectives', ['objectiveService', function(objectiveService) {
    return {
        restrict: 'E',
        scope: {
            game: '@'
        },
        templateUrl: "directives/game-objectives/template.html",
        link: function(scope) {
            scope.objectives = objectiveService.getObjectives();
        }

    }
}]);
