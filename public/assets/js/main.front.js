let player = document.querySelector('#player-container');
let contentContainer = document.querySelector('#content-container');
let navBar = document.querySelector('#navbar');
window.addEventListener('resize', resizeContentAndPlayer);

function resizeContentAndPlayer()
{
    if (player !== null) {
        contentContainer.style.height = (window.innerHeight - (player.offsetHeight + navBar.offsetHeight)) + 'px';
    }
}

resizeContentAndPlayer();

function getRandomNumber()
{
    return Math.round((Math.random() * 100000) * 100000);
}

/** addTrackToDownloadList */
let addToPlaylist = document.querySelectorAll('.addToPlaylist');
if (addToPlaylist !== null) {
    addToPlaylist.forEach(btn =>
    {
        btn.addEventListener('click', addToDownloadList);
    });
}

function addToDownloadList()
{
    console.log(this.getAttribute('data-id'));
}

/** Bouton interdit de télécharger car pas connecté */
let forbidDownloadNotConnectedBtn = document.querySelectorAll('.forbidDownloadNotConnected');

forbidDownloadNotConnectedBtn.forEach(btn =>
{
    btn.addEventListener('click', () =>
    {
        Swal.fire({
            title            : 'Connexion requise',
            text             : 'Une connexion est requise pour télécharger les fichier audio.',
            icon             : 'warning',
            showCancelButton : true,
            showConfirmButton: true,
            showDenyButton   : true,
            cancelButtonText : 'M\'inscrire',
            denyButtonText   : 'Annuler',
            confirmButtonText: 'Me connecter',
        }).then((result) =>
        {
            console.log(result);
            if (result.isConfirmed) {
                window.location.replace('/login');
            }
            else if (result.isDismissed) {
                window.location.replace('/register');
            }
        });
    });
});

/** Bouton interdit de télécharger car pas d'abonnement */
let forbidDownloadBtn = document.querySelectorAll('.forbidDownload');

forbidDownloadBtn.forEach(btn =>
{
    btn.addEventListener('click', () =>
    {
        Swal.fire({
            title            : 'Abonnement requis',
            text             : 'Veuillez vous abonnez à une formule pour pouvoir télécharger un titre.',
            icon             : 'warning',
            showCancelButton : true,
            showConfirmButton: true,
            cancelButtonText : 'Annuler',
            confirmButtonText: 'Voir les abonnements',
        }).then((result) =>
        {
            console.log(result);
            if (result.isConfirmed) {
                window.location.replace('/#container-offers');
            }
        });
    });
});

/** Paginate with ajax */
let categoryMusicContainer = document.querySelector('#category-music-container');
let categoryImgLoaderContainer = document.querySelector('#category-img-loader-container');
let paginationContainer = document.querySelector('#category-pagination');
if (paginationContainer !== null) {
    let paginationBtnList = paginationContainer.querySelectorAll('.page-link');
    let musicTable = categoryMusicContainer.querySelector('#music-table');

    function listenPaginationBtn()
    {
        paginationContainer = document.querySelector('#category-pagination');

        paginationBtnList = paginationContainer.querySelectorAll('.page-link');
        musicTable = categoryMusicContainer.querySelector('#music-table');
        paginationBtnList.forEach(btn =>
        {
            btn.addEventListener('click', requestCategory);
        });
    }

    listenPaginationBtn();
}

function requestCategory()
{
    let url = this.getAttribute('data-link');
    let pageNumber = url.split('/').pop();
    console.log(pageNumber);
    categoryMusicContainer.innerHTML = '';
    categoryImgLoaderContainer.classList.replace('d-none', 'd-block');


    let xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function ()
    {
        if (this.readyState === 4 && this.status === 200) {
            // Typical action to be performed when the document is ready:
            categoryImgLoaderContainer.classList.replace('d-block', 'd-none');
            categoryMusicContainer.innerHTML = xhttp.responseText;
            window.history.pushState({'html': xhttp.responseText, 'pageTitle': ''}, '', pageNumber);
            listenBtnMusicCategory();
            listenPaginationBtn();
        }
    };
    xhttp.open('GET', url, true);
    xhttp.send();
}