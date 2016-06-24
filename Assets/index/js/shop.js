define(function(require, exports, module) {
	var App = require('app');

	var shop ={
		addOrder : function(){
			$('.gift-list').find('li').hover(function(){
				$(this).css('border-color','#e9e9e9').css('box-shadow','0 0 5px #e9e9e9')
			},function(){
				$(this).css('border-color','#fff').css('box-shadow','0 0 5px #fff')
			})
			$('.subtract').click(function(){
				if(!isNaN($('#num').val()) && $('#num').val()>1){
					$('#num').val(Number($('#num').val())-1);
				}else{
					$('#num').val(1);
				}
			})
			$('.add').click(function(){
				var gift_score=$(this).parent().attr('data-gift-score');
				var user_score=$(this).parent().attr('data-user-score');
				if(!isNaN($('#num').val()) && user_score>(Number($('#num').val())+1)*gift_score){
					$('#num').val(Number($('#num').val())+1);
				}else{
					alert('你拥有的麻币最多可兑换'+$('#num').val()+'件');
				}
			})
			$('#num').blur(function(){
				var gift_score=$(this).parent().attr('data-gift-score');
				var user_score=$(this).parent().attr('data-user-score');
				if(!isNaN($(this).val())){
					var num=parseInt($(this).val());
					if(num>0){
						if(user_score<num*gift_score){
							alert('你拥有的麻币最多可兑换'+parseInt(user_score/gift_score)+'件');
							$(this).val(parseInt(user_score/gift_score));
						}else{
							$(this).val(num);
						}
					}else{
						$(this).val(1);
					}
				}else{
					alert('请输入有效数字');
					$(this).val(1);
				}
			})
			$.validator.setDefaults({       
				submitHandler: function() {
					$('#form_gift_info').find(".g_submit").attr("disabled", true).val('提交中...').css('background','#bcbcbd');
					$.ajax({
						type : 'POST',
						url : giftUrl,
						dataType : 'json',
						data : $('#form_gift_info').serialize(),
						success : function(ret){
							if(ret.code > 0) {
								$('#form_gift_info').find(".g_submit").val('提交成功');
								$.tips({text:'兑换信息提交成功！'});
							} else {
								$('#message').html('<p>'+ret.message+'</p>');
								$('#form_gift_info').find(".g_submit").attr("disabled", false).val('兑换').css('background','#ff7e69');
							}
						}
					})
				}
			}),
			$('#form_gift_info').validate({
				onkeyup : false,
				rules:{
					g_county : {
						required : true
					},
					g_street : {
						required : true
					},
					g_name : {
						required : true
					},
					g_phone_number : {
						required : true,
						isMobile : true
					}
				},
				messages : {
					g_county :{
						required : '所以地区不能为空'
					},
					g_street : {
						required : '街道地址不能为空'
					},
					g_name : {
						required : '收货人姓名不能为空'
					},
					g_phone_number : {
						required : '手机号不能为空',
						isMobile : '请输入正确的手机号'
					}
				},
				errorElement: "span",
				errorPlacement: function (error, element) { 
		      		if (element.is(':radio') || element.is(':checkbox')) {
		          		var eid = element.attr('name');
		          		error.appendTo(element.parent());
		      		} else {
		          		error.appendTo(element.closest("dd"));
		     		}
				},
				focusInvalid: true,
				success:function(e)
				{
					e.html('&nbsp;').addClass('valid');
				}
			})
			$.validator.setDefaults({       
				submitHandler: function() {
					$('#form_gift_info_phoneNumber').find(".g_submit").attr("disabled", true).val('提交中...').css('background','#bcbcbd');
					$.ajax({
						type : 'POST',
						url : giftUrl,
						dataType : 'json',
						data : $('#form_gift_info_phoneNumber').serialize(),
						success : function(ret){
							if(ret.code > 0) {
								$('#form_gift_info_phoneNumber').find(".g_submit").val('提交成功');
								$.tips({text:'兑换信息提交成功！'});
							} else {
								$('#message').html('<p>'+ret.message+'</p>');
								$('#form_gift_info_phoneNumber').find(".g_submit").attr("disabled", false).val('兑换').css('background','#ff7e69');
							}
						}
					})
				}
			})


		}
	}

	//文本框获取焦点 显示隐藏提示文字
	function input_value_gift(t){
		t.find('input').focus(function(){
			if($(this).val() == $(this).attr('title') || $(this).val() == ''){
				$(this).parent().find('.g_value').hide();
			}
		});
		t.find('input').blur(function(){
			if($(this).val() == $(this).attr('title') || $(this).val() == ''){
				$(this).val('');
				$(this).parent().find('.g_value').show();
			}
		})
	}

	return shop;
});