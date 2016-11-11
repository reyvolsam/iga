 angular.module('app', []).controller('appCtrl', ['$http', provider_init]);


function provider_init($http){
    var vm = this;

	init_vars();
	vm.provider_list = {};
	vm.page = 1;
	vm.bank_list = {};
	vm.provider.id = null;
	vm.provider.type = null;
	vm.provider.type = provider_type;
	$('#select_bank').hide();

	function init_vars()
	{
		vm.provider = {};

		vm.phone = {};
		vm.phone.phone = null;
		vm.phone.exts = null;

		vm.contact = {};
		vm.contact.name = null;
		vm.contact.email = null;
		vm.contact.phone = null;
		vm.contact.cargo = null;

		vm.bank = {};
		vm.bank.no_count = null;
		vm.bank.inter_key = null;
		vm.bank.branch_office = null;
		vm.bank.type_coin = null;
		
		vm.provider.phones = {};
		vm.provider.phones = new Array();
		vm.provider.contacts = {};
		vm.provider.contacts = new Array();
		vm.provider.banks = {};
		vm.provider.banks.list = new Array();
		vm.provider.banks.deleted = new Array();
	}//init_vars

	$('#provider_modal').on('hide.bs.modal', function (e) {
		init_vars();
	});

	vm.SaveProvider = function ()
	{
		console.log(vm.provider);
		$('#save_provider_btn').html('<i class="fa fa-spinner fa-spin fa-2x"></i>');
		$('#save_provider_btn').attr('disabled', 'disabled');
        $http.post('save', vm.provider)
            .success(function(res) {
            	console.log(res);
				$('#save_provider_btn').html('Guardar Proveedor');
				$('#save_provider_btn').removeAttr('disabled');
                if(res.status){
                	$('#provider_modal').modal('toggle');
                	init_vars();
                	$('#provider_list_msg').html('<div class="alert alert-success" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+res.msg+'</div>');
                } else {
					$('#provider_msg').html('<div class="alert alert-warning" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+res.msg+'</div>');
                }
        }).error(function (res){
			console.log(res);
        });		
	}//vm.SaveProvider

    vm.BankList = function ()
    {
        $('#select_bank').show();
        $http.post('../../finances/bank/list')
            .success(function(res) {
                $('#select_bank').hide();
                console.log(res.data);
                if(res.status){
                    vm.bank_list = res.data;
                    console.log(vm.bank_list);
                } else {
                    alert(res.msg);
                }
        }).error(function (res){
                console.log(res);
        });
    }//BankList

	vm.AddPhoneContact = function ()
	{
		if(vm.phone.phone != null || vm.phone.exts != null){
			vm.provider.phones.push({
					phone: vm.phone.phone,
					exts: vm.phone.exts
				});
			vm.phone.phone = null;
			vm.phone.exts = null;
			console.log(vm.provider);
		} else {
			alert('Por favor, introduze un Nùmero de Telefono y su Extensión.');
		}
	}//vm.AddPhoneContact

	vm.DeletePhone = function (cont)
	{
		vm.provider.phones.splice(cont, 1);
	}//vm.DeletePhone

	vm.AddContact = function ()
	{
		if(vm.contact.name != null || vm.contact.email != null || vm.contact.phone != null || vm.contact.cargo != null){
			vm.provider.contacts.push({
					name: vm.contact.name,
					email: vm.contact.email,
					phone: vm.contact.phone,
					cargo: vm.contact.cargo
				});
			vm.contact.name = null;
			vm.contact.email = null;
			vm.contact.phone = null;
			vm.contact.cargo = null;
			console.log(vm.provider);
		} else {
			alert('Por favor, introduze los datos del contacto.');
		}
	}//vm.AddPhoneContact

	vm.DeleteContact = function (cont)
	{
		vm.provider.contacts.splice(cont, 1);
	}//vm.DeletePhone

    vm.AddBank = function ()
    {
    	
    	if( vm.bank.id != null ){
	    	for(i in vm.bank_list){
	    		console.log(vm.bank_list[i].id+' = '+vm.bank.id);
	    		if(vm.bank.id != null || vm.bank.no_count != null || vm.bank.inter_key != null || vm.bank.branch_office != null || vm.bank.type_coin != null){
	    			if(vm.bank_list[i].id == vm.bank.id){
	    				vm.provider.banks.list.push({
	    					id: vm.bank.id,
	    					name: vm.bank_list[i].name,
	    					no_count: vm.bank.no_count,
	    					inter_key: vm.bank.inter_key,
	    					branch_office: vm.bank.branch_office,
	    					type_coin: vm.bank.type_coin,
	    					new: true
	    				});
	    				console.log(vm.provider);
	    			}
				} else {
	    			alert('Por favor, introduze los datos del Banco.');
	    		}
	    	}
    	} else {
			alert('Por favor, introduze los datos del Banco.');
    	}
    }//vm.AddBank

    vm.DeleteBank = function (cont)
    {
    	vm.provider.banks.deleted.push({
    		id: vm.provider.banks.list[cont].id
    	});
    	vm.provider.banks.list.splice(cont, 1);
    }//vm.DeleteBank

    $('#provider_list_loader').hide();

    function GetProviderList()
    {
		$('#provider_list_loader').show();
		vm.provider_list = {};
        $http.post('list', {type: vm.provider.type, page:vm.page})
            .success(function(res) {
            	console.log(res);
            	$('#provider_list_loader').hide();
                if(res.status){
                	vm.provider_list = res.data;
                	PageRender(res.tp);
                } else {
					$('#provider_list_msg').html('<div class="alert alert-warning" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+res.msg+'</div>');
                }
        }).error(function (res){
			console.log(res);
        });
    }//GetPoviderList

	vm.GetProviderList = function ()
	{
		GetProviderList();
	}//vm.GetProviderList

	function PageRender(tp)
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
				GetProviderList();
			});
		}
	}//PageRender

	vm.EditProvider = function (cont)
	{
		$('#provider_modal').modal('toggle');
		console.log(vm.provider_list[cont]);
		vm.provider.id = vm.provider_list[cont].id;

		vm.provider.name = vm.provider_list[cont].name;
		vm.provider.comercial = vm.provider_list[cont].comercial;
		vm.provider.rfc = vm.provider_list[cont].rfc;

		vm.provider.street = vm.provider_list[cont].street;
		vm.provider.number = vm.provider_list[cont].number;
		vm.provider.colony = vm.provider_list[cont].colony;
		vm.provider.city = vm.provider_list[cont].city;
		vm.provider.state = vm.provider_list[cont].state;
		vm.provider.country = vm.provider_list[cont].country;
		vm.provider.cp = vm.provider_list[cont].cp;

		vm.provider.phones = vm.provider_list[cont].phones;
		vm.provider.contacts = vm.provider_list[cont].contacts;

		vm.provider.credit_type = vm.provider_list[cont].credit_type;
		vm.provider.credit_days = vm.provider_list[cont].credit_days;
		vm.provider.credit_limit = vm.provider_list[cont].credit_limit;
		vm.provider.notes = vm.provider_list[cont].notes;

		vm.provider.banks.list = vm.provider_list[cont].banks;
		console.log(vm.provider);

		
	}//vm.EditProvider

	vm.DeleteProvider = function(cont)
	{
		var r = confirm('¿Desea Eliminar este Proveedor?');
		if(r){
			$('#'+vm.provider_list[cont].id).html('<i class="fa fa-spinner fa-spin fa-2x"></i>');
			$('#'+vm.provider_list[cont].id).attr('disabled', 'disabled');
	        $http.post('delete', {type: vm.provider.type, page:vm.page, id: vm.provider_list[cont].id})
	            .success(function(res) {
	            	console.log(res);
					$('#'+vm.provider_list[cont].id).html('<i class = "fa fa-trash"></i>');
					$('#'+vm.provider_list[cont].id).removeAttr('disabled');
	                if(res.status){
                		vm.provider_list = res.data;
                		PageRender(res.tp);
	                	$('#provider_list_msg').html('<div class="alert alert-success" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+res.msg+'</div>');
	                } else {
						$('#provider_msg').html('<div class="alert alert-warning" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+res.msg+'</div>');
	                }
	        }).error(function (res){
				$('#'+vm.provider_list[cont].id).html('<i class = "fa fa-trash"></i>');
				$('#'+vm.provider_list[cont].id).removeAttr('disabled');
				console.log(res);
	        });
		}		
	}//vm.DeleteProvider

}//raw_material_init