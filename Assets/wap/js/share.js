
define(function(require, exports, module) {
	
	var Share = {
		//分享到新浪网
		shareToSina:function(title, url, pic) {
		    window.open('http://service.weibo.com/share/share.php?title='+encodeURIComponent(title)+'&url='+encodeURIComponent(url)+'&pic='+encodeURIComponent(pic), '_blank');
		},
		//分享到腾讯微博
		shareToTencent:function(title,url, pic) {
		    window.open('http://share.v.t.qq.com/index.php?c=share&a=index&title='+encodeURIComponent(title)+'&url='+encodeURIComponent(url)+'&pic='+encodeURIComponent(pic), '_blank');
		},
		//分享到QQ空间
		shareToQzone:function(title, url, pics) {
			window.open('http://sns.qzone.qq.com/cgi-bin/qzshare/cgi_qzshare_onekey?title='+encodeURIComponent(title)+'&url='+encodeURIComponent(url)+'&pics='+encodeURIComponent(pics)+'&desc='+encodeURIComponent(title),'_blank');
		},
		//分享给好友
		shareToFriend:function(title, url, pics) {
			window.open('http://connect.qq.com/widget/shareqq/index.html?title='+encodeURIComponent(title)+'&url='+encodeURIComponent(url)+'&pics='+encodeURIComponent(pics)+'&desc='+encodeURIComponent(title),'_blank');
		}
		
	};
	return Share;
});