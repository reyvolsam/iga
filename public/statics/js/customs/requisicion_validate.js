angular.module('app', []).controller('appCtrl', ['$http', requisition_init]);

function requisition_init($http){
    var vm = this;

    vm.requisition_list = {};

    $('#requisition_list_loader').hide();

    vm.RequisitionList = function ()
    {
        $('#requisition_list_loader').show();
        $http.post('../../list').success(function(res) {
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

    vm.ValidateRequisition = function (id)
    {
        var re = confirm('¿Desea Validar esta Requisición?');
        if(re){
            $('#del_'+id).html('<i class="fa fa-spinner fa-spin fa-2x"></i>');
            $('#del_'+id).attr('disabled', 'disabled');
            $http.post('../../validate', {id: id}).success(function(res) {
                console.log(res);
                $('#del_'+id).html('<i class = "fa fa-check-circle"></i>');
                $('#del_'+id).removeAttr('disabled');
                if(res.status){
                    vm.requisition_list = res.data;
                    $('#requisition_msg').html('<div class="alert alert-success" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+res.msg+'</div>');
                } else {
                    $('#requisition_msg').html('<div class="alert alert-warning" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+res.msg+'</div>');
                }
            }).error(function (res){
                    console.log(res);
            });
        }
    }//vm.ValidateRequisition

}//index_init