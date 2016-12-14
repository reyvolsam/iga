angular.module('app', []).controller('appCtrl', ['$http', stock_init]);

function stock_init($http)
{
    var vm = this;

	vm.stock_list = {};

    vm.GetStockList = function ()
    {
    	vm.stock_list = {};
    	$('#stock_list_loader').show();
    	$('#stock_list_msg').html('');
        $http.post('list')
            .success(function(res) {
            	console.log(res);
            	$('#stock_list_loader').hide();
            	if(res.status){
            		vm.stock_list = res.data;
                } else {
                	$('#stock_list_msg').html('<div class="alert alert-warning" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+res.msg+'</div>');
                }
        }).error(function (res){
        	console.log(res);
        	$('#stock_list_loader').hide();
        });		
    }//vm.GetStockList

}//stock_init