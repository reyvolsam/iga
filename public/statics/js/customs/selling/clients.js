angular.module('app', []).controller('appCtrl', ['$http', clients_init]);

function clients_init($http){
    var vm = this;

    vm.page = 1;
    vm.users_list = {};
    vm.banks_list_select = {};
    vm.send_list_select = {};
    var_init();

    function var_init()
    {
        vm.client = {};
        vm.client.id = null;
        vm.client.type_company = null;
        vm.client.pay_type = 'Contado';
        vm.client.rfc = null;
        vm.client.razon_social = null;
        vm.client.tradename = null;
        vm.client.web = null;
        vm.client.pay_method = null;
        vm.client.user_id = null;
        
        vm.contact = {};
        vm.contacts_list = Array();
        
        vm.banks_list = Array();
        vm.bank = {};
        vm.fiscal = {};
        vm.fiscal.country = 'MX';
        vm.fiscal_list = Array();
        vm.send = {};
        vm.send.country = 'MX';
        
        vm.send_list = Array();
    }

    $('#select_users').hide();
    $('#select_banks').hide();
    $('#select_send').hide();

    vm.SubmitCreateClient = function ()
    {
        $('#submit_client_btn').html('<i class="fa fa-spinner fa-spin fa-2x"></i>');
        $('#submit_client_btn').attr('disabled', 'disabled');
        $('#cancel_client_btn').attr('disabled', 'disabled');
        $http.post('clients/save', { client: vm.client, banks: vm.banks_list, contacts: vm.contacts_list, fiscal: vm.fiscal_list, send: vm.send_list, page: vm.page })
            .success(function(res) {
                $('#submit_client_btn').html('Guardar Cliente');
                $('#submit_client_btn').removeAttr('disabled');
                $('#cancel_client_btn').removeAttr('disabled');
                if(res.status){
                    var_init();
                    vm.clients_list = res.data;
                    RenderPage(res.tp);
                    $('#save_client_msg').html('<div class="alert alert-success" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+res.msg+'</div>');                    
                } else {
                    $('#save_client_msg').html('<div class="alert alert-warning" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+res.msg+'</div>');
                }
        }).error(function (res){
            $('#submit_client_btn').removeAttr('disabled');
            $('#cancel_client_btn').removeAttr('disabled');
            $('#save_client_msg').html('<div class="alert alert-warning" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>¡Error!</div>');
            console.log(res);
        });
    }//vm.SubmitCreateClient

    vm.GetUsersList = function ()
    {
    	$('#select_users').show();
        $http.post('users_list')
            .success(function(res) {
                $('#select_users').hide();
                if(res.status){
                	vm.users_list = res.data;
                } else {
                    $('#save_client_msg').add('<div class="alert alert-warning" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+res.msg+'</div>');
                }
        }).error(function (res){
        	$('#select_users').hide();
        	$('#save_client_msg').html('<div class="alert alert-warning" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>¡Error!</div>');
            console.log(res);
        });
    }//vm.GetUsersList

    vm.AddContact = function ()
    {
    	if( typeof(vm.contact.name) === "undefined" || vm.contact.name.length == 0 
    		|| typeof(vm.contact.cargo) === "undefined" || vm.contact.cargo.length == 0 
    		|| typeof(vm.contact.email) === "undefined" || vm.contact.email.length == 0
    		|| typeof(vm.contact.phone) === "undefined" || vm.contact.phone.length == 0
    		|| typeof(vm.contact.ext) === "undefined" || vm.contact.ext.length == 0 ){
    		alert('Todos los Campos del Contacto deben de estar llenos.');
    	} else {
    		vm.contacts_list.push(vm.contact);
    		vm.contact = {};
    	}
    }//vm.AddContact()

    vm.DeleteContact = function (ind)
    {
    	vm.contacts_list.splice(ind, 1);
    }//vm.DeleteContact(cont)

    vm.GetBanksList = function ()
    {
    	$('#select_banks').show();
        $http.post('banks_list')
            .success(function(res) {
                $('#select_banks').hide();
                if(res.status){
                	vm.banks_list_select = res.data;
                } else {
                    $('#save_client_msg').add('<div class="alert alert-warning" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+res.msg+'</div>');
                }
        }).error(function (res){
        	$('#select_banks').hide();
        	$('#save_client_msg').html('<div class="alert alert-warning" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>¡Error!</div>');
            console.log(res);
        });
    }//vm.GetBanksList()

    vm.AddBank = function ()
    {
    	if( typeof(vm.bank.id) === "undefined" || vm.bank.id == '' || typeof(vm.bank.no_account) === "undefined" || vm.bank.no_account == 0 ){
    		alert('Todos los Campos del Banco de Clientex deben de estar llenos.');
    	} else {
    		for(i in vm.banks_list_select){
    			if(vm.banks_list_select[i].id == vm.bank.id){
    				var aux = {
    					'name': vm.banks_list_select[i].name,
    					'id': vm.banks_list_select[i].id,
    					'no_account': vm.bank.no_account
    				};
		    		vm.banks_list.push(aux);
		    		vm.bank = {};
		    		break;
    			}
    		}
    	}
    }//vm.AddBank()

    vm.DeleteBank = function (ind)
    {
    	vm.banks_list.splice(ind, 1);	
    }//vm.DeleteBank

    vm.AddFiscal = function ()
    {
        if( typeof(vm.fiscal.country) === "undefined" || vm.fiscal.country.length == 0 
            || typeof(vm.fiscal.street) === "undefined" || vm.fiscal.street.length == 0 
            || typeof(vm.fiscal.no_ext) === "undefined" || vm.fiscal.no_ext.length == 0
            || typeof(vm.fiscal.no_int) === "undefined" || vm.fiscal.no_int.length == 0
            || typeof(vm.fiscal.colony) === "undefined" || vm.fiscal.colony.length == 0 
            || typeof(vm.fiscal.city) === "undefined" || vm.fiscal.city.length == 0
            || typeof(vm.fiscal.state) === "undefined" || vm.fiscal.state.length == 0 
            || typeof(vm.fiscal.cp) === "undefined" || vm.fiscal.cp.length == 0 
            || typeof(vm.fiscal.contact) === "undefined" || vm.fiscal.contact.length == 0 ){
            alert('Todos los Campos del Domicilio Fiscal deben de estar llenos.');
        } else {
            vm.fiscal_list.push(vm.fiscal);
            vm.fiscal = {};
        }
    }//vm.AddFiscal()

    vm.DeleteFiscal = function (ind)
    {
        vm.fiscal_list.splice(ind, 1);
    }//vm.DeleteFiscal

    vm.CopyFromFiscal = function ()
    {
        vm.send.country = vm.fiscal.country;
        vm.send.street = vm.fiscal.street;
        vm.send.no_ext = vm.fiscal.no_ext;
        vm.send.no_int = vm.fiscal.no_int;
        vm.send.colony = vm.fiscal.colony;
        vm.send.city = vm.fiscal.city;
        vm.send.state = vm.fiscal.state;
        vm.send.cp = vm.fiscal.cp;
        vm.send.contact = vm.fiscal.contact;

    }//vm.CopyFromFiscal()

    vm.GetSendlist = function ()
    {
        $('#select_send').hide();
        $http.post('send_list')
            .success(function(res) {
                $('#select_send').hide();
                if(res.status){
                    vm.send_list_select = res.data;
                } else {
                    $('#save_client_msg').add('<div class="alert alert-warning" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+res.msg+'</div>');
                }
        }).error(function (res){
            $('#select_send').hide();
            $('#save_client_msg').html('<div class="alert alert-warning" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>¡Error!</div>');
            console.log(res);
        });
    }//vm.GetSendlistSelect


    vm.AddSend = function ()
    {
        if( typeof(vm.send.country) === "undefined" || vm.send.country.length == 0 
            || typeof(vm.send.street) === "undefined" || vm.send.street.length == 0 
            || typeof(vm.send.street_r1) === "undefined" || vm.send.street_r1.length == 0 
            || typeof(vm.send.street_r2) === "undefined" || vm.send.street_r2.length == 0 
            || typeof(vm.send.no_ext) === "undefined" || vm.send.no_ext.length == 0
            || typeof(vm.send.no_int) === "undefined" || vm.send.no_int.length == 0
            || typeof(vm.send.colony) === "undefined" || vm.send.colony.length == 0 
            || typeof(vm.send.city) === "undefined" || vm.send.city.length == 0
            || typeof(vm.send.state) === "undefined" || vm.send.state.length == 0 
            || typeof(vm.send.cp) === "undefined" || vm.send.cp.length == 0 
            || typeof(vm.send.flete_type) === "undefined" || vm.send.flete_type.length == 0 
            || typeof(vm.send.pack) === "undefined" || vm.send.pack.length == 0 
            || typeof(vm.send.contact) === "undefined" || vm.send.contact.length == 0 ){
            alert('Todos los Campos del Domicilio de Envio deben de estar llenos.');
        } else {
            vm.send_list.push(vm.send);
            vm.send = {};
        }
    }//vm.AddSend

    vm.DeleteSend = function (ind)
    {
        vm.send_list.splice(ind, 1);
    }//vm.DeleteSend

    $('#clients_list_loader').hide();

    function GetClientsList ()
    {
        vm.clients_list = {};
        $('#clients_list_loader').show();
        $http.post('clients/list', { page: vm.page })
            .success(function(res) {
                $('#clients_list_loader').hide();
                if(res.status){
                    vm.clients_list = res.data;
                    RenderPage(res.tp);
                } else {
                    $('#save_client_msg').add('<div class="alert alert-warning" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+res.msg+'</div>');
                }
        }).error(function (res){
            console.log(res);
        });
    }//vm.ClientsList

    vm.GetClientsList = function ()
    {
        GetClientsList();
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

    vm.DeleteClient = function (ind)
    {
        var r = confirm('¿Desea Eliminar este Cliente?');
        if(r){
            $('#del_'+vm.clients_list[ind].id).html('<i class="fa fa-spinner fa-spin fa-1x"></i>');
            $('#del_'+vm.clients_list[ind].id).attr('disabled', 'disabled');
            $http.post('clients/delete', { id: vm.clients_list[ind].id, page: vm.page })
                .success(function(res) {
                    $('#del_'+vm.clients_list[ind].id).removeAttr('disabled');
                    $('#del_'+vm.clients_list[ind].id).html('<i class="fa fa-trash"></i>');
                    if(res.status){
                        vm.clients_list = res.data;
                        RenderPage(res.tp);
                    } else {
                        $('#clients_list_msg').add('<div class="alert alert-warning" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+res.msg+'</div>');
                    }
            }).error(function (res){
                $('#del_'+vm.clients_list[ind].id).removeAttr('disabled');
                $('#del_'+vm.clients_list[ind].id).html('<i class="fa fa-trash"></i>');
                $('#clients_list_msg').html('<div class="alert alert-warning" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>¡Error!</div>');
                console.log(res);
            });
        }
    }//vm.DeleteClient()

    vm.EditClient = function (ind)
    {
        console.log(vm.clients_list[ind]);
        $('#save_client_msg').html('');
        vm.client = {};
        vm.client.id = vm.clients_list[ind].id;
        vm.client.type_company = vm.clients_list[ind].type_company;
        vm.client.pay_type = vm.clients_list[ind].pay_type;
        vm.client.rfc = vm.clients_list[ind].rfc;
        vm.client.razon_social = vm.clients_list[ind].razon_social;
        vm.client.tradename = vm.clients_list[ind].tradename;
        vm.client.web = vm.clients_list[ind].web;
        vm.client.pay_method = vm.clients_list[ind].pay_method;
        vm.client.user_id = vm.clients_list[ind].user_id;
        vm.contacts_list = vm.clients_list[ind].contacts;
        vm.banks_list = vm.clients_list[ind].banks;
        vm.fiscal_list = vm.clients_list[ind].fiscal;
        vm.send_list = vm.clients_list[ind].send;

        $('#create_client_modal').modal('toggle');

    }//vm.EditClient

    vm.CancelClient = function ()
    {
        var_init();
    }//vm.CancelClient()

}//clients_init