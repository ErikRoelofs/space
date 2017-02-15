angular.module('game').directive('tacticalPane', ['$http', 'piecesService', 'boardService', function($http, piecesService, boardService) {
    return {
        restrict: 'E',
        scope: {
            board: '@'
        },
        templateUrl: "directives/tactical-pane/template.html",
        link: function(scope) {
            scope.open = false;
            console.log('hello tactical');

			scope.$on('tactical.show', function(event, tile) {
				console.log(tile);
				scope.open = true;
			})
        }
    }
}]);