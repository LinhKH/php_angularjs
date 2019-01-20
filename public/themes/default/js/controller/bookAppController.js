bookApp.controller('bookAppController', ['$scope', '$rootScope', '$q', '$location', '$sce', 'commonService','blockUI',
  function ($scope, $rootScope, $q, $location, $sce, commonService,blockUI) {

    $rootScope.listApoBusinessId = [];
    $rootScope.currentBusinessId = '';
    $rootScope.mode = 'detail';
    $rootScope.unitcdSearch = 1;
    $rootScope.app = {
      baseUrl: commonService.baseURL,
      imgUrl: commonService.baseURL + 'assets/images/',
      userInfo: userInfo,
      class: '',
      callTab: userInfo.main_sale_item_cd,
      windowWidth: $(window).width(),
      windowHeight: $(window).height()
    };
    $rootScope.copyToClipboard = function (val, breakLineFlg) {
      breakLineFlg = breakLineFlg | false;

      if (breakLineFlg) {
        var $temp = $('<textarea style="height: 0px">');
      } else {
        var $temp = $('<input style="height: 0px">');
      }

      $('body').append($temp);
      $temp.val(val).select();
      document.execCommand('copy');
      $temp.remove();
    };
    $rootScope.msg = {
      'MSGI0001': '検索結果が存在しません。',
      'MSGI0002': 'レコードのエックスポートが正常に完了しました。',
      'MSGI0003': '正常に完了しました。',
      'MSGI0004': 'この内容で登録しますか？',
    };

    $rootScope.stopUi = function (){
      blockUI.stop();
    };

    $rootScope.startUi = function(){
        blockUI.start();
    };


    $scope.$on('$viewContentLoaded', function () {
      setTimeout(function () {
          // init_app();
      }, 200);
      
    });
  }
]);
