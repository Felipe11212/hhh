document.getElementById("cta-button").addEventListener("click", function() {
    alert("¡Gracias por tu interés! Pronto te contactaremos.");
});


document.getElementById("contact-form").addEventListener("submit", function(event) {
    event.preventDefault();
    alert("¡Mensaje enviado! Nos pondremos en contacto contigo pronto.");
    this.reset();
});