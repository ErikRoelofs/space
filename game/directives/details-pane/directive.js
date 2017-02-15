angular.module('game').directive('detailsPane', ['$http', 'piecesService', 'boardService', function($http, piecesService, boardService) {
    return {
        restrict: 'E',
        scope: {
            board: '@'
        },
        templateUrl: "directives/details-pane/template.html",
        link: function(scope) {
            scope.open = false;
            scope.$on('details.show', function(event, toShow, data) {
                scope.data = data;
                scope.src = scope.getSource(toShow);
                scope.open = true;
            });

			scope.$on('details.close', function() {
				scope.close();
			})

            scope.close = function() {
                scope.open = false;
            }

            scope.getSource = function(type) {
                switch(type) {
                    case 'piece': return 'directives/details/piece.html';
                    case 'tile': return 'directives/details/tile.html';
                }
            }

        }
    }
}]);