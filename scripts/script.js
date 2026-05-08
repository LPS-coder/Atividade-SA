 const form = document.getElementById("loginForm");
    const erro = document.getElementById("erro");

    form.addEventListener("submit", function(event){
      event.preventDefault();

      const email = document.getElementById("email").value;
      const senha = document.getElementById("senha").value;

      if(email === "" || senha === ""){
        erro.style.display = "block";
      } else {
        erro.style.display = "none";

        alert("Login realizado com sucesso!");
    
      }
    });