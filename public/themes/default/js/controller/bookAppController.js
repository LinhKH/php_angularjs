bookApp.controller('bookAppController', ['$scope', '$rootScope', '$q', '$location', '$sce',
function ($scope, $rootScope, $q, $location, $sce) {
  console.log('bookAppController');
  $scope.firstName = 'Linh';
  $scope.lastName = 'Kieu';
  
  $rootScope.listApoBusinessId = [];
  $rootScope.currentBusinessId = '';
  $rootScope.mode = 'detail';
  $rootScope.unitcdSearch = 1;
  $rootScope.app = {
      // baseUrl: commonService.baseURL,
      // imgUrl: commonService.baseURL + 'assets/images/',
      userInfo: userInfo,
      class: '',
      callTab: userInfo.main_sale_item_cd,
      windowWidth: $(window).width(),
      windowHeight: $(window).height()
  };
}
]);
