<?php


namespace App\Controllers;


use App\Models\Tracks;
use Cassandra\Date;

class PlayerController
{
    /**
     * Permet de lire une musique via le script PHP.
     *
     * La fonction vérifie que le timestamp est valide par rapport à celui envoyé par la requête du
     * lecteur audio, que le header Host est bien égal à la constante DEFAULT_DOMAIN_NAME, et que
     * le header Referer contient bien aussi la valeur de la constante DEFAULT_DOMAIN_NAME
     *
     *
     * @param string $hash
     * @param int    $date
     *
     * @copyright https://github.com/tuxxin/MP4Streaming
     */
    public function listenMusic(string $hash, int $date): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'GET')
        {
            header('HTTP/1.1 405 Method Not Allowed', 405);
            exit();
        }
        /** Ne pas utiliser la session pour ce script, il bloque le chargement de tout autre script charger tant que ce script est en cours */
        session_write_close();
        /** Timestamp en millisecondes (- 1613951000000) renvoyé en GET lors du lancement de la lecture d'une musique */
        $requestLinkDate = (new \DateTime())->setTimestamp(($date + 1613951000000) / 1000);
        $diffDate        = (new \DateTime())->diff($requestLinkDate);

        /**
         * Si l'entête contient Content-Range, que le referer est "DOMAIN/category"... et que le temps du timestamp soit inférieur à 10 minutes
         */
        if ($diffDate->i < 10 && $diffDate->h === 0 && $diffDate->days === 0)
        {
            if (isset($_SERVER['HTTP_RANGE']) && isset($_SERVER['HTTP_HOST']) && $_SERVER['HTTP_HOST'] === DEFAULT_DOMAIN_NAME && isset($_SERVER['HTTP_REFERER']) && (str_contains($_SERVER['HTTP_REFERER'], '/category/') || str_contains($_SERVER['HTTP_REFERER'], '/new-release') || str_contains($_SERVER['HTTP_REFERER'], '/top-50/') || str_contains($_SERVER['HTTP_REFERER'], '/profile/download-lists/') || str_contains($_SERVER['HTTP_REFERER'], '/search')))
            {
                $trackInfo = (new Tracks())->setHash($hash)
                                           ->getMp3ByHash();

                if ($trackInfo)
                {
                    $file   = APP_ROOT . 'public' . $trackInfo->getPath();
                    $fp     = @fopen($file, 'rb');
                    $size   = filesize($file); // File size
                    $length = $size; // Content length
                    $start  = 0; // Start byte
                    $end    = $size - 1; // End byte
                    header('Expires: ' . (new \DateTime())->format('D, d M Y h:i:s e'));
                    header('Content-type: audio/mpeg');

                    header('Accept-Ranges: bytes');
                    if (isset($_SERVER['HTTP_RANGE']))
                    {
                        $c_start = $start;
                        $c_end   = $end;
                        [, $range] = explode('=', $_SERVER['HTTP_RANGE'], 2);
                        if (str_contains($range, ','))
                        {
                            header('HTTP/1.1 416 Requested Range Not Satisfiable');
                            header('Content-Range: bytes ' . $start . '-' . $end . '/' . $size);
                            exit;
                        }

                        if ($range === '-')
                        {
                            $c_start = $size - substr($range, 1);
                        }
                        else
                        {
                            $range   = explode('-', $range);
                            $c_start = $range[0];
                            $c_end   = (isset($range[1]) && is_numeric($range[1])) ? $range[1] : $size;
                        }
                        $c_end = ($c_end > $end) ? $end : $c_end;

                        if ($c_start > $c_end || $c_start > $size - 1 || $c_end >= $size)
                        {
                            header('HTTP/1.1 416 Requested Range Not Satisfiable');
                            header('Content-Range: bytes ' . $start . '-' . $end . '/' . $size);
                            exit;
                        }
                        $start  = $c_start;
                        $end    = $c_end;
                        $length = $end - $start + 1;
                        fseek($fp, $start);
                        header('HTTP/1.1 206 Partial Content');
                    }
                    header('Content-Range: bytes ' . $start . '-' . $end . '/' . $size);
                    header('Content-Length: ' . $length);
                    $buffer = 1024 * 8;
                    while (!feof($fp) && ($p = ftell($fp)) <= $end)
                    {
                        if ($p + $buffer > $end)
                        {
                            $buffer = $end - $p + 1;
                        }
                        set_time_limit(0);
                        echo fread($fp, $buffer);
                        flush();
                    }
                    fclose($fp);
                    exit();
                }
                header('HTTP/1.1 404 Not Found');
                exit;
            }
        }
        header('HTTP/1.1 406 Not Acceptable', 406);
        exit;
    }
}