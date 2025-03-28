function set_msg(id, msg, error)
{
	msg_box = document.getElementById(id);
	msg_box.innerHTML = msg;

	if(error)
		msg_box.style.color = '#C00';
	else
	{
		msg_box.style.color = '#090';
	}
}

function msg_box_msg(msg, error) {
	box = document.getElementById('msg_box');
	box.innerHTML = msg + '<br>';
	box.style.display = 'inline';	// This line unique to IBDI, not in IS
	
	if(!error) {			// NO ERROR
		box.style.borderColor = '#0A0';
		box.style.color = '#000';
		//box.style.backgroundColor = '#EFE';
	}
	else {					// ERROR
		box.style.borderColor = '#900';
		box.style.color = '#F00';
		//box.style.backgroundColor = '#FFD';
	}
}

function random_banner() {
	var imgs = ["banner1.jpg","banner2.jpg","banner3.jpg","banner4.jpg","banner5.jpg","banner6.jpg","banner7.jpg","banner8.jpg","banner9.jpg","banner10.jpg","banner11.jpg","banner12.jpg"];
	var rand = Math.round(Math.random()*imgs.length);
	if ( rand == 12 ) { rand = 11; }; /* array.length is base1  but array[] is base 0 */
	var bannerdiv = document.getElementById('header');
	bannerdiv.style.backgroundImage = "url(/seasonal/current/banners/"+imgs[rand]+")";
}

/** valid in 2013 version **/
function set_bg(bg) {
	var global_background = bg;
}
function new_bg(bg) {
	var content_bg_div = document.getElementById('content_bg');
	content_bg_div.style.backgroundImage = "url(/images/backgrounds/"+bg+")";
}
/*
function display_recurring(recur) { 
	if (recur) {
		document.getElementById('rday').style.display 	= '';
		document.getElementById('odate').style.display 	= 'none';
	} else {
		document.getElementById('rday').style.display 	= 'none';
		document.getElementById('odate').style.display 	= '';
	}
}
*/
function show(div_name) {
	var toHide = document.getElementsByName("hideMe");
	for (var i=0; i < toHide.length; i++) {
		if(toHide[i].id == div_name)
			toHide[i].style.display = '';
		else
			toHide[i].style.display = 'none';
	}
	var myMenu = document.getElementById('aboutMenu');
	if (myMenu) {
		myMenu.style.opacity	= 0.5;
		myMenu.style.left		= '-550px';
	}
}

function ice_order_vis(div_name) {
	if($("#"+div_name).css('display') == 'none')
		$("#"+div_name).slideDown();
	else
		$("#"+div_name).slideUp();
}