function updateDetectiveCost() {
    var cost = Number(document.querySelector(".detective-form [name=cost]").value);
    var hours = Number(document.querySelector(".detective-form [name=hours]").value);
    var detectives = Number(document.querySelector(".detective-form [name=detectives]").value);
    var totalCost = (cost * detectives * hours).toLocaleString('us', {minimumFractionDigits: 0, maximumFractionDigits: 0});
    document.querySelector(".detective-form button").innerHTML = "Hire Detectives ($" + totalCost + ")";
}

updateDetectiveCost();