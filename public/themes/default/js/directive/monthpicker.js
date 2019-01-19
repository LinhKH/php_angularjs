bookApp.directive('monthPicker', function () {
    return {
        restrict: 'A',
        require: 'ngModel',
        link: function (scope, element, attrs, ngModelCtrl) {
            $(function () {
                $(element).datepicker({
                    format: "yyyy/mm",
					language: 'ja',
					startView: 1,
					minViewMode: 1,
					autoclose: true,
                    todayBtn: "linked",
                    clearBtn: true,
                    forceParse: false
                });
            });
            element.bind('input', function (e) {
                var obj = e.target;
                var value = obj.value;
                var transformedInput = converToUnicode(value);
                transformedInput = transformedInput ? transformedInput.replace(/・/g,'/').replace(/。/g,'.').replace(/[^\d.\/\*\=\.\.\.]/g, '') : null;
                ngModelCtrl.$setViewValue(transformedInput);
                ngModelCtrl.$render();
            });
        }
    };
});