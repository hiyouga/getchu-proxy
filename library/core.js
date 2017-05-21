/*JS - by hoshi_hiyouga*/
var GetUrlValue = function(name) {
    var reg = new RegExp('(^|&)' + name + '=([^&]*)(&|$)', 'i');
    var r = window.location.search.substr(1).match(reg);
    if (r != null) {
        try {
            return decodeURIComponent(r[2]);
        } catch (e) {
            return null;
        }
    }
    return null;
}
$(document).ready(function(){
	var OriginTitle = document.title;
	var id = GetUrlValue('id');
	$("#mainimg").attr("imgdata","http://www.getchu.com/brandnew/" + id + "/c" + id + "package.jpg");
	$.get("get.php?type=info&id=" + id, function(data){
		$("#main_a").removeClass("hidden");
		data = JSON.parse(data);
		document.title = data.title;
		$("#getchu").attr("href","http://www.getchu.com/soft.phtml?id=" + id);
		$("#title").text(data.title);
		$("#story").html(data.story);
		$("#storyimg").attr("imgdata",data.storyimg);
		var info = data.info.split("|");
		$.each(info,function(index,item){
			$("#info").append('<tr>'+item+'</tr>');
		});
	});
});
$("#button_b").one("click",function(){
	var id = GetUrlValue('id');
	$.get("get.php?type=chara&id=" + id, function(data){
		$("#main_b").removeClass("hidden");
		data = JSON.parse(data);
		var chara = data.chara.split("|");
		$.each(chara,function(index,item){
			var t = item.split("#");
			$("#chara").append('<tr><td width="30%">' + "<img class=\"charaimg\" id=\"charaimg"+ index +"\" alt=\"点击显示\" class=\"img-thumbnail\" imgdata=\"" + t[0] + "\" />" + '</td><td class="charatext">' + t[1] + '</td></tr>');
		});
	});
});
$("#button_c").one("click",function(){
	var id = GetUrlValue('id');
	$.get("get.php?type=cg&id=" + id, function(data){
		$("#main_c").removeClass("hidden");
		data = JSON.parse(data);
		var cg = data.cg.split("|");
		$.each(cg,function(index,item){
			$("#cg").append("<img class=\"cgimg\" id=\"cgimg"+ index +"\" alt=\"点击显示\" class=\"img-thumbnail\" imgdata=\"" + item + "\" />");
		});
	});
});
$("#button_a").click(function(){
	$("#button_b").removeClass("active");
	$("#button_c").removeClass("active");
	$("#button_a").addClass("active");
	$("#main_b").addClass("hidden");
	$("#main_c").addClass("hidden");
	$("#main_a").removeClass("hidden");
	
});
$("#button_b").click(function(){
	$("#button_a").removeClass("active");
	$("#button_c").removeClass("active");
	$("#button_b").addClass("active");
	$("#main_a").addClass("hidden");
	$("#main_c").addClass("hidden");
	$("#main_b").removeClass("hidden");
	
});
$("#button_c").click(function(){
	$("#button_a").removeClass("active");
	$("#button_b").removeClass("active");
	$("#button_c").addClass("active");
	$("#main_a").addClass("hidden");
	$("#main_b").addClass("hidden");
	$("#main_c").removeClass("hidden");
});
$("body").on("click","img",function(){
	if($(this).attr("src") == undefined){
		//alert($(this).attr("src"));
		var id = $(this).attr("id");
		var img = $(this).attr("imgdata");
		var url = "img.php?url=" + img;
		var xhr = new XMLHttpRequest();
		xhr.open("GET",url,true);
		xhr.responseType = "blob";
		xhr.onload = function() {
			if (this.status == 200) {
				var blob = this.response;
				src = window.URL.createObjectURL(blob);
				$("#"+id).attr("src",src);
			}
		}
		xhr.send();
	}
});