//http://forum.ionicframework.com/t/adding-an-angular-directive-in-ionic/1552/5
angular.module('starter.filters', []);


//http://stackoverflow.com/questions/17441254/why-angularjs-currency-filter-formats-negative-numbers-with-parenthesis
angular.module('starter.filters').filter('customCurrency', ["$filter", function ($filter) {
    return function (amount, currencySymbol) {
        var currency = $filter('currency');
        var prefix = "up";

        if (amount < 0) {
            prefix = "down";
            return prefix + " " + currency(amount, currencySymbol).replace("(", "").replace(")", "");
        }

        return prefix + " " + currency(amount, currencySymbol);
    };
}]);
