
document.getElementById("scrollButton1").addEventListener("click", function() {
    document.getElementById("sistema").scrollIntoView({ behavior: "smooth" });
 });
    document.getElementById("scrollButton2").addEventListener("click", function() {
        document.getElementById("equipo").scrollIntoView({ behavior: "smooth" }); 
    });
        document.getElementById("scrollButton3").addEventListener("click", function() {
        document.getElementById("contacto").scrollIntoView({ behavior: "smooth" }); 
    });

    function redireccionar(red) {
        let urls = {
            facebook: "https://www.facebook.com/",
            whatsapp: "https://wa.me/573104080184",
              instagram: "https://www.instagram.com/",
            gmail: "mailto:correo@gmail.com",
        };
        window.location.href = urls[red];
    }