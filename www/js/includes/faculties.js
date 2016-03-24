function activateFaculties(el, faculties) {
	if (el.className.match('red')) {
    	parameters = {activate_faculties:faculties, method: 'get'};
	} else {
		parameters = {deactivate_faculties:faculties, method: 'get'};
	}
    var url    = 'administrator.php?ctg=faculties';
    ajaxRequest(el, url, parameters, onActivateFaculties);
}
function onActivateFaculties(el, response) {
    if (response == 0) {
    	setImageSrc(el, 16, "trafficlight_red.png");
        el.writeAttribute({alt:activate, title:activate});
    } else if (response == 1) {
    	setImageSrc(el, 16, "trafficlight_green.png");
        el.writeAttribute({alt:deactivate, title:deactivate});
    }
}

function deleteFaculties(el, faculties) {
	parameters = {delete_faculties:faculties, method: 'get'};
	 var url    = 'administrator.php?ctg=faculties';
	ajaxRequest(el, url, parameters, onDeleteFaculties);	
}
function onDeleteFaculties(el, response) {
	new Effect.Fade(el.up().up());
    /*try {
        eF_js_changePage(0, 0);
    } catch (e) {alert(e);}*/
}