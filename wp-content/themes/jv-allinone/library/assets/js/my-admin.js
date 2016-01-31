        var $ = jQuery;
        $(document).ready(function(){

	

	// Sortable album
	if($(".album-list-image").length>0){
		$(".album-list-image").sortable();
	}
	
	// Save album
	$("#publish").on("click",function(){
		var albums = new Array();
		if($(".album-list-image").length>0){
			$(".album-list-image").find("li").each(function(){
				albums.push({
					id:$(this).children("input.idattch").val(),
					caption:$(this).children("input.album-title").val()
				});
			});
			$(".album-list-image").prev(".album_json_data").val(JSON.stringify(albums));
           
		}
	});
});
        // Album in post type
var file_frame;
function open_wp_media_ablum(obj){
	var $ = jQuery;
	var parent = $(obj).parents(".w-album");
	var album_container = $(obj).prev(".album-list-image");
	if (file_frame) {
		file_frame.open();
		return false;
	}
	
	file_frame = wp.media.frames.file_frame = wp.media({
		title: jQuery(this).data('uploader_title'),
		button: {
			text: "Insert to album",
		},
		multiple: true
	});
	
	file_frame.on("select", function () {
		var selection = file_frame.state().get('selection');
		selection.map(function( attachment ){
			attachment = attachment.toJSON();
			var html = 	"<li>";
			html	+=	"	<input class='idattch' type='hidden' value='"+attachment.id+"' />";
			html	+=	"	<img class='album-img' src='"+attachment.url+"' />";
			html	+=	"	<input placeholder='Caption..' type='text' class='album-title' />";
			html	+=	"	<a onclick='return remove_image_album(this)' class='album-remove' href='#'>x</a>";
			html 	+=	"</li>";
			album_container.append(html);
		});
		
	});
	// Finally, open the modal
	file_frame.open();
	return false;
}

// Remove image album
function remove_image_album(obj){
	var $ = jQuery;
	$(obj).parent("li").css({
		'border-color': 'red'
	}).fadeOut("slow",function(){
		$(this).remove();
	});
	return false;
}