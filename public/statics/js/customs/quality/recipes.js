angular.module('app', []).controller('appCtrl', ['$http', recipes_init]);

function recipes_init($http){
    var vm = this;

    vm.page = 1;
    vm.recipes_list = {};
    
    vm.recipe = {};

    vm.semifinished_products_list = {};
    vm.finished_products_types_list = {};

	vm.product_list_select = {};

    vm.adjust = {};
    vm.model = {};
    vm.class = {};
    vm.color = {};
    vm.feets = {};

    

	$('#adjust_div').hide();
	$('#class_div').hide();
	$('#model_div').hide();
	$('#color_div').hide();
	$('#feets_div').hide();

    $('#finished_product_type_div').hide();
    $('#semifinished_product_type_div').hide();
    Recipe_init();

    function Recipe_init()
    {
        vm.recipe = {};
        vm.recipe.id = null;
        vm.recipe.product_type = null;
        vm.recipe.adjust_id =  null;
        vm.recipe.model_id =  null;
        vm.recipe.class_id =  null;
        vm.recipe.color_id =  null;
        vm.recipe.feets_id =  null;
        vm.recipe.semifinished_product_id = null;
        vm.recipe.finished_product_id = null;
        vm.recipe.finished_product_type_id = null;

        vm.product = {};
        vm.product.type = null;
        vm.product.id = null;
        vm.product.quantity = null;
        vm.product.unit = null;
        vm.products_recipe_list = Array();
    }//Recipe_init

    $('#recipes_list_loader').hide();

    vm.RecipesList = function ()
    {
        $('#recipes_list_loader').show();
        $http.post('recipes/list', { page: vm.page })
            .success(function(res) {
                console.log(res);
                $('#recipes_list_loader').hide();
                if(res.status){
                    vm.recipes_list = res.data;
                    RenderPage(res.tp);
                } else {
                    $('#recipes_msg').html('<div class="alert alert-warning" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+res.msg+'</div>');
                }
        }).error(function (res){
            console.log(res);
        });                
    }//vm.RecipesList

    vm.SaveRecipe = function ()
    {
        console.log(vm.recipe);
        console.log(vm.products_recipe);
        $('#save_recipe_btn').html('<i class="fa fa-spinner fa-spin fa-2x"></i>');
        $('#save_recipe_btn').attr('disabled', 'disabled');
        $('#cancel_recipe_btn').attr('disabled', 'disabled');
        $http.post('recipes/save', { recipe: vm.recipe, products:vm.products_recipe_list, page: vm.page })
            .success(function(res) {
                console.log(res);
                $('#save_recipe_btn').html('Guardar Receta');
                $('#save_recipe_btn').removeAttr('disabled');
                $('#cancel_recipe_btn').removeAttr('disabled');
                if(res.status){
                    vm.recipes_list = res.data;
                    RenderPage(res.tp);
                    $('#semifinished_product_type_div').hide();
                    $('#finished_product_type_div').hide();
                    $('#adjust_div').hide();
                    $('#class_div').hide();
                    $('#model_div').hide();
                    $('#color_div').hide();
                    $('#feets_div').hide();
                    Recipe_init();
                    $('#recipes_msg').html('');
                    $('#recipes_save_msg').html('<div class="alert alert-success" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+res.msg+'</div>');
                } else {
                    $('#recipes_save_msg').html('<div class="alert alert-warning" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+res.msg+'</div>');
                }
        }).error(function (res){
            console.log(res);
            $('#save_recipe_btn').html('Guardar Receta');
            $('#save_recipe_btn').removeAttr('disabled');
            $('#cancel_recipe_btn').removeAttr('disabled');
        });        
    }//vm.SaveRecipe

    vm.LoadDataRecipe = function ()
    {
    	$('#save_recipe_btn').attr('disabled', 'disabled');
        $http.post('recipes/get_data')
            .success(function(res) {
            	console.log(res);
            	$('#save_recipe_btn').removeAttr('disabled');
            	$('#recipes_save_msg').html('');
                if(res.status){
                	vm.semifinished_products_list = res.sfpl;
                	vm.finished_products_types_list = res.fpl;
				    vm.adjust = res.adjust;
				    vm.model = res.model;
				    vm.class = res.class;
				    vm.color = res.color;
				    vm.feets = res.feets;
                } else {
                	$('#recipes_save_msg').html('<div class="alert alert-warning" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+res.msg+'</div>');
                }
        }).error(function (res){
            console.log(res);
        });
    }//vm.LoadDataRecipe

    vm.ChangeProductType = function ()
    {
    	console.log(vm.recipe.product_type);
        $('#save_recipe_btn').removeAttr('disabled');
        $('#recipes_save_msg').html('');
    	vm.recipe.semifinished_product_id = '';
    	vm.recipe.finished_product_id = '';
		$('#adjust_div').hide();
		$('#class_div').hide();
		$('#model_div').hide();
		$('#color_div').hide();
		$('#feets_div').hide();

    	if(vm.recipe.product_type == 'semifinished_product'){
    		$('#finished_product_type_div').hide();
    		$('#semifinished_product_type_div').show();    		
    	}
    	if(vm.recipe.product_type == 'finished_product'){
    		$('#finished_product_type_div').show();
    		$('#semifinished_product_type_div').hide();
    	}
    }//vm.ChangeProductType

    vm.ChangeFinishedProductType = function ()
    {
    	console.log(vm.recipe.finished_product_type_id);
    	if(vm.recipe.finished_product_type_id != null){
	        for(i in vm.finished_products_types_list){
				if(vm.recipe.finished_product_type_id == vm.finished_products_types_list[i].id){
	                if(vm.finished_products_types_list[i].adjust == 1){
	                    $('#adjust_div').show();
	                } else {
	                    $('#adjust_div').hide();
	                }
	                if(vm.finished_products_types_list[i].class == 1){
	                    $('#class_div').show();
	                } else {
	                    $('#class_div').hide();
	                }
	                if(vm.finished_products_types_list[i].model == 1){
	                    $('#model_div').show();
	                } else {
	                    $('#model_div').hide();
	                }
	                if(vm.finished_products_types_list[i].color == 1){
	                    $('#color_div').show();
	                } else {
	                    $('#color_div').hide();
	                }
	                if(vm.finished_products_types_list[i].feets == 1){
	                    $('#feets_div').show();
	                } else {
	                    $('#feets_div').hide();
	                }
	            }            
	        }
    	} else {
    		$('#adjust_div').hide();
    		$('#class_div').hide();
    		$('#model_div').hide();
    		$('#color_div').hide();
    		$('#feets_div').hide();
    	}
    }//vm.ChangeProductType

    vm.ChangeFeatureProduct = function ()
    {
        $('#adjust').attr('disabled', 'disabled');
        $('#class').attr('disabled', 'disabled');
        $('#model').attr('disabled', 'disabled');
        $('#color').attr('disabled', 'disabled');
        $('#feets').attr('disabled', 'disabled');
        $('#save_recipe_btn').attr('disabled', 'disabled');
        $http.post('recipes/feature', { recipe: vm.recipe })
            .success(function(res) {
                console.log(res);
                $('#adjust').removeAttr('disabled');
                $('#class').removeAttr('disabled');
                $('#model').removeAttr('disabled');
                $('#color').removeAttr('disabled');
                $('#feets').removeAttr('disabled');
                if(res.status){
                    vm.recipe.finished_product_id = res.product_id;
                    $('#save_recipe_btn').removeAttr('disabled');                    
                    $('#recipes_save_msg').html('');
                } else {
                    $('#recipes_save_msg').html('<div class="alert alert-warning" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+res.msg+'</div>');
                }
        }).error(function (res){
            console.log(res);
        });
    }//vm.ChangeFeatureProduct()

    $('#select_product').hide();

    vm.ChangeProductTypeRecipe = function ()
    {
        $('#select_product').show();
    	console.log(vm.product.type);
        vm.product_list_select = {};
    	$('#product_type_recipe').attr('disabled', 'disabled');
        $http.post('recipes/product', { product_type: vm.product.type })
            .success(function(res) {
            	console.log(res);
                $('#select_product').hide();
            	$('#product_type_recipe').removeAttr('disabled');
            	$('#recipes_save_msg').html('');
                if(res.status){
                    vm.product_list_select = res.data;
                } else {
                	$('#recipes_save_msg').html('<div class="alert alert-warning" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+res.msg+'</div>');
                }
        }).error(function (res){
            console.log(res);
            $('#select_product').hide();
            $('#product_type_recipe').removeAttr('disabled');
        });
    }//ChangeProductType

    vm.ChangeProductRecipe = function ()
    {
        for(i in vm.product_list_select){
            if(vm.product.id == vm.product_list_select[i].id){
                vm.product.unit = vm.product_list_select[i].unit;
                vm.product.name = vm.product_list_select[i].name;
                vm.product.description = vm.product_list_select[i].description;
                break;
            }
        }
    }//vm.ChangeProductRecipe



    vm.AddProduct = function ()
    {
        if(vm.product.type != null  && vm.product.id != null && vm.product.quantity != null && vm.product.unit != null ){
            console.log('enrtre');
            $('#recipes_save_msg').html('');
            vm.products_recipe_list.push(vm.product);
            vm.product = {};
            console.log(vm.products_recipe_list);
        } else {
            $('#recipes_save_msg').html('<div class="alert alert-warning" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>Todos los campos del Producto deben de estar llenos.</div>');
        }
    }//vm.AddProduct

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
                GetClientsList();
            });
        }
    }//PageRender

    vm.EditRecipe = function (ind)
    {
        console.log(vm.recipes_list[ind]);
        $('#save_client_msg').html('');
        $('#recipes_msg').html('');
        vm.recipe.product_type = vm.recipes_list[ind].product_type;
        vm.recipe.finished_product_id = vm.recipes_list[ind].product_type_id;
        if( vm.recipe.product_type == 'finished_product' ){
            $('#finished_product_type_div').show();
            vm.recipe.finished_product_type_id = vm.recipes_list[ind].product_type_id;
            if(vm.recipes_list[ind].adjust == 1){
                $('#adjust_div').show();
            } else {
                $('#adjust_div').hide();
            }
            if(vm.recipes_list[ind].class == 1){
                $('#class_div').show();
            } else {
                $('#class_div').hide();
            }
            if(vm.recipes_list[ind].model == 1){
                $('#model_div').show();
            } else {
                $('#model_div').hide();
            }
            if(vm.recipes_list[ind].color == 1){
                $('#color_div').show();
            } else {
                $('#color_div').hide();
            }
            if(vm.recipes_list[ind].feets == 1){
                $('#feets_div').show();
            } else {
                $('#feets_div').hide();
            }
        }
        if( vm.recipe.product_type == 'semifinished_product' ){
            $('#semifinished_product_type_div').show();
            vm.recipe.semifinished_product_id = vm.recipes_list[ind].product_type_id;
            $('#semifinished_product_type_div').show();
        }
        vm.recipe.id = vm.recipes_list[ind].id;

        vm.recipe.adjust_id = vm.recipes_list[ind].adjust_id;
        vm.recipe.class_id = vm.recipes_list[ind].class_id;
        vm.recipe.model_id = vm.recipes_list[ind].model_id;
        vm.recipe.color_id = vm.recipes_list[ind].color_id;
        vm.recipe.feets_id = vm.recipes_list[ind].feets_id;

        vm.products_recipe_list = vm.recipes_list[ind].products;
        $('#create_recipes_modal').modal('toggle');

    }//vm.EditClient

    vm.DeleteRecipe = function (ind)
    {
        var r = confirm('¿Desea Eliminar la Receta de este Producto?');
        if(r){
            $('#rec_del_'+vm.recipes_list[ind].id).html('<i class="fa fa-spinner fa-spin fa-1x"></i>');
            $('#rec_del_'+vm.recipes_list[ind].id).attr('disabled', 'disabled');
            $http.post('recipes/delete', { id: vm.recipes_list[ind].id, page: vm.page })
                .success(function(res) {
                    console.log(res);
                    $('#rec_del_'+vm.recipes_list[ind].id).removeAttr('disabled');
                    $('#rec_del_'+vm.recipes_list[ind].id).html('<i class="fa fa-trash"></i>');
                    if(res.status){
                        vm.recipes_list = res.data;
                        RenderPage(res.tp);
                    } else {
                        $('#recipes_msg').html('<div class="alert alert-warning" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+res.msg+'</div>');
                    }
            }).error(function (res){
                console.log(res);
                $('#rec_del_'+vm.recipes_list[ind].id).removeAttr('disabled');
                $('#rec_del_'+vm.recipes_list[ind].id).html('<i class="fa fa-trash"></i>');
                $('#recipes_msg').html('<div class="alert alert-warning" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>¡Error!</div>');
            });
        }
    }//vm.DeleteRecipe

    vm.CancelRecipe = function ()
    {   
        $('#semifinished_product_type_div').hide();
        $('#finished_product_type_div').hide();
        $('#adjust_div').hide();
        $('#class_div').hide();
        $('#model_div').hide();
        $('#color_div').hide();
        $('#feets_div').hide();
        $('#save_recipe_btn').removeAttr('disabled');
        Recipe_init();
    }//vm.CancelRecipe

}//index_init