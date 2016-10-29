 angular.module('app', []).controller('appCtrl', ['$http', provider_init]);


function provider_init($http){
    var vm = this;

	init_vars();
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
		vm.provider.banks = new Array();
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
    	for(i in vm.bank_list){
    		if(vm.bank_list[i].id == vm.bank.id){
    			if(vm.bank.id != null || vm.bank.no_count != null || vm.bank.inter_key != null || vm.bank.branch_office != null || vm.bank.type_coin != null){
    				vm.provider.banks.push({
    					id: vm.bank.id,
    					name: vm.bank_list[i].name,
    					no_count: vm.bank.no_count,
    					inter_key: vm.bank.inter_key,
    					branch_office: vm.bank.branch_office,
    					type_coin: vm.bank.type_coin
    				});
    			} else {
    				alert('Por favor, introduze los datos del Banco.');
    			}
    		}
    	}
    }//vm.AddBank

    vm.DeleteBank = function (cont)
    {
    	vm.provider.banks.splice(cont, 1);
    }//vm.DeleteBank

    $('#provider_list_loader').hide();

	vm.GetProviderList = function ()
	{
		$('#provider_list_loader').show();
        $http.post('list', {type: vm.provider.type})
            .success(function(res) {
            	console.log(res);
            	$('#provider_list_loader').hide();
                if(res.status){
                	vm.provider_list = res.data;
                } else {
					$('#provider_list_msg').html('<div class="alert alert-warning" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+res.msg+'</div>');
                }
        }).error(function (res){
			console.log(res);
        });
	}//vm.GetProviderList

}//raw_material_init