AgencyManagementApp.controller('MasterDocumentCtrl', ['$scope', '$rootScope', 'commonService', '$timeout', '$compile','fileUpload',
    function ($scope, $rootScope, commonService, $timeout, $compile, fileUpload) {
        $scope.urlList = 'listMasterDoc';
        $scope.urlUpsert = 'upsertMasterDoc';
        $scope.urlDelete = 'deleteMasterDoc';
        $scope.urlDetail = 'detailMasterDoc';
        $scope.title = '資料マスタ';

        /** List form fields */
        $scope.arrFields = [
            {   
                'key':'doc_name','label':'資料名','type': 'text','data_source':{},'attr':[]
            },
            {
                'key':'doc_filename','label':'ファイル名','type': 'text','data_source':{},'attr':[]
            },
        ];

        /** Config table seach result */
        $scope.arrTableData = [
            {
                'label':'No','content':'{{($index+1) + (currentPage-1)*pageSize}}'
            },
            {
                'label':'資料名','content':'{{item.doc_name}}'
            },
            {
                'label':'ファイル名','content':'<a class="pointer" ng-click="download(item.doc_url,item.doc_filename)">{{item.doc_filename}}</a>'
            },
            {
                'label':'更新日','content':'{{item.update_time}}'
            },
            {
                'label':'編集','class':'text-center',
                'content': '<button ng-click="edit(item.id)" class="btn btn-primary" type="button">編集</button>'
                                    +'<button ng-click="confirmDelete(item.id)" class="btn btn-danger" type="button">削除</button>'
            }
        ];
        
        $scope.createTableContent = function () {
            var content = '';
            var count = 1; 
            angular.forEach($scope.arrTableData, function (v, i) {
                if (typeof v.content == 'undefined') {
                    v.content = '';
                }
                if(count==1) {
                    content += '<td class="col-sm-1 col-md-1 col-lg-1" style="width:3% !important;">' + v.content + '</td>';
                } else if (count==4) {
                    content += '<td class="col-sm-1 col-md-1 col-lg-1" align="center">' + v.content + '</td>';
                } else {
                    content += '<td class="col-sm-1 col-md-1 col-lg-1 '+v.class+'">' + v.content + '</td>';
                }
                // align="center"
                count++;
            });
            $scope.tableContent = '<tr dir-paginate="item in list|itemsPerPage:pageSize" current-page="currentPage" total-items="totalItems" class="view">' + content + '</tr>';
        };
        
        $scope.createTableHeader = function () {
            var header = '';
            var count = 1; 
            angular.forEach($scope.arrTableData, function (v, i) {
                if (typeof v.label == 'undefined') {
                    v.label = '';
                }
                if(count==1) {
                    header += '<th class="col-sm-1 col-md-1 col-lg-1 bg-color-grey" style="width:3% !important;">' + v.label + '</th>';
                } else {
                    header += '<th class="col-sm-1 col-md-1 col-lg-1 bg-color-grey">' + v.label + '</th>';
                }
                count++;
            });
            $scope.tableHeader = '<tr>' + header + '</tr>';
        };

        $scope.add = function(){
            $scope.mode = 'add';
            $scope.current = {};
            $('input#file_upload_doc_filename').val(null);
            $('#modalUpsertDoc').modal('show');
        }

        $scope.edit = function ($id) {
            $scope.mode = 'edit';
            $rootScope.startUi();
            commonService.requestFunction($scope.urlDetail, {id: $id}, function (res) {
                if (res.code === 200) {
                    $('input#file_upload_doc_filename').val(null);
                    $scope.current = res.data;
                    $('#modalUpsertDoc').modal('show');
                }
                $rootScope.stopUi();
            });
        };

        $scope.saveUpsert = function () {
            $rootScope.startUi();
            var file = $('input#file_upload_doc_filename');
            fileUpload.uploadFileToUrl(file, $scope.current, $scope.urlUpsert, function (res) {
                if (res.code === 200) {
                    $scope.getList();
                    $('#modalUpsertDoc').modal('hide');
                }
                $rootScope.stopUi();
            });
        };
        
        $scope.$on('$viewContentLoaded', function () {
            
        });


    }
]);
