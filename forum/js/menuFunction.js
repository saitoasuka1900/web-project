function openPostMenu() {
    document.getElementById("PostMenu").style.display = "block";
    document.getElementById("myOverlay").style.display = "block";
}

function closePostMenu() {
    document.getElementById("PostMenu").style.display = "none";
    document.getElementById("myOverlay").style.display = "none";
}

function openManageMenu() {
    document.getElementById("ManageMenu").style.display = "block";
    document.getElementById("myOverlay").style.display = "block";
}

function closeManageMenu() {
    document.getElementById("ManageMenu").style.display = "none";
    document.getElementById("myOverlay").style.display = "none";
}

function turn_to_login() {
    window.location.href = "index.html";
}
var nameManege = new Array("passed-post","my-comment","checked-post","submit-post","post-check","post-manage");
var namePost = new Array('learn', 'sport', 'play', 'food', 'life', 'other');
function close_all() {
    for(i = 0;i <= 5; i++){
        if(document.getElementById(nameManege[i]) != null)
            document.getElementById(nameManege[i]).style.display = "none";
    }
    for(i = 0;i <= 5; i++){
        if(document.getElementById(namePost[i]) != null)
            document.getElementById(namePost[i]).style.display = "none";
    }
    document.getElementById("PostMenu").style.display = "none";
    document.getElementById("ManageMenu").style.display = "none";
    document.getElementById("myOverlay").style.display = "none";
}
function ManageMenuFunction(id) {
    close_all();
    document.getElementById(nameManege[id]).style.display = "block";
}

function PostMenuFunction(id) {
    close_all();
    document.getElementById(namePost[id]).style.display = "block";
}