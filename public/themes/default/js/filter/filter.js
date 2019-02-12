bookApp.filter('split', function () {
    return function (input, splitChar, splitIndex) {
        return input.split(splitChar)[splitIndex];
    };
});

bookApp.filter('nl2br', function ($sce) {
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

bookApp.filter('isArray', function () {
    return function (input) {
        return angular.isArray(input);
    };
});

bookApp.filter('isNumber', function () {
    return function (input) {
        return angular.isNumber(input);
    };
});

bookApp.filter('isString', function () {
    return function (input) {
        return angular.isString(input);
    };
});

bookApp.filter('isUndefined', function () {
    return function (input) {
        return angular.isUndefined(input);
    };
});

bookApp.filter('isEqual', function () {
    return function (input1, input2) {
        return angular.equals(input1, input2);
    };
});

bookApp.filter('toJson', function () {
    return function (obj, pretty) {
        if (pretty === true) {
            return angular.toJson(obj, pretty);
        }
    };
});

bookApp.filter('copy', function () {
    return function (source, destination) {
        return angular.copy(source, destination);
    };
});

bookApp.filter('in_array', function () {
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

bookApp.filter('isEmpty', function () {
    return function (input) {
        var key = Object.keys(input);
        if (key.length > 0)
            return false;
        return true;
    };
});

bookApp.filter('cut', function () {
    return function (value, wordwise, max, tail) {
        if (!value) return '';

        max = parseInt(max, 10);
        if (!max) return value;
        if (value.length <= max) return value;

        value = value.substr(0, max);
        if (wordwise) {
            var lastspace = value.lastIndexOf(' ');
            if (lastspace !== -1) {
              //Also remove . and , so its gives a cleaner result.
              if (value.charAt(lastspace-1) === '.' || value.charAt(lastspace-1) === ',') {
                lastspace = lastspace - 1;
              }
              value = value.substr(0, lastspace);
            }
        }

        return value + (tail || ' …');
    };
});
