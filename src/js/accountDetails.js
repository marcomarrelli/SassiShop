document.querySelector("input[value='Modifica']").addEventListener("click", function(){
    let data = document.querySelectorAll("input[type='text']");
    data.forEach(d => {
        d.disabled = false;
    });
    document.querySelectorAll("input[name='save'], input[name='cancel']").forEach(b => {
        b.classList.remove("d-none");
    });
    document.querySelector("input[name='edit']").disabled = true;
});

document.querySelector("input[value='Cancella']").addEventListener("click", function(){
    let data = document.querySelectorAll("input[type='text']");
    data.forEach(d => {
        d.disabled = true;
    });
    let button = document.querySelectorAll("input[name='save'], input[name='cancel']");
    console.log(button);
    button.forEach(b => {
        b.classList.add("d-none");
    });
    document.querySelector("input[name='edit']").disabled = false;
});

