<?php
class IMDbAPI {
    private $url = 'https://graph.imdbapi.dev/v1';

    public function queryTitleById( $titleId ) {
        $query = $this->buildQuery( $titleId );
        $response = $this->sendRequest( $query );
        return $response;
    }

    private function buildQuery( $titleId ) {
        return json_encode( [
            'query' => "
                query titleById {
                    title(id: \"$titleId\") {
                        id
                        type
                        primary_title
                        original_title
                        start_year
                        end_year
                        plot
                        rating {
                            aggregate_rating
                            votes_count
                        }
                        genres
                        posters {
                            url
                        }
                    }
                }"
        ] );
    }

    private function sendRequest( $query ) {
        $ch = curl_init($this->url);

        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
        curl_setopt( $ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ',
            'Content-Type: application/json'
        ]);
        curl_setopt( $ch, CURLOPT_POST, true );
        curl_setopt( $ch, CURLOPT_POSTFIELDS, $query );

        $response = curl_exec( $ch );

        if ( curl_errno($ch) ) {
            throw new Exception( 'cURL error: ' . curl_error($ch) );
        }

        curl_close( $ch );
        
        return json_decode( $response, true );
    }
};

// $imdbAPI = new IMDbAPI();

// $titleId = 'tt0468569';
// $response = $imdbAPI->queryTitleById( $titleId );
// print_r( $response );
