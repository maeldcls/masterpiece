var modal = document.querySelector("#modal");
var modalImg = document.querySelector("#modal-div");


function showModal(id) {
    modal.classList.remove('hidden');
    modal.classList.add('fixed');
    
}


function closeModal() {
    modal.classList.add('hidden');
}

window.onclick = function (event) {
    console.log("oui")
    if (event.target === modal) {
        modal.classList.remove('fixed');
        modal.classList.add('hidden');
    }
};