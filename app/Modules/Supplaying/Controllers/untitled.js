    vm.SaveProduct = function ()
    {
        console.log(vm.product);
        $('#product_msg').html('');
        $('#save_product_btn').html('<i class="fa fa-spinner fa-spin fa-2x"></i>');
        $('#save_product_btn').attr('disabled', 'disabled');
        if(uploader.queue.length > 0){
            if(uploader.queue.length == 1){
                /*if(uploader.queue[0]._file.size < 5000000){
                    AjaxSaveProduct();
                } else{
                    uploader.queue.splice(0, 1);
                    $('#save_product_btn').html('Guardar Producto');
                    $('#save_product_btn').removeAttr('disabled');
                    $('#product_msg').html('<div class="alert alert-warning" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>Archivo muy Pesado, debe pesar menos de 5.0 MB</div>');
                }*/
            } else if(uploader.queue.length == 2) {
                console.log('--------2');
            }    
        } else {
            $('#save_product_btn').html('Guardar Producto');
            $('#save_product_btn').removeAttr('disabled');
            $('#product_msg').html('<div class="alert alert-warning" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>Selecciona una de Ficha Tecnica para el Producto.</div>');
        }
    }//vm.SaveRawMaterial



        /*uploader.filters.push({
        name: 'customFilter',
        fn: function(item, options) {
            return this.queue.length < 10;
        }
    });*/


            //uploader.queue.splice(0, 1);
        /*for(i in uploader.queue){
            uploader.queue[i].remove();
        }*/


                        $.each(uploader.queue, function (ind, elem){
                            elem.upload();
                        });