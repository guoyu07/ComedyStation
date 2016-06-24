function redirect(url) {
	location.href = url;
}
//滚动条
/*$(function(){
	$(":text").addClass('input-text');
})*/

/**
 * 全选checkbox,注意：标识checkbox id固定为为check_box
 * @param string name 列表check名称,如 uid[]
 */
function selectall(name) {
	if ($("#check_box").attr("checked")==undefined) {
		$("input[name='"+name+"']").each(function() {
			this.checked=false;
		});
	} else {
		$("input[name='"+name+"']").each(function() {
			this.checked=true;
		});
	}
}
//禁止选择一级分类
function check_cate(obj){ 
	var level=parseInt($("option:selected",$(obj)).attr('level'));
	var pid=parseInt($("option:selected",$(obj)).attr('pid'));
	if(pid==0||level==0||level==1){
		alert("一级、二级分类禁止选择!");
		$('option[value="0"]',$(obj)).attr('selected','selected');
	}
}

function getRegion(currSelectObj,bindSelectObj) {
  var parent_id = currSelectObj.val();

  if(currSelectObj.attr('id') == 'province') {
    $('#city').html('<option value="">-请选择-</option>');  
  }
  $('#area').html('<option value="">-请选择-</option>');

  if(parent_id == '') return;
  $.getJSON('admin.php?a=get_region&m=public',
        {parent_id:parent_id},
        function(data) {
            var option = '<option value="">-请选择-</option>';
            for(var i=0; i<data.length; i++) {
               option += '<option value="'+data[i]['region_id']+'">'+data[i]['region_name']+'</option>';
            }
            bindSelectObj.html(option);
        });
}

function uploadify(obj,save_path) {
    
    $(obj).uploadify({
        'formData'     : {
          'save_path' : save_path
        },
        'buttonText': '选择图片',
        'buttonClass': 'browser',
        'dataType':'html',
        'removeCompleted': false,
        'swf'      : '/Assets/js/uploadify/uploadify.swf',
        'uploader' : '/antidisestablishmentarianism.php?m=uploadify&a=index',
        'multi' : false,
        'debug': false,
        'height': 30,
        'width':90,
        'auto': true,
        'fileTypeExts': '*.jpg;*.gif;*.png;*.jpeg',
        'fileTypeDesc': '图片类型(*.jpg;*.jpeg;*.gif;*.png)',
        'fileSizeLimit': '1024',
        'progressData':'speed',
        'removeCompleted':true,
        'removeTimeout':0,
        'requestErrors':true,
        'onFallback':function()
          {
              alert("您的浏览器没有安装Flash");
          },
        'onUploadSuccess' : function (file, data, response) {
            var result = $.parseJSON(data);
            if(result.status == 0) {
               alert(result.data);
            }else {
              var parent = $(obj).parent();
              var children = parent.children();
			  //alert(result.data);
              $(children[children.length - 1]).attr('src',result.data.substr(1,result.data.length)).show();
              $(children[children.length - 2]).val(result.data.substr(1,result.data.length));
            }
        }
      });
    
  }