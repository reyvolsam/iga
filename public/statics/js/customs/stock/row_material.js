angular.module('app', []).controller('appCtrl', ['$http', raw_material_init]);

function raw_material_init($http){
    var vm = this;

    vm.move_list = {};
	vm.provider_list = {};
	vm.packs_list = {};
	vm.product_list = {};
	vm.product = {};
	vm.product.quantity = null;
	vm.product.lote = null;
	vm.product.id = null;
	vm.item_product_list = new Array();
	vm.product_list = {};
	vm.move = {};

	vm.ind_product = null;

	vm.stock_move_list = {}

    $('#move_id_loader').hide();
    $('#move_list_loader').hide();

    $('#provider_div').hide();
    $('#invoice_div').hide();
    $('#invoice_date_div').hide();
    $('#order_production_number_div').hide();
    $('#factory_product_div').hide();
    $('#factory_pieces_div').hide();
    $('#pack_send_div').hide();
    $('#pack_send_invoice_div').hide();
    $('#pack_send_cost_div').hide();

	vm.ChangeMove = function ()
	{
		console.log( 'id: '+vm.move.id );
		if(vm.move.id == null){
			console.log('entreeeee');
			vm.move.provider_id = null;
			$('#provider_div').hide('fast');
		}
		if(vm.move.id == 7 || vm.move.id == 4 || vm.move.id == 2){
			$('#provider_div').show();
			$('#invoice_div').hide();
			vm.move.invoice = '';
			$('#invoice_date_div').hide();
			vm.move.invoice_date = '';
			$('#order_production_number_div').hide();
			vm.move.order_production_number = '';
			$('#factory_product_div').hide();
			vm.move.factory_product = '';
			$('#factory_pieces_div').hide();
			vm.move.factory_pieces = '';
			$('#pack_send_div').hide();
			vm.move.pack_send = '';
			$('#pack_send_invoice_div').hide();
			vm.move.pack_send_invoice = '';
			$('#pack_send_cost_div').hide();
			vm.move.pack_send_cost = '';
		}
		if (vm.move.id == 6 || vm.move.id == 3) {
			console.log('s');
			$('#provider_div').show();
			$('#invoice_div').hide();
			vm.move.invoice = '';
			$('#invoice_date_div').hide();
			vm.move.invoice_date = '';
			$('#order_production_number_div').show();
			$('#factory_product_div').show();
			$('#factory_pieces_div').show();
			$('#pack_send_div').hide();
			vm.move.pack_send = '';
			$('#pack_send_invoice_div').hide();
			vm.move.pack_send_invoice = '';
			$('#pack_send_cost_div').hide();
			vm.move.pack_send_cost = '';
      	}
      	if (vm.move.id == 3) {
      		console.log('4');
			$('#provider_div').show();
			$('#invoice_div').show();
			$('#invoice_date_div').hide();
			vm.move.invoice_date = '';
			$('#order_production_number_div').hide();
			vm.move.order_production_number = '';
			$('#factory_product_div').hide();
			vm.move.factory_product = '';
			$('#factory_pieces_div').hide();
			vm.move.factory_pieces = '';
			$('#pack_send_div').hide();
			vm.move.pack_send = '';
			$('#pack_send_invoice_div').hide();
			vm.move.pack_send_invoice = '';
			$('#pack_send_cost_div').hide();
			vm.move.pack_send_cost = '';
      	}
      	if (vm.move.id == 1) {
      		console.log('1');
			$('#provider_div').show();
			$('#invoice_div').show();
			$('#invoice_date_div').show();
			$('#order_production_number_div').hide();
			vm.move.order_production_number = '';
			$('#factory_product_div').hide();
			vm.move.factory_product = '';
			$('#factory_pieces_div').hide();
			vm.move.factory_pieces = '';
			$('#pack_send_div').show();
			$('#pack_send_invoice_div').show();
			$('#pack_send_cost_div').show();
      	}
      	if (vm.move.id != 1 && vm.move.id != 3 && vm.move.id != 6 && vm.move.id != 3) {
      		console.log('0');
			$('#provider_div').show();
			$('#invoice_div').hide();
			vm.move.invoice = '';
			$('#invoice_date_div').hide();
			vm.move.invoice_date = '';
			$('#order_production_number_div').hide();
			vm.move.order_production_number = '';
			$('#factory_product_div').hide();
			vm.move.factory_product = '';
			$('#factory_pieces_div').hide();
			vm.move.factory_pieces = '';
			$('#pack_send_div').hide();
			vm.move.pack_send = '';
			$('#pack_send_invoice_div').hide();
			vm.move.pack_send_invoice = '';
			$('#pack_send_cost_div').hide();
			vm.move.pack_send_cost = '';
		}
	}//vm.ChangeMove

	vm.SubmitRawMaterialEntry = function ()
	{
		$('#submit_move_btn').html('<i class="fa fa-spinner fa-spin fa-2x"></i>');
        $('#submit_move_btn').attr('disabled', 'disabled');
        $http.post('entry/save', {move: vm.move, products: vm.item_product_list})
            .success(function(res) {
            	console.log(res);
				$('#submit_move_btn').html('Guardar Movimiento');
				$('#submit_move_btn').removeAttr('disabled');
                if(res.status){
					vm.product = {};
					vm.product.quantity = null;
					vm.product.lote = null;
					vm.product.id = null;
					vm.item_product_list = new Array();
					vm.move = {};
					vm.ind_product = null;
					vm.stock_move_list = res.data;
					$('#move_msg').html('<div class="alert alert-success" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+res.msg+'</div>');
                } else {
                	$('#move_msg').html('<div class="alert alert-warning" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+res.msg+'</div>');
                }
        }).error(function (res){
        	console.log(res);
			$('#submit_move_btn').html('Guardar Movimiento');
			$('#submit_move_btn').removeAttr('disabled');
        });
	}//SubmitRawMaterialEntry

	vm.ChangeProduct = function ()
	{
		for(i in vm.product_list){
			if( vm.product_list[i].id == vm.product.id ){
				vm.ind_product = i;
				vm.product.code = vm.product_list[i].code;
				vm.product.unit = vm.product_list[i].unit;
				vm.product.description = vm.product_list[i].description;
			}
		}
	}//vm.ChangeProduct

    vm.LoadDataRawMaterialEntry = function ()
    {
    	$('#submit_move_btn').html('<i class="fa fa-spinner fa-spin fa-2x"></i>');
		$('#submit_move_btn').attr('disabled', 'disabled');
		$('#move_id_loader').show();
        $http.post('entry/load')
            .success(function(res) {
            	//console.log(res);
            	$('#move_list_msg').html('');
    			$('#submit_move_btn').html('Guardar Movimiento');
				$('#submit_move_btn').removeAttr('disabled');
				$('#move_id_loader').hide();
                if(res.status){
                	//vm.stock_move_list = res.data;
                	vm.move.date 		= res.date;
                	vm.move_list 		= res.entry;
                	vm.provider_list 	= res.providers;
                	vm.packs_list 		= res.packs;
                	vm.move.type 		= 'Movimiento de Entrada de MP';
                	vm.product_list 	= res.products;
                } else {
                	$('#move_msg').html('<div class="alert alert-warning" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+res.msg+'</div>');
                }
        }).error(function (res){
        	console.log(res);
    		$('#submit_move_btn').html('Guardar Movimiento');
			$('#submit_move_btn').removeAttr('disabled');
			$('#move_msg').html('<div class="alert alert-warning" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+res.msg+'</div>');
        });		
    }//vm.LoadDataRawMaterialEntry
    
    vm.AddProductMove = function ()
    {
    	console.log(vm.product.quantity);
    	console.log(vm.product.id);
    	if( vm.product.quantity != null && vm.product.id != null ){
	    	var aux = {
	    			'id': vm.product_list[vm.ind_product].id,
			    	'code': vm.product_list[vm.ind_product].code,
			    	'unit': vm.product_list[vm.ind_product].unit,
			    	'name': vm.product_list[vm.ind_product].name,
			    	'description': vm.product_list[vm.ind_product].description,
			    	'quantity': vm.product.quantity,
			    	'lote': vm.product.lote
	    	};
	    	vm.item_product_list.push(aux);
	    	$('#move_msg').html('');
	    	vm.product.quantity = null;
	    	vm.product.lote = null;
	    	vm.product.id = null;
	    	vm.product.code = null;
	    	vm.product.unit =null;
	    	vm.product.description = null;
		} else {
			$('#move_msg').html('<div class="alert alert-warning" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>Por Favor, seleccione un Producto y/o seleccione una cantidad de Producto</div>');
		}
    }//vm.AddProductMove

    vm.DeleteProductItem = function (cont){
    	vm.item_product_list.splice(cont, 1);
    }

    vm.GetMoveList = function ()
    {
    	vm.stock_move_list = {};
    	$('#move_list_loader').show();
    	$('#move_list_msg').html('');
        $http.post('entry/list')
            .success(function(res) {
            	console.log(res);
            	$('#move_list_loader').hide();
            	if(res.status){
            		vm.stock_move_list = res.data;
                } else {
                	$('#move_list_msg').html('<div class="alert alert-warning" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+res.msg+'</div>');
                }
        }).error(function (res){
        	console.log(res);
        	$('#move_list_loader').hide();
        });		
    }//vm.GetMoveList

}//raw_material_init