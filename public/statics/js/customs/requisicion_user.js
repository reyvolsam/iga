angular.module('app', []).controller('appCtrl', ['$http', requisition_init]);

function requisition_init($http){
    var vm = this;

    $("[data-mask]").inputmask();
    vm.group_list = {};
    vm.requisition_data = {};

    vm.SubmitRequisitionUser = function (){
    	console.log(vm.requisition_data);
        $('#btn_create_requisition').html('<i class="fa fa-spinner fa-spin fa-2x"></i>');
        $('#btn_create_requisition').attr('disabled', 'disabled');
        $http.post('requisition/save', vm.requisition_data).success(function(res) {
            console.log(res);
        	$('#btn_create_requisition').html('Crear Rquisición');
        	$('#btn_create_requisition').removeAttr('disabled');
            if(res.status){
            	$('#create_requisition_form')[0].reset();
				$('#requisition_msg').html('<div class="alert alert-success" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+res.msg+'</div>');
				$('#requisition_msg').append('Para crear tu Personal Necesitaras el ID de esta Requisición.');
            } else {
                $('#requisition_msg').html('<div class="alert alert-warning" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+res.msg+'</div>');
            }
        }).error(function (res){
			$('#requisition_msg').html('<div class="alert alert-danger" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>¡Error!, contacte a su Administrador para mas Información.</div>');
        	$('#btn_create_requisition').html('Crear Rquisición');
        	$('#btn_create_requisition').removeAttr('disabled');
			console.log(res);
        });
    }//SubmitRequisitionUser

    vm.GetGroupList = function ()
    {
		GetGroupList();
    }//vm.GetPostList

    function GetGroupList()
    {
        vm.group_list = {};
        $('#select_loader_list').show();
        $http.post('post/list').success(function(res) {
        	$('#select_loader_list').hide();
            console.log(res.data);
            if(res.status){
                vm.group_list = res.data;
            } else {
                $('#requisition_msg').html('<div class="alert alert-warning" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+res.msg+'</div>');
            }
        }).error(function (res){
                console.log(res);
        });
    }//PostList

    vm.requisition_list = {};

    $('#requisition_list_loader').hide();

    vm.RequisitionList = function ()
    {
        console.log(vm.requisition_list);
        $('#requisition_list_loader').show();
        $http.post('../list').success(function(res) {
            console.log(res.data);
            $('#requisition_list_loader').hide();
            if(res.status){
                console.log(vm.requisition_list);
                vm.requisition_list = res.data;
            } else {
            }
        }).error(function (res){
                console.log(res);
        });
    }//vm.RequisitionList

}//index_init