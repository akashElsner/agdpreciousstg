//http://forum.ionicframework.com/t/adding-an-angular-directive-in-ionic/1552/5
angular.module('starter.Directives', []);


//http://fredonism.com/archive/radio-button-group.aspx
//http://jsfiddle.net/YwZwE/22/
angular.module('starter.Directives').directive('radioButtonGroup', function () {
    return {
        restrict: 'E',
        scope: { model: '=', options: '=', id: '=', name: '=', suffix: '=', updateFn: '&' },
        controller: function ($scope) {
            $scope.activate = function (option, $event) {
                $scope.model = option[$scope.name];
                $scope.updateFn({ option: $scope.model });
            };

            $scope.isActive = function (option) {
                return option[$scope.name] == $scope.model;
            };

            $scope.getName = function (option) {
                return option[$scope.name];
            }
        },
        template: "<div type='button' class='btn btn-{{suffix}}' " +
        "ng-class='{active: isActive(option)}'" +
            "ng-repeat='option in options' " +
            "ng-click='activate(option, $event)'>{{getName(option)}}" +
            "</div>"
    };
});

//http://stackoverflow.com/questions/21708730/why-are-multiple-click-events-fired-when-using-ngtouch
angular.module('starter.Directives').directive('touchclick', function () {
    return function (scope, element, attrs) {
        element.bind('touchstart click', function (event) {

            event.preventDefault();
            event.stopPropagation();

            scope.$apply(attrs['touchclick']);
        });
    };
});