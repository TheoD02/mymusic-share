let sweetAlertManager = new SweetAlertManager();
/** Gestion du formulaire d'ajout de music */

/** Load ID3 Tags dynamically */
let musicUploadForm = document.getElementById('musicForm');

if (musicUploadForm !== null) {
    let musicFile       = musicUploadForm.querySelector('#musicFile'),
        tempMusicFile   = musicUploadForm.querySelector('#tempMusicFile'),
        titleInput      = musicUploadForm.querySelector('#title'),
        artistsInput    = musicUploadForm.querySelector('#artists'),
        songBPM         = musicUploadForm.querySelector('#bpm'),
        songBitrate     = musicUploadForm.querySelector('#bitrate'),
        songKey         = musicUploadForm.querySelector('#id_musicKey'),
        songCategory    = musicUploadForm.querySelector('#id_categories'),
        tagify          = null,
        mp3Upload       = musicUploadForm.querySelector('#mp3-upload'),
        mp3Informations = musicUploadForm.querySelector('#mp3-informations');

    if (artistsInput !== null) {
        window.addEventListener('DOMContentLoaded', () =>
        {
            console.log('loaded');
            /** Se servir de la librairie Tagify sur l'input artists pour une saisie plus simple */
            tagify = new Tagify(artistsInput, {
                originalInputValueFormat: valuesArr => valuesArr.map(item => item.value).join(','),
                whitelist               : ['aaa', 'aaab', 'aaabb', 'aaabc', 'aaabd', 'aaabe', 'aaac', 'aaacc'],
                dropdown                : {
                    classname     : 'color-blue',
                    enabled       : 1,              // show the dropdown immediately on focus
                    maxItems      : 5,
                    position      : 'text',         // place the dropdown near the typed text
                    closeOnSelect : false,          // keep the dropdown open after selecting a suggestion
                    highlightFirst: true,
                },
            });
        });
    }

    if (musicFile !== null || tempMusicFile !== null) {

        musicFile.addEventListener('change', function selectedFileChanged()
        {
            let file = this.files.item(0);
            /** Si le fichier n'est pas du mime type (audio/mpeg) on indique que le type de fichier n'est pas compatible. */
            if (file.type !== 'audio/mpeg') {
                sweetAlertManager.showSimpleAlert('Fichier audio non compatible', 'Vérifier que votre fichier audio soit bien au format MP3 (mpeg) !', 'error', true, 'Ok', false, '');
                /** En cas d'erreur on enlève les tags et remise à zéro de tous les champs */
                if (tagify !== null) {
                    //tagify.destroy();
                }
                songBPM.value = titleInput.value = artistsInput.value = '';
                songBitrate.value = songKey.value = songCategory.value = 'default';
                return;
            }
            let swalWaitLoadTags = Swal.fire({
                title            : 'Chargement des tags depuis votre fichier',
                text             : 'Veuillez patientez....',
                icon             : 'warning',
                allowOutsideClick: false,
                showConfirmButton: false,
                showDenyButton   : false,
                showCancelButton : false,
            });
            mp3Informations.classList.remove('d-none');
            jsmediatags.read(file, {
                onSuccess: function (tag)
                {
                    musicFile.classList.remove('is-invalid');
                    /** Récupère les tags qui nous intéresse */
                    let songTagTitle = typeof tag.tags.title === 'string' ? tag.tags.title : '';
                    let songTagArtists = typeof tag.tags.artist === 'string' ? tag.tags.artist : '';
                    let songTagBPM = typeof tag.tags.TBPM === 'object' && typeof parseInt(tag.tags.TBPM.data) === 'number' ? parseInt(tag.tags.TBPM.data) : '';
                    let songTagKey = typeof tag.tags.TKEY === 'object' ? tag.tags.TKEY.data : '';

                    /** Défini les valeurs défini dans les tags ID3 ou une chaine vide si pas de valeur */
                    titleInput.value = songTagTitle;
                    artistsInput.value = sanitizeArtistsName(songTagArtists);
                    songBPM.value = songTagBPM;
                    songKey.selectedIndex = convertHarmonicKey(songTagKey);

                    if (tagify !== null) {
                        tagify.destroy();
                    }

                    /** Se servir de la librairie Tagify sur l'input artists pour une saisie plus simple */
                    tagify = new Tagify(artistsInput, {
                        originalInputValueFormat: valuesArr => valuesArr.map(item => item.value).join(','),
                        whitelist               : ['aaa', 'aaab', 'aaabb', 'aaabc', 'aaabd', 'aaabe', 'aaac', 'aaacc'],
                        dropdown                : {
                            classname     : 'color-blue',
                            enabled       : 1,              // show the dropdown immediately on focus
                            maxItems      : 5,
                            position      : 'text',         // place the dropdown near the typed text
                            closeOnSelect : false,          // keep the dropdown open after selecting a suggestion
                            highlightFirst: true,
                        },
                    });

                    /** Afficher un message dans le cas ou il manque des tags ou si il n'y en a pas */
                    if (songTagTitle !== '' || songTagArtists !== '' || songTagBPM !== '' || songTagKey !== '') {
                        /** Indiquer a l'utilisateur que des informations on pu être charger depuis le fichier MP3 et de les vérifié */
                        sweetAlertManager.showToast('Des tags on pu être récupérer depuis le fichier audio,<br>merci de les vérifié avant l\'envoi.',
                            'center', 'info', true, 5000);
                    }
                    else if (songTagTitle === '' || songTagArtists === '' || songTagBPM === '' || songTagKey === '') {
                        /** Indiquer a l'utilisateur qu'aucune informations a pu être récupérer depuis les tags */
                        sweetAlertManager.showToast('Aucun tags n\'a été trouvée dans le fichier audio, veillez à remplir les champs correctement',
                            'center', 'warning', true, 5000);
                    }
                },
                onError  : function (error)
                {
                    /** En cas d'erreur on enlève les tags et remise à zéro de tous les champs */
                    if (tagify !== null) {
                        tagify.destroy();
                    }
                    titleInput.value = artistsInput.value;
                    songBitrate = songBPM.value = songKey.value = 'default';
                    sweetAlertManager.showToast('Impossible de récupérer des données depuis le fichier audio.<br>Veuillez remplir le formulaire manuellement',
                        'center', 'warning', true, 5000);
                },
            });
        });

        /** Supprime les éléments (feat., ft. etc...) pour utiliser Tagify. */
        function sanitizeArtistsName(artists)
        {
            if (artists !== undefined) {
                artists.trim();
                let pattern = /( ft.?| feat.?| & | vs.? | x | , | and )/gi;
                let matches = artists.split(pattern);
                matches.forEach((artist, id) =>
                {
                    if (artist.match(pattern)) {
                        matches.splice(id, 1);
                    }
                });
                matches.map(artistName => ucWord(artistName.trim()));
                return matches.join(',');
            }
            return '';
        }

        /** Remplace chaque première lettre de chaque mot en majuscule */
        function ucWord(value)
        {
            return value.replace(/(^\w{1})|(\s+\w{1})/g, letter => letter.toUpperCase());
        }

        /** Converti la clé harmonique au format numérique */
        function convertHarmonicKey(value)
        {
            switch (value.toLowerCase().trim()) {
                case 'g#m':
                    return 1;
                case 'b':
                    return 2;
                case 'ebm':
                    return 3;
                case 'gb':
                    return 4;
                case 'bbm':
                    return 5;
                case 'db':
                    return 6;
                case 'fm':
                    return 7;
                case 'ab':
                    return 8;
                case 'cm':
                    return 9;
                case 'eb':
                    return 10;
                case 'gm':
                    return 11;
                case 'bb':
                    return 12;
                case 'dm':
                    return 13;
                case 'f':
                    return 14;
                case 'am':
                    return 15;
                case 'c':
                    return 16;
                case 'em':
                    return 17;
                case 'g':
                    return 18;
                case 'bm':
                    return 19;
                case 'd':
                    return 20;
                case 'f#m':
                    return 21;
                case 'a':
                    return 22;
                case 'c#m':
                    return 23;
                case 'e':
                    return 24;
                default:
                    return 0;
            }
        }
    }
}

/** Gère le statut du focus de la fenêtre (Stoper le timer des alert si on sort de l'onget/navigateur */
let isFocused = true;
window.addEventListener('focus', () =>
{
    this.isFocused = true;
    Swal.resumeTimer();
});
window.addEventListener('blur', () =>
{
    this.isFocused = false;
    Swal.stopTimer();
});

/** Gestion des alerts */

const alertsContainer = document.querySelector('#alerts');
const alerts = alertsContainer.querySelectorAll('ul li');
//console.log(alertsContainer);
let alertsQueue = [];

alerts.forEach(alertElement =>
{
    let alertContent = alertElement.innerHTML;
    const parentElement = alertElement.parentNode;
    let type = parentElement.className.replace('my-0', '').trim();
    alertsQueue.push({
        html             : alertContent,
        icon             : type,
        position         : 'center',
        timer            : 3000,
        timerProgressBar : true,
        allowOutsideClick: false,
        background       : '#3B4654',
        didOpen          : (toast) =>
        {
            toast.addEventListener('mouseenter', Swal.stopTimer);
            toast.addEventListener('mouseleave', Swal.resumeTimer);
        },
    });
});
Swal.queue(alertsQueue);
/** Si l'onglet n'est pas focus ou la page n'est pas visible */
if (!isFocused || document.visibilityState) {Swal.stopTimer();}