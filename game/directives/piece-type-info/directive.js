angular.module('game').directive('pieceTypeInfo', ['$http', 'pieceTypesService', 'playersService', '$rootScope', function($http, pieceTypesService, playersService, $rootScope) {
    return {
        restrict: 'E',
        replace: true,
        scope: {
            pieceType: '=',
            color: '=?'
        },
        templateUrl: "directives/piece-type-info/template.html",
        link: function(scope) {

			scope.hasTrait = function(name) {
				return scope.pieceType.traits[name] !== undefined;
			};
			scope.getTraitValue = function(name) {
				return scope.pieceType.traits[name];
			};

        }

    }
}]);
