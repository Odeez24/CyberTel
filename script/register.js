

function valideMDP(mdp){
    var Reg = new RegExp(/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,}$/);
    return Reg.test(mdp);
}

function valideNom (nom) {
    var Reg = new RegExp (/^[A-Za-z^\s][A-Za-z\s]{1,14}[A-Za-z^\s]/);
    return Reg.test(nom);
}

function valideregister() {     
    var nom = document.forms["register"]["nom"];
    var prenom = document.forms["register"] ["prenom"];              
    var email = document.forms["register"]["email"];    
    var phone = document.forms["register"]["tel"];  
    var password = document.forms["register"]["mdp"];    
    alert("test1");
    if (nom.value == "" || !valideNom (nom.value)) {
        $("nom").addClass("err");
        $(document.getElementById(errnom)).hidden = false;
        return false; 
    } else {
        $("nom").removeClass("err");
        $(document.getElementById(errnom)).hidden = true;
    }
    if (prenom.value == "" || !valideNom (prenom.value)) { 
        document.getElementById
        alert("Mettez votre nom."); 
        prenom.focus(); 
        return false; 
    }    
    if (address.value == "") { 
        alert("Mettez votre adresse."); 
        address.focus(); 
        return false; 
    }        
    if (email.value == "") { 
        alert("Mettez une adresse email valide."); 
        email.focus(); 
        return false; 
    }       
    if (phone.value == "") { 
        alert("Mettez votre numéro de téléphone."); 
        phone.focus(); 
        return false; 
    }    
    if (password.value == "" || !valideMDP(password.value)) { 
        alert("Saisissez votre mot de passe"); 
        password.focus(); 
        return false; 
    }
    alert("test");   
    return true; 
}