async function startApp(){
    const data = await getHistoricApi();
    loadHistoricTable(data);
}

document.addEventListener('DOMContentLoaded', function() {
    startApp();
});