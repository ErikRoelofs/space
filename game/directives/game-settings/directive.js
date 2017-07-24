angular.module('game')
    .directive('gameSettings', ['$http', 'pieceTypesService', 'activePlayerService', function($http, pieceTypesService, activePlayerService) {
    return {
        restrict: 'E',
        scope: {
            game: '@'
        },
        templateUrl: "directives/game-settings/template.html",
        link: function(scope) {
            scope.color = activePlayerService.color();
            scope.pieceTypes = pieceTypesService.getAll();

            scope.hasTrait = function(pieceType, name) {
                return pieceType.traits[name] !== undefined;
            };
            scope.getTraitValue = function(pieceType, name) {
                return pieceType.traits[name];
            };

        }
    }
}])
    .directive('weaponSystem', [function() {
        return {
            restrict: 'E',
            scope: {
                trait: '=',
                name: '@'
            },
            template: '<div><span ng-if="shots > 1">{{ shots }}</span> {{ name }}, {{ power}}%</div>',
            link: function(scope) {
                scope.shots = scope.trait.shots;
                scope.power = scope.trait.firepower * 10;
            }
        }
    }]);
