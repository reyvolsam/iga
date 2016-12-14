angular.module('app', []).controller('appCtrl', ['$http', requisition_init]);

function requisition_init($http){
    var vm = this;

    $("[data-mask]").inputmask();
    vm.page = 1;
    vm.requirement_list = {};
	vm.requirement = {};
	vm.requirement.id = null;
	vm.requirement.subtotal = 0;
	vm.requirement.iva = 0;
	vm.requirement.total = 0;

	Product_Init();
	Finances_Init();

	function Finances_Init()
	{
		vm.finances = {};
		vm.finances.importe = 0;
		vm.finances.dollar_value = 0;
		vm.finances.dollar_price = 0;
		vm.finances.pesos_price = 0;
	}//Finances_Init()

	vm.products_list = Array();
	vm.product_list_select = {};

	function Product_Init()
	{
		vm.product = {};
		vm.product.catalog = {};
		vm.product.catalog.id = null;
		vm.product.catalog.name = null;
		vm.product.catalog.unit = null;
		vm.product.catalog.description = null;
		vm.product.catalog.use = null;
		vm.product.catalog.pieces = null;
		vm.product.new = {};
		vm.product.new.id = null;
		vm.product.new.name = null;
		vm.product.new.unit = null;
		vm.product.new.new_unit = null;
		vm.product.new.description = null;
		vm.product.new.use = null;
		vm.product.new.pieces = null;
	}
	$('#requisition_list_loader').hide();

	$('#pesos_price_div').hide();
	$('#importe_div').hide();

	$('#catalog_product_div').hide();
	$('#new_product_div').hide();

	$('#select_product').hide();

	$('#save_requisition_modal').on('shown.bs.modal', function () {
  		$('#save_requisition_msg').html('');
	});
	
	function OrderProductionList()
	{
		$('#requisition_list_loader').show();
        $http.post('requisition/list')
            .success(function(res) {
            	console.log(res);
            	$('#requisition_list_loader').hide();
                if(res.status){
                	vm.requirement_list = res.data;
                	RenderPage(res.tp);
                } else {
                	$('#requisition_list_msg').html('<div class="alert alert-warning" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+res.msg+'</div>');	
                }
        }).error(function (res){
        	$('#requisition_list_loader').hide();
            console.log(res);
        });
	}//OrderProductionList

    vm.OrderProductionList = function ()
    {
        OrderProductionList();
    }//vm.GetProviderList

    function RenderPage(tp)
    {
        $('.pagination').html('');
        if(tp > 1){
            var i = 1;
            for(i; i <= tp; i++){
                var actived = '';
                if(i == vm.page){
                    actived = 'active';
                }
                $('.pagination').append('<li class ="item_paginador '+actived+'"><a href="#">'+i+'</a></li>');
            }

            $('.item_paginador').on('click', function (e){
                e.preventDefault();
                vm.page = $(this).text();
                GetClientsList();
            });
        }
    }//PageRender

	vm.SubmitRequisition = function ()
	{
		console.log(vm.requirement);
		console.log(vm.products_list);
		submit_requisition_btn
        $('#submit_requisition_btn').html('<i class="fa fa-spinner fa-spin fa-2x"></i>');
        $('#submit_requisition_btn').attr('disabled', 'disabled');
        $('#cancel_requirement_btn').attr('disabled', 'disabled');
        $http.post('requisition/save',{ requirement: vm.requirement, products: vm.products_list })
            .success(function(res) {
            	console.log(res);
        		$('#submit_requisition_btn').html('Guardar Requisición');
        		$('#submit_requisition_btn').removeAttr('disabled');
        		$('#cancel_requirement_btn').removeAttr('disabled');
                if(res.status){
					vm.requirement = {};
					vm.requirement.id = null;
					vm.requirement.subtotal = 0;
					vm.requirement.iva = 0;
					vm.requirement.total = 0;
                	Product_Init();
                	Finances_Init();
					vm.products_list = Array();
					vm.product_list_select = {};
                	vm.requirement_list = res.data;
                	RenderPage(res.tp);
					$('#save_requisition_msg').html('<div class="alert alert-success" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+res.msg+'</div>');					
                } else {
					$('#save_requisition_msg').html('<div class="alert alert-warning" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+res.msg+'</div>');
                }
        }).error(function (res){
            console.log(res);
    		$('#submit_requisition_btn').html('Guardar Requisición');
    		$('#submit_requisition_btn').removeAttr('disabled');
    		$('#cancel_requirement_btn').removeAttr('disabled');
        });			
	}//vm.SubmitRequisition()

	vm.GetDate = function ()
	{
        $http.post('requisition/get_date')
            .success(function(res) {
                if(res.status){
                	vm.requirement.requested_date = res.data;	
                }
        }).error(function (res){
            console.log(res);
        });
	}//vm.GetDate()

	vm.ChangeProductType = function ()
	{
		if(vm.requirement.product_type == 'catalog'){
			$('#catalog_product_div').show();
			$('#new_product_div').hide();
		} else if(vm.requirement.product_type == 'no_catalog'){
			$('#new_product_div').show();
			$('#catalog_product_div').hide();
		} else {
			$('#catalog_product_div').hide();
			$('#new_product_div').hide();
		}
		$('#pesos_price_div').hide();
		$('#importe_div').hide();
		$('#dollar_value_id').hide();
		$('#dollar_price_div').hide();
		vm.requirement.filter_product = '';
		vm.product_list_select = {};
		Product_Init();
		vm.finances = {};
		vm.finances.pesos_price = 0;
		vm.finances.importe = 0;
		vm.finances.dollar_value = 0;
		vm.finances.dollar_price = 0;
		vm.finances.pesos_price = 0;
		vm.finances.money_type = '';
	}//vm.ChangeProductType

	vm.ChangeFilterProduct = function ()
	{
		console.log(vm.requirement.filter_product);
		$('#select_product').show();
		vm.product_list_select = {};
		vm.product = {};
		$('#filter_product').attr('disabled', 'disabled');
        $http.post('requisition/product', { type: vm.requirement.filter_product })
            .success(function(res) {
            	console.log(res);
            	$('#filter_product').removeAttr('disabled');
            	$('#select_product').hide();
                if(res.status){
                	vm.product_list_select = res.data;
                } else {
                	$('#product_msg').html('<div class="alert alert-warning" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+res.msg+'</div>');
                }
        }).error(function (res){
        	$('#filter_product').removeAttr('disabled');
        	$('#select_product').hide();
        	$('#product_msg').html('<div class="alert alert-warning" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>¡Error!</div>');
            console.log(res);
        });
	}//vm.ChangeFilterProduct()

	vm.ChangeProduct = function ()
	{
		for(i in vm.product_list_select){
			if(vm.product.catalog.id == vm.product_list_select[i].id){
				vm.product.catalog.name = vm.product_list_select[i].description;
				vm.product.catalog.unit = vm.product_list_select[i].unit;
				vm.product.catalog.description = vm.product_list_select[i].description;
				break;
			}
		}
	}//vm.ChangeProduct()

	$('#new_unit_div').hide();

	vm.ChangeUnit = function ()
	{
		if(vm.product.new.unit == 'Other'){
			$('#new_unit_div').show();
		} else {
			$('#new_unit_div').hide();
		}
	}//vm.ChangeUnit()

	$('#dollar_value_id').hide();
	$('#dollar_price_div').hide();

	vm.ChangeMoneyType = function()
	{
		$('#product_msg').html('');
		if(vm.finances.money_type == 'USD'){
			$('#pesos_price_div').show();
			$('#importe_div').show();
			$('#dollar_value_id').show();
			$('#dollar_price_div').show();
			$('#pesos_price').attr('disabled', 'disabled');
		} else if(vm.finances.money_type == 'MX'){
			$('#pesos_price_div').show();
			$('#importe_div').show();
			$('#dollar_value_id').hide();
			$('#dollar_price_div').hide();
			$('#pesos_price').removeAttr('disabled');
		} else {
			$('#pesos_price_div').hide();
			$('#importe_div').hide();
			$('#dollar_value_id').hide();
			$('#dollar_price_div').hide();
		}
		vm.finances.importe = 0;
		vm.finances.dollar_value = 0;
		vm.finances.dollar_price = 0;
		vm.finances.pesos_price = 0;
	}//vm.ChangeMoneyType()

	vm.ChangeDollarValue = function ()
	{
		var r = CheckProduct();
		if( r == true ){
			$('#product_msg').html('');
			if( vm.finances.dollar_value > 0 ){
				//alert('mayor a 0');
			} else {
				vm.finances.dollar_value = 0;
			}
		} else {
			$('#product_msg').html('<div class="alert alert-warning" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>Todos los Campos del Producto deben de estar llenos</div>');
			vm.finances.dollar_value = 0;
		}
	}//vm.ChangeDollarValue()

	function CheckProduct()
	{

		if(vm.requirement.product_type == 'catalog'){
			if( typeof(vm.requirement.filter_product) === "undefined" || vm.requirement.filter_product.length == 0 
				|| vm.product.catalog.id == null
				|| vm.product.catalog.unit == null
				|| vm.product.catalog.description == null
				|| vm.product.catalog.use == null
				|| vm.product.catalog.pieces == null ){
				return false;
			} else {
				return true;
			}
		} else if( vm.product.new.name == null
					|| vm.product.new.unit == null
					|| vm.product.new.use == null
					|| vm.product.new.description == null
					|| vm.product.catalog.pieces == null ){
			return false;
		} else {
			if( vm.product.new.unit == 'Other' ){
				if( typeof(vm.product.new.new_unit) === "undefined" || vm.product.new.new_unit == 0  ){
					return false;
				} else {
					return true;
				}
			}
		}
		
	}//CheckProduct

	vm.ChangeDollarPrice = function ()
	{
		var pmx = 0;
		var importe = 0;
		
		var msg = 'Introduzca un número valido en la Piezas del producto.';
		if( vm.requirement.product_type == 'catalog' ){
			if( is_number( vm.product.catalog.pieces ) == true ){
				pmx = parseFloat(vm.finances.dollar_value) * parseFloat(vm.finances.dollar_price);
				vm.finances.pesos_price = pmx;
				importe = parseFloat(vm.finances.pesos_price) *	parseInt(vm.product.catalog.pieces);
				vm.finances.importe = parseFloat(importe);
				$('#add_product_item_btn').removeAttr('disabled');
				$('#product_msg').html('');
			} else {
				$('#add_product_item_btn').attr('disabled', 'disabled');
				$('#product_msg').html('<div class="alert alert-warning" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+msg+'</div>');
			}
		}
		if( vm.requirement.product_type == 'no_catalog' ){
			if( is_number( vm.product.new.pieces ) == true ){
				pmx = parseFloat(vm.finances.dollar_value) * parseFloat(vm.finances.dollar_price);
				vm.finances.pesos_price = pmx;
				importe = parseFloat(vm.finances.pesos_price) *	parseInt(vm.product.new.pieces);
				vm.finances.importe = parseFloat(importe);
				$('#add_product_item_btn').removeAttr('disabled');
				$('#product_msg').html('');
			} else {
				$('#add_product_item_btn').attr('disabled', 'disabled');
				$('#product_msg').html('<div class="alert alert-warning" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+msg+'</div>');
			}
		}
	}//vm.ChangeDollarPrice

	vm.ChangePesosPrice = function ()
	{
		var importe = 0;
		var msg = 'Introduzca un número valido en la Piezas del producto.';
		if( vm.requirement.product_type == 'catalog' ){
			if( vm.product.catalog.pieces == null ){
				$('#add_product_item_btn').attr('disabled', 'disabled');
				$('#product_msg').html('<div class="alert alert-warning" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+msg+'</div>');
			} else {
				if( is_number( vm.product.catalog.pieces ) == true ){
					$('#add_product_item_btn').removeAttr('disabled');
					$('#product_msg').html('');
					importe = parseFloat(vm.finances.pesos_price) *	parseInt(vm.product.catalog.pieces);
				} else {
					$('#add_product_item_btn').attr('disabled', 'disabled');
					$('#product_msg').html('<div class="alert alert-warning" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+msg+'</div>');
				}
			}
		}
		if( vm.requirement.product_type == 'no_catalog' ){
			if( vm.product.new.pieces == null ){
				$('#add_product_item_btn').attr('disabled', 'disabled');
				$('#product_msg').html('<div class="alert alert-warning" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+msg+'</div>');
			} else {
				if( is_number( vm.product.new.pieces ) == true ){
					$('#add_product_item_btn').removeAttr('disabled');
					$('#product_msg').html('');
					importe = parseFloat(vm.finances.pesos_price) *	parseInt(vm.product.new.pieces);
				} else {
					$('#add_product_item_btn').attr('disabled', 'disabled');
					$('#product_msg').html('<div class="alert alert-warning" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+msg+'</div>');
				}
			}
		}

		vm.finances.importe = parseFloat(importe);
	}//vm.ChangePesosPrice

	vm.ChangePiecesCatalog = function ()
	{
		ValidatePieces();
	}//vm.ChangePiecesCatalog

	vm.ChangePiecesNew = function ()
	{
		ValidatePieces();
	}//vm.ChangePiecesNew

	function ValidatePieces()
	{
		var importe = 0;
		var pmx = 0;
		var msg = 'Introduzca un número valido en la Piezas del producto.';
		if( vm.requirement.product_type == 'catalog' ){
			CalculateImport();
		}
		if( vm.requirement.product_type == 'no_catalog' ){
			CalculateImport();
		}
	}//vm.ChangePiecesNew

	function CalculateImport()
	{
		var msg = 'Introduzca un número valido en la Piezas del producto.';
		var ban = false;
		var pieces = 0;
		if( vm.requirement.product_type == 'catalog' ){
			if( is_number(vm.product.catalog.pieces) == true ){
				pieces = vm.product.catalog.pieces;
				ban = true;
			} else {
				$('#add_product_item_btn').attr('disabled', 'disabled');
				$('#product_msg').html('<div class="alert alert-warning" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+msg+'</div>');
			}		
		}
		if( vm.requirement.product_type == 'no_catalog' ){
			if( is_number(vm.product.new.pieces) == true ){
				pieces = vm.product.new.pieces;
				ban = true;
			}
		}
		if(ban = true){
			if(vm.finances.money_type == 'USD'){
				pmx = parseFloat(vm.finances.dollar_value) * parseFloat(vm.finances.dollar_price);
				vm.finances.pesos_price = pmx;
			}
			importe = parseFloat(vm.finances.pesos_price) * parseInt(pieces);
			vm.finances.importe = importe;
			$('#add_product_item_btn').removeAttr('disabled');
			$('#product_msg').html('');
		}
	}//CalculateImport

	vm.AddProductItem = function ()
	{
		var r = CheckProduct();
		if(r == true){
			var aux = {
				"product_type": vm.requirement.product_type,
				"filter_product": vm.requirement.filter_product,
				"product_id": '',
				"product_name": '',
				"product_unit": '',
				"product_new_unit": '',
				"product_description": '',
				"product_use": '',
				"product_pieces": 0,
				"money_type": '',
				"dollar_value": 0,
				"dollar_price": 0,
				"pesos_price": 0,
				"importe": 0,
			};
			if(vm.requirement.product_type == 'catalog'){
				aux.product_id 			= vm.product.catalog.id;
				aux.product_name 		= vm.product.catalog.description;
				aux.product_unit 		= vm.product.catalog.unit;
				aux.product_description = vm.product.catalog.description;
				aux.product_use 		= vm.product.catalog.use;
				aux.product_pieces 		= vm.product.catalog.pieces;
				aux.money_type 			= vm.finances.money_type;
				aux.dollar_value 		= vm.finances.dollar_value;
				aux.dollar_price 		= vm.finances.dollar_price;
				aux.pesos_price 		= vm.finances.pesos_price;
				aux.importe 			= vm.finances.importe;
			}
			if(vm.requirement.product_type == 'no_catalog'){
				aux.product_id 			= null;
				aux.product_name 		= vm.product.new.name;
				aux.product_unit 		= vm.product.new.unit;
				aux.product_new_unit 	= vm.product.new.new_unit;
				aux.product_description = vm.product.new.description;
				aux.product_use 		= vm.product.new.use;
				aux.product_pieces 		= vm.product.new.pieces;
				aux.money_type 			= vm.finances.money_type;
				aux.dollar_value 		= vm.finances.dollar_value;
				aux.dollar_price 		= vm.finances.dollar_price;
				aux.pesos_price 		= vm.finances.pesos_price;
				aux.importe 			= vm.finances.importe;
			}
			vm.products_list.push(aux);
			Product_Init();

			vm.finances = {};
			vm.finances.importe = 0;
			vm.finances.dollar_value = 0;
			vm.finances.dollar_price = 0;
			vm.finances.pesos_price = 0;
			vm.product_list_select = {};
			vm.product.catalog.id = null;
			$('#pesos_price_div').hide();
			$('#importe_div').hide();

			$('#catalog_product_div').hide();
			$('#new_product_div').hide();
			vm.requirement.filter_product = '';
			vm.requirement.product_type = '';
			CalculateTotal();
			console.log(vm.products_list);
		} else {
			$('#product_msg').html('<div class="alert alert-warning" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>Todos los Campos del Producto son obligatorios.</div>');
		}
	}//vm.AddProductItem()


	function CalculateTotal()
	{
		var subt = 0;
		for(i in vm.products_list ){
			subt = parseFloat(subt)+parseFloat(vm.products_list[i].importe);
		}
		vm.requirement.subtotal = subt;
		vm.requirement.iva = 0;
		vm.requirement.total = subt;
	}//CalculateTotal

	vm.ChangeIVA = function ()
	{
		var iva = 0;
		vm.requirement.total = 0;
		console.log(vm.requirement.iva);
		if( vm.requirement.iva.length != 0 ){
			iva = (vm.requirement.subtotal * parseFloat(vm.requirement.iva) )/100;
			vm.requirement.total = parseFloat(vm.requirement.subtotal) + parseFloat(iva);
			vm.requirement.total = vm.requirement.total.toFixed(2);

		}
	}//vm.ChangeIVA

	function is_number(numero)
	{
    	if(isNaN(numero) ){
      		return false;
    	} else {
    		return true;
    	}
  	}//is_number

  	vm.EditRequirement = function (ind)
  	{
  		console.log(vm.requirement_list[ind].id);
  		vm.requirement.id = vm.requirement_list[ind].id;
  		vm.requirement.requested_date = vm.requirement_list[ind].requested_date;
  		vm.requirement.required_date = vm.requirement_list[ind].required_date;

  		vm.products_list = vm.requirement_list[ind].products;

		vm.requirement.subtotal = vm.requirement_list[ind].subtotal;
		vm.requirement.iva = vm.requirement_list[ind].iva;
		vm.requirement.total = vm.requirement_list[ind].total;

		vm.requirement.use = vm.requirement_list[ind].use;
		vm.requirement.observations = vm.requirement_list[ind].observations;
  		$('#save_requisition_modal').modal('toggle');
  	}//EditRequirement

  	vm.DeleteRequirement = function (ind)
  	{
  		var r = confirm('¿Desea Eliminar esta Requisición?');
  		if(r == true){
  			$('#req_del_'+vm.requirement_list[ind].id).html('<i class="fa fa-spinner fa-spin fa-1x"></i>');
  			$('#req_del_'+vm.requirement_list[ind].id).attr('disabled', 'disabled');
	        $http.post('requisition/delete', { id:vm.requirement_list[ind].id })
	            .success(function(res) {
	            	console.log(res);
                    $('#req_del_'+vm.requirement_list[ind].id).removeAttr('disabled');
                    $('#req_del_'+vm.requirement_list[ind].id).html('<i class="fa fa-trash"></i>');
	                if(res.status){
                        vm.requirement_list = res.data;
                        RenderPage(res.tp);
	                } else {
						$('#requisition_list_msg').add('<div class="alert alert-warning" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+res.msg+'</div>');
	                }
	        }).error(function (res){
	            console.log(res);
	        });
  		}
  	}//DeleteRequirement

}//index_init