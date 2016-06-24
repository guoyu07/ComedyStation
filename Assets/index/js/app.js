
define(function(require, exports, module) {
	
	var App = {
		alert : function(info,cb){
			var e = $('<div class="tips"><div class="info">' + info + '</div></div>');
			e.appendTo('body');
			setTimeout(function() {
				 e.remove();
				 cb && typeof cb == 'function' && cb();
			},2000);
		},
		loading: {
			ele: false,
			init: function() {
				if(!this.ele) {
					this.ele = $('<div id="loading" class="loading"><div class="loading_inner"><i class="loading_icon"></i> 请稍候...</div></div>');
					this.ele.appendTo('body');
				}
			},
			show: function() {
				this.init();
				return this.ele.show();
			},
			hide: function() {
				this.init();
				return this.ele.hide();
			}
		},
		request: function(opt) {
			if(opt['loading'] !== false) {
				App.loading.show();
			}
			var _conf = {
				error: function(xhr) {
					xhr.responseText && App.alert(xhr.responseText);
					if(opt['loading'] !== false) {
						App.loading.hide();
					}
				},
				complete: function() {},
				timeout: 10000,
				type : opt.type || 'POST',
				url : opt.url,
				data : opt.data || {},
				async : opt.async,
				dataType : opt.rtype || 'json'
			};
			_conf.beforeSend = function(xhr) {
				opt.prefn && opt.prefn();
			};
			if (opt.success) {
				_conf.success = function(result) {
					opt.success(result) || true;
					if(opt['loading'] !== false) {
						App.loading.hide();
					}
				};
			}
			$.ajax(_conf);
		},
		lazyload:function() {
			require.async(['/Assets/js/jquery.lazyload'], function() {
				$("img[original]").lazyload({
					placeholder: "/Assets/index/images/load_img.png",
					effect: "fadeIn"
				});
			});
		},
		uploadify:function(obj,cb){
			require.async(['/Assets/js/jquery.uploadify.min','/Assets/js/uploadify/uploadify.css'], function() {
				
				obj.uploadify({
					'buttonText' : '选择图片',
					'swf' : '/Assets/js/uploadify/uploadify.swf?v=' + ( parseInt(Math.random()*1000) ),
					'uploader' : '/user/info/uploadify',
					'auto' : true,
					'height': 40,
					'multi'	: false,
					'method' : 'post',
					'fileObjName' : 'Filedata',
					'queueSizeLimit' : 1,
					'fileSizeLimit' : '1000KB',
					'fileTypeExts': '*.gif; *.jpg; *.png; *.jpeg',
					'fileTypeDesc': '只允许.gif .jpg .png .jpeg 图片！' ,
					'onSelect': function(file) {
						
					},
					'onUploadSuccess' : function(file, data, response){
						obj.uploadify('disable', true);
						var rst = (new Function("","return "+data))();
						if( rst.status == 0 ){
							alert('上传失败:'+rst.info);
						}else{
							cb && typeof cb == 'function' && cb(rst);							
						};
					},'onUploadError' : function(file, errorCode, errorMsg, errorString){
						alert(errorString);
					}
				});
			});
		},
		check_phone:function(phone) {
			var re = /^(13|14|15|17|18)[0-9]{9}/;
			 if(re.test(phone)) return true;
			 return false;
		}
	};

	// jq 
	$.fn.serializeObject = function() {
		var o = {};
		var a = this.serializeArray();
		$.each(a, function() {
			if (o[this.name]) {
				if (!o[this.name].push) {
					o[this.name] = [ o[this.name] ];
				}
				o[this.name].push(this.value || '');
			} else {
				o[this.name] = this.value || '';
			}
		});
		return o;
	};
	return App;
});