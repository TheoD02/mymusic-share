<?php


namespace App\Controllers\FrontOffice;


use App\Models\Tracks;
use Core\Base\BaseController;

class NewReleaseController extends BaseController
{
    public function showNewRelease(): void
    {
        $tracksList = (new Tracks())->getTracksList(false, 0, 50);
        $this->render('NewRelease/NewRelease', 'Nouveautés', [
            'tracksList' => $tracksList,
        ]);
    }
}