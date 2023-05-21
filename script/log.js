
function viewmdpreg() {
    var pass = document.getElementById("mdp");
    var img = document.getElementById("regeyes");
    if (pass.getAttribute("type") == "password") {
        pass.setAttribute("type", "text");
        img.setAttribute ("src", "../src/openeyes.jpg");
    } else {
        pass.setAttribute("type", "password");
        img.setAttribute ("src", "../src/closeeyes.jpg");
    }
}

function viewmdp() {
    var pass = document.getElementById("mdp");
    var img = document.getElementById("logeyes");
    if (pass.getAttribute("type") == "password") {
        pass.setAttribute("type", "text");
        img.setAttribute ("src", "../src/openeyes.jpg");
    } else {
        pass.setAttribute("type", "password");
        img.setAttribute ("src", "../src/closeeyes.jpg");
    }
}