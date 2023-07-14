function showLoading() {
    var overlay = document.createElement("div");
    overlay.className = "loading-overlay";
    var spinner = document.createElement("div");
    spinner.className = "loading-spinner";
    overlay.appendChild(spinner);
    document.body.appendChild(overlay);
}

function showConfirmation(confirmMessage) {
    if (confirm(confirmMessage)) {
        showLoading();
        return true;
    } else {
        return false;
    }
}
