angular.module('app', []).controller('appCtrl', ['$http', material_type_init]);

function material_type_init($http){
    var vm = this;

	vm.material_type = {};
	vm.material_type_list = {};

    vm.SubmitMaterialType = function (){
        $('#submit_material_type_form').html('<i class="fa fa-spinner fa-spin fa-2x"></i>');
        $('#submit_material_type_form').attr('disabled', 'disabled');

        $http.post('type/save', vm.material_type).success(function(res) {
            console.log(vm.material_type);
            vm.material_type = {};
            $('#submit_material_type_form').html('Guardar Tipo de Materia');
            $('#submit_material_type_form').removeAttr('disabled');
            if(res.status){
                vm.material_type_list = res.data;
                $('#material_type_list_msg').html('');
                $('#material_type_msg').html('<div class="alert alert-success" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+res.msg+'</div>')
            } else {
                $('#material_type_msg').html('<div class="alert alert-warning" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+res.msg+'</div>');
            }
        }).error(function (res){
            console.log(res);
        });        
    }//vm.SubmitPost

    vm.GetMaterialTypeList = function ()
    {
        vm.material_type_list = {};
        $('#select_material_type').show();
        $http.post('type/list').success(function(res) {
            $('#material_type_loader_list').hide();
            console.log(res.data);
            if(res.status){
                vm.material_type_list = res.data;
            } else {
                $('#material_type_list_msg').html('<div class="alert alert-warning" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+res.msg+'</div>');
            }
        }).error(function (res){
                console.log(res);
        });
    }//vm.GetMaterialTypeList

}//material_type_init