function toggleBounty(button, id) {
    var bounties = document.querySelectorAll("[data-id='" + id + "']");

    bounties.forEach(function(bounty) {
        console.log(bounty, bounty.style);
        if (bounty.style.display == "none") {
            bounty.style.display = "table-row";
            button.innerHTML = "Close"
        } else {
            bounty.style.display = "none";
            button.innerHTML = "View"
        }
    });
}