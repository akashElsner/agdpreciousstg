angular.module('starter.services', [])

.service('DataService', function ($rootScope, $http) {
    var service = this;



    //var headers = {
    //    'Access-Control-Allow-Origin': '*',
    //    'Access-Control-Allow-Methods': 'POST, GET, OPTIONS, PUT',
    //    'Content-Type': 'application/json',
    //    'Accept': 'application/json'
    //};


    ////Get request call for HTTP GET request
    //this.GetRequest = function (url, successCallback, failedCallback) {

    //    $http({
    //        method: "GET",
    //        headers: headers,
    //        url: url
    //    }).success(function (data) {
    //        if (successCallback !== undefined)
    //            successCallback(data);
    //    }).error(function (data, status) {
    //        if (failedCallback !== undefined)
    //            failedCallback(data, status);
    //    });
    //};



    //Get request call for HTTP GET request
    this.GetRequest = function (url, successCallback, failedCallback) {
        $http.get(url).success(function (data) {
            if (successCallback !== undefined)
                successCallback(data);
        }).error(function (data, status) {
            if (failedCallback !== undefined)
                failedCallback(data, status);
        });
    };

    //Put request call for HTTP PUT request
    this.PutRequest = function (url, putData, successCallback, failedCallback) {
        $http.put(url, putData).success(function (data) {
            if (successCallback !== undefined)
                successCallback(data);
        }).error(function (data, status) {
            if (failedCallback !== undefined)
                failedCallback(data, status);
        });
    };

    //Post request call for HTTP POST request
    this.PostRequest = function (url, postData, successCallback, failedCallback) {
        $http.post(url, postData).success(function (data) {
            if (successCallback !== undefined)
                successCallback(data);
        }).error(function (data, status) {
            if (failedCallback !== undefined)
                failedCallback(data, status);
        });
    };

    //Delete request call for HTTP DELETE request
    this.DeleteRequest = function (url, successCallBack, failedCallBack) {
        $http.delete(url, null).success(function (data) {
            if (successCallBack !== undefined) {
                successCallBack(data);
            }
        }).error(function (data, status) {
            if (failedCallBack !== undefined) {
                failedCallBack(data, status);
            }
        });
    };
    
    //Recursive method to allow synchronous data calls
    this.deleteRecurse = function (url, objects, index, completeCallBack, successCall, failedCall) {
        if ((objects.length === 0 || objects.length === index) && completeCallBack !== undefined)
            completeCallBack();

        if (index >= objects.length)
            return;

        this.DeleteRequest(url + objects[index], function () {
            service.deleteRecurse(url, objects, index + 1, completeCallBack, successCall, failedCall);
            if (successCall !== undefined)
                successCall();
        }, function (data, status) {
            service.deleteRecurse(url, objects, index + 1, completeCallBack, successCall, failedCall);
            if (failedCall !== undefined)
                failedCall(data, status);
        });
    };

    //Recursive method to allow synchronous data calls
    this.postRecurse = function (url, objects, index, completeCallBack, successCall, failedCall) {
        if ((objects.length === 0 || objects.length === index) && completeCallBack !== undefined)
            completeCallBack();

        if (index >= objects.length)
            return;

        this.PostRequest(url, objects[index], function () {
            service.postRecurse(url, objects, index + 1, completeCallBack, successCall, failedCall);
            if (successCall !== undefined)
                successCall();
        }, function (data, status) {
            service.postRecurse(url, objects, index + 1, completeCallBack, successCall, failedCall);
            if (failedCall !== undefined)
                failedCall(data, status);
        });
    };

    this.deactivateRequest = function (url, successCallBack, failedCallBack) {
        this.DeleteRequest(url, successCallBack, failedCallBack);
    };

    //Recursive method to allow synchronous data calls
    this.deactivateRecurse = function (url, objects, index, completeCallBack, successCall, failedCall) {
        if ((objects.length === 0 || objects.length === index) && completeCallBack !== undefined)
            completeCallBack();

        if (index >= objects.length)
            return;

        this.DeleteRequest(url + objects[index], function () {

            service.deactivateRecurse(url, objects, index + 1, completeCallBack, successCall, failedCall);
            if (successCall !== undefined)
                successCall();
        }, function (data, status) {
            service.deactivateRecurse(url, objects, index + 1, completeCallBack, successCall, failedCall);
            if (failedCall !== undefined)
                failedCall(data, status);
        });
    };

    this.reactivateRequest = function (url, successCallBack, failedCallBack) {
        this.PostRequest(url, successCallBack, failedCallBack);
    };

    //Recursive method to allow synchronous data calls
    this.reactivateRecurse = function (url, objects, index, completeCallBack, successCall, failedCall) {
        if ((objects.length === 0 || objects.length === index) && completeCallBack !== undefined)
            completeCallBack();

        if (index >= objects.length)
            return;

        this.PostRequest(url + objects[index] + "/undelete", undefined, function () {
            service.reactivateRecurse(url, objects, index + 1, completeCallBack, successCall, failedCall);
            if (successCall !== undefined)
                successCall();
        }, function (data, status) {
            service.reactivateRecurse(url, objects, index + 1, completeCallBack, successCall, failedCall);
            if (failedCall !== undefined)
                failedCall(data, status);
        });
    };

    //TODO: Change function to have a mapping to validData var which has a field marked as lookup
    this.findItem = function (rawData, lookupProperty, lookupValue) {
        if (rawData === undefined || rawData === null || lookupProperty === undefined || lookupProperty === null || lookupValue === undefined || lookupValue === null)
            return undefined;

        var foundItem = undefined;
        lookupValue = lookupValue.toString().toLowerCase();

        for (var i = 0; i < rawData.length; i++) {
            if (rawData[i][lookupProperty].toString().toLowerCase() === lookupValue) {
                foundItem = this.copyObject(rawData[i]);
                break;
            }
        }

        return foundItem;
    };

    //Does a deep copy of the object, jsonify seems to be better performance wise as compared to jQuery's deep copy
    //Warning will strip out functions
    this.copyObject = function (source) {
        return JSON.parse(JSON.stringify(source));
    };

    this.searchItem = function (rawData, lookupProperty, lookupValue) {
        if (rawData === undefined || rawData === null || lookupProperty === undefined || lookupProperty === null || lookupValue === undefined || lookupValue === null)
            return undefined;

        var foundItems = [];
        lookupValue = lookupValue.toString().toLowerCase();

        for (var i = 0; i < rawData.length; i++) {
            var foundItem = undefined;
            if (rawData[i][lookupProperty].toString().toLowerCase().indexOf(lookupValue) !== -1) {
                foundItem = this.copyObject(rawData[i]);
            }
            if (foundItem !== undefined)
                foundItems.push(foundItem);
        }

        return foundItems;
    };

    this.searchItemExact = function (rawData, lookupProperty, lookupValue) {
        if (rawData === undefined || rawData === null || lookupProperty === undefined || lookupProperty === null || lookupValue === undefined || lookupValue === null)
            return undefined;

        var foundItem = undefined;
        lookupValue = lookupValue.toString().toLowerCase();

        for (var i = 0; i < rawData.length; i++) {
            if (rawData[i][lookupProperty].toString().toLowerCase() === lookupValue) {
                foundItem = this.copyObject(rawData[i]);
                return foundItem;
            }
        }

        return undefined;
    };

    this.searchItemsExact = function (rawData, lookupProperty, lookupValue) {
        if (rawData === undefined || rawData === null || lookupProperty === undefined || lookupProperty === null || lookupValue === undefined || lookupValue === null)
            return undefined;

        var foundItems = [];
        lookupValue = lookupValue.toString().toLowerCase();

        for (var i = 0; i < rawData.length; i++) {
            var foundItem = undefined;
            if (rawData[i][lookupProperty].toString().toLowerCase() === lookupValue) {
                foundItem = this.copyObject(rawData[i]);
                foundItems.push(foundItem);
            }
        }

        return foundItems;
    };

    this.searchItemMultiProperty = function (rawData, lookupProperties, lookupValue) {
        if (rawData === undefined || rawData === null || lookupProperties === undefined || lookupProperties === null || lookupProperties.length === 0 || lookupValue === undefined || lookupValue === null)
            return undefined;

        var foundItems = [];
        lookupValue = lookupValue.toLowerCase();

        for (var i = 0; i < rawData.length; i++) {
            var foundItem = undefined;
            for (var j = 0; j < lookupProperties.length; j++) {
                if (String(rawData[i][lookupProperties[j]]).toLowerCase().indexOf(lookupValue) !== -1) {
                    foundItem = this.copyObject(rawData[i]);
                    break;
                }
            }

            if (foundItem !== undefined)
                foundItems.push(foundItem);
        }

        return foundItems;
    };

    this.addLookupObjectArrayToObjectArray = function (list, lookupList, lookupProperty, objectName, objectMatchIdName) {
        for (var i = 0; i < list.length; i++) {
            this.addLookupObjectArrayToObject(list[i], lookupList, lookupProperty, objectName, objectMatchIdName);
        }
    };

    this.addLookupObjectArrayToObject = function (obj, lookupList, lookupProperty, objectName, objectMatchIdName) {
        obj[objectName] = this.searchItemsExact(lookupList, lookupProperty, obj[objectMatchIdName]);
    };

    this.addLookupObjectToObjectArray = function (list, lookupList, lookupProperty, objectName, objectMatchIdName) {
        for (var i = 0; i < list.length; i++) {
            this.addLookupObjectToObject(list[i], lookupList, lookupProperty, objectName, objectMatchIdName);
        }
    };

    this.addLookupObjectToObject = function (obj, lookupList, lookupProperty, objectName, objectMatchIdName) {
        obj[objectName] = this.searchItemExact(lookupList, lookupProperty, obj[objectMatchIdName]);
    };

    this.addCopyOfPropertyToObjectArray = function (objs, propName) {
        for (var i = 0; i < objs.length; i++) {
            this.addCopyOfPropertyToObject(objs[i], propName);
        }
    };

    this.addCopyOfPropertyToObject = function (obj, propName) {
        obj["Old" + propName] = obj[propName];
    };

    this.addProperties = function (objArray, lookupProperty, additionalData, propertyName) {

        for (var i = 0; i < objArray.length; i++) {
            for (var j = 0; j < additionalData.length; j++) {
                if (objArray[i][lookupProperty] === additionalData[j][lookupProperty]) {
                    objArray[i][propertyName] = additionalData[j];
                }
            }
        }
    };

    this.generateRandomString = function (stringLength) {
        var text = "";
        var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
        for (var i = 0; i < stringLength; i++) {
            text += possible.charAt(Math.floor(Math.random() * possible.length));
        }
        return text;
    };

});