angular.module('app', []).controller('appCtrl', ['$http', feature_init]);

function feature_init($http){
    var vm = this;

	vm.feature_list = {};
	vm.feature = {};
    vm.feature.type = null;
	vm.feature.type = feature;

	$('#feature_list_loader').hide();

	vm.SaveFeature = function ()
	{
        console.log(vm.feature);
        $('#save_feature_btn').html('<i class="fa fa-spinner fa-spin fa-2x"></i>');
        $('#save_feature_btn').attr('disabled', 'disabled');
        $http.post('save', vm.feature)
            .success(function(res) {
                console.log(res);
				$('#save_feature_btn').html('Guardar');
				$('#save_feature_btn').removeAttr('disabled');
                if(res.status){
                    vm.feature = {};
                    vm.feature.type = feature;
                	vm.feature_list = res.data;
                	$('#feature_modal').modal('toggle');
                	$('#feature_msg').html('<div class="alert alert-success" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+res.msg+'</div>');
                } else {
                    $('#feature_save_msg').html('<div class="alert alert-warning" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+res.msg+'</div>');
                }
        }).error(function (res){
            $('#save_feature_btn').html('Guardar Producto');
            $('#save_feature_btn').removeAttr('disabled');
            console.log(res);
        });
	}//vm.SaveFeature()


	vm.FeatureList = function ()
	{
		$('#feature_list_loader').show();
        $http.post('list', vm.feature)
            .success(function(res) {
                console.log(res);
                $('#feature_list_loader').hide();
                if(res.status){
                	vm.feature_list = res.data;
                } else {
                    $('#feature_msg').html('<div class="alert alert-warning" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+res.msg+'</div>');
                }
        }).error(function (res){
            console.log(res);
        });
	}//vm.FeatureList

}//index_init