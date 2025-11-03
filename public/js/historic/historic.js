async function startApp(){
    const data = await getHistoricApi();
    console.log('data: ', data);
}

document.addEventListener('DOMContentLoaded', function() {
    startApp();
});