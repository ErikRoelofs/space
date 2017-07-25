angular.module('game').directive('tacticalOrderLog', ['$http', 'piecesService', 'pieceTypesService', 'orderService', 'playersService', function($http, piecesService, pieceTypesService, orderService, playersService) {
    return {
        restrict: 'E',
        scope: {
            log: '='
        },
        templateUrl: "directives/logs/tactical-order-log/template.html",
        link: function(scope) {
            $http.get('/log/' + scope.log.id ).then(function(response) {
                scope.fullLog = response.data;
            });

            scope.getPieceType = function (typeId) {
                return pieceTypesService.getPieceTypeById(typeId);
            }

            scope.getColorByPlayer = function (player) {
                return playersService.getPlayer(player).color;
            }

            scope.getNameByPlayer = function (player) {
                return playersService.getPlayer(player).name;
            }
		}

    }
}]);
