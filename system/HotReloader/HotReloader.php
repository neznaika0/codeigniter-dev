<?php

namespace CodeIgniter\HotReloader;

class HotReloader
{
    public function run()
    {
        ini_set('output_buffering', 'Off');
        ini_set('zlib.output_compression', 'Off');

        header("Cache-Control: no-store");
        header("Content-Type: text/event-stream");
        header('Access-Control-Allow-Methods: GET');

        ob_end_clean();
        set_time_limit(0);

        $hasher = new DirectoryHasher();
        $appHash = $hasher->hash();

        while (true) {
            if( connection_status() != CONNECTION_NORMAL or connection_aborted() ) {
                break;
            }

            $currentHash = $hasher->hash();

            // If hash has changed, tell the browser to reload.
            if ($currentHash !== $appHash) {
                $appHash = $currentHash;

                $this->sendEvent('reload', ['time' => date('Y-m-d H:i:s')]);
                break;
            } elseif (rand(1, 10) > 8) {
                $this->sendEvent('ping', ['time' => date('Y-m-d H:i:s')]);
            }

            sleep(1);
        }
    }

    /**
     * Send an event to the browser.
     */
    private function sendEvent(string $event, array $data): void
    {
        echo "event: {$event}\n";
        echo "data: " . json_encode($data) ."\n\n";

        ob_flush();
        flush();
    }
}
