angular.module('app', []).controller('appCtrl', ['$http', order_production_init]);

function order_production_init($http){
    var vm = this;

    vm.order_products_list = {};

    order_production_init();

    vm.product_type_list = {};
    vm.adjust_list = {};
    vm.class_list = {};
    vm.model_list = {};
    vm.feets_list = {};

	vm.products = Array();

    $('#product_type_select_loader_list').hide();

    function order_production_init()
    {
        vm.order_production = {};
        vm.order_production.observations = null;
        vm.order_production.type = null;
        vm.order_production.stock_location = null;
        vm.order_production.adjust = null;
        vm.order_production.color = null;
        vm.order_production.model = null;
        vm.order_production.class = null;
        vm.order_production.feets = null;
        vm.order_production.quantity = null;

        vm.order_production.total_pieces = 0;
    }//order_production_init

    vm.LoadProductTypes = function ()
    {
    	$('#product_type_select_loader_list').show();
        $http.post('order_production/data')
            .success(function(res) {
            	console.log(res);
            	$('#product_type_select_loader_list').hide();
                if(res.status){
                	vm.product_type_list = res.data;
                	vm.adjust_list = res.adjust;
                	vm.color_list = res.color;
                	vm.class_list = res.class;
                	vm.model_list = res.model;
                	vm.feets_list = res.feets;
                	vm.order_production.date = res.date;

                } else {
                	$('#save_order_production_msg').html('<div class="alert alert-warning" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+res.msg+'</div>');
                }
        }).error(function (res){
        	console.log(res);
        	$('#product_type_select_loader_list').hide();
        	console.log(res);
        });
    }//vm.LoadProductTypes

    $('#adjust_div').hide();
    $('#class_div').hide();
    $('#model_div').hide();
    $('#color_div').hide();
    $('#feets_div').hide();

    vm.ChangeProductType = function ()
    {
        for(i in vm.product_type_list){
            console.log(vm.order_production.type+' = '+vm.product_type_list[i].id);
            if(vm.order_production.type == vm.product_type_list[i].id){
                if(vm.product_type_list[i].adjust == 1){
                    $('#adjust_div').show();
                } else {
                    $('#adjust_div').hide();
                }
                if(vm.product_type_list[i].class == 1){
                    $('#class_div').show();
                } else {
                    $('#class_div').hide();
                }
                if(vm.product_type_list[i].model == 1){
                    $('#model_div').show();
                } else {
                    $('#model_div').hide();
                }
                if(vm.product_type_list[i].color == 1){
                    $('#color_div').show();
                } else {
                    $('#color_div').hide();
                }
                if(vm.product_type_list[i].feets == 1){
                    $('#feets_div').show();
                } else {
                    $('#feets_div').hide();
                }
            }
        }
    }//vm.ChangeProductType

    vm.AddProduct = function ()
    {
    	$('#add_product_btn').html('<i class="fa fa-spinner fa-spin fa-2x"></i>');
        $('#add_product_btn').attr('disabled', 'disabled');
        console.log(vm.order_production);
        $http.post('order_production/validate', {order_production: vm.order_production})
            .success(function(res) {
            	console.log(res);
    			$('#add_product_btn').html('<i class="icon-plus-sign"></i> Agregar Producto');
        		$('#add_product_btn').removeAttr('disabled');
                $('#save_order_production_msg').html('');
                if(res.status){
                	console.log(vm.order_production.quantity);
                	if(vm.order_production.quantity != null){
                		if(vm.order_production.quantity > 0){
		                	var aux = {
		                		'product_id': res.product.id,
		                		'product_unit': res.product.unit,
		                		'product_description': res.product.description,
		                		'quantity': vm.order_production.quantity,
                                'stock_location': vm.order_production.stock_location
		                	}
		                	vm.order_production.total_pieces = parseInt(vm.order_production.total_pieces)+parseInt(vm.order_production.quantity);
		                	vm.products.push(aux);
                            vm.order_production.stock_location = null;
		                	vm.order_production.adjust = null;
		                	vm.order_production.color = null;
		                	vm.order_production.class = null;
		                	vm.order_production.model = null;
		                	vm.order_production.feets = null;
		                	vm.order_production.quantity = null;
		                	console.log(vm.products);
                		} else {
							$('#save_order_production_msg').html('<div class="alert alert-warning" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>Introduzca una Cantidad para el Producto, mayor a 0</div>');	                	
                		}
	                } else {
						$('#save_order_production_msg').html('<div class="alert alert-warning" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>Introduzca una Cantidad para el Producto.</div>');	                	
	                }
                } else {
                	$('#save_order_production_msg').html('<div class="alert alert-warning" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+res.msg+'</div>');
                }
        }).error(function (res){
        	console.log(res);
            $('#save_order_production_msg').html('<div class="alert alert-danger" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>Error, contacte al Administrador del Sistema.</div>');
    		$('#add_product_btn').html('Guardar Orden de Producción');
        	$('#add_product_btn').removeAttr('disabled');
        });
    }//vm.AddProduct

    vm.SubmitOrderProduction = function ()
    {
    	$('#submit_order_production_btn').html('<i class="fa fa-spinner fa-spin fa-2x"></i>');
        $('#submit_order_production_btn').attr('disabled', 'disabled');
        $http.post('order_production/save', {order_production: vm.order_production, products: vm.products})
            .success(function(res) {
            	console.log(res);
    			$('#submit_order_production_btn').html('Guardar Orden de Producción');
        		$('#submit_order_production_btn').removeAttr('disabled');
                if(res.status){
                    order_production_init();
                    vm.products = Array();
                    vm.order_products_list = {};
                    vm.order_products_list = res.data;
                    $('#save_order_production_msg').html('');
					$('#order_production_list_msg').html('<div class="alert alert-success" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+res.msg+'</div>');                	
                    $('#order_production_modal').modal('toggle');
                } else {
                	$('#save_order_production_msg').html('<div class="alert alert-warning" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+res.msg+'</div>');
                }
        }).error(function (res){
        	console.log(res);
            $('#save_order_production_msg').html('<div class="alert alert-danger" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>Error, contacte al Administrador del Sistema.</div>');
    		$('#submit_order_production_btn').html('Guardar Orden de Producción');
        	$('#submit_order_production_btn').removeAttr('disabled');
        });
    }//vm.SubmitOrderProduction
 
    $('#order_production_list_loader').hide();

    vm.OrderProductionList = function()
    {
    	$('#order_production_list_loader').show();
        $http.post('order_production/list')
            .success(function(res) {
            	console.log(res);
            	$('#order_production_list_loader').hide();
                if(res.status){
             		vm.order_products_list = res.data;
                } else {
                	$('#save_order_production_msg').html('<div class="alert alert-warning" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+res.msg+'</div>');
                }
        }).error(function (res){
        	$('#order_production_list_loader').hide();
        	console.log(res);
        });
    }//vm.OrderProductionList

}//index_init