document.querySelector("input[value='Modifica']").addEventListener("click", function(){
    let data = document.querySelectorAll("input[type='text']");
    console.log(data);
    data.forEach(d => {
        d.disabled = false;
    })
});