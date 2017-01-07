angular.module('app', ['angularFileUpload', "checklist-model"])
	.controller('appCtrl', ['$http', 'FileUploader', '$scope', requisition_validate_init]);

function requisition_validate_init($http, FileUploader, $scope){
    var vm = this;

    vm.page = 1;
    vm.filter_user = 'all';
    vm.requisition_list = {};
    
    vm.order_buy = {};
    vm.order_buy.providers = {};
    vm.order_buy.providers_selected = {};

    uploader = $scope.uploader = new FileUploader({
        url: 'order_buy/finances/uploadticket',
        removeAfterUpload: true
    });
    
    uploader.onProgressItem = function(fileItem, progress) {
        $('#submit_validate_pay_btn').html('Subiendo Baucher de Pago...');
        $('#submit_validate_pay_btn').attr('disabled', 'disabled');
        $('#cancel_validate_pay_btn').attr('disabled', 'disabled');
        $('#progress_bar_file').css('width', progress+'%');
    };

    uploader.onCompleteAll = function( response, status, headers) {
        $('#validate_pay_modal').modal('toggle');
        $('#submit_validate_pay_btn').html('Guardar Producto');
        $('#submit_validate_pay_btn').removeAttr('disabled');
        $('#cancel_validate_pay_btn').removeAttr('disabled');

        $('#save_validate_pay_msg').html('');
       	$('#progress_bar_file').css('width', '0%');
        RequisitionList();
        console.log(uploader.queue);
    };

    uploader.onErrorItem = function(fileItem, response, status, headers) {
        console.info('onErrorItem', fileItem, response, status, headers);
    };

	vm.ChangeFilterUser = function ()
	{
		RequisitionList();
	}//vm.ChangeFilterUser()

	function RequisitionList()
	{
		vm.requisition_list = {};
		$('#requisition_list_loader').show();
        $('#requisition_list_msg').html('');
        $http.post('../requisition/validate/list', { page: vm.page, filter_user: vm.filter_user })
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

  	vm.ValidatePayRequisition = function (ind)
  	{
        uploader.queue.length = 0;
        $('#save_validate_pay_msg').html('');
        vm.order_id = null;
        vm.order_buy.providers_selected = {};
        vm.order_buy.providers = {};

  		vm.order_id = vm.requisition_list[ind].id;
        vm.order_buy.providers = vm.requisition_list[ind].provider_contacts;
  		$('#validate_pay_modal').modal('toggle');
  	}//vm.ValidatePayRequisition

  	vm.SubmitValidatePayRequsition = function ()
  	{
        if(uploader.queue.length > 0){
            if(uploader.queue.length == 1){
                if(uploader.queue[0]._file.size < 5000000){
                	SaveValidatePayRequisitionAjax();
                } else {
                	$('#save_validate_pay_msg').html('<div class="alert alert-warning" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>El archivo debe de pesar menos de 5.00MB</div>');
                }
            } else {
				$('#save_validate_pay_msg').html('<div class="alert alert-warning" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>Solo se puede seleeccionar un archivo.</div>');
            }
        } else {
        	$('#save_validate_pay_msg').html('<div class="alert alert-warning" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>Seleccione un archivo</div>');
        }
  	}//vm.SubmitValidatePayRequsition()

  	function SaveValidatePayRequisitionAjax()
  	{
        $('#submit_validate_pay_btn').html('<i class="fa fa-spinner fa-spin fa-2x"></i>');
        $('#submit_validate_pay_btn').attr('disabled', 'disabled');
        $('#cancel_validate_pay_btn').attr('disabled', 'disabled');
        $http.post('order_buy/finances/validate', { order_id: vm.order_id, page: vm.page, filter_user: vm.filter_user, provider_list: vm.order_buy.providers_selected })
            .success(function(res) {
                console.log(res);
                $('#submit_validate_pay_btn').html('Validar Pago de Requisición');
                $('#submit_validate_pay_btn').removeAttr('disabled');
                $('#cancel_validate_pay_btn').removeAttr('disabled');
                if(res.status){
                    $('#save_validate_pay_msg').html('<div class="alert alert-success" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+res.msg+'</div>');
					uploader.queue[0].upload();
                	vm.requisition_list = res.data;
                	RenderPage(res.tp);
                } else {
					$('#save_validate_pay_msg').html('<div class="alert alert-warning" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+res.msg+'</div>');
                }
        }).error(function (res){
			$('#submit_validate_pay_btn').html('Validar Pago de Requisición');
			$('#submit_validate_pay_btn').removeAttr('disabled');
			$('#cancel_validate_pay_btn').removeAttr('disabled');
            console.log(res);
        });
  	}//SaveValidatePayRequisitionAjax

  	vm.CancelValidatePay = function ()
  	{

  	}//vm.CancelValidatePay()

    vm.SeeRequisition = function (ind)
    {
        vm.order_buy_products_list = vm.requisition_list[ind].products;
        GetProviders();
        vm.order_buy.date                   = vm.requisition_list[ind].date;
        vm.order_buy.pay_conditions         = vm.requisition_list[ind].pay_conditions;
        vm.order_buy.provider_id            = vm.requisition_list[ind].provider_id;
        vm.order_buy.deliver_place          = vm.requisition_list[ind].deliver_place;
        if(vm.requisition_list.deliver_place == 'other'){
            $('#order_buy_new_place').show();
        } else{
            $('#order_buy_new_place').hide();
        }
        vm.order_buy.subtotal               = vm.requisition_list[ind].subtotal;
        vm.order_buy.iva                    = vm.requisition_list[ind].iva;
        vm.order_buy.total                  = vm.requisition_list[ind].total;

        vm.order_buy.new_place              = vm.requisition_list[ind].new_place;
        vm.order_buy.order_observations     = vm.requisition_list[ind].order_observations;

        $('#order_buy_date').attr('disabled', 'disabled');
        $('#order_buy_pay_conditions').attr('disabled', 'disabled');
        $('#order_buy_provider_id').attr('disabled', 'disabled');
        $('#order_buy_deliver_place').attr('disabled', 'disabled');

        $('#order_buy_new_place').attr('disabled', 'disabled');
        $('#order_buy_observations').attr('disabled', 'disabled');
        $('#order_buy_modal').modal('toggle');
    }//SeeRequisition

    function GetProviders()
    {
        vm.providers_list_select = {};
        $('#select_providers').show();
        $http.post('order_buy/providers')
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

}//index_init