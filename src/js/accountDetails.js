document.querySelector("input[value='Modifica']").addEventListener("click", function(){
    let data = document.querySelectorAll("input[type='text']");
    data.forEach(d => {
        d.disabled = false;
    });
    let button = document.querySelectorAll("input[class='d-none']");
    console.log(button);
    button.forEach(b => {
        b.classList.remove("d-none");
    })
});
