jQuery(window).load(function(){
	jQuery("#page_template").change(function(){
		var str = jQuery(this).val();
		if(str.indexOf("blog-templates")!=-1){
			jQuery("#page_option").fadeIn();
		}
		else{
			jQuery("#page_option").fadeOut();
		}
		if(str=="page-templates/front-page.php"){
				jQuery("#postcustom").fadeOut();
				jQuery("#commentstatusdiv").fadeOut();
				jQuery("#commentsdiv").fadeOut();
				jQuery("#mymetabox_revslider_0").fadeOut();
				jQuery("#page_option").fadeIn();
		}
		else{
				jQuery("#postcustom").fadeIn();
				jQuery("#commentstatusdiv").fadeIn();
				jQuery("#commentsdiv").fadeIn();
				jQuery("#mymetabox_revslider_0").fadeIn();
		}
		
	});							 
});

jQuery(document).ready(function(){
	if(jQuery('#page_template').length){
		var str = jQuery("#page_template").val();
			if(str.indexOf("blog-templates")!=-1){
				jQuery("#page_option").fadeIn();
		}
		
		if(str=="page-templates/front-page.php"){
				jQuery("#postcustom").fadeOut();
				jQuery("#commentstatusdiv").fadeOut();
				jQuery("#commentsdiv").fadeOut();
				jQuery("#mymetabox_revslider_0").fadeOut();
				jQuery("#page_option").fadeIn();
		}
	}
	
})

