function showLogin(){
    document.getElementById("loginForm").style.display="block";
    document.getElementById("registerForm").style.display="none";
}

function showRegister(){
    document.getElementById("loginForm").style.display="none";
    document.getElementById("registerForm").style.display="block";
}

document.getElementById("registerForm").addEventListener("submit", async function(e){
    e.preventDefault();

    const formData = new FormData();
    formData.append("name", document.getElementById("regName").value);
    formData.append("email", document.getElementById("regEmail").value);
    formData.append("password", document.getElementById("regPassword").value);

    const res = await fetch("backend/register.php", {
        method:"POST",
        body:formData
    });

    const data = await res.text();
    document.getElementById("message").innerText=data;
});

document.getElementById("loginForm").addEventListener("submit", async function(e){
    e.preventDefault();

    const formData = new FormData();
    formData.append("email", document.getElementById("loginEmail").value);
    formData.append("password", document.getElementById("loginPassword").value);

    const res = await fetch("backend/login.php", {
        method:"POST",
        body:formData
    });

    const data = await res.text();

    if(data==="success"){
        window.location="dashboard.php";
    }else{
        document.getElementById("message").innerText=data;
    }
});