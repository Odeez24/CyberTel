function viewmdp() {
    var pass = document.getElementById("existmdp");
    var passn = document.getElementById("newmdp");
    var passn2 = document.getElementById("newmdp2");
    var img = document.getElementById("eyes");
    if (pass.getAttribute("type") == "password") {
        pass.setAttribute("type", "text");
        passn.setAttribute("type", "text");
        passn2.setAttribute("type", "text");
        img.setAttribute ("src", "../src/openeyes.jpg");
    } else {
        pass.setAttribute("type", "password");
        passn.setAttribute("type", "password");
        passn2.setAttribute("type", "password");
        img.setAttribute ("src", "../src/closeeyes.jpg");
    }
}