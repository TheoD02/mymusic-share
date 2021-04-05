import {AudioPlayerManager} from './player/player.js';

const playerManager = new AudioPlayerManager();


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

/** Paginate with ajax */
let categoryMusicContainer = document.querySelector('#category-music-container');
let categoryImgLoaderContainer = document.querySelector('#category-img-loader-container');

function listenPaginationBtn()
{
    let paginationContainer = document.querySelector('#category-pagination');
    if (paginationContainer !== null) {
        let paginationBtnList = paginationContainer.querySelectorAll('.page-link');
        let musicTable = categoryMusicContainer.querySelector('#music-table');
        paginationContainer = document.querySelector('#category-pagination');

        paginationBtnList = paginationContainer.querySelectorAll('.page-link');
        musicTable = categoryMusicContainer.querySelector('#music-table');
        paginationBtnList.forEach(btn =>
        {
            btn.addEventListener('click', requestCategory);
        });
    }
}

listenPaginationBtn();

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
            playerManager.updatePlayBtnInCategoryListAndSetListener();
            listenPaginationBtn();
            listenAllPlaylistAddBtn();
        }
    };
    xhttp.open('GET', url, true);
    xhttp.send();
}

/** DownloadList */
let addDownloadListBtn = document.querySelector('[name="addDownloadList"]');

if (addDownloadListBtn !== null) {
    addDownloadListBtn.addEventListener('click', () =>
    {
        Swal.fire({
            title              : 'Ajouter une liste de téléchargement',
            input              : 'text',
            inputAttributes    : {
                autocapitalize: 'off',
            },
            showCancelButton   : true,
            confirmButtonText  : 'Créer la nouvelle liste de téléchargement',
            cancelButtonText   : 'Annuler',
            showLoaderOnConfirm: true,
            preConfirm         : (categoryName) =>
            {
                return fetch(`/ajax/add-download-list`, {
                    method : 'POST',
                    headers: {'Content-Type': 'application/x-www-form-urlencoded', 'Accept': 'application/json'},
                    body   : 'categoryName=' + categoryName,
                })
                    .then(response =>
                    {
                        console.log(response);
                        if (!response.ok) {
                            throw new Error(response.statusText);
                        }
                        return response.json();
                    })
                    .catch(error =>
                    {
                        Swal.showValidationMessage(
                            `Request failed: ${error}`,
                        );
                    });
            },
            allowOutsideClick  : () => !Swal.isLoading(),
        }).then((result) =>
        {
            if (result.isConfirmed) {
                Swal.fire({
                    title: result.value.message,
                }).then((swalResult) =>
                {
                    if (swalResult.isConfirmed) {
                        document.location.href = 'http://mymusic-share.local/profile/download-lists/' + result.value.downloadListID;
                    }
                });
            }
        });
    });
}

/** Select Download List */
let downloadsListId = document.querySelector('#downloadsListId');
let downloadListTableContainer = document.querySelector('#download-list-table-container');
let downloadListIdInput = document.querySelector('#downloadListId');

if (downloadsListId !== null) {
    downloadsListId.addEventListener('change', getDownloadList);
}

function getDownloadList()
{
    let listId = (this.options[this.selectedIndex].value);
    let url = '/ajax/get-download-list/' + listId;
    let xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function ()
    {
        if (this.readyState === 4 && this.status === 200) {
            downloadListIdInput.setAttribute('value', listId);
            // Typical action to be performed when the document is ready:
            downloadListTableContainer.innerHTML = xhttp.responseText;
            window.history.pushState({'html': xhttp.responseText, 'pageTitle': ''}, '', listId);
            playerManager.updatePlayBtnInCategoryListAndSetListener();
            listenPaginationBtn();
        }
    };
    xhttp.open('GET', url, true);
    xhttp.send();
}

let downloadListSelector = document.querySelector('#downloadListSelector');

if (downloadListSelector !== null) {
    downloadListSelector.addEventListener('change', (e) =>
    {
        let downloadListId = (e.currentTarget.options[e.currentTarget.selectedIndex].value);
        let url = '/ajax/select-download-list/' + downloadListId;
        let xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function ()
        {
            if (this.readyState === 4 && this.status === 200) {
                let addToPlaylistBtn = document.querySelectorAll('.addToPlaylist');
                addToPlaylistBtn.forEach(btn =>
                {
                    btn.setAttribute('data-playlist', downloadListId);
                });
            }
        };
        xhttp.open('GET', url, true);
        xhttp.send();
    });
}

function listenAllPlaylistAddBtn()
{
    let addToPlaylistBtn = document.querySelectorAll('.addToPlaylist');
    addToPlaylistBtn.forEach(btn =>
    {
        btn.addEventListener('click', (e) =>
        {
            let clickedBtn = e.currentTarget;
            let dataPlaylist = clickedBtn.getAttribute('data-playlist');
            let trackHash = clickedBtn.getAttribute('data-hash');

            if (dataPlaylist === null) {
                (new SweetAlertManager).showToast('Veuillez sélectionner une playlist dans le sélecteur au dessus de liste ou créer en une si vous n\'en n\'avez pas encore créer via votre panel dans "<a href="/profile/download-lists/">Liste de téléchargement</a>" !');
            }
            else {
                let url = '/ajax/add-track-to-download-list/' + dataPlaylist + '/' + trackHash;
                let xhttp = new XMLHttpRequest();
                xhttp.onreadystatechange = function ()
                {
                    if (this.readyState === 4 && this.status === 200) {
                        // Typical action to be performed when the document is ready:
                        let responseMessage = JSON.parse(xhttp.response);
                        (new SweetAlertManager).showToast(responseMessage.message, 'top', responseMessage.isAdded);
                    }
                };
                xhttp.open('GET', url, true);
                xhttp.send();
            }
        });
    });
}

listenAllPlaylistAddBtn();

let selectCategoriesElementNumber = document.querySelector('#categoriesElementSelector');

if (selectCategoriesElementNumber !== null){
    selectCategoriesElementNumber.addEventListener('change', (e) =>
    {
        // TODO si pas de page par défaut revoir la mise en forme url
        e.preventDefault();
        let selectNumberOfElement = selectCategoriesElementNumber.querySelector('#numberOfElementPerPage');
        let url = window.location.href.split('/');
        url.pop();
        url = url.join('/') + '/' + selectNumberOfElement.value;
        window.location.href = url;
    });
}
