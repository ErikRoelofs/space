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
				scope.open = true;
			});

			scope.$on('tactical.add', function(event, piece) {
				if(scope.piecesToMove.indexOf(piece) == -1) {
					scope.piecesToMove.push(piece);
				}
			});

			scope.piecesToMove = [];
			scope.piecesToBuild = [];
			scope.buildablePieces = [];
        }
    }
}]);