function changelikebox(){
    
var likebox_width = document.getElementById("new_likebox_width").value;
var likebox_height = document.getElementById("new_likebox_height").value;
var likebox_fanpageurl = document.getElementById("new_likebox_fanpageurl").value;
var likebox_colorscheme = document.getElementById("new_likebox_colorscheme").value;
var likebox_showfaces = document.getElementById("new_likebox_showfaces").checked;
var likebox_bordercolor = document.getElementById("new_likebox_bordercolor").value;
var likebox_showstream = document.getElementById("new_likebox_showstream").checked;
var likebox_showheader = document.getElementById("new_likebox_showheader").checked;
var bgcolor;

if(likebox_colorscheme=="dark"){
    bgcolor="background:black;"
}

document.getElementById("likeboxpreview").innerHTML="<div style='margin:auto; "+bgcolor+"; display:block; width:"+likebox_width+"px;'><iframe src='//www.facebook.com/plugins/likebox.php?href="+likebox_fanpageurl+"&amp;width="+likebox_width+"&amp;height="+likebox_height+"&amp;colorscheme="+likebox_colorscheme+"&amp;show_faces="+likebox_showfaces+"&amp;border_color="+likebox_bordercolor+"&amp;stream="+likebox_showstream+"&amp;header="+likebox_showheader+"&amp;appId=112465995526913 scrolling='no' frameborder='0' style=\"border:none; overflow:hidden; width:"+likebox_width+"px; height:"+likebox_height+"px;\" allowTransparency='true'></iframe></div>";
}