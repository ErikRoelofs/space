angular.module('game').directive('hexmap', ['$rootScope', function($rootScope) {
    return {
        restrict: 'E',
        templateUrl: 'directives/hexmap/template.html',
        link: function(scope) {
            scope.rows = [
                [[0,0],[0,1],[0,2],[0,3]],
                [[1,0],[1,1],[1,2],[1,3],[1,4]],
                [[2,0],[2,1],[2,2],[2,3],[2,4],[2,5]],
                [[3,0],[3,1],[3,2],[3,3],[3,4],[3,5],[3,6]],
                [[4,1],[4,2],[4,3],[4,4],[4,5],[4,6]],
                [[5,2],[5,3],[5,4],[5,5],[5,6]],
                [[6,3],[6,4],[6,5],[6,6]]];
            scope.width = 200;
            scope.height = 200;
            scope.margins = [
                1.5 * scope.width,1 * scope.width,0.5 * scope.width,0 * scope.width,0.5 * scope.width,1 * scope.width,1.5 * scope.width,
            ]
            scope.margin = function($index) {
                return scope.margins[$index] + 'px';
            }
            scope.widthPx = function() {
                return scope.width + 'px';
            }
            scope.heightPx = function() {
                return scope.height + 'px';
            }
        }
    };
}]);