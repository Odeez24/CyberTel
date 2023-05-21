function viewmdpex() {
    var pass = document.getElementById("existmdp");
    var img = document.getElementById("existeyes");
    if (pass.getAttribute("type") == "password") {
        pass.setAttribute("type", "text");
        img.setAttribute ("src", "../src/openeyes.jpg");
    } else {
        pass.setAttribute("type", "password");
        img.setAttribute ("src", "../src/closeeyes.jpg");
    }
}

function viewmdpnew() {
    var pass = document.getElementById("newmdp");
    var img = document.getElementById("neweyes");
    if (pass.getAttribute("type") == "password") {
        pass.setAttribute("type", "text");
        img.setAttribute ("src", "../src/openeyes.jpg");
    } else {
        pass.setAttribute("type", "password");
        img.setAttribute ("src", "../src/closeeyes.jpg");
    }
}

function viewmdpnew2() {
    var pass = document.getElementById("newmdp2");
    var img = document.getElementById("neweyes2");
    if (pass.getAttribute("type") == "password") {
        pass.setAttribute("type", "text");
        img.setAttribute ("src", "../src/openeyes.jpg");
    } else {
        pass.setAttribute("type", "password");
        img.setAttribute ("src", "../src/closeeyes.jpg");
    }
}