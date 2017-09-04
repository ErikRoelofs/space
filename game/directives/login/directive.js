angular.module('game').directive('login', ['loginService', 'userService', function(loginService, userService) {

    return {
        restrict: 'E',
        templateUrl: 'directives/login/template.html',
        link: function(scope) {

            scope.new = {};

            scope.login = function() {
                loginService.authenticate(scope.username, scope.password).then(function(response) {
                    window.location = "/game/";
                }, function(error) {
                    scope.error = error;
                });
            };

            scope.register = function() {
                userService.registerAccount(scope.new.username, scope.new.password, scope.new.email);
            }
        }
    };

}]);
