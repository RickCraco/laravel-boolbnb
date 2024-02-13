import "./bootstrap";
import "~resources/scss/app.scss";
import * as bootstrap from "bootstrap";
import.meta.glob(["../img/**", "../fonts/**"]);

const buttons = document.querySelectorAll('.delete-button');
// console.log(buttons);
buttons.forEach((button) => {
    button.addEventListener('click', (event) => {
        // impedisco che il form venga inviato
        event.preventDefault();

        // prendo il titolo dell'item dal bottone
        const dataTitle = button.getAttribute('data-item-title');

        // prendo l'elemento con la modale
        const modal = document.getElementById('deleteModal');

        // creo nuova modale di bootstrap
        const bootstrapModal = new bootstrap.Modal(modal);

        // mostro la modale usando show()
        bootstrapModal.show();

        // prendo l'elemento della modale con il titolo
        const title = modal.querySelector('#modal-item-title');


        // inserisco il titolo nella modale
        title.innerHTML = dataTitle;

        // prendo dalla modale il bottone di conferma del delete
        const buttonDelete = modal.querySelector('button.btn-danger');

        // faccio cancellare l'elemento dall'utente
        buttonDelete.addEventListener('click', (event) => {
            button.parentElement.submit();
        });
    });
});

const previewImage = document.getElementById("cover_img");
previewImage.addEventListener("change", (event) => {
  var oFReader = new FileReader();

  oFReader.readAsDataURL(previewImage.files[0]);

  oFReader.onload = function (oFREvent) {
    document.getElementById("uploadPreview").src = oFREvent.target.result;
  };
});


