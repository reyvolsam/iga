angular.module('app', []).controller('appCtrl', ['$http', product_type_init]);

function product_type_init($http){
    var vm = this;

    vm.product_type = {};
    vm.product_type.name = null;
    vm.product_type.description = null;
    vm.product_type.adjust = false;
    vm.product_type.model = false;
    vm.product_type.class = false;
    vm.product_type.color = false;
    vm.product_type.feets = false;

	vm.product_type_list = {};

	$('#producto_type_list_loader').hide();

    vm.SubmitProductType = function ()
    {
    	console.log(vm.product_type);
		$('#product_type_btn').html('<i class="fa fa-spinner fa-spin fa-2x"></i>');
		$('#product_type_btn').attr('disabled', 'disabled');
        $http.post('save', vm.product_type)
            .success(function(res) {
                console.log(res);
				$('#product_type_btn').html('Guardar Tipo de Producto');
				$('#product_type_btn').removeAttr('disabled');
                if(res.status){
                	vm.product_type = {};
                	vm.product_type_list = res.data;
                	$('#product_type_msg').html('<div class="alert alert-success" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+res.msg+'</div>');
                } else {
                    $('#product_type_msg').html('<div class="alert alert-warning" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+res.msg+'</div>');
                }
        }).error(function (res){
			$('#product_type_btn').html('Guardar Tipo de Producto');
			$('#product_type_btn').removeAttr('disabled');
            console.log(res);
        });
    }//SubmitProductType

    vm.ProductTypeList = function ()
    {
    	$('#producto_type_list_loader').show();
        $http.post('list', vm.product_type)
            .success(function(res) {
                console.log(res);
                $('#producto_type_list_loader').hide();
                if(res.status){
                	vm.product_type_list = res.data;
                } else {
                    $('#product_type_msg').html('<div class="alert alert-warning" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+res.msg+'</div>');
                }
        }).error(function (res){
            console.log(res);
        });
    }//vm.ProductTypeList

}//index_init