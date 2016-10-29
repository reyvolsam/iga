angular.module('app', []).controller('appCtrl', ['$http', internal_code_init]);

function internal_code_init($http){
    var vm = this;

    vm.internal_code = {};
}//internal_code_type_init