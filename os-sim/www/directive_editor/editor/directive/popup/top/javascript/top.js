
function isAll() {

	var chk_o = document.getElementsByName("chk");

	for (i = 0; i < chk_o.length; i++) {

		if (chk_o[i].checked == "") return false;
	}

	return true;
}


function isNone() {

	var chks = document.getElementsByName("chk");

	for (i = 0; i < chks.length; i++) {

		if (chks[i].checked != "") return false;
	}

	return true;
}

function isMod() {

	return document.getElementById("is_mod").value == "true";
}

function onClickAll() {

	var checked = "";

	if (isAll()) checked = "";
	else checked = "checked";

	var chk_o = document.getElementsByName("chk");

	for (i = 0; i < chk_o.length; i++) {

		chk_o[i].checked = checked;
	}

	var is_mod = document.getElementById("is_mod");
	if (is_mod) is_mod.value = "true";

	isMod();
}


function onClickInv() {

	var chk_o = document.getElementsByName("chk");

	for (i = 0; i < chk_o.length; i++) {

		if (chk_o[i].checked == "") chk_o[i].checked = "checked";
		else chk_o[i].checked = "";
	}

	var is_mod = document.getElementById("is_mod");
	if (is_mod) is_mod.value = "true";

	isMod();
}
