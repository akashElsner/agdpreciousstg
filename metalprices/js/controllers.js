angular.module('starter.controllers', [])

.controller('AppCtrl', function ($scope, CurrencySettings) {
	$scope.togglecurrency = function ()
	{
	    if (CurrencySettings.getCurrentCurrency() == "AUD") {
	        CurrencySettings.setCurrentCurrency("USD");
	    }
	    else {
	        CurrencySettings.setCurrentCurrency("AUD");
	    }
	    $scope.currentCurrency = CurrencySettings.getCurrentCurrency();
	}

	$scope.currentCurrency = CurrencySettings.getCurrentCurrency();
})

.controller('preciousMetals', function ($scope, $stateParams, PricePoller, CurrencySettings, GraphDataPoller) {
    //Protperties
    $scope.metalType = "Gold";
    troyOunce = 1;
    troyOunceToGrams = 1 / 31.1034768;
    troyOunceToKilos = troyOunceToGrams * 1000;

    $scope.weightMultiplierGold = troyOunceToGrams;
    $scope.weightMultiplierSilver = troyOunceToGrams;
    $scope.weightMultiplierPlatinum = troyOunceToGrams;

    $scope.goldBreakdown = [
        { display: "9k", value: "0.375" },
        { display: "10k", value: "0.4166666" },
        { display: "14k", value: "0.5833333" },
        { display: "18k", value: "0.75" },
        { display: "21k", value: "0.875" },
        { display: "22k", value: "0.9166666" },
        { display: "24k", value: "1" }
    ];

    $scope.silverBreakdown = [
        { display: ".500", value: "0.500" },
        { display: ".800", value: "0.800" },
        { display: ".925", value: "0.925" },
        { display: ".999", value: "0.999" }
    ];

    $scope.platinumBreakdown = [
        { display: ".950", value: "0.950" },
        { display: ".9999", value: "0.9999" }
    ];

    $scope.metalAskGold = "";
    $scope.metalBidGold = "";
    $scope.metalChangeGold = "";

    $scope.metalAskSilver = "";
    $scope.metalBidSilver = "";
    $scope.metalChangeSilver = "";

    $scope.metalAskPlatinum = "";
    $scope.metalBidPlatinum = "";
    $scope.metalChangePlatinum = "";

    goldData = null;
    silverData = null;
    platinumData = null;

    $scope.lastUpdateTime = "";

    $scope.metalWeightOptions = [{ id: 1, name: "t oz" }, { id: 2, name: "g" }, { id: 3, name: "kg" }];

    $scope.goldWeight = "g";
    $scope.silverWeight = "g";
    $scope.platinumWeight = "g";

    $scope.dateZoomOptions = [{ id: 1, name: "1W" }, { id: 2, name: "1M" }, { id: 3, name: "1Y" }, { id: 4, name: "5Y" }, { id: 5, name: "10Y" }];

    $scope.dateZoomGold = "10Y";
    $scope.dateZoomSilver = "10Y";
    $scope.dateZoomPlatinum = "10Y";

    $scope.minGraphValue = "";
    $scope.maxGraphValue = "";

    $scope.goldChart = null;
    $scope.silverChart = null;
    $scope.platinumChart = null;

    var goldData = [];
    var silverData = [];
    var platinumData = [];

    var goldDataSeries = {
        type: "area",
        color: "#D0B57D",
        fillOpacity: .3,
        axisYType: "secondary",
        xValueType: "dateTime"
    };

    var silverDataSeries = {
        type: "area",
        color: "#D0B57D",
        fillOpacity: .3,
        axisYType: "secondary",
        xValueType: "dateTime"
    };

    var platinumDataSeries = {
        type: "area",
        color: "#D0B57D",
        fillOpacity: .3,
        axisYType: "secondary",
        xValueType: "dateTime"
    };

    var limit = 0;

    //Functions
    $scope.doRefresh = function () {
        var refreshed = false;
        GraphDataPoller.refresh();
        PricePoller.refresh().then(null, null, function () {
            if (refreshed)
                return;
            refreshed = true;
            $scope.$broadcast('scroll.refreshComplete');
        });
    };

    $scope.setMetalWeightMultiplier = function (option, metalType) {
        var weightMultiplier = null;

        switch (option) {
            case "t oz":
                weightMultiplier = troyOunce;
                break;
            case "g":
                weightMultiplier = troyOunceToGrams;
                break;
            case "kg":
                weightMultiplier = troyOunceToKilos;
        }

        if (metalType == "Gold")
            $scope.weightMultiplierGold = weightMultiplier

        if (metalType == "Silver")
            $scope.weightMultiplierSilver = weightMultiplier

        if (metalType == "Platinum")
            $scope.weightMultiplierPlatinum = weightMultiplier
    };

    $scope.updateMetalPrices = function (data) {

        if ((data == undefined) || (data.aud == undefined) || (data.usd == undefined))
            return;

        var isAUD = CurrencySettings.getCurrentCurrency() == "AUD";
       
        if (isAUD) {
            $scope.metalAskGold = data.aud.gold_ask_aud_toz;
            $scope.metalBidGold = data.aud.gold_bid_aud_toz;
            $scope.metalChangeGold = data.aud.gold_change_dollar_aud_toz;

            $scope.metalAskSilver = data.aud.silver_ask_aud_toz;
            $scope.metalBidSilver = data.aud.silver_bid_aud_toz;
            $scope.metalChangeSilver = data.aud.silver_change_dollar_aud_toz;

            $scope.metalAskPlatinum = data.aud.platinum_ask_aud_toz;
            $scope.metalBidPlatinum = data.aud.platinum_bid_aud_toz;
            $scope.metalChangePlatinum = data.aud.platinum_change_dollar_aud_toz;
        }
        else {
            $scope.metalAskGold = data.usd.gold_ask_usd_toz;
            $scope.metalBidGold = data.usd.gold_bid_usd_toz;
            $scope.metalChangeGold = data.usd.gold_change_dollar_usd_toz;

            $scope.metalAskSilver = data.usd.silver_ask_usd_toz;
            $scope.metalBidSilver = data.usd.silver_bid_usd_toz;
            $scope.metalChangeSilver = data.usd.silver_change_dollar_usd_toz;

            $scope.metalAskPlatinum = data.usd.platinum_ask_usd_toz;
            $scope.metalBidPlatinum = data.usd.platinum_bid_usd_toz;
            $scope.metalChangePlatinum = data.usd.platinum_change_dollar_usd_toz;
        }   
    };

    $scope.updateData = function () {
        if (PricePoller.getCurrentData() != undefined) {
            metalData = PricePoller.getCurrentData();
            $scope.updateMetalPrices(metalData);
            $scope.lastUpdateTime = PricePoller.getLastUpdateTime();
        }
    };

    PricePoller.start().then(null, null, function () {
        console.log("PricePoller.start");
        $scope.updateData()
    });

    GraphDataPoller.start().then(null, null, function () {
        console.log("GraphDataPoller.start");
    	$scope.updateData()
    	$scope.updateGraphZoom($scope.dateZoomGold, "Gold");
    	$scope.updateGraphZoom($scope.dateZoomSilver, "Silver");
    	$scope.updateGraphZoom($scope.dateZoomPlatinum, "Platinum");
    });

    CurrencySettings.start().then(null, null, function () {
        console.log("CurrencySettings.start");
        $scope.updateData()
        $scope.updateGraphZoom($scope.dateZoomGold, "Gold");
        $scope.updateGraphZoom($scope.dateZoomSilver, "Silver");
        $scope.updateGraphZoom($scope.dateZoomPlatinum, "Platinum");
    });

    $scope.updateData();

    $scope.$on('$destroy', function () {
        PricePoller.stop();
        CurrencySettings.stop();
    });

    $scope.initialiseGraphs = function () {
        //http://www.chaosm.net/blog/2014/05/29/angularjs-charts-with-canvasjs/
        //http://canvasjs.com/docs/charts/basics-of-creating-html5-chart/zooming-panning/

        goldData = [];
        goldData.push(goldDataSeries);

        silverData = [];
        silverData.push(silverDataSeries);

        platinumData = [];
        platinumData.push(platinumDataSeries);

        //Landscape split screen chart
        $scope.goldChart = new CanvasJS.Chart("goldChart",
        {
            interactivityEnabled: false,
            zoomEnabled: false,
            exportEnabled: true,
            toolTip: {
                enabled: false,
                animationEnabled: false,
            },
            axisX: {
                lineThickness: 0.5,
                tickThickness: 0,
                margin: 0,
                labelFontColor: "#909BA2",
                minimum: limit,
                //maximum: limit,
                labelFontSize: 11,
                valueFormatString: "MMM DD YY",
            },
            axisY2: {
                lineThickness: 0,
                tickThickness: 0,
                gridThickness: 0.5,
                includeZero: false,
                labelFontColor: "#909BA2",
                labelFontSize: 11,
            },
            data: goldData
        });

        $scope.silverChart = new CanvasJS.Chart("silverChart",
        {
            interactivityEnabled: false,
            zoomEnabled: false,
            exportEnabled: true,
            toolTip: {
                enabled: false,
                animationEnabled: false,
            },
            axisX: {
                lineThickness: 0.5,
                tickThickness: 0,
                margin: 0,
                labelFontColor: "#909BA2",
                minimum: limit,
                //maximum: limit,
                labelFontSize: 11,
                valueFormatString: "MMM DD YY",
            },
            axisY2: {
                lineThickness: 0,
                tickThickness: 0,
                gridThickness: 0.5,
                includeZero: false,
                labelFontColor: "#909BA2",
                labelFontSize: 11,
            },
            data: silverData
        });

        $scope.platinumChart = new CanvasJS.Chart("platinumChart",
        {
            interactivityEnabled: false,
            zoomEnabled: false,
            exportEnabled: true,
            toolTip: {
                enabled: false,
                animationEnabled: false,
            },
            axisX: {
                lineThickness: 0.5,
                tickThickness: 0,
                margin: 0,
                labelFontColor: "#909BA2",
                minimum: limit,
                //maximum: limit,
                labelFontSize: 11,
                valueFormatString: "MMM DD YY",
            },
            axisY2: {
                lineThickness: 0,
                tickThickness: 0,
                gridThickness: 0.5,
                includeZero: false,
                labelFontColor: "#909BA2",
                labelFontSize: 11,
            },
            data: platinumData
        });
    }

    $scope.updateGraphZoom = function (option, metalType) {
        //var limit = 0;
        var valueFormatString = "";

        switch (option) {
            case "1W":
                limit = moment().subtract('weeks', 1);
                valueFormatString = "DDD DD/MM";
                break;
            case "1M":
                limit = moment().subtract('months', 1);
                valueFormatString = "DDD DD/MM";
                break;
            case "1Y":
                limit = moment().subtract('years', 1);
                valueFormatString = "MMM YYYY";
                break;
            case "5Y":
                limit = moment().subtract('years', 5);
                valueFormatString = "MMM YYYY";
                break;
            case "10Y":
                limit = moment().subtract('years', 10);
                valueFormatString = "MMM YYYY";
        }

        var graphData = GraphDataPoller.getCurrentData()
        var isAUD = CurrencySettings.getCurrentCurrency() == "AUD";

        var dataSeries = undefined;

        if ((graphData.goldAUD == undefined) || (graphData.goldUSD == undefined) ||
			(graphData.silverAUD == undefined) || (graphData.silverUSD == undefined) ||
			(graphData.platinumAUD == undefined) || (graphData.platinumUSD == undefined)) {

            goldDataSeries.dataPoints = undefined;
            silverDataSeries.dataPoints = undefined;
            platinumDataSeries.dataPoints = undefined;
        }
        else {

            switch (metalType) {
                case "Gold":
                    goldDataSeries.dataPoints = isAUD ? graphData.goldAUD : graphData.goldUSD;
                    dataSeries = goldDataSeries.dataPoints;
                    break;
                case "Silver":
                    silverDataSeries.dataPoints = isAUD ? graphData.silverAUD : graphData.silverUSD;
                    dataSeries = silverDataSeries.dataPoints;
                    break;
                case "Platinum":
                    platinumDataSeries.dataPoints = isAUD ? graphData.platinumAUD : graphData.platinumUSD;
                    dataSeries = platinumDataSeries.dataPoints;
                    break;
            }
        }

        var min = undefined;
        var max = undefined;
        if (dataSeries != undefined) {
            for (var index = (dataSeries.length - 1) ; index >= 0; index--) {
                var point = dataSeries[index];

                if (point.x < limit.valueOf())
                    break;

                if ((min == undefined) || (point.y < min))
                    min = point.y;

                if ((max == undefined) || (point.y > max))
                    max = point.y;
            }
        }

        if (min != undefined)
            $scope.minGraphValue = "Low: $" + min;
        else
            $scope.minGraphValue = "Low: NA";

        if (max != undefined)
            $scope.maxGraphValue = "High: $" + max;
        else
            $scope.maxGraphValue = "High: NA";


        if(metalType == "Gold") {
            goldData = [];
            goldData.push(goldDataSeries);

            $scope.goldChart.data = goldData;
            $scope.goldChart.options.axisX.minimum = limit;
            $scope.goldChart.options.axisX.valueFormatString = valueFormatString;
            $scope.goldChart.render();

            $scope.dateZoomGold = option;
            $scope.minGoldGraphValue = $scope.minGraphValue;
            $scope.maxGoldGraphValue = $scope.maxGraphValue;

        }

        if (metalType == "Silver") {
            silverData = [];
            silverData.push(silverDataSeries);
            $scope.silverChart.data = silverData;
            $scope.silverChart.options.axisX.minimum = limit;
            $scope.silverChart.options.axisX.valueFormatString = valueFormatString;
            $scope.silverChart.render();

            $scope.dateZoomSilver = option;
            $scope.minSilverGraphValue = $scope.minGraphValue;
            $scope.maxSilverGraphValue = $scope.maxGraphValue;
        }

        if (metalType == "Platinum") {
            platinumData = [];
            platinumData.push(platinumDataSeries);
            $scope.platinumChart.data = platinumData;
            $scope.platinumChart.options.axisX.minimum = limit;
            $scope.platinumChart.options.axisX.valueFormatString = valueFormatString;
            $scope.platinumChart.render();

            $scope.dateZoomPlatinum = option;
            $scope.minPlatinumGraphValue = $scope.minGraphValue;
            $scope.maxPlatinumGraphValue = $scope.maxGraphValue;
		}
    };

    console.log("initialiseGraphs1111");
    $scope.initialiseGraphs();

    //console.log("updateGraphZoom");
    //$scope.updateGraphZoom($scope.dateZoomGold, "Gold");
    //$scope.updateGraphZoom($scope.dateZoomSilver, "Silver");
    //$scope.updateGraphZoom($scope.dateZoomPlatinum, "Platinum");
});

