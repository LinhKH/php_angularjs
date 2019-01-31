AgencyManagementApp.directive('numbersSearchOnly', function () {
    return {
        require: 'ngModel',
        link: function (scope, element, attr, ngModelCtrl) {
            element.bind('input', function (e) {
                var obj = e.target;
                var value = obj.value;
                var transformedInput = converToUnicode(value);
                transformedInput = transformedInput ? transformedInput.replace(/。/g, '.').replace(/[^\d\*\=\.\.\.]/g, '') : null;
                ngModelCtrl.$setViewValue(transformedInput);
                ngModelCtrl.$render();
            });
        }
    };
});

AgencyManagementApp.directive('telnoOnly', function () {
    return {
        require: 'ngModel',
        link: function (scope, element, attr, ngModelCtrl) {
            element.bind('input', function (e) {
                var obj = e.target;
                var value = obj.value;
                var transformedInput = converToUnicode(value);
                transformedInput = transformedInput ? transformedInput.replace(/[^\d-]/g, '') : null;
                ngModelCtrl.$setViewValue(transformedInput);
                ngModelCtrl.$render();
            });
        }
    };
});

AgencyManagementApp.directive('telnoNumOnly', function () {
    return {
        require: 'ngModel',
        link: function (scope, element, attr, ngModelCtrl) {
            element.bind('input', function (e) {
                var obj = e.target;
                var value = obj.value;
                var transformedInput = converToUnicode(value);
                transformedInput = transformedInput ? transformedInput.replace(/[^\d]/g, '') : null;
                ngModelCtrl.$setViewValue(transformedInput);
                ngModelCtrl.$render();
            });
        }
    };
});

AgencyManagementApp.directive('zipCodeOnly', function () {
    return {
        require: 'ngModel',
        link: function (scope, element, attr, ngModelCtrl) {
            element.bind('input', function (e) {
                var obj = e.target;
                var value = obj.value;
                var transformedInput = converToUnicode(value);
                transformedInput = transformedInput ? transformedInput.replace(/[^\d]/g, '') : null;
                ngModelCtrl.$setViewValue(transformedInput.substring(0, 7));
                ngModelCtrl.$render();
            });
        }
    };
});

AgencyManagementApp.directive('numbersFaxOnly', function () {
    return {
        require: 'ngModel',
        link: function (scope, element, attr, ngModelCtrl) {
            element.bind('input', function (e) {
                var obj = e.target;
                var value = obj.value;
                var transformedInput = converToUnicode(value);
                transformedInput = transformedInput ? transformedInput.replace(/ー/g, '-').replace(/[^\d-]/g, '') : null;
                ngModelCtrl.$setViewValue(transformedInput);
                ngModelCtrl.$render();
            });
        }
    };
});

AgencyManagementApp.directive('fileModel', ['$parse',
    function ($parse) {
        return {
            restrict: 'A',
            link: function (scope, element, attrs) {
                var _function = attrs.fileModelOnchange;
                element.bind('change', function () {
                    if (typeof _function !== 'undefined') {
                        scope[_function](element);
                    }
                });
            }
        };
    }
]);

AgencyManagementApp.directive('bnList', function () {
    function link(scope, element, attributes) {
        scope.$watch(
                attributes.bnList,
                function handlePersonBindingChangeEvent(newValue) {
                    scope.bnList = newValue;
                }
        );
    }

    // Return the isolate-scope directive configuration.
    return({
        link: link,
        templateUrl: 'list.html'
    });
});

AgencyManagementApp.directive('numbersOnly', function () {
    return {
        require: 'ngModel',
        link: function (scope, element, attr, ngModelCtrl) {
            element.bind('input', function (e) {
                var obj = e.target;
                var value = obj.value;
                var transformedInput = converToUnicode(value);
                transformedInput = transformedInput ? transformedInput.replace(/ー/g, '-').replace(/[^\d.-]/g, '') : null;
                ngModelCtrl.$setViewValue(transformedInput);
                ngModelCtrl.$render();
            });
        }
    };
});

AgencyManagementApp.directive('numbersIntOnly', function () {
    return {
        require: 'ngModel',
        link: function (scope, element, attr, ngModelCtrl) {
            element.bind('input', function (e) {
                var obj = e.target;
                var value = obj.value;
                var transformedInput = converToUnicode(value);
                transformedInput = transformedInput ? transformedInput.replace(/[^\d]/g, '') : null;
                ngModelCtrl.$setViewValue(transformedInput);
                ngModelCtrl.$render();
            });
        }
    };
});

AgencyManagementApp.directive('trustedHtml', ['$sce',
    function ($sce) {
        return {
            require: 'ngModel',
            link: function (scope, element, attrs, ngModel) {
                ngModel.$formatters.push(function (value) {
                    function htmlDecode(input) {
                        var elem = document.createElement('div');
                        elem.innerHTML = input;
                        return elem.childNodes.length === 0 ? '' : elem.childNodes[0].nodeValue;
                    }
                    return htmlDecode(value);
                });
            }
        };
    }
]);

AgencyManagementApp.directive('repeatDone', function () {
    return function (scope, element, attrs) {
        if (scope.$last) { // all are rendered
            scope.$eval(attrs.repeatDone);
        }
    };
});


AgencyManagementApp.directive('select2', function ($timeout) {
    return {
        restrict: 'A',
        require: 'ngModel',
        link: function (scope, element, attrs, model) {
            var e = $(element);
            var option  = {
                placeholder: " ",
                allowClear: true,
                theme: "bootstrap"
            };
            $timeout(function () {
                e.select2(option);
            });
            scope.$watch(attrs.ngModel, function (val) {
                $timeout(function () {
                    e.select2(option);
                });
            });
        }
    };
});

AgencyManagementApp.directive('whenScrollEnds', ['blockUI',
    function (blockUI) {
        return {
            restrict: 'A',
            link: function (scope, element, attrs) {
                var visibleHeight = element.height();
                var threshold = 450;
                element.scroll(function () {
                    var scrollableHeight = element.prop('scrollHeight');
                    var hiddenContentHeight = scrollableHeight - visibleHeight;
                    if (hiddenContentHeight - element.scrollTop() <= threshold) {
                        scope.$apply(attrs.whenScrollEnds);
                    }
                });
            }
        };
    }
]);

AgencyManagementApp.directive('viewDate', function () {
    return {
        require: 'ngModel',
        link: function (scope, element, attr, ngModelCtrl) {
            element.bind('input', function (e) {
                var obj = e.target;
                var value = obj.value;
                var transformedInput = converToUnicode(value);
                transformedInput = transformedInput ? transformedInput.replace(/・/g, '/').replace(/[^\d.\/]/g, '') : null;
                ngModelCtrl.$setViewValue(transformedInput);
                ngModelCtrl.$render();
            });
        }
    };
});

AgencyManagementApp.directive('unicodeValue', function () {
    return {
        require: 'ngModel',
        link: function (scope, element, attr, ngModelCtrl) {
            element.bind('input', function (e) {
                var obj = e.target;
                var value = obj.value;
                var transformedInput = converToUnicode(value);
                transformedInput = transformedInput ? transformedInput.replace(/・/g, '/').replace(/。/g, '.').replace(/[^\d.\/\*\=\.\.\.]/g, '') : null;
                ngModelCtrl.$setViewValue(transformedInput);
                ngModelCtrl.$render();
            });
        }
    };
});


AgencyManagementApp.directive('maxLength', function () {
    return {
        require: 'ngModel',
        link: function (scope, element, attrs, ngModelCtrl) {
            ngModelCtrl.$parsers.push(function (inputValue) {
                var length = attrs.maxLength ? attrs.maxLength : 0;
                var transformedInput = inputValue ? inputValue.substring(0, length) : null;
                if (transformedInput !== inputValue) {
                    ngModelCtrl.$setViewValue(transformedInput);
                    ngModelCtrl.$render();
                }
                return transformedInput;
            });
        }
    };
});

function converToUnicode(param) {
    var chars = param;
    var ascii = '';
    for (var i = 0, l = chars.length; i < l; i++) {
        var c = chars[i].charCodeAt(0);

        // make sure we only convert half-full width char
        if (c >= 0xFF00 && c <= 0xFFEF) {
            c = 0xFF & (c + 0x20);
        }
        ascii += String.fromCharCode(c);
    }
    return ascii;
}

AgencyManagementApp.directive('compile', ['$compile', function ($compile) {
    return function(scope, element, attrs) {
      scope.$watch(
        function(scope) {
          return scope.$eval(attrs.compile);
        },
        function(value) {
          element.html(value);
          $compile(element.contents())(scope);
        }
    );
  };
}]);

AgencyManagementApp.directive('clockpicker', function () {
    return {
        restrict: 'A',
        require: 'ngModel',
        replace: true,
        link: function (scope, element, attrs, ngModelCtrl) {
            $(function () {
                $(element).clockpicker({
                    autoclose: true
                });
            });
            element.bind('input', function (e) {
                var obj = e.target;
                var value = obj.value;
                var transformedInput = converToUnicode(value);
                transformedInput = transformedInput ? transformedInput.replace(/。/g,'.').replace(/[^\d\*\=\.\.\.\:]/g, ''): null;
                ngModelCtrl.$setViewValue(transformedInput);
                ngModelCtrl.$render();
            });
        }
    };
});