angular.module('app', []).controller('appCtrl', ['$http', login_init]);

function login_init($http){
    var vm = this;

    vm.login_data = {};

    vm.SubmitLogin = function(){
        $('#submit_login_button').html('<i class="fa fa-spinner fa-spin fa-2x">');
        $('#submit_login_button').attr('disabled', 'disabled');
        $http.post("login/form", vm.login_data)
            .success(function(res){
                console.log(res);
                if(res.status){
                    $('#submit_login_button').html('Redireccionando...');
                    window.location = 'index';
                } else {
                    $('#submit_login_button').removeAttr('disabled');
                    $('#submit_login_button').html('Iniciar Sesión');
                    $('#msg_login').html('<div class="alert alert-warning" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+res.msg+'</div>');
                }
            }).error(function (res){
                console.log(res);
                $('#submit_login_button').removeAttr('disabled');
                $('#submit_login_button').html('Iniciar Sesión');
                $('#msg_login').html('<div class="alert alert-warning" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>Ups! Algo salio mal, recarga de nuevo la pagina y vuelve a intentarlo.</div>');
        });
    }//end SubmitRegister    
}//end register_init