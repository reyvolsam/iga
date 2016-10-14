angular.module('app', []).controller('appCtrl', ['$http', users_init]);

function users_init($http){
    var vm = this;

	vm.pa = 1;
	vm.users_list = {};

	$('#users_loader_list_loader').hide();
	vm.usersList = function ()
	{
		$('#users_loader_list_loader').show();
		$http.post('users/list', {pa: vm.pa}).success(function (res){
			$('#users_loader_list_loader').hide();
			if(res.status){
				console.log(res);
				vm.users_list = res.data;
				RenderPaginator(res.tp);
			} else {
				$('#users_msg').html('<div class="alert alert-warning" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+res.msg+'</div>');
			}
		}).error(function (res){
			console.log(res);
			$('#users_loader_list_loader').hide();
			$('#users_msg').html('<div class="alert alert-danger" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+res.msg+'</div>');
		});
	}//users_list

	function RenderPaginator(paginas)
	{
		$('.pagination').html('');
		if(paginas > 1){
			var i = 1;
			for(i; i <= paginas; i++){
				var actived = '';
				if(i == pag_actual){
					actived = 'active';
				}
				$('.pagination').append('<li class ="item_paginador '+actived+'"><a href="#">'+i+'</a></li>');
			}

			$('.item_paginador').on('click', function (e){
				e.preventDefault();
				pag_actual = $(this).text();
				search();
			});
		}
	}

}//customs_init