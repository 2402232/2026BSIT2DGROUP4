// Buttons
const reportBtn = document.getElementById("reportBtn");
const modal = document.getElementById("modal");
const closeModal = document.getElementById("closeModal");
const locationText = document.getElementById("locationText");


// Open Modal
function openModal() {
    modal.style.display = "flex";
    getLocation();
}


// Close Modal
closeModal.addEventListener("click", () => {
    modal.style.display = "none";
});


// Report Button
reportBtn.addEventListener("click", (e) => {
    e.preventDefault();
    openModal();
});


// Get Location
function getLocation() {

    if (navigator.geolocation) {

        navigator.geolocation.getCurrentPosition(
            showPosition,
            showError
        );

    } else {

        locationText.innerText = "Geolocation not supported.";
    }

}


function showPosition(position) {

    const lat = position.coords.latitude.toFixed(4);
    const lon = position.coords.longitude.toFixed(4);

    locationText.innerText =
        `Location: ${lat}, ${lon}`;

    // Simulate sending report
    setTimeout(() => {

        locationText.innerText +=
            " | Responders notified ✔";

    }, 2000);

}


function showError() {

    locationText.innerText =
        "Location access denied.";

}

