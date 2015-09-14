define([
	'dialogView',
	'cropzoom_custom',
	'cropzoom'
], function (dialog) {
	var layer ={};
	layer.setCropzoom = function() {//设置裁剪
		$('.crop-boxes').each(function() {
			var $box = $(this);
			var img = $box.data('img');
			var width = $box.data('imgWidth');
			var height = $box.data('imgHeight');
			var post = $box.data('post');
			
			var cropzoom = $box.children("[name='crop_container']").cropzoom({
	            width: 400,
	            height: 300,
	            bgColor: '#CCC',
	            enableRotation:true,
	            enableZoom:true,
	            zoomSteps:10,
	            rotationSteps:10,
	            selector:{      
				  w: 150,
	              centered: true,
	              borderColor: 'blue',
	              borderColorHover: 'red',
	              showPositionsOnDrag : false,
				  showDimetionsOnDrag : false
	            },
	            image:{
	                source: img,
	                width: width,
	                height: height,
	                minZoom: 10,
	                maxZoom: 150
	            }
	        });
	        cropzoom.setSelector(45, 45, 150, 150, true);
	        
	        $btn_container = $('<div>').addClass('btn-container');
	        $btn_confirm = $('<button>').attr('type', 'button').addClass('btn btn-info').html('确定返回');
	        $btn_crop = $('<button>').attr('type', 'button').addClass('btn btn-success').html('裁剪');
	        $btn_restore = $('<button>').attr('type', 'button').addClass('btn btn-warning').html('还原');
	        $btn_container.append($btn_confirm);
	        $btn_container.append($btn_crop);
	        $btn_container.append($btn_restore);
	        
	        var hidden_url = '';
	        var hidden_imageid = 0;
	        
	        //弹出层按钮
	        $btn_confirm.click(function() {
	        	//传数据回去
	        	var json = {'url':hidden_url, 'imageid':hidden_imageid};
	        	dialog.close({'data':json});
	        });
	        
	        $btn_crop.click(function(){ 
	            cropzoom.send(post, 'POST', {}, function(json) {
	            	hidden_url = json.url;
	            	hidden_imageid = ~~json.imageid;
	                $('.crop-result').find('img').remove();
	                var img = $('<img />').attr('src', json.url);
	                $('.crop-result').find('.txt').hide().end().append(img);
	            });
	        });
	        
	        $btn_restore.click(function(){
	            $('.crop-result').find('img').remove();
	            $('.crop-result').find('.txt').show();
	            cropzoom.restore();
	        });
	        
	        $box.append($btn_container);
		});

        

	};

	return layer;
});
