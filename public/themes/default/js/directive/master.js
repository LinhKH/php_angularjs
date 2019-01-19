bookApp.directive('master', function (commonService, $rootScope, $compile,$location) {
    return {
        templateUrl: 'themes/main/html/partials/master.html',
        controller: function ($scope) {

            $scope.list = [];
            $scope.search = {};
            $scope.totalItems = 0;
            $scope.currentPage = 1;
            $scope.pageSize = 10;
            $scope.listIds = [];
            $scope.current = {};
            $scope.paginationFunction = 'getList';
            $scope.formSearch = '';
            $scope.formEdit = '';
            $scope.tableHeader = '';
            $scope.tableContent = '';
            $scope.selector = '.fixheader-table';
            $scope.templateRow = {label:'', content:'', class:''};

            $scope.menuActive = function ($path) {
                if ($location.path() === $path) {
                    return 'btn-active';
                }
            };

            function __construct() {

                /**
                 * create parent functions
                 */

                /** setPagination */
                if (typeof $scope.setPagination === 'undefined') {
                    $scope.setPagination = function (page) {
                        if (typeof page !== 'undefined' && typeof page.pageSize !== 'undefined' && page.pageSize !== $scope.pageSize) {
                            $scope.pageSize = page.pageSize;
                            if ($scope.currentPage === 1) {
                                $scope[$scope.paginationFunction]();
                            } else {
                                $scope.currentPage = 1;
                            }
                        } else {
                            $scope[$scope.paginationFunction]();
                        }
                    };
                }

                /** setPagination */
                if (typeof $scope.getList === 'undefined') {
                    $scope.getList = function () {
                        $rootScope.startUi();
                        var params = angular.copy($scope.search);
                        params.itemperpage = angular.copy($scope.pageSize);
                        params.page = angular.copy($scope.currentPage);
                        commonService.requestFunction($scope.urlList, params, function (res) {
                            if (res.code === 200) {
                                
                                $scope.list = res.data.list;
                                $scope.totalItems = res.data.total;

                                $scope.destroyHeader();
                                $scope.initHeader();
                            }
                            $rootScope.stopUi();
                        });
                    };
                }

                /** edit */
                if (typeof $scope.edit === 'undefined') {
                    $scope.edit = function ($id) {
                        $rootScope.startUi();
                        commonService.requestFunction($scope.urlDetail, {id: $id}, function (res) {
                            if (res.code === 200) {
                                $scope.current = res.data;
                                $('#modalUpdate').modal('show');
                            }
                            $rootScope.stopUi();
                        });
                    };
                }

                /**add */
                if (typeof $scope.add === 'undefined') {
                    $scope.add = function () {
                        if($location.path()==='/master/user') {
                           $scope.showModalAddNewUser();
                        } else {
                            $scope.current = {};
                            $('#modalUpdate').modal('show');
                        }
                    };
                }

                /** delete */
                if (typeof $scope.delete === 'undefined') {
                    $scope.delete = function ($id) {
                        $rootScope.startUi();
                        commonService.requestFunction($scope.urlDelete, {id: $id}, function (res) {
                            if (res.code === 200) {
                                $scope.getList();
                                $scope.commonPopupClose();
                            }
                            $rootScope.stopUi();
                        });
                    };
                }

                /** save upsert */
                if (typeof $scope.saveUpsert === 'undefined') {
                    $scope.saveUpsert = function () {
                        $rootScope.startUi();
                        commonService.requestFunction($scope.urlUpsert, $scope.current, function (res) {
                            if (res.code === 200) {
                                $scope.getList();
                                $('#modalUpdate').modal('hide');
                                commonService.showAlert($rootScope.msg.MSGI0002);
                            }
                            $rootScope.stopUi();
                        });
                    };
                }

                /** confirmDelete */
                if (typeof $scope.confirmDelete === 'undefined') {
                    $scope.confirmDelete = function ($id) {
                        if (typeof $scope.listIds !== 'uddefined' && $scope.listIds.length > 0) {
                            return false;
                        }
                        commonService.commonPopupOpen($scope, {
                            title: $scope.title,
                            content: 'よろしいですか？',
                            button: [
                                {title: 'OK', _function: "delete('" + $id + "')", class:"btn-primary"},
                                {title: 'キャンセル', _function: 'commonPopupClose()', class:"btn-default"}
                            ]
                        });
                    };
                }

                if (typeof $scope.createSearchForm === 'undefined') {
                    $scope.createSearchForm = function () {
                        var form = '<tr>';
                        angular.forEach($scope.arrFields, function (v, i) {
                            var templateInput = '';
                            switch (v.type) {
                                case 'select':
                                    templateInput = $scope.createInputSelect(v, 'search');
                                    break;
                                default:
                                    templateInput = $scope.createInputText(v, 'search');
                                    break;
                            }

                            if (((i + 1) % 4) == 0) {
                                form += templateInput + '</tr><tr>';
                            } else {
                                form += templateInput;
                            }
                            // is last item 
                            if ((i + 1) == $scope.arrFields.length) {
                                var num = (i + 1) % 4;
                                if (num !== 0) {
                                    form += "<td colspan=" + 2 * (4 - num) + ">";
                                }
                            }
                        });
                        form += '</tr>';

                        var btnSearch = '<div class="btn-function">'
                                        +    '<button type="button" ng-click="getList()" class="btn btn-lg btn-primary margin-auto">'
                                        +       '<span><i class="fas fa-search"></i></span>&nbsp;検索'
                                        +    '</button>'
                                        +'</div>'

                        $scope.formSearch = '<table class="table table-bordered">'+ form +'</table>' + btnSearch;
                    };
                }

                if (typeof $scope.createInputText === 'undefined') {
                    $scope.createInputText = function (iTem, typeForm) {
                        var sAttr = createAttr(iTem.attr);
                        $sResult = '<th class="bg-color-grey th-width-1">' + iTem.label + '</th> '
                                + '<td>'
                                + '<input class="form-control" ' + sAttr + '  type="text" ng-model="' + typeForm + '.' + iTem.key + '">'
                                + '</td>';
                        return $sResult;
                    }
                }

                if (typeof $scope.createInputFile === 'undefined') {
                    $scope.createInputFile = function (iTem, typeForm) {
                        var sAttr = createAttr(iTem.attr);
                        $sResult = '<th class="bg-color-grey th-width-1">' + iTem.label + '</th> '
                                + '<td>'
                                + '<input type="file" name="file_upload" class="form-control" ' + sAttr + '  type="text" ng-model="' + typeForm + '.' + iTem.key + '">'
                                + '</td>';
                        return $sResult;
                    }
                }


                if (typeof $scope.createInputSelect === 'undefined') {
                    $scope.createInputSelect = function (iTem, typeForm) {
                        var sAttr = createAttr(iTem.attr);
                        $sResult = '<th class="bg-color-grey th-width-1" >' + iTem.label + '</th> '
                                + '<td>'
                                + '<select class="form-control" ng-model="' + typeForm + '.' + iTem.key + '" ' + sAttr + ' >'
                                + '<option value="">選択してください。</option>'
                                + '<option ng-repeat=" item in ' + iTem.data_source.data + ' " value="{{item.' + iTem.data_source.code_val + '}}">{{item.' + iTem.data_source.disp_val + '}}</option>'
                                + '</select>'
                                + '</td>';
                        return $sResult;
                    }
                }
                
                if (typeof $scope.createEditForm === 'undefined') {
                    $scope.createEditForm = function () {
                        var form = '';
                        angular.forEach($scope.arrFields, function (v, i) {
                            var templateInput = '';
                            switch (v.type) {
                                case 'select':
                                    templateInput = $scope.createInputSelect(v, 'current');
                                    break;
                                case 'file':
                                    templateInput = $scope.createInputFile(v, 'current');
                                    break;
                                default:
                                    templateInput = $scope.createInputText(v, 'current');
                                    break;
                            }
                            form += '<tr>' + templateInput + '</tr>';
                        });
                        $scope.formEdit = form;
                    };
                }

                if (typeof $scope.createTableHeader === 'undefined') {
                    $scope.createTableHeader = function () {
                        var header = '';
                        angular.forEach($scope.arrTableData, function (v, i) {
                            v = Object.assign(angular.copy($scope.templateRow),v);
                            if (typeof v.label == 'undefined') {
                                v.label = '';
                            }
                            header += '<th class="bg-color-grey '+v.class+'">' + v.label + '</th>';
                        });
                        $scope.tableHeader = '<tr>' + header + '</tr>';
                    };
                }


                if (typeof $scope.createTableContent === 'undefined') {
                    $scope.createTableContent = function () {
                        var content = '';
                        angular.forEach($scope.arrTableData, function (v, i) {
                            v = Object.assign(angular.copy($scope.templateRow),v);
                            if (typeof v.content == 'undefined') {
                                v.content = '';
                            }
                            content += '<td class="'+v.class+'">' + v.content + '</td>';

                        });
                        $scope.tableContent = '<tr dir-paginate="item in list|itemsPerPage:pageSize" current-page="currentPage" total-items="totalItems" class="view">' + content + '</tr>';
                    }
                }


                if (typeof $scope.initHeader === 'undefined') {
                    $scope.initHeader = function () {
                        setTimeout(function () {
                            $($scope.selector).fixedHeaderTable({
                                footer: false,
                                cloneHeadToFoot: true,
                                altClass: 'odd',
                                autoShow: true,
                                height: 480 ,
                                width:1000
                            });
                            
                            $('.fht-thead > table'+$scope.selector).css('width', $('.fht-tbody > table'+$scope.selector).css('width'));
                            $compile($($scope.selector).closest('.fht-thead'))($scope);
                        }, 500);    
                    }
                }


                if (typeof $scope.destroyHeader === 'undefined') {
                    $scope.destroyHeader = function () {
                        $($scope.selector).fixedHeaderTable('destroy');
                        var header = $($scope.selector);
                        if(typeof header != 'undefined' && header.length >= 2){
                            header[0].remove();
                        }
                    }
                };

                /** initApp */
                if (typeof $scope.initApp === 'undefined') {
                    $scope.initApp = function () {
                        $scope.getList();
                    };
                }
            }

            /** helper */
            function createAttr(arrAttr) {
                var result = '';
                if (arrAttr.length > 0) {
                    result = arrAttr.join(' ');
                }
                return result;
            }

            __construct();
            $scope.createSearchForm();
            $scope.createEditForm();
            $scope.createTableHeader();
            $scope.createTableContent();
            $scope.initApp();

        }
    };
});