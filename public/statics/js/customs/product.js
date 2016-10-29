angular.module('app', []).controller('appCtrl', ['$http', products_init]);

function products_init($http){
    var vm = this;

    vm.product = {};
	vm.provider_list = {};
	vm.product.type = null;
	vm.product.type = product_type;
    vm.material_type_list = {};

	$('#select_provider').hide();
    $('#select_material_type').hide();
    
	vm.GetProviderList = function ()
	{
        $('#select_provider').show();
        console.log(vm.product.type);
        $http.post('../provider/list', {type: vm.product.type})
            .success(function(res) {
                $('#select_provider').hide();
                console.log(res.data);
                if(res.status){
                    vm.provider_list = res.data;
                } else {
                	$('#product_msg').html('<div class="alert alert-warning" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+res.msg+'</div>');
                    //alert(res.msg);
                }
        }).error(function (res){
                console.log(res);
        });
	}//vm.GetProviderList

    vm.GetMaterialTypeList = function ()
    {
        vm.material_type_list = {};
        $('#select_material_type').show();
        $http.post('material/type/list')
            .success(function(res) {
                $('#select_material_type').hide();
                console.log(res.data);
                if(res.status){
                    vm.material_type_list = res.data;
                } else {
                    $('#product_msg').html('<div class="alert alert-warning" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+res.msg+'</div>');
                }
        }).error(function (res){
                console.log(res);
        });
    }//vm.GetMaterialTypeList

}//products_init