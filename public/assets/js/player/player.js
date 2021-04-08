export class AudioPlayerManager
{
    static audio = null;
    static AP = document.querySelector('#AP');
    static lastMusicHash = null;
    static isListened = false;
    static currentPlayBtnClicked = null;
    static playBtnInCategoryList = null;
    static playBtn = null;
    static songTitle = null;
    static songArtists = null;
    static progressContainer = null;
    static progressTime = null;
    static progressBarPlayed = null;
    static progressBarSeek = null;
    static volumeBtn = null;
    static volumeSlide = null;
    static volumeContainer = null;
    static currentMusicPlayedSecondInterval;
    static currentMusicTotalPlayedSecond = 0;
    static countState = false;
    static musicPlayed = [];

    constructor()
    {
        /**
         * Si le lecteur est affichée
         */
        if (AudioPlayerManager.AP !== null) {
            AudioPlayerManager.audio = new Audio();
            this.updatePlayBtnInCategoryListAndSetListener();
            AudioPlayerManager.playBtn = AP.querySelector('#play-btn');
            AudioPlayerManager.songTitle = AP.querySelector('#song-data-title');
            AudioPlayerManager.songArtists = AP.querySelector('#song-data-artists');
            AudioPlayerManager.progressContainer = AP.querySelector('#progress-container');
            AudioPlayerManager.progressTime = AudioPlayerManager.progressContainer.querySelector('#progress-time');
            AudioPlayerManager.progressBarPlayed = AudioPlayerManager.progressContainer.querySelector('#progress-bar');
            AudioPlayerManager.progressBarSeek = AudioPlayerManager.progressContainer.querySelector('#progress-buffer');
            AudioPlayerManager.volumeBtn = AP.querySelector('#volumeIcon');
            AudioPlayerManager.volumeSlide = AP.querySelector('#playerVolume');
            AudioPlayerManager.volumeContainer = AP.querySelector('#container-informations');
            AudioPlayerManager.setAllListener();
            /**
             * Si des musique son détecter
             */
            if (AudioPlayerManager.playBtnInCategoryList.length !== 0) {
                this.setPlayerSource(AudioPlayerManager.playBtnInCategoryList[0]);
            }
        }
    }

    /**
     * Récupère les bouton de la liste de musique dans la page catégorie
     */
    updatePlayBtnInCategoryListAndSetListener()
    {
        /** Récupère les boutons dans la liste de musique*/
        AudioPlayerManager.playBtnInCategoryList = document.querySelectorAll('i[data-player-path]');
        AudioPlayerManager.playBtnInCategoryList.forEach(btn =>
        {
            /** Si le dernier hash est le même qu'un bouton dans la liste alors définir le bouton et si l'audio est en cours on remplace le bouton par un bouton pause */
            if (AudioPlayerManager.lastMusicHash === btn.getAttribute('data-player-path')) {
                AudioPlayerManager.currentPlayBtnClicked = btn;
                if (AudioPlayerManager.isPlaying()) {
                    btn.classList.replace('mdi-play-circle-outline', 'mdi-pause-circle-outline');
                }
            }

            /** Ajout d'un eventListener sur chaque bouton au click */
            btn.addEventListener('click', (e) =>
            {
                this.setPlayerSource(e);
                AudioPlayerManager.audioPlayBtnHandler();
            });
        });
    }


    /**
     * Récupère le token du bouton et défini le lien dans le lecteur audio
     * @param e
     */
    setPlayerSource(e)
    {
        let button = (e.currentTarget ?? e);
        let musicHash = button.getAttribute('data-player-path');
        /** Si le dernier hash est null ou que le dernier hash est différent de celui actuel */
        if (AudioPlayerManager.lastMusicHash === null || AudioPlayerManager.lastMusicHash !== musicHash) {
            /** Défini le dernier Hash utilisée */
            AudioPlayerManager.lastMusicHash = musicHash;
            /** Défini le dernier button cliquer */
            AudioPlayerManager.currentPlayBtnClicked = button;


            /** Défini tout les icons Play/Pause en icon Play */
            AudioPlayerManager.resetAllIcons();
            /** Défini l'url au lecteur */
            AudioPlayerManager.audio.src = AudioPlayerManager.calculateUrl(musicHash);

            /** Récupère l'artiste et le titre de la musique pour l'insérer dans le lecteur */
            AudioPlayerManager.setTitleToPlayer();
            AudioPlayerManager.setArtistToPlayer();

            /** Remise à zéro du temps écouter sur la musique */
            AudioPlayerManager.currentMusicTotalPlayedSecond = 0;
        }
    }

    /**
     * Gère le button play du lecteur
     */
    static playBtnClickHandler()
    {
        AudioPlayerManager.audioPlayBtnHandler();
    }

    /**
     * Si le lecteur joue de la musique mettre le logo play et mettre la musique en pause,
     * Si le lecteur joue pas mettre le logo pause et reprendre/lancer la musique
     */
    static audioPlayBtnHandler()
    {
        /** Mettre en pause le comptage de secondes jouée */
        clearInterval(AudioPlayerManager.currentMusicPlayedSecondInterval);
        /** Si le lecteur est en cours de lecteur mettre en pause */
        if (AudioPlayerManager.isPlaying()) {
            AudioPlayerManager.playBtn.classList.replace('mdi-pause-circle-outline', 'mdi-play-circle-outline');
            AudioPlayerManager.currentPlayBtnClicked.classList.replace('mdi-pause-circle-outline', 'mdi-play-circle-outline');
            AudioPlayerManager.pauseAudio();
        }
        /** Si non reprendre la lecture, et relancer le comptage des secondes de la musique si pas déjà compter comme ecouter */
        else {
            AudioPlayerManager.playBtn.classList.replace('mdi-play-circle-outline', 'mdi-pause-circle-outline');
            AudioPlayerManager.currentPlayBtnClicked.classList.replace('mdi-play-circle-outline', 'mdi-pause-circle-outline');
            AudioPlayerManager.playAudio();
            if (!AudioPlayerManager.musicPlayed.includes(AudioPlayerManager.lastMusicHash)) {
                AudioPlayerManager.countTotalElapsedSeconds();
            }
            else {
                console.log('already played');
            }
        }
    }

    /**
     * Compte le nombre de secondes jouer dans une musique et ajoute un écoute sur la musique lorsque le nombre de secondes écouter atteint la moitié du temps jouables
     */
    static countTotalElapsedSeconds()
    {
        /** Démarre un timer */
        AudioPlayerManager.currentMusicPlayedSecondInterval = setInterval(() =>
        {
            if (!isNaN(AudioPlayerManager.audio.duration)) {
                AudioPlayerManager.currentMusicTotalPlayedSecond++;
                console.log(AudioPlayerManager.currentMusicTotalPlayedSecond);
            }
            /** Si le nombre de secondes jouer atteigne la moitié du nombre total de secondes possible de la musique ajouter une écoute à la musique*/
            if (AudioPlayerManager.currentMusicTotalPlayedSecond === AudioPlayerManager.getHalfOfTotalDuration()) {
                console.log('addListen');
                /** Ajouter la musique comme écouter */
                AudioPlayerManager.musicPlayed.push(AudioPlayerManager.lastMusicHash);
                /** Ajouter une écoute sur la musique actuel en base de données */
                AudioPlayerManager.addListenOnTrack();
                let currentNumberOfPlayed = AudioPlayerManager.currentPlayBtnClicked.parentNode.parentNode.querySelector('.listen-count').textContent;
                currentNumberOfPlayed++;
                AudioPlayerManager.currentPlayBtnClicked.parentNode.parentNode.querySelector('.listen-count').textContent = currentNumberOfPlayed;

                /** Reset timer */
                clearInterval(AudioPlayerManager.currentMusicPlayedSecondInterval);
                AudioPlayerManager.countState = false;
            }
        }, 1000);
    }

    /**
     * Ajoute une écoute sur une musique
     */
    static addListenOnTrack()
    {
        let xmlHttpRequest = new XMLHttpRequest();
        let url = '/ajax/track/add-listen/' + AudioPlayerManager.lastMusicHash;

        let data = {hash: AudioPlayerManager.lastMusicHash};
        data = Object.values(data).join('=');
        xmlHttpRequest.open('POST', url, true);
        xmlHttpRequest.send(data);
    }

    /**
     * Défini tous les icônes en bouton play
     * @returns {number}
     */
    static resetAllIcons()
    {
        AudioPlayerManager.playBtnInCategoryList.forEach(btn =>
        {
            btn.classList.replace('mdi-pause-circle-outline', 'mdi-play-circle-outline');
        });

        AudioPlayerManager.playBtn.classList.replace('mdi-pause-circle-outline', 'mdi-play-circle-outline');
        return 0;
    }

    /**
     * Gestion du temps jouer, total et avancement de progressbar played
     */
    static timeUpdateHandler()
    {
        let played = AudioPlayerManager.getFormattedPlayedTime();
        let totalDuration = AudioPlayerManager.getFormattedTotalDuration();
        if (totalDuration !== null) {
            AudioPlayerManager.progressTime.textContent = played + '/' + totalDuration;
        }
        else {
            AudioPlayerManager.progressBarSeek.style.width = 0 + '%';
            AudioPlayerManager.progressBarPlayed.style.width = 0 + '%';
            AudioPlayerManager.progressTime.textContent = 'Chargement ...';
        }

        AudioPlayerManager.progressBarPlayed.style.width = AudioPlayerManager.calculatePourcentagePlayed() + '%';
    }

    /**
     * Retourne la moitié du temps total jouable
     * @returns {number}
     */
    static getHalfOfTotalDuration()
    {
        return Math.round(AudioPlayerManager.audio.duration / 2);
    }

    /**
     * Calcul le temps jouer en pourcentage
     * @returns {number}
     */
    static calculatePourcentagePlayed()
    {
        return Math.floor((this.audio.currentTime / this.audio.duration) * 100);
    }

    /**
     * Gère le clique et le slide de la barre de volume
     */
    static volumeBarHandlerListener()
    {
        AudioPlayerManager.lastVolume = 0.5;
        AudioPlayerManager.volumeSlide.addEventListener('mouseup', (e) =>
        {
            AudioPlayerManager.setVolume(e);
            AudioPlayerManager.volumeSlide.removeEventListener('mousemove', AudioPlayerManager.setVolume);
        });

        AudioPlayerManager.volumeSlide.addEventListener('mousedown', (e) =>
        {
            AudioPlayerManager.volumeSlide.addEventListener('mousemove', AudioPlayerManager.setVolume);
        });

        window.addEventListener('resize', AudioPlayerManager.setVolumeOnDevice);
    }

    static setVolumeOnDevice()
    {
        if (/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)) {
            // true for mobile device
            AudioPlayerManager.audio.volume = 1;
            AudioPlayerManager.volumeSlide.value = 100;
            AudioPlayerManager.volumeBtn.style.display = 'none';
            AudioPlayerManager.volumeSlide.style.display = 'none';
            AudioPlayerManager.volumeContainer.classList.replace('col', 'col-10');
        }
        else {
            AudioPlayerManager.volumeContainer.classList.replace('col-10', 'col');
            AudioPlayerManager.volumeBtn.style.display = 'initial';
            AudioPlayerManager.volumeSlide.style.display = 'initial';
        }
    }

    static setVolume(e)
    {
        AudioPlayerManager.lastVolume = AudioPlayerManager.audio.volume;
        AudioPlayerManager.audio.volume = e.currentTarget.value / 100;
        AudioPlayerManager.audioIconUpdate();
    }

    static audioIconUpdate()
    {
        if (AudioPlayerManager.audio.volume === 0) {
            AudioPlayerManager.volumeIconHandler('mdi-volume-mute');
        }
        else if (AudioPlayerManager.audio.volume <= 0.4) {
            AudioPlayerManager.volumeIconHandler('mdi-volume-low');
        }
        else if (AudioPlayerManager.audio.volume <= 0.8) {
            AudioPlayerManager.volumeIconHandler('mdi-volume-medium');
        }
        else {
            AudioPlayerManager.volumeIconHandler('mdi-volume-high');
        }
    }

    static volumeIconHandler(replaceClass)
    {
        AudioPlayerManager.volumeBtn.classList.replace('mdi-volume-mute', replaceClass);
        AudioPlayerManager.volumeBtn.classList.replace('mdi-volume-low', replaceClass);
        AudioPlayerManager.volumeBtn.classList.replace('mdi-volume-medium', replaceClass);
        AudioPlayerManager.volumeBtn.classList.replace('mdi-volume-high', replaceClass);
    }

    /**
     * Active tous les listeners
     */
    static setAllListener()
    {
        AudioPlayerManager.audio.addEventListener('timeupdate', AudioPlayerManager.timeUpdateHandler);
        AudioPlayerManager.audio.addEventListener('progress', AudioPlayerManager.loadedProgressHandler);
        AudioPlayerManager.progressTime.addEventListener('click', AudioPlayerManager.progressBarClickHandler);
        AudioPlayerManager.playBtn.addEventListener('click', AudioPlayerManager.playBtnClickHandler);
        AudioPlayerManager.volumeBarHandlerListener();
    }

    /**
     * Récupère le titre et le définit dans le lecteur
     */
    static setTitleToPlayer()
    {
        AudioPlayerManager.songTitle.textContent = AudioPlayerManager.currentPlayBtnClicked.parentNode.parentNode.querySelector('.track-title').textContent;
    }

    /**
     * Récupère l'artiste et le définit dans le lecteur
     */
    static setArtistToPlayer()
    {
        AudioPlayerManager.songArtists.textContent = AudioPlayerManager.currentPlayBtnClicked.parentNode.parentNode.querySelector('.track-artists').textContent;
    }

    /**
     * Calcul l'url avec un timestamp et le hash
     *
     * @param hash
     * @returns {string}
     */
    static calculateUrl(hash)
    {
        let currentDate = Date.now() - 1613951000000;
        return '/listen/' + hash + '/' + currentDate;
    }

    /**
     * Récupère le temps total jouer formater
     * @returns string
     */
    static getFormattedPlayedTime()
    {
        let seconds_played = parseInt(this.audio.currentTime % 60);
        let minutes_played = parseInt((this.audio.currentTime / 60) % 60);
        return AudioPlayerManager.zeroWarn(seconds_played, minutes_played);
    }

    /**
     * Récupère le temps total à jouer formater
     * @returns {string|null}
     */
    static getFormattedTotalDuration()
    {
        let total_seconds = parseInt(this.audio.duration % 60);
        let total_minutes = parseInt((this.audio.duration / 60) % 60);
        if (isNaN(total_minutes) || isNaN(total_seconds)) {
            return null;
        }
        return AudioPlayerManager.zeroWarn(total_seconds, total_minutes);
    }

    /**
     * Au clique sur la progress bar définir la taille de la progress bar la ou on clique
     * Puis appeler la fonction sliderAvancement pour calculer le temps en seconde par rapport au pourcentage
     *
     * @param e
     */
    static progressBarClickHandler(e)
    {
        AudioPlayerManager.progressBarPlayed.style.width = e.offsetX + 'px';
        let pct = Math.floor((e.offsetX / AudioPlayerManager.progressTime.offsetWidth) * 100);
        AudioPlayerManager.sliderAvancement(pct);
        console.log('clicked');
    }

    /**
     * Change l'avancement de la musique a partir du pourcentage
     *
     * @param pourcent
     */
    static sliderAvancement(pourcent)
    {
        let seconds = AudioPlayerManager.audio.duration * (pourcent / 100);
        AudioPlayerManager.audio.currentTime = seconds;
        if (!this.isPlaying()) {
            AudioPlayerManager.audio.play();
        }
    }

    /**
     * Gère la bar de chargement
     */
    static loadedProgressHandler()
    {
        try {

            let loadedPourcentage = Math.floor((AudioPlayerManager.audio.buffered.end(0) / AudioPlayerManager.audio.duration) * 100);
            AudioPlayerManager.progressBarSeek.style.width = loadedPourcentage + '%';
        }
        catch (e) {}
    }

    static zeroWarn(minutes, seconds)
    {
        return [(seconds >= 10) ? seconds : '0' + seconds, (minutes >= 10) ? minutes : '0' + minutes].join(':');
    }

    static playAudio()
    {
        this.audio.play();
    }

    static pauseAudio()
    {
        this.audio.pause();
    }

    static isPlaying()
    {
        return !AudioPlayerManager.audio.paused;
    }
}