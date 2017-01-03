angular.module('app', []).controller('appCtrl', ['$http', order_buy_init]);

function order_buy_init($http){
    var vm = this;

	vm.page = 1;
    vm.order_buy = {};
	vm.orders_buy_list = {};
    vm.order_buy_products_list = {};
    vm.order_id = null;
    vm.providers_list_select = {};

    vm.provier_emails = {};

    vm.OrdersBuyList = function ()
    {
		vm.orders_buy_list = {};
		$('#orders_buy_list_loader').show();
        $http.post('order_buy/list', { page: vm.page })
            .success(function(res) {
            	console.log(res);
            	$('#orders_buy_list_loader').hide();
                if(res.status){
                	vm.orders_buy_list = res.data;
                	RenderPage(res.tp);
                } else {
                	$('#orders_buy_list_msg').html('<div class="alert alert-warning" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+res.msg+'</div>');	
                }
        }).error(function (res){
        	$('#orders_buy_list_loader').hide();
            console.log(res);
        });
    }//vm.OrdersBuyList()

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

    vm.ViewPayTicket = function (ind)
    {
        vm.provier_emails = {};
        vm.order_id = vm.orders_buy_list[ind].id;
        vm.provier_emails = vm.orders_buy_list[ind].provider_email;
        $('#view_pay').attr('src', '../order_buy_ticket/'+vm.orders_buy_list[ind].ticket_pay_file);

        $('#view_pay_modal').modal('toggle');
    }//vm.ViewPayTicket

    vm.SeeRequisition = function (ind)
    {
        vm.order_buy_products_list = vm.orders_buy_list[ind].products;
        GetProviders();
        vm.order_buy.date                   = vm.orders_buy_list[ind].date;
        vm.order_buy.pay_conditions         = vm.orders_buy_list[ind].pay_conditions;
        vm.order_buy.provider_id            = vm.orders_buy_list[ind].provider_id;
        vm.order_buy.deliver_place          = vm.orders_buy_list[ind].deliver_place;
        if(vm.order_buy.deliver_place == 'other'){
            $('#order_buy_new_place').show();
        } else{
            $('#order_buy_new_place').hide();
        }
        vm.order_buy.new_place              = vm.orders_buy_list[ind].new_place;
        vm.order_buy.order_observations     = vm.orders_buy_list[ind].order_observations;

        $('#order_buy_date').attr('disabled', 'disabled');
        $('#order_buy_pay_conditions').attr('disabled', 'disabled');
        $('#order_buy_provider_id').attr('disabled', 'disabled');
        $('#order_buy_deliver_place').attr('disabled', 'disabled');

        $('#order_buy_new_place').attr('disabled', 'disabled');
        $('#order_buy_observations').attr('disabled', 'disabled');
        $('#order_buy_modal').modal('toggle');
    }//vm.SeeRequisition

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

}//order_buy_init