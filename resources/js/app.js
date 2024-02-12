import "./bootstrap";
import "~resources/scss/app.scss";
import * as bootstrap from "bootstrap";
import.meta.glob(["../img/**", "../fonts/**"]);

const previewImage = document.getElementById("cover_img");
previewImage.addEventListener("change", (event) => {
  var oFReader = new FileReader();

  oFReader.readAsDataURL(previewImage.files[0]);

  oFReader.onload = function (oFREvent) {
    document.getElementById("uploadPreview").src = oFREvent.target.result;
  };
});


const value1 = document.querySelector(".range-value1");
const input1 = document.querySelector(".my-form-range1");
value1.textContent = input1.value;
input1.addEventListener("input", (event) => {
  value1.textContent = event.target.value;
});

const value2 = document.querySelector(".range-value2");
const input2 = document.querySelector(".my-form-range2");
value2.textContent = input2.value;
input2.addEventListener("input", (event) => {
  value2.textContent = event.target.value;
});

const value3 = document.querySelector(".range-value3");
const input3 = document.querySelector(".my-form-range3");
value3.textContent = input3.value;
input3.addEventListener("input", (event) => {
  value3.textContent = event.target.value;
});
