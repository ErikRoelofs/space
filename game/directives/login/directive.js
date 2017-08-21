angular.module('game').directive('login', ['loginService', function(loginService) {

    return {
        restrict: 'E',
        templateUrl: 'directives/login/template.html',
        link: function(scope) {

            scope.new = {};

            scope.login = function() {
                loginService.authenticate(scope.username, scope.password).then(function(response) {
                    window.location = "/game/";
                });
            };

            scope.register = function() {
                // @todo
            }
        }
    };

}]);
