angular.module('app', []).controller('appCtrl', ['$http', users_init]);

function users_init($http){
    var vm = this;

	vm.user = {};
	vm.requisition_data = {};
	vm.requisition_data.id = null;
	vm.user.requisition_data = {};

	$("[data-mask]").inputmask();
	vm.group_list = {};

	GetGroupList();

	vm.CreateUser = function ()
	{
		console.log(vm.user);
		console.log(vm.requisition_data);
		vm.user.requisition_data = vm.requisition_data;
		$('#btn_create_employee').html('<i class="fa fa-spinner fa-spin fa-2x"></i>');
		$('#btn_create_employee').attr('disabled', 'disabled');
		$http.post('../users', vm.user).success(function (res){
			console.log(res);
			$('#btn_create_employee').html('Guardar Empleado');
			$('#btn_create_employee').removeAttr('disabled');
			if(res.status){
				vm.user = {};
				vm.requisition_data = {};
				vm.requisition_data.id = null;
				$('#user_msg').html('<div class="alert alert-success" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+res.msg+'</div>');
			} else {
				$('#user_msg').html('<div class="alert alert-warning" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+res.msg+'</div>');
			}
		}).error(function (res){
			console.log(res);
		});
	}//vm.submitCreateUser

	function LoadReqData()
	{
		$('#user_msg').html('');
        $('#btn_requisition_data').html('<i class="fa fa-spinner fa-spin fa-2x"></i>');
        $('#btn_requisition_data').attr('disabled', 'disabled');
		$http.post('requisition/data', {id: vm.requisition_data.id}).success(function (res){
        	$('#btn_requisition_data').html('Cargar Datos de Requisición');
        	$('#btn_requisition_data').removeAttr('disabled');
			if(res.status){
				vm.requisition_data = res.data;
				vm.user.group_id = vm.requisition_data.group_id;
				$('#group_id').attr('disabled', 'disabled');
				$('#req_msg').html('');
				console.log(vm.requisition_data.required_person)
				if(vm.requisition_data.required_person == 0){
					$('#btn_create_employee').attr('disabled', 'disabled');
					$('#req_msg').html('<div class="alert alert-warning" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>Esta Requisición esta Finalizada.</div>');	
				} else {
					$('#btn_create_employee').removeAttr('disabled');
				}
			} else {
				vm.requisition_data = {};
				vm.requisition_data.id = null;
				$('#req_msg').html('<div class="alert alert-warning" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+res.msg+'</div>');	
			}
		}).error(function (res){
			console.log(res);
		});
	}//LoadReqData

	vm.LoadDataRequisition = function ()
	{
		LoadReqData();
	}//vm.LoadDataRequisition

	vm.GetRequisitionData = function ()
	{
		LoadReqData();
	}//vm.GetRquisitionData

    function GetGroupList(){
        vm.group_list = {};
        $('#group_loader_list').show();
        $('#select_loader_list').show();
        $http.post('post/list').success(function(res) {
            $('#posts_loader_list').hide();
            if(res.status){
                vm.group_list = res.data;
                $('#select_loader_list').hide();
            } else {
                $('#req_msg').html('<div class="alert alert-warning" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+res.msg+'</div>');
            }
        }).error(function (res){
                console.log(res);
        });
    }

}//users_init