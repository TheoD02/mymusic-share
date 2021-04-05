<?php


namespace App\Controllers\FrontOffice;


use App\Models\Tracks;
use Core\Base\BaseController;
use Core\Form\FormValidator;

class TracksSearchController extends BaseController
{
    public function searchTrack(): void
    {
        $FV = new FormValidator($_POST);
        /** Si le formulaire est bien envoyée et le token CSRF est valide */
        if ($FV->checkFormIsSend('searchTrackForm'))
        {
            /** Si le champ est valide */
            $FV->verify('searchTerms')->isNotEmpty();

            $searchTerms = $FV->getFieldValue('searchTerms');

            $trackMdl   = new Tracks();
            /** Rechercher en base de données si on trouve une correspondance */
            $tracksList = $trackMdl->searchTrackByTerms($searchTerms);
        }
        $this->render('TracksSearch', 'Résultat pour : ' . $searchTerms, [
            'viewTitle'         => 'Résultat pour : ' . $searchTerms,
            'tracksList'        => $tracksList ?? null,
            'userDownloadsList' => $userDownloadsList ?? null,
        ]);
    }
}