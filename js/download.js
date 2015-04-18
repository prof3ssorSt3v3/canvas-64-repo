//download.js

document.addEventListener("DOMContentLoaded", function(){
	document.getElementById("b").addEventListener("click", fetchImg);
});

function fetchImg(ev){
	var url = "http://faculty.edumedia.ca/griffis/mad9022/final-w15/get.php?dev=234234&img_id=70";
	sendRequest(url, imgReturned, null);
}

function imgReturned(xhr){
	var json = JSON.parse(xhr.responseText);
	alert(json.id);
	var img = document.createElement("img");
	img.src = json.data;
	var w = img.width;
	var h = img.height;
	
	//now load the image into the canvas
	var c = document.getElementById("c");
	var ctx = c.getContext("2d");
	c.width = w;
	c.height = h;
	c.style.width = w + "px";
	c.style.height = h + "px";
	
	ctx.drawImage(img, 0, 0);
}