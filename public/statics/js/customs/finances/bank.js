angular.module('app', []).controller('appCtrl', ['$http', finance_init]);

function finance_init($http){
    var vm = this;

    vm.bank = {};
	vm.bank_list = {};

    $('#bank_loader_list').hide();

    vm.SubmitBank = function (){
        $('#submit_bank_form').html('<i class="fa fa-spinner fa-spin fa-2x"></i>');
        $('#submit_bank_form').attr('disabled', 'disabled');

        $http.post('bank/save', vm.bank).success(function(res) {
            console.log(vm.bank);
            vm.bank = {};
            $('#submit_bank_form').html('Crear Bank');
            $('#submit_bank_form').removeAttr('disabled');
            if(res.status){
                vm.bank_list = res.data;
                $('#bank_list_msg').html('');
                $('#bank_msg').html('<div class="alert alert-success" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+res.msg+'</div>')
            } else {
                $('#bank_msg').html('<div class="alert alert-warning" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+res.msg+'</div>');
            }
        }).error(function (res){
            console.log(res);
        });        
    }//vm.SubmitBank

    vm.GetBankList = function ()
    {
        vm.bank_list = {};
        $('#bank_loader_list').show();
        $http.post('bank/list').success(function(res) {
            $('#bank_loader_list').hide();
            console.log(res.data);
            if(res.status){
                vm.bank_list = res.data;
            } else {
                $('#bank_list_msg').html('<div class="alert alert-warning" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+res.msg+'</div>');
            }
        }).error(function (res){
                console.log(res);
        });
    }//vm.GetBankList

}//raw_material_init