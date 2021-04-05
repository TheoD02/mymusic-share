const AP = document.querySelector('#AP');
if (AP !== null) {
    const audio = new Audio();
    audio.volume = 0.5;

    let playBtnInLists = document.querySelectorAll(' ');
    const playBtn               = AP.querySelector('#play-btn'),
          coverImage            = AP.querySelector('#cover'),
          songTitle             = AP.querySelector('#song-data-title'),
          songArtists           = AP.querySelector('#song-data-artists'),
          progressContainer     = AP.querySelector('#progress-container'),
          progressTime          = progressContainer.querySelector('#progress-time'),
          progressBarsContainer = progressContainer.querySelector('#progress-bars-container'),
          progressBarPlayed     = progressContainer.querySelector('#progress-bar'),
          progressBarSeek       = progressContainer.querySelector('#progress-buffer'),
          volumeBtn             = AP.querySelector('#volumeIcon'),
          volumeSlide           = AP.querySelector('#playerVolume');
    let containerInformations = AP.querySelector('#container-informations');
    let lastHash = null;
    let isListened = false;
    let currentBtnClicked = null;
    setPlayerSrc(playBtnInLists[0]);

    function setPlayerSrc(btnClicked)
    {
        let hash = btnClicked.getAttribute('data-player-path');
        if (lastHash === null || hash !== lastHash) {
            isListened = false;
            lastHash = hash;
            resetAllIconToPlay();
            currentBtnClicked = btnClicked;
            let currentDate = Date.now() - 1613951000000;
            audio.src = '/listen/' + hash + '/' + currentDate;
            songTitle.textContent = btnClicked.parentNode.parentNode.querySelector('.track-title').textContent;
            songArtists.textContent = btnClicked.parentNode.parentNode.querySelector('.track-artists').textContent;
            resizeContentAndPlayer();
        }
    }

    function listenBtnMusicCategory()
    {
        playBtnInLists = document.querySelectorAll('i[data-player-path]');
        /** Écouter les clicks sur les boutons de la liste de musique */
        playBtnInLists.forEach((btn) =>
        {
            if (lastHash === btn.getAttribute('data-player-path')) {
                if (audio.paused) {
                    btn.classList.replace('mdi-pause-circle-outline', 'mdi-play-circle-outline');

                }
                else {
                    btn.classList.replace('mdi-play-circle-outline', 'mdi-pause-circle-outline');
                }
            }
            btn.addEventListener('click', (e) =>
            {
                setPlayerSrc(e.currentTarget);
                playPauseAudio();
            });
        });
    }

    listenBtnMusicCategory();


    /** Écouter les clicks sur le play/pause du player */
    playBtn.addEventListener('click', (e) =>
    {
        playPauseAudio();
    });

    /**
     * Met en pause ou reprend la lecture selon le status actuel
     */
    function playPauseAudio()
    {
        if (audio.paused) {
            audio.play();
        }
        else {
            audio.pause();
        }
        changeIconBtn();
    }

    /**
     * Remet a zéro tous les icons dans la liste des musique
     */
    function resetAllIconToPlay()
    {
        playBtnInLists.forEach((btn) =>
        {
            btn.classList.add('mdi-play-circle-outline');
            btn.classList.remove('mdi-pause-circle-outline');
        });
    }

    /**
     * Change l'icône dans le player et le dernier cliquer dans la liste par l'icône play ou pause selon le status du player
     */
    function changeIconBtn()
    {
        if (audio.paused) {
            playBtn.classList.add('mdi-play-circle-outline');
            playBtn.classList.remove('mdi-pause-circle-outline');
            if (currentBtnClicked !== null) {
                currentBtnClicked.classList.add('mdi-play-circle-outline');
                currentBtnClicked.classList.remove('mdi-pause-circle-outline');
            }
        }
        else {
            playBtn.classList.remove('mdi-play-circle-outline');
            playBtn.classList.add('mdi-pause-circle-outline');
            if (currentBtnClicked !== null) {
                currentBtnClicked.classList.remove('mdi-play-circle-outline');
                currentBtnClicked.classList.add('mdi-pause-circle-outline');
            }
        }
    }

    /** Mise à jour du temps (avancement de la musique sur le temps total) */
    audio.addEventListener('timeupdate', updateTime, false);
    let alreadyListened = [];

    function updateTime()
    {
        console.log('update');
        // Calcul du temps déjà jouer
        let seconds_played = parseInt(audio.currentTime % 60);
        let minutes_played = parseInt((audio.currentTime / 60) % 60);
        // Calcul du temps total de la musique
        let total_seconds = parseInt(audio.duration % 60);
        let total_minutes = parseInt((audio.duration / 60) % 60);
        if (isNaN(total_minutes) || isNaN(total_seconds)) {
            progressBarSeek.style.width = 0 + '%';
            progressBarPlayed.style.width = 0 + '%';
            progressTime.textContent = 'Chargement ...';
        }
        else {
            let correct_time_played = zeroWarn(seconds_played, minutes_played);
            let correct_total_time = zeroWarn(total_seconds, total_minutes);
            let final_time = correct_time_played[1] + ':' + correct_time_played[0] + '/' + correct_total_time[1] + ':' + correct_total_time[0];
            // Calcul & Update Progress Bar
            let pourcentage = Math.floor((audio.currentTime / audio.duration) * 100);
            progressTime.textContent = final_time + ' - ' + pourcentage.toFixed(0) + ' %';
            progressBarPlayed.style.width = pourcentage + '%';

            /** Compter les nombre d'écoutes */
            if (pourcentage === 50 && isListened === false) {
                isListened = true;
                let currentHash = currentBtnClicked.getAttribute('data-player-path');
                if (!alreadyListened.includes(currentHash)) {
                    let xhr = new XMLHttpRequest();
                    xhr.open('POST', '/ajax/track/add-listen/' + currentHash, true);
                    xhr.onreadystatechange = function ()
                    {
                        if (this.readyState === 4 && this.status === 200) {
                            console.log(xhr.response);
                        }
                    };
                    xhr.send();
                }
                alreadyListened.push(currentHash);
            }
        }
    }

    audio.addEventListener('progress', function ()
    {
        try {
            let loadedPourcentage = Math.floor((audio.buffered.end(0) / audio.duration) * 100);
            progressBarSeek.style.width = loadedPourcentage + '%';
        }
        catch (e) {}
    });

    // Rajoute un zéro devant si le chiffre et inférieur à 10 (Retour de valeur [secondes | minutes])
    function zeroWarn(seconds, minutes)
    {
        return [(seconds >= 10) ? seconds : '0' + seconds, (minutes >= 10) ? minutes : '0' + minutes];
    }

    /** Changer la position dans la musique avec un clique sur la progress bar */
    progressTime.addEventListener('click', function (e)
    {
        progressBarPlayed.style.width = e.offsetX + 'px';
        let pct = Math.floor((e.offsetX / progressTime.offsetWidth) * 100);
        sliderAvancement(pct);
        changeIconBtn();
    }, false);

    // Changer l'avancement dans la musique
    function sliderAvancement(pourcent)
    {
        let seconds = audio.duration * (pourcent / 100);
        audio.currentTime = seconds;
        if (audio.paused) {
            audio.play();
        }
    }

    volumeSlide.addEventListener('click', setVolume);

    volumeSlide.addEventListener('mouseup', (e) =>
    {
        volumeSlide.removeEventListener('mousemove', setVolume);
    });

    volumeSlide.addEventListener('mousedown', (e) =>
    {
        volumeSlide.addEventListener('mousemove', setVolume);
    });

    window.addEventListener('resize', setVolumeOnDevice);
    let lastVolume = 0.5;

    function setVolumeOnDevice()
    {
        if (/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)) {
            // true for mobile device
            audio.volume = 1;
            volumeSlide.value = 100;
            volumeBtn.style.display = 'none';
            volumeSlide.style.display = 'none';
            containerInformations.classList.replace('col', 'col-10');
        }
        else {
            containerInformations.classList.replace('col-10', 'col');
            volumeBtn.style.display = 'initial';
            volumeSlide.style.display = 'initial';
        }
    }

    setVolumeOnDevice();

    volumeBtn.addEventListener('click', () =>
    {
        if (audio.volume === 0) {
            audio.volume = lastVolume;
            volumeSlide.value = lastVolume * 100;
        }
        else {
            lastVolume = audio.volume;
            audio.volume = 0;
            volumeSlide.value = 0;
        }
        audioIconUpdate();
    });

    function setVolume(e)
    {
        lastVolume = audio.volume;
        audio.volume = e.currentTarget.value / 100;
        audioIconUpdate();
    }

    function audioIconUpdate()
    {
        console.log('set ' + audio.volume);
        if (audio.volume === 0) {
            volumeIconHandler('mdi-volume-mute');
        }
        else if (audio.volume <= 0.4) {
            volumeIconHandler('mdi-volume-low');
        }
        else if (audio.volume <= 0.8) {
            volumeIconHandler('mdi-volume-medium');
        }
        else {
            volumeIconHandler('mdi-volume-high');
        }
    }

    function volumeIconHandler(replaceClass)
    {
        console.log(replaceClass);
        volumeBtn.classList.replace('mdi-volume-mute', replaceClass);
        volumeBtn.classList.replace('mdi-volume-low', replaceClass);
        volumeBtn.classList.replace('mdi-volume-medium', replaceClass);
        volumeBtn.classList.replace('mdi-volume-high', replaceClass);
    }
}