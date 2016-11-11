angular.module('app', ['angularFileUpload'])
    .controller('appCtrl', ['$http', 'FileUploader', '$scope', products_init]);

function products_init($http, FileUploader, $scope){
    var vm = this;

    vm.page = 1;

    vm.product = {};
    vm.product.id = null;
	vm.provider_list = {};
	vm.product.type = null;
	vm.product.type = product_type;

    vm.product_list = {};

    vm.product_type_list = {};
    vm.adjust = {};
    vm.model = {};
    vm.class = {};
    vm.color = {};
    vm.feets = {};

	$('#select_provider').hide();

    if(vm.product.type == 'semifinished_product'){
        vm.product.provider_name = 'PLASTICOS DEL GOLFO SUR S.A DE C.V';
        vm.product.provider_id = 26;
    }

    uploader = $scope.uploader = new FileUploader({
        url: 'save/technical',
        removeAfterUpload: true
    });
    
    uploader.onProgressItem = function(fileItem, progress) {
        $('#save_product_btn').html('Subiendo Ficha Tecnica del Producto...');
        $('#save_product_btn').attr('disabled', 'disabled');
        $('#progress_bar_file').css('width', progress+'%');
    };

    uploader.onCompleteAll = function( response, status, headers) {
        if(vm.product.type == 'raw_material' || vm.product.type == 'semifinished_product'){
            $('#'+vm.product.type+'_modal').modal('toggle');
            $('#save_product_btn').html('Guardar Producto');
            $('#save_product_btn').removeAttr('disabled');

            $('#product_msg').html('');
            $('#progress_bar_file').css('width', '0%');
            vm.product = {};
            vm.product.type = product_type;
            vm.product.id = null;
            
            if(vm.product.type == 'semifinished_product'){
                vm.product.provider_name = 'PLASTICOS DEL GOLFO SUR S.A DE C.V';
                vm.product.provider_id = 26;
            }
        }
        console.log(uploader.queue);
    };

    vm.SaveProduct = function ()
    {
        if(uploader.queue.length > 0){
            if(uploader.queue.length == 1){
                if(uploader.queue[0]._file.size < 5000000){
                    $('#product_msg').html('');
                    AjaxSaveProduct();
                } else {
                    $('#product_msg').html('<div class="alert alert-warning" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>La Ficha Tecnica no puede pesar mas de 5.0 MB.</div>');
                }
            } else {
                $('#product_msg').html('<div class="alert alert-warning" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>Solo puedes seleccionar un archivo.</div>');
            }
        } else {
            $('#product_msg').html('<div class="alert alert-warning" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>Selecciona una de Ficha Tecnica para el Producto.</div>');
        }
    }//vm.SaveRawMaterial

    uploader_img = $scope.uploader_img = new FileUploader({
        url: 'save/img',
        removeAfterUpload: true
    });
    
    uploader_img.onProgressItem = function(fileItem, progress) {
        $('#save_product_btn').html('Subiendo Ficha Tecnica del Producto...');
        $('#save_product_btn').attr('disabled', 'disabled');
        $('#progress_bar_file').css('width', progress+'%');
    };

    uploader_img.onCompleteAll = function( response, status, headers) {
        $('#'+vm.product.type+'_modal').modal('toggle');
        $('#save_product_btn').html('Guardar Producto');
        $('#save_product_btn').removeAttr('disabled');

        $('#product_msg').html('');
        $('#progress_bar_file').css('width', '0%');
        vm.product = {};
        vm.product.type = product_type;
        vm.product.id = null;
    
        console.log(uploader_img.queue);
    };

    vm.SaveProductPT = function ()
    {
        if(uploader.queue.length > 0){
            if(uploader_img.queue.length > 0){
                if(uploader_img.queue.length == 1 || uploader.queue.length == 1){
                    if(uploader.queue[0]._file.size < 5000000){
                        if(uploader_img.queue[0]._file.size < 5000000){
                            $('#product_msg').html('');
                            console.log('suve');
                            AjaxSaveProduct();
                        } else {
                            $('#product_msg').html('<div class="alert alert-warning" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>La Imagen del Producto no puede pesar mas de 5.0 MB.</div>');
                        }
                    } else {
                        $('#product_msg').html('<div class="alert alert-warning" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>La Ficha Tecnica no puede pesar mas de 5.0 MB.</div>');
                    }
                } else {
                    $('#product_msg').html('<div class="alert alert-warning" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>Solo puedes seleccionar un archivo para la Imagen del Producto o la Ficha Tecnica.</div>');
                }
            } else {
                $('#product_msg').html('<div class="alert alert-warning" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>Selecciona una Imagen para el Producto.</div>');
            }
        } else {
            $('#product_msg').html('<div class="alert alert-warning" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>Selecciona una Ficha Tecnica para el Producto.</div>');
        }
    }//vm.SaveRawMaterial
    
    function AjaxSaveProduct()
    {
        console.log(vm.product);
        $http.post('save', vm.product)
            .success(function(res) {
                console.log(res);
                $('#save_product_btn').html('Guardar Producto');
                $('#save_product_btn').removeAttr('disabled');
                $('#product_msg').html('<div class="alert alert-success" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+res.msg+'</div>');
                if(res.status){
                    if(vm.product.type == 'raw_material' || vm.product.type == 'semifinished_product' ){
                        uploader.queue[0].upload();
                    } else if(vm.product.type == 'finished_product') {
                        uploader.queue[0].upload();
                        uploader_img.queue[0].upload();
                    }
                } else {
                    $('#save_product_btn').html('Guardar Producto');
                    $('#save_product_btn').removeAttr('disabled');
                    $('#product_msg').html('<div class="alert alert-warning" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+res.msg+'</div>');
                }
        }).error(function (res){
            $('#save_product_btn').html('Guardar Producto');
            $('#save_product_btn').removeAttr('disabled');
            console.log(res);
        });
    }//AjaxSaveProduct

	vm.GetProviderList = function ()
	{
        $('#select_provider').show();
        console.log(vm.product.type);
        $http.post('../provider/list', {type: vm.product.type})
            .success(function(res) {
                $('#select_provider').hide();
                console.log(res.data);
                if(res.status){
                    vm.provider_list = res.data;
                } else {
                	$('#product_msg').html('<div class="alert alert-warning" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+res.msg+'</div>');
                }
        }).error(function (res){
                console.log(res);
        });
	}//vm.GetProviderList

    $('#adjust_div').hide();
    $('#class_div').hide();
    $('#model_div').hide();
    $('#color_div').hide();
    $('#feets_div').hide();

    vm.ProductTypeList = function ()
    {
        $http.post('type/list')
            .success(function(res) {
                console.log(res);
                if(res.status){
                    vm.product_type_list = res.data;
                    vm.adjust = res.adjust;
                    vm.model = res.model;
                    vm.class = res.class;
                    vm.color = res.color;
                    vm.feets = res.feets;
                } else {
                    $('#product_type_msg').html('<div class="alert alert-warning" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+res.msg+'</div>');
                }
        }).error(function (res){
            console.log(res);
        });
    }//vm.ProductTypeList

    vm.ChangeProductType = function ()
    {
        for(i in vm.product_type_list){
            console.log(vm.product.product_type_id+' = '+vm.product_type_list[i].id);
            if(vm.product.product_type_id == vm.product_type_list[i].id){
                if(vm.product_type_list[i].adjust == 1){
                    $('#adjust_div').show();
                } else {
                    $('#adjust_div').hide();
                }
                if(vm.product_type_list[i].class == 1){
                    $('#class_div').show();
                } else {
                    $('#class_div').hide();
                }
                if(vm.product_type_list[i].model == 1){
                    $('#model_div').show();
                } else {
                    $('#model_div').hide();
                }
                if(vm.product_type_list[i].color == 1){
                    $('#color_div').show();
                } else {
                    $('#color_div').hide();
                }
                if(vm.product_type_list[i].feets == 1){
                    $('#feets_div').show();
                } else {
                    $('#feets_div').hide();
                }
            }
        }
    }//vm.ChangeProductType

    $('#product_list_loader').hide();

    vm.GetProductList = function ()
    {
        GetProductList();
    }//vm.GetProductList

    function GetProductList()
    {
        vm.product_list = {};
        console.log(vm.product.type);
        $('#product_list_loader').show();
        $http.post('list', {type: vm.product.type, page:vm.page})
            .success(function(res) {
                $('#product_list_loader').hide();
                console.log(res);
                if(res.status){
                    vm.product_list = res.data
                    PageRender(res.tp);                
                } else {
                    $('#product_msg').html('<div class="alert alert-warning" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+res.msg+'</div>');
                }
        }).error(function (res){
                console.log(res);
        });
    }//GetProductList

    function PageRender(tp)
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
                GetProductList();
            });
        }
    }//PageRender

}//products_init