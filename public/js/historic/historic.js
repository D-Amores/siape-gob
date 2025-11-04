async function startApp(){
    const data = await getHistoricApi();
    if (data) showLoadingAnimation("loading-spinner", "table-container");
    loadHistoricTable(data);
    if (data) hideLoadingAnimation("loading-spinner", "table-container");
}

document.addEventListener('DOMContentLoaded', function() {
    startApp();
});