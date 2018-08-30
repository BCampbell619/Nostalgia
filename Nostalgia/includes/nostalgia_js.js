/*jslint browser: true, devel: true */

var elReset = document.getElementById("reset");
var btnReset = document.getElementById("resetbtn");
var msgReset = document.getElementById("resetmsg");
    
function showReset() {

    btnReset.innerHTML = "";
    msgReset.innerHTML = "";
    elReset.style.width = "100%";
    elReset.style.height = "100%";
    elReset.style.display = "block";
    
}
    
function hideReset() {

    btnReset.innerHTML = "<button type=\"button\" class=\"mytblbtn\" onclick=\"showReset()\">Change Password</button>";
    
}

var hero = document.getElementsByClassName("hero"), heroHead = hero[0].firstChild.nextSibling;


if (window.innerWidth <= 800) {

    heroHead.style.fontSize = "60px";

} else {

    heroHead.style.fontSize = "96px";

}