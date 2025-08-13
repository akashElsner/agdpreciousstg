//http://forum.ionicframework.com/t/adding-an-angular-directive-in-ionic/1552/5
angular.module('starter.factories', []);

angular.module('starter.factories').factory('AppSettings', function () {

    var storage = (typeof window.localStorage === 'undefined') ? undefined : window.localStorage;
    var supported = !(typeof storage == 'undefined' || typeof window.JSON == 'undefined');

    var factory = {};

    var appSettings = 
		{
		    isAutoUpdateEnabled: true,
		    autoUpdateIntervalMs: 60000,
			hasSeenDisclaimer: false,
		};

    factory.getIsAutoUpdateEnabled = function () {
        return appSettings.isAutoUpdateEnabled;
    }

    factory.getAutoUpdateIntervalInMilliseconds = function () {
        return appSettings.autoUpdateIntervalMs;
    }

    factory.getHasSeenDisclaimer = function () {
    	return appSettings.hasSeenDisclaimer;
    }

    factory.setIsAutoUpdateEnabled = function (isAutoUpdateEnabled) {
        appSettings.isAutoUpdateEnabled = isAutoUpdateEnabled;
        if (supported)
            storage.setItem("appSettings", JSON.stringify(appSettings));
    }

    factory.setAutoUpdateIntervalInMilliseconds = function (autoUpdateIntervalMs) {
        appSettings.autoUpdateIntervalMs = autoUpdateIntervalMs;
        if (supported)
            storage.setItem("appSettings", JSON.stringify(appSettings));
    }

    factory.setHasSeenDisclaimer = function (hasSeenDisclaimer) {
    	appSettings.hasSeenDisclaimer = hasSeenDisclaimer;
    	if (supported)
    		storage.setItem("appSettings", JSON.stringify(appSettings));
    }

    var item = storage.getItem("appSettings");

    if (item != null)
        appSettings = JSON.parse(item)
    else
        storage.setItem("appSettings", JSON.stringify(appSettings));

    return factory;
});

angular.module('starter.factories').factory('CurrencySettings', function ($q) {

    var storage = (typeof window.localStorage === 'undefined') ? undefined : window.localStorage;
    var supported = !(typeof storage == 'undefined' || typeof window.JSON == 'undefined');
    var currentDefer = undefined;

    var factory = {};

    var currencySettings =
		{
		    currentCurrency: "AUD",
		};

    factory.getSupportedCurrencies = function () {
        return [ "AUD", "USD" ];
    }

    factory.getCurrentCurrency = function () {
        return currencySettings.currentCurrency;
    }

    factory.setCurrentCurrency = function (currentCurrency) {
        currencySettings.currentCurrency = currentCurrency;
        if (supported)
            storage.setItem("currencySettings", JSON.stringify(currencySettings));

        if (currentDefer != undefined)
            currentDefer.notify();
    }

    factory.start = function () {
        currentDefer = $q.defer();
        return currentDefer.promise;
    }

    factory.stop = function () {
        currentDefer = undefined;
    }

    var item = storage.getItem("currencySettings");

    if (item != null)
        currencySettings = JSON.parse(item)
    else
        storage.setItem("currencySettings", JSON.stringify(currencySettings));

    return factory;
});

angular.module('starter.factories').factory('PricePoller', function (DataService, $http, $interval, $q) {
    var interval = null;
    var currentDefer = undefined;
    var priceData = { aud:undefined, usd:undefined};
    var lastUpdateTime = "No data available";
    var factory = {};

    //var dataSource = function (type) { return '../testData/testMetalData' + type + '.json'; };
    var dataSource = function (type) { return 'http://www.australiangolddealers.com.au/app/get_prices.php?db=' + type; };

    //http://www.australiangolddealers.com.au/app/get_prices.php?db=usd
    //http://www.australiangolddealers.com.au/app/get_prices.php?db=aud

    var notify = function () {
    	if (currentDefer != undefined)
    		currentDefer.notify();
    };

    var updateData = function (customNotfiy)
    {
    	lastUpdateTime = moment().format('MMMM Do YYYY, h:mm:ss a');

    	DataService.GetRequest(dataSource("aud"), function (r) { priceData.aud = r; customNotfiy(); }, function () { customNotfiy(); });
    	DataService.GetRequest(dataSource("usd"), function (r) { priceData.usd = r; customNotfiy(); }, function () { customNotfiy(); });
    };

    updateData(notify);

    factory.refresh = function () {

    	var refreshDefer = $q.defer();
    	updateData(function () 
    	{
    		if (refreshDefer != null) {
    			refreshDefer.notify();
    			refreshDefer = null;
    		}
    		notify();
    	});

    	return refreshDefer.promise
    }

    factory.getCurrentData = function () {
    	return priceData;
    }

    factory.getLastUpdateTime = function () {
        return lastUpdateTime;
    }

    factory.start = function () {
        currentDefer = $q.defer();
        return currentDefer.promise;
    }

    factory.stop = function () {
        currentDefer = undefined;
    }

    factory.setRefreshRate = function (isEnabled, delay) {
        $interval.cancel(interval);
        interval = null;
        if (isEnabled)
        	interval = $interval(function () { updateData(notify); }, delay);
    }

    return factory;
});

angular.module('starter.factories').factory('GraphDataPoller', function (DataService, $http, $interval, $q) {
	var interval = null;
	var currentDefer = undefined;
	var graphData =
		{
			goldAUD: undefined,
			goldUSD: undefined,
			silverAUD: undefined,
			silverUSD: undefined,
			platinumAUD: undefined,
			platinumUSD: undefined,
		};
	var lastUpdateTime = "No data available";
	var factory = {};

	//var dataSource = function (type) { return '../testData/testGraphData' + type + '.json'; };
	var dataSource = function (type) { return 'http://www.australiangolddealers.com.au/app/get_graph.php?db=' + type; };

	var notify = function () {
		if (currentDefer != undefined)
			currentDefer.notify();
	};

	var updateData = function (customNotify)
	{
		DataService.GetRequest(dataSource("AUD-XAU"), function (r) { graphData.goldAUD = r; customNotify(); }, function () { customNotify();});
		DataService.GetRequest(dataSource("USD-XAU"), function (r) { graphData.goldUSD = r; customNotify(); }, function () { customNotify(); });
		DataService.GetRequest(dataSource("AUD-XAG"), function (r) { graphData.silverAUD = r; customNotify(); }, function () { customNotify(); });
		DataService.GetRequest(dataSource("USD-XAG"), function (r) { graphData.silverUSD = r; customNotify(); }, function () { customNotify(); });
		DataService.GetRequest(dataSource("AUD-XPT"), function (r) { graphData.platinumAUD = r; customNotify(); }, function () { customNotify(); });
		DataService.GetRequest(dataSource("USD-XPT"), function (r) { graphData.platinumUSD = r; customNotify(); }, function () { customNotify(); });
		lastUpdateTime = moment().format('MMMM Do YYYY, h:mm:ss a');
	};

	updateData(notify);

	interval = $interval(function () { updateData(notify); }, 60000 * 60 * 12);

	factory.refresh = function () {

		var refreshDefer = $q.defer();
		updateData(function () {
			if (refreshDefer != null) {
				refreshDefer.notify();
				refreshDefer = null;
			}
			notify();
		});

		return refreshDefer.promise
	}

	factory.getCurrentData = function () {
		return graphData;
	}

	factory.getLastUpdateTime = function () {
		return lastUpdateTime;
	}

	factory.start = function () {
		currentDefer = $q.defer();
		return currentDefer.promise;
	}

	factory.stop = function () {
		currentDefer = undefined;
	}

	return factory;
});

angular.module('starter.factories').factory('FeedService', function ($http) {
	return {
		parseFeed: function (url) {
			return $http.jsonp('//ajax.googleapis.com/ajax/services/feed/load?v=1.0&num=50&callback=JSON_CALLBACK&q=' + encodeURIComponent(url));
		}
	}
});