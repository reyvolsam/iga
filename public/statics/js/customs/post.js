angular.module('app', []).controller('appCtrl', ['$http', post_init]);

function post_init($http){
    var vm = this;

    vm.post_data = {};
    vm.post_list = {};
    vm.post_data.id = null;
    vm.post_data.less = null;

    $('#posts_loader_list').hide();
    $('#cancel_edit_post_form').hide();

    vm.SubmitPost = function (){
        $('#submit_post_form').html('<i class="fa fa-spinner fa-spin fa-2x"></i>');
        $('#submit_post_form').attr('disabled', 'disabled');

        vm.post_data.less = null;
        $('#cancel_edit_post_form').hide();

        $http.post('post/save', vm.post_data).success(function(res) {
            console.log(vm.post_data);
            vm.post_data = {};
            $('#submit_post_form').html('Crear Puesto');
            $('#submit_post_form').removeAttr('disabled');
            if(res.status){
                vm.post_list = res.data;
                vm.post_list_select = res.data;
                $('#post_msg').html('<div class="alert alert-success" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+res.msg+'</div>')
            } else {
                $('#post_msg').html('<div class="alert alert-warning" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+res.msg+'</div>');
            }
        }).error(function (res){
            console.log(res);
        });        
    }//vm.SubmitPost

    vm.GetPostList = function(){
        GetPostList();
    }

    function GetPostList(){
        vm.post_list = {};
        $('#posts_loader_list').show();
        $('#select_loader_list').show();
        $http.post('post/list', vm.post_data).success(function(res) {
            $('#posts_loader_list').hide();
            console.log(res.data);
            if(res.status){
                vm.post_list = res.data;
                vm.post_list_select = res.data;
                $('#select_loader_list').hide();
            } else {
                $('#save_post_msg').html('<div class="alert alert-warning" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+res.msg+'</div>');
                $('#post_list_msg').html('<div class="alert alert-warning" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+res.msg+'</div>');
            }
        }).error(function (res){
                console.log(res);
        });
    }

    vm.EditPost = function ($index){
        console.log($index);
        console.log(vm.post_list[$index]);
        $('#cancel_edit_post_form').show();
        $('#submit_post_form').html('Actualizar Puesto');

        vm.post_data.id = vm.post_list[$index].id;
        vm.post_data.post_name = vm.post_list[$index].name;
        if( vm.post_list[$index].chief_id != null){
            vm.post_data.chief_id = vm.post_list[$index].chief_id;
        }
        vm.post_data.less = vm.post_list[$index].id;
        
        $('#select_loader_list').show();
        $http.post('post/list', vm.post_data).success(function(res) {
            console.log(res.data);
            $('#select_loader_list').hide();
            if(res.status){
                vm.post_list_select = res.data;
            } else {
                $('#save_post_msg').html('<div class="alert alert-warning" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+res.msg+'</div>');
            }
        }).error(function (res){
                console.log(res);
        });
    }//EditPost

    vm.CancelEditPost = function (){
        vm.post_data.less = null;
        vm.post_data.post_name = null
        vm.post_data.chief_id = null;
        GetPostList();
        $('#cancel_edit_post_form').hide();
    }//vm.cancelEditPost

    vm.DeletePost = function($index){
        var conf = confirm('¿Desea Eliminar este Puesto?');
        if(conf){
            console.log(vm.post_list[$index].id);
            $('#del_'+$index).html('<i class="fa fa-spinner fa-spin fa-2x"></i>');
            $('#del_'+$index).attr('disabled', 'disabled');
            $http.post('post/delete',{id:vm.post_list[$index].id})
                .success(function(res){
                    console.log(res);
                    $('#del_'+$index).html('<i class="glyphicon glyphicon-trash"></i>');
                    $('#del_'+$index).removeAttr('disabled');
                    if(res.status){
                        vm.post_list.splice($index, 1);
                        console.log(vm.post_list);
                        $('#post_list_msg').html('<div class="alert alert-success" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+res.msg+'</div>');
                    } else {
                        $('#post_list_msg').html('<div class="alert alert-warning" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+res.msg+'</div>');
                    }
                }).error(function (res){
                    $('#del_'+$index).html('<i class="fa fa-times"></i>');
                    $('#del_'+$index).removeAttr('disabled');
                    $('#post_list_msg').html('<div class="alert alert-warning" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+res.msg+'</div>');
                    console.log(res);
                });
        }
    }

}//index_init