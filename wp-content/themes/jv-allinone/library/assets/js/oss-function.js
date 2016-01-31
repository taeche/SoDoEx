jQuery(window).load(function(){
	jQuery("#currency").change(function(){
		postbyurl('hide_me',wnm_oss_p_url.a_url+'?action=change_cur','cur='+jQuery(this).val());							
	});
	jQuery(document).delegate("ul.load_widget li a","click",function(){
		var widget=jQuery(this).attr('data-widget');
		if(jQuery("#"+widget).html()==""){
			postbyurl(widget,wnm_oss_p_url.a_url + '?action=load_content_widget','widget='+widget,1);
		}
	})
});
