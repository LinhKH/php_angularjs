AgencyManagementApp.filter('split', function () {
    return function (input, splitChar, splitIndex) {
        return input.split(splitChar)[splitIndex];
    };
});

AgencyManagementApp.filter('nl2br', function ($sce) {
    return function (msg, is_xhtml) {
        if (typeof msg !== 'undefined') {
            var is_xhtml = is_xhtml || true;
            var breakTag = (is_xhtml) ? '<br />' : '<br>';
            var msg = (msg + '').replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, '$1' + breakTag + '$2');
        } else {
            return '';
        }
        return $sce.trustAsHtml(msg);
    }
});

AgencyManagementApp.filter('isArray', function () {
    return function (input) {
        return angular.isArray(input);
    };
});

AgencyManagementApp.filter('isNumber', function () {
    return function (input) {
        return angular.isNumber(input);
    };
});

AgencyManagementApp.filter('isString', function () {
    return function (input) {
        return angular.isString(input);
    };
});

AgencyManagementApp.filter('isUndefined', function () {
    return function (input) {
        return angular.isUndefined(input);
    };
});

AgencyManagementApp.filter('isEqual', function () {
    return function (input1, input2) {
        return angular.equals(input1, input2);
    };
});

AgencyManagementApp.filter('toJson', function () {
    return function (obj, pretty) {
        if (pretty === true) {
            return angular.toJson(obj, pretty);
        }
    };
});

AgencyManagementApp.filter('copy', function () {
    return function (source, destination) {
        return angular.copy(source, destination);
    };
});

AgencyManagementApp.filter('in_array', function () {
    return function (needed, stack) {
        var i, k = 0;
        for (i = 0; i < stack.length; i++) {
            if (needed == stack[i]) {
                k = k + 1;
            }
        }
        if (k > 0) {
            return true;
        }
        return false;
    };
});

AgencyManagementApp.filter('isEmpty', function () {
    return function (input) {
        var key = Object.keys(input);
        if (key.length > 0)
            return false;
        return true;
    };
});
