const form = document.getElementById("loginForm");
const erro = document.getElementById("erro");

form.addEventListener("submit", function(event) {
    event.preventDefault();

    const email = document.getElementById("email").value;
    const senha = document.getElementById("senha").value;

    if (email === "" || senha === "") {
        erro.style.display = "block";
        erro.innerText = "Preencha todos os campos!";
    } 
    else if (email === "admin@gmail.com" && senha === "1234") {
        erro.style.display = "none";

        window.location.href = "dashboard.html";
    } 
    else {
        erro.style.display = "block";
        erro.innerText = "E-mail ou senha incorretos!";
    }
});