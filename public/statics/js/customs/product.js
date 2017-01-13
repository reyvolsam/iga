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

    $('#technical_file_raw_material_div').hide();

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
        console.log(fileItem);
    };
    uploader.onErrorItem = function(fileItem, response, status, headers) {
            console.info('onErrorItem', fileItem, response, status, headers);
    };
    uploader.onCompleteAll = function( response, status, headers) {
        if(vm.product.type == 'raw_material' || vm.product.type == 'semifinished_product'){
            CloseSaveProduct();
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
        $('#save_product_btn').attr('disabled', 'disabled');
        $('#close_btn_product').attr('disabled', 'disabled');
        if(vm.product.id == null){
            if(uploader.queue.length > 0){
                if(uploader.queue.length == 1){
                    if(uploader.queue[0]._file.size < 5000000){
                        $('#product_msg').html('');
                        AjaxSaveProduct(true, false);
                    } else {
                        $('#save_product_msg').html('<div class="alert alert-warning" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>La Ficha Tecnica no puede pesar mas de 5.0 MB.</div>');
                    }
                } else {
                    $('#save_product_msg').html('<div class="alert alert-warning" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>Solo puedes seleccionar un archivo.</div>');
                }
            } else {
                $('#save_product_msg').html('<div class="alert alert-warning" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>Selecciona una de Ficha Tecnica para el Producto.</div>');
            }
        } else {
            var f1 = false;
            if(uploader.queue.length > 0){
                if(uploader.queue.length == 1){
                    if(uploader.queue[0]._file.size < 5000000){
                        f1 = true;
                    } else {
                        $('#save_product_msg').html('<div class="alert alert-warning" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>La Ficha Tecnica no puede pesar mas de 5.0 MB.</div>');
                        f1 = false;
                    }
                } else {
                    $('#save_product_msg').html('<div class="alert alert-warning" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>Solo puedes seleccionar un archivo para la Ficha Tecnica.</div>');
                    f1 = false; 
                }
            } else {
                f1 = false;
            }
            AjaxSaveProduct(f1, false);
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
        CloseSaveProduct();
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
        $('#save_product_btn').attr('disabled', 'disabled');
        $('#close_btn_product').attr('disabled', 'disabled');
        if(vm.product.id == null){
            if(uploader.queue.length > 0){
                if(uploader_img.queue.length > 0){
                    if(uploader_img.queue.length == 1 || uploader.queue.length == 1){
                        if(uploader.queue[0]._file.size < 5000000){
                            if(uploader_img.queue[0]._file.size < 5000000){
                                $('#save_product_msg').html('');
                                console.log('suve');
                                AjaxSaveProduct(true, true);
                            } else {
                                $('#save_product_msg').html('<div class="alert alert-warning" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>La Imagen del Producto no puede pesar mas de 5.0 MB.</div>');
                            }
                        } else {
                            $('#save_product_msg').html('<div class="alert alert-warning" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>La Ficha Tecnica no puede pesar mas de 5.0 MB.</div>');
                        }
                    } else {
                        $('#save_product_msg').html('<div class="alert alert-warning" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>Solo puedes seleccionar un archivo para la Imagen del Producto o la Ficha Tecnica.</div>');
                    }
                } else {
                    $('#save_product_msg').html('<div class="alert alert-warning" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>Selecciona una Imagen para el Producto.</div>');
                }
            } else {
                $('#save_product_msg').html('<div class="alert alert-warning" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>Selecciona una Ficha Tecnica para el Producto.</div>');
            }
        } else {
            var f1 = false;
            var f2 = false;
            if(uploader.queue.length > 0){
                if(uploader.queue.length == 1){
                    if(uploader.queue[0]._file.size < 5000000){
                        f1 = true;
                    } else {
                        $('#save_product_msg').html('<div class="alert alert-warning" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>La Ficha Tecnica no puede pesar mas de 5.0 MB.</div>');
                        f1 = false;
                    }
                } else {
                    $('#save_product_msg').html('<div class="alert alert-warning" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>Solo puedes seleccionar un archivo para la Ficha Tecnica.</div>');
                    f1 = false; 
                }
            } else {
                f1 = false;
            }
            if(uploader_img.queue.length > 0){
                if(uploader_img.queue.length == 1){
                    if(uploader_img.queue[0]._file.size < 5000000){
                        f2 = true;
                    } else {
                        $('#save_product_msg').html('<div class="alert alert-warning" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>La Imagen del Producto no puede pesar mas de 5.0 MB.</div>');
                        f2 = false;
                    }
                } else {
                    $('#save_product_msg').html('<div class="alert alert-warning" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>Solo puedes seleccionar un archivo para la Imagen del Producto.</div>');
                    f2 = false;
                }
            } else {
                f2 = false;
            }
            console.log('img: '+f1+' - '+'img2: '+f2);
            AjaxSaveProduct(f1, f2);
        }
    }//vm.SaveRawMaterial
    
    function AjaxSaveProduct(f1, f2)
    {
        vm.product.page = vm.page;
        vm.product.uploader = f1;
        vm.product.uploader_img = f2;
        console.log('img: '+f1+' - '+'img2: '+f2);
        console.log(vm.product);
        $http.post('save', vm.product)
            .success(function(res) {
                console.log(res);
                $('#save_product_btn').html('Guardar Producto');
                $('#save_product_btn').removeAttr('disabled');
                $('#close_btn_product').removeAttr('disabled');
                var msg = '<div class="alert alert-success" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+res.msg+'</div>'
                $('#save_product_msg').html(msg);
                $('#product_msg').html(msg);
                if(res.status){
                    vm.product.id = null;
                    vm.product_list = {};
                    if(vm.product.type == 'raw_material' || vm.product.type == 'semifinished_product' ){
                        if(f1 == true){
                            console.log('lleguee');
                            uploader.queue[0].upload();
                        } else {
                            CloseSaveProduct();
                            if(vm.product.type == 'raw_material'){
                                $('#raw_material_modal').modal('toggle');
                            }
                            if(vm.product.type == 'semifinished_product'){
                                $('#semifinished_product_modal').modal('toggle');
                            }
                        }
                    } else if(vm.product.type == 'finished_product') {
                        if(f1 == true){
                            uploader.queue[0].upload();
                        }
                        if(f2 == true){
                            uploader_img.queue[0].upload();
                        } else {
                            $('#finished_product_modal').modal('toggle');
                            CloseSaveProduct();
                        }

                    }
                } else {
                    $('#save_product_btn').html('Guardar Producto');
                    $('#save_product_btn').removeAttr('disabled');
                    $('#save_product_msg').html('<div class="alert alert-warning" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+res.msg+'</div>');
                }
        }).error(function (res){
            $('#save_product_btn').html('Guardar Producto');
            $('#save_product_btn').removeAttr('disabled');
            $('#save_product_msg').html('Error');
            console.log(res);
        });
    }//AjaxSaveProduct

	vm.GetProviderList = function ()
	{
        $('#select_provider').show();
        console.log(vm.product.type);
        $http.post('../provider/list/select', {type: vm.product.type})
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
                    vm.product_list = res.data;
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

    vm.EditProduct = function (ind)
    {
        vm.product = vm.product_list[ind];
        vm.product.id = vm.product_list[ind].id;
        vm.product.type = product_type;
        $('#save_product_msg').html('');
        $('#product_msg').html('');
        if(vm.product.type == 'raw_material'){
            $('#technical_file_div').show();
            vm.product.provider_id = vm.product_list[ind].provider_id;
            if( vm.product_list[ind].technical_file == ''){
                $('#product_technical_file').text('No hay Ficha Tecnica Disponible');
                $('#product_technical_file').removeClass('label-info');
                $('#product_technical_file').addClass('label-warning');
                $('#product_technical_file').attr('target', '');
            } else {
                $('#product_technical_file').attr('href', '../../statics/technical_file/'+vm.product_list[ind].technical_file);
            }
            //vm.product.code
            $('#raw_material_modal').modal('toggle');
            
        }
        if(vm.product.type == 'semifinished_product'){
            console.log('semifinished');
            vm.product = vm.product_list[ind];
            vm.product.id = vm.product_list[ind].id;
            vm.product.type = product_type;
            if( vm.product_list[ind].technical_file == ''){
                $('#product_technical_file').text('No hay Ficha Tecnica Disponible');
                $('#product_technical_file').removeClass('label-info');
                $('#product_technical_file').addClass('label-warning');
                $('#product_technical_file').attr('target', '');
            } else {
                $('#product_technical_file').attr('href', '../../statics/technical_file/'+vm.product_list[ind].technical_file);
            }
            $('#semifinished_product_modal').modal('toggle');
        }
        if(vm.product.type == 'finished_product'){
            console.log(vm.product_list[ind]);
            vm.product = vm.product_list[ind];
            console.log(vm.product);
            vm.product.id = vm.product_list[ind].id;
            vm.product.use = vm.product_list[ind].product_use;
            vm.product.type = product_type;

            vm.product.coatza_min = vm.product_list[ind].finished_products_coatza.min;
            vm.product.coatza_max = vm.product_list[ind].finished_products_coatza.max;
            vm.product.coatza_max_ped = vm.product_list[ind].finished_products_coatza.prod_max;
            vm.product.coatza_status = String(vm.product_list[ind].finished_products_coatza.status);

            vm.product.guadalajara_min = vm.product_list[ind].finished_products_guadalajara.min;
            vm.product.guadalajara_max = vm.product_list[ind].finished_products_guadalajara.max;
            vm.product.guadalajara_max_ped = vm.product_list[ind].finished_products_guadalajara.prod_max;
            vm.product.guadalajara_status = String(vm.product_list[ind].finished_products_guadalajara.status);

            if( vm.product_list[ind].technical_file == ''){
                $('#technical_file_div').show();
                $('#product_technical_file').text('No hay Ficha Tecnica Disponible');
                $('#product_technical_file').removeClass('label-info');
                $('#product_technical_file').addClass('label-warning');
                $('#product_technical_file').attr('target', '');
            } else {
                $('#product_technical_file').attr('href', '../../technical_file/'+vm.product_list[ind].technical_file);
            }
            if( vm.product_list[ind].img_product == ''){
                $('#img_product_div').hide();
            } else {
                $('#img_product_div').show();
                $('#img_product_file').attr('src', '../../img_product/'+vm.product_list[ind].img_product);
            }
            for(i in vm.product_type_list){
                //console.log(vm.product.product_type_id+' = '+vm.product_type_list[i].id);
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
            $('#finished_product_modal').modal('toggle');
        }
    }//vm.EditProduct

    function CloseSaveProduct()
    {
        vm.product.id = null;
        vm.product = {};
        //vm.provider_list = {};
        vm.product.type = product_type;

        //vm.product_type_list = {};
        /*vm.adjust = {};
        vm.model = {};
        vm.class = {};
        vm.color = {};
        vm.feets = {};*/
        $('#product_technical_file').text('Descargar Ficha Tecnica');
        $('#product_technical_file').removeClass('label-warning');
        $('#product_technical_file').addClass('label-info');
        $('#product_technical_file').attr('target', '_blank');
        $('#save_product_msg').html('');
        vm.product_list = {};
        GetProductList();
    }//CloseSaveProduct()

    vm.CloseSaveProduct = function()
    {
        CloseSaveProduct();
    }//vm.CloseSaveProduct

}//products_init