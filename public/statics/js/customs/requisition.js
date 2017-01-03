angular.module('app', [])
	.controller('appCtrl', ['$http', requisition_init]);


function requisition_init($http){
    var vm = this;

    $("[data-mask]").inputmask();
    vm.page = 1;
    vm.filter_user = 'all';
    vm.ind = null;
    vm.order_id = null;

    vm.requisition_list = {};
	vm.requisition = {};
	vm.requisition.id = null;
	vm.requisition.subtotal = 0;
	vm.requisition.iva = 0;
	vm.requisition.total = 0;

	Product_Init();
	Finances_Init();


	vm.products_list = Array();
	vm.product_list_select = {};

	vm.order_buy = {};
	vm.order_buy.date = null;
	vm.order_buy.pay_conditions = null;
	vm.order_buy.provider_id = null;
	vm.order_buy.deliver_place = null;
	vm.order_buy.new_place = null;
	vm.order_buy.order_observations = null;

	vm.order_buy_list = Array();
	vm.providers_list_select = {};

	function Finances_Init()
	{
		vm.finances = {};
		vm.finances.importe = 0;
		vm.finances.dollar_value = 0;
		vm.finances.dollar_price = 0;
		vm.finances.pesos_price = 0;
	}//Finances_Init()

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
	
	vm.ChangeFilterUser = function ()
	{
		RequisitionList();
	}//vm.ChangeFilterUser()

	function RequisitionList()
	{
		vm.requisition_list = {};
		$('#requisition_list_loader').show();
        $http.post('requisition/list', { page: vm.page, filter_user: vm.filter_user })
            .success(function(res) {
            	console.log(res);
            	$('#requisition_list_loader').hide();
                if(res.status){
                	vm.requisition_list = res.data;
                	RenderPage(res.tp);
                } else {
                	$('#requisition_list_msg').html('<div class="alert alert-warning" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+res.msg+'</div>');	
                }
        }).error(function (res){
        	$('#requisition_list_loader').hide();
            console.log(res);
        });
	}//OrderProductionList

    vm.RequisitionList = function ()
    {
        RequisitionList();
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
                RequisitionList();
            });
        }
    }//PageRender

	vm.SubmitRequisition = function ()
	{
		console.log(vm.requisition);
		console.log(vm.products_list);
        $('#submit_requisition_btn').html('<i class="fa fa-spinner fa-spin fa-2x"></i>');
        $('#submit_requisition_btn').attr('disabled', 'disabled');
        $('#cancel_requirement_btn').attr('disabled', 'disabled');
        $http.post('requisition/save',{ requisition: vm.requisition, products: vm.products_list, page: vm.page, filter_user: vm.filter_user })
            .success(function(res) {
            	console.log(res);
        		$('#submit_requisition_btn').html('Guardar Requisición');
        		$('#submit_requisition_btn').removeAttr('disabled');
        		$('#cancel_requisition_btn').removeAttr('disabled');
                if(res.status){
					vm.requisition = {};
					vm.requisition.id = null;
					vm.requisition.subtotal = 0;
					vm.requisition.iva = 0;
					vm.requisition.total = 0;
                	Product_Init();
                	Finances_Init();
					vm.products_list = Array();
					vm.product_list_select = {};
                	vm.requisition_list = res.data;
                	RenderPage(res.tp);
                	$('#requisition_list_msg').html('');
					$('#save_requisition_msg').html('<div class="alert alert-success" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+res.msg+'</div>');					
                } else {
					$('#save_requisition_msg').html('<div class="alert alert-warning" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+res.msg+'</div>');
                }
        }).error(function (res){
            console.log(res);
    		$('#submit_requisition_btn').html('Guardar Requisición');
    		$('#submit_requisition_btn').removeAttr('disabled');
    		$('#cancel_requisition_btn').removeAttr('disabled');
        });			
	}//vm.SubmitRequisition()

	vm.GetDate = function ()
	{
        $http.post('requisition/get_date')
            .success(function(res) {
                if(res.status){
                	vm.requisition.requested_date = res.data;	
                }
        }).error(function (res){
            console.log(res);
        });
	}//vm.GetDate()

	vm.ChangeProductType = function ()
	{
		if(vm.requisition.product_type == 'catalog'){
			$('#catalog_product_div').show();
			$('#new_product_div').hide();
		} else if(vm.requisition.product_type == 'no_catalog'){
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
		vm.requisition.filter_product = '';
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
		console.log(vm.requisition.filter_product);
		$('#select_product').show();
		vm.product_list_select = {};
		vm.product = {};
		$('#filter_product').attr('disabled', 'disabled');
        $http.post('requisition/product', { type: vm.requisition.filter_product })
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

		if(vm.requisition.product_type == 'catalog'){
			if( typeof(vm.requisition.filter_product) === "undefined" || vm.requisition.filter_product.length == 0 
				|| vm.product.catalog.id == null
				|| vm.product.catalog.unit == null
				|| vm.product.catalog.description == null
				|| vm.product.catalog.use == null
				|| vm.product.catalog.pieces == null ){
				console.log('primer r');
				return false;
			} else {
				return true;
			}
			
		} else if(vm.requisition.product_type == 'no_catalog'){
			if( vm.product.new.name == null
					|| vm.product.new.unit == null
					|| vm.product.new.use == null
					|| vm.product.new.description == null
					|| vm.product.new.pieces == null ){
				console.log(vm.product);
				console.log('tercer r');
				return false;
			} else {
				if( vm.product.new.unit == 'Other' ){
					if( typeof(vm.product.new.new_unit) === "undefined" || vm.product.new.new_unit == 0  ){
						console.log('cuarto r');
						return false;
					} else {
						console.log('segundo t');
						return true;
					}
				} else {
					console.log('tercer t');
					return true
				}
			}
		}
		
	}//CheckProduct

	vm.ChangeDollarPrice = function ()
	{
		var pmx = 0;
		var importe = 0;
		
		var msg = 'Introduzca un número valido en la Piezas del producto.';
		if( vm.requisition.product_type == 'catalog' ){
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
		if( vm.requisition.product_type == 'no_catalog' ){
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
		if( vm.requisition.product_type == 'catalog' ){
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
		if( vm.requisition.product_type == 'no_catalog' ){
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
		if( vm.requisition.product_type == 'catalog' ){
			CalculateImport();
		}
		if( vm.requisition.product_type == 'no_catalog' ){
			CalculateImport();
		}
	}//vm.ChangePiecesNew

	function CalculateImport()
	{
		var msg = 'Introduzca un número valido en la Piezas del producto.';
		var ban = false;
		var pieces = 0;
		if( vm.requisition.product_type == 'catalog' ){
			if( is_number(vm.product.catalog.pieces) == true ){
				pieces = vm.product.catalog.pieces;
				ban = true;
			} else {
				$('#add_product_item_btn').attr('disabled', 'disabled');
				$('#product_msg').html('<div class="alert alert-warning" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+msg+'</div>');
			}		
		}
		if( vm.requisition.product_type == 'no_catalog' ){
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
				"product_type": vm.requisition.product_type,
				"filter_product": vm.requisition.filter_product,
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
			if(vm.requisition.product_type == 'catalog'){
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
			if(vm.requisition.product_type == 'no_catalog'){
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
			vm.requisition.filter_product = '';
			vm.requisition.product_type = '';
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
		vm.requisition.subtotal = subt;
		//vm.requisition.iva = 0;
		vm.requisition.total = subt;
		CalculateIVA();
	}//CalculateTotal

	vm.ChangeIVA = function ()
	{
		CalculateIVA();
	}//vm.ChangeIVA

	function CalculateIVA()
	{
		var iva = 0;
		vm.requisition.total = 0;

		if( vm.requisition.iva.length != 0 ){
			iva = (vm.requisition.subtotal * parseFloat(vm.requisition.iva) )/100;
			vm.requisition.total = parseFloat(vm.requisition.subtotal) + parseFloat(iva);
			vm.requisition.total = vm.requisition.total.toFixed(2);
		}
	}//CalculateIVA

	function is_number(numero)
	{
    	if(isNaN(numero) ){
      		return false;
    	} else {
    		return true;
    	}
  	}//is_number

  	vm.EditRequisition = function (ind)
  	{
  		vm.ind = ind;
  		console.log(vm.requisition_list[ind].id);
  		vm.requisition.id = vm.requisition_list[ind].id;
  		vm.requisition.requested_date = vm.requisition_list[ind].requested_date;
  		vm.requisition.required_date = vm.requisition_list[ind].required_date;

  		vm.products_list = vm.requisition_list[ind].products;

		vm.requisition.subtotal = vm.requisition_list[ind].subtotal;
		vm.requisition.iva = vm.requisition_list[ind].iva;
		vm.requisition.total = vm.requisition_list[ind].total;

		vm.requisition.use = vm.requisition_list[ind].use;
		vm.requisition.observations = vm.requisition_list[ind].observations;
		if(vm.requisition_list[ind].pre_order == 1){
			$('#required_date').attr('disabled', 'disabled');
			$('#iva').attr('disabled', 'disabled');
			$('#use').attr('disabled', 'disabled');
			$('#observations').attr('disabled', 'disabled');
			
			$('#submit_requisition_btn').attr('disabled', 'disabled');
			$('#submit_requisition_btn').hide();
			$('#product_type_div').hide();
			$('#finances_info').hide();
			$('#add_product_item_btn').attr('disabled', 'disabled');
			$('#add_product_item_btn').hide();
		} else {
			$('#required_date').removeAttr('disabled');
			$('#iva').removeAttr('disabled');
			$('#use').removeAttr('disabled');
			$('#observations').removeAttr('disabled');
			
			$('#submit_requisition_btn').removeAttr('disabled');
			$('#submit_requisition_btn').show();
			$('#product_type_div').show();
			$('#finances_info').show();
			$('#add_product_item_btn').removeAttr('disabled');
			$('#add_product_item_btn').show();
		}
  		$('#save_requisition_modal').modal('toggle');
  	}//EditRequirement

  	vm.EditProductPieces = function (ind)
  	{
  		if(vm.requisition_list[vm.ind].pre_order == 0){
  			var n = prompt('Cambiar Número de Piezas', '');
  			console.log(n.length);
  			var msg = 'Introduza un Número Valido.';
  			if( n.length != 0){
  				if(is_number(n) == true){
  					$('#edit_product_msg').html('');
					vm.requisition_list[vm.ind].products[ind].product_pieces = n;
					vm.requisition_list[vm.ind].products[ind].importe = parseInt(vm.requisition_list[vm.ind].products[ind].product_pieces) * parseFloat(vm.requisition_list[vm.ind].products[ind].pesos_price);
					console.log(vm.requisition_list[vm.ind].products[ind].product_pieces);
					CalculateTotal();
					CalculateIVA();
  				} else {
  					$('#edit_product_msg').html('<div class="alert alert-warning" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+msg+'</div>');
  				}
  			} else {
  				$('#edit_product_msg').html('<div class="alert alert-warning" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+msg+'</div>');
  			}
  		}
  	}//vm.EditProductPieces

  	vm.DeleteProductPieces = function ($index)
  	{
  		var r = confirm('¿Desea Eliminar este Producto de la Requisición?');
  		if(r == true){
			vm.products_list.splice($index, 1);
  		}
  	}//vm.DeleteProductPieces

  	vm.CancelRequisition = function ()
  	{
  		if(vm.requisition.id != null){
  			vm.requisition.id = null;
 			RequisitionList();
  		}
  	}//CancelRequisition()

  	vm.DeleteRequisition = function (ind)
  	{
  		var r = confirm('¿Desea Eliminar esta Requisición?');
  		if(r == true){
  			$('#req_del_'+vm.requisition_list[ind].id).html('<i class="fa fa-spinner fa-spin fa-1x"></i>');
  			$('#req_del_'+vm.requisition_list[ind].id).attr('disabled', 'disabled');
	        $http.post('requisition/delete', { id:vm.requisition_list[ind].id })
	            .success(function(res) {
	            	console.log(res);
                    $('#req_del_'+vm.requisition_list[ind].id).removeAttr('disabled');
                    $('#req_del_'+vm.requisition_list[ind].id).html('<i class="fa fa-trash"></i>');
	                if(res.status){
                        vm.requisition_list = res.data;
                        RenderPage(res.tp);
	                } else {
						$('#requisition_list_msg').add('<div class="alert alert-warning" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+res.msg+'</div>');
	                }
	        }).error(function (res){
	            console.log(res);
	        });
  		}
  	}//DeleteRequisition

  	$('#new_place_div').hide();
  	$('#select_providers').hide();

  	vm.ConvertRequisition = function (ind)
  	{	
  		vm.order_id = vm.requisition_list[ind].id;
		vm.order_buy_list = vm.requisition_list[ind].products;
		GetProviders();
  		if(vm.requisition_list[ind].pre_order == 1){
  			
  			vm.order_buy.date 					= vm.requisition_list[ind].date;
  			vm.order_buy.pay_conditions 		= vm.requisition_list[ind].pay_conditions;
  			vm.order_buy.provider_id 			= vm.requisition_list[ind].provider_id;
  			vm.order_buy.deliver_place 			= vm.requisition_list[ind].deliver_place;
  			if(vm.order_buy.deliver_place == 'other'){
 				$('#order_buy_new_place').show();
  			} else{
  				$('#order_buy_new_place').hide();
  			}
  			vm.order_buy.new_place 				= vm.requisition_list[ind].new_place;
  			vm.order_buy.order_observations 	= vm.requisition_list[ind].order_observations;

  			$('#order_buy_date').attr('disabled', 'disabled');
  			$('#order_buy_pay_conditions').attr('disabled', 'disabled');
  			$('#order_buy_provider_id').attr('disabled', 'disabled');
			$('#order_buy_deliver_place').attr('disabled', 'disabled');

			$('#order_buy_new_place').attr('disabled', 'disabled');
			$('#order_buy_observations').attr('disabled', 'disabled');
			$('#submit_order_buy_btn').attr('disabled', 'disabled');
			$('#submit_order_buy_btn').hide();
			$('#modal_title_msg').html('Orden de Compra');
			$('#save_order_buy_msg').html('<div class="alert alert-warning" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>Esta Orden de Compra esta a la espera de ser Validada por el Depto. de Finanzas.</div>');

  		} else {
			vm.order_buy.date = null;
			vm.order_buy.pay_conditions = null;
			vm.order_buy.provider_id = null;
			vm.order_buy.deliver_place = null;
			vm.order_buy.new_place = null;
			vm.order_buy.order_observations = null;
			$('#order_buy_new_place').hide();
  			$('#order_buy_date').removeAttr('disabled');
  			$('#order_buy_pay_conditions').removeAttr('disabled');
  			$('#order_buy_provider_id').removeAttr('disabled');
			$('#order_buy_deliver_place').removeAttr('disabled');
			$('#order_buy_new_place').removeAttr('disabled');
			$('#order_buy_observations').removeAttr('disabled');
			$('#submit_order_buy_btn').removeAttr('disabled');
			$('#submit_order_buy_btn').show();
			$('#modal_title_msg').html('Convertir a Orden de Compra');
			$('#save_order_buy_msg').html('');
  		}
		$('#convert_requisition_modal').modal('toggle');
  	}//vm.ConvertRequisition

  	function GetProviders()
  	{
  		vm.providers_list_select = {};
  		$('#select_providers').show();
        $http.post('requisition/order_buy/providers')
            .success(function(res) {
            	console.log(res);
				$('#select_providers').hide();
                if(res.status){
                    vm.providers_list_select = res.data;
                } else {
					$('#save_order_buy_msg').add('<div class="alert alert-warning" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+res.msg+'</div>');
                }
        }).error(function (res){
        	$('#select_providers').hide();
            console.log(res);
        });
  	}//GetProviders

  	vm.ChangeDeliverPlace = function ()
  	{
  		if(vm.order_buy.deliver_place == 'other'){
  			$('#new_place_div').show();
  		}
  		if(vm.order_buy.deliver_place != 'other'){
  			$('#new_place_div').hide();
  		}
  	}//vm.ChangeDeliverPlace()

  	vm.CancelOrderBuy = function ()
  	{
  		vm.order_buy = {};
		vm.order_buy_list = Array();
		vm.providers_list_select = {};
  	}//vm.CancelOrderBuy

  	vm.SubmitConvertRequisition = function ()
  	{
  		
        $('#submit_order_buy_btn').html('<i class="fa fa-spinner fa-spin fa-2x"></i>');
        $('#submit_order_buy_btn').attr('disabled', 'disabled');
        $('#cancel_order_buy_btn').attr('disabled', 'disabled');
        $http.post('requisition/order_buy/save', { order_buy: vm.order_buy, id: vm.order_id, page: vm.page, filter_user: vm.filter_user })
            .success(function(res) {
            	console.log(res);
        		$('#submit_order_buy_btn').html('Convertir a Orden de Compra');
        		$('#submit_order_buy_btn').removeAttr('disabled');
        		$('#cancel_order_buy_btn').removeAttr('disabled');
                if(res.status){
					vm.order_buy = {};
					vm.order_buy.date = null;
					vm.order_buy.pay_conditions = null;
					vm.order_buy.provider_id = null;
					vm.order_buy.deliver_place = null;
					vm.order_buy.new_place = null;
					vm.order_buy.order_observations = null;

					vm.providers_list_select = {};

                	vm.requisition_list = res.data;
                	RenderPage(res.tp);
					$('#requisition_list_msg').html('<div class="alert alert-success" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+res.msg+'</div>');                
					$('#convert_requisition_modal').modal('toggle');
                } else {
					$('#save_order_buy_msg').html('<div class="alert alert-warning" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+res.msg+'</div>');
                }
        }).error(function (res){
        	console.log(res);
        	$('#submit_order_buy_btn').html('Convertir a Orden de Compra');
        	$('#submit_order_buy_btn').removeAttr('disabled');
        	$('#cancel_order_buy_btn').removeAttr('disabled');
        });
  	}//vm.ConvertRequisition


  	vm.FinalizeOrderBuy = function ()
  	{
  		var r = confirm('¿Desea convertir esta Requisición a Orden de Compra?');
  		if(r == true){
			$('#finalize_order_buy_btn').html('<i class="fa fa-spinner fa-spin fa-2x"></i>');
			$('#finalize_order_buy_btn').removeAttr('disabled');
			$('#cancel_order_buy_view_btn').attr('disabled', 'disabled');
	        $http.post('requisition/order_buy/finalize', { order_id: vm.order_id, page: vm.page, filter_user: vm.filter_user })
	            .success(function(res) {
	                console.log(res);
	                $('#finalize_order_buy_btn').html('Validar Pago de Requisición');
	                $('#finalize_order_buy_btn').removeAttr('disabled');
	                if(res.status){
	                	vm.requisition_list = res.data;
	                	RenderPage(res.tp);
	                } else {
						$('#view_pay_msg').html('<div class="alert alert-warning" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+res.msg+'</div>');
	                }
	        }).error(function (res){
				$('#finalize_order_buy_btn').html('Validar Pago de Requisición');
				$('#finalize_order_buy_btn').removeAttr('disabled');
	            console.log(res);
	        });
  		}
  	}//vm.FinalizeOrderBuy

}//index_init