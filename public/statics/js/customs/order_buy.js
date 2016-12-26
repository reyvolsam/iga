angular.module('app', []).controller('appCtrl', ['$http', order_buy_init]);

function order_buy_init($http){
    var vm = this;

	vm.page = 1;
	vm.orders_buy_list = {};

    vm.OrdersBuyList = function ()
    {
		vm.requisition_list = {};
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

}//order_buy_init