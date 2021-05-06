<?php
use GuzzleHttp\Client;
use PHPUnit\Framework\TestCase;

class BookTest extends TestCase
{
    public function testIndex()
    {
        $client = new Client(['base_uri' =>'http://localhost/shoppinal/index.php/']);
        $response = $client->get('books');
        $decodedResponse = json_decode($response->getBody()->getContents(), true);
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testGet()
    {
        $client = new Client(['base_uri' =>'http://localhost/shoppinal/index.php/']);
        $response = $client->get('books/get?id=1');
        $decodedResponse = json_decode($response->getBody()->getContents(), true);
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testStore()
    {
        $client = new Client(['base_uri' =>'http://localhost/shoppinal/index.php/']);
        $response = $client->request('POST', 'books/store', [
            'form_params' => [
            'title' => 'test title',
            'author'=> 'test author',
            'isbn'=>'ISBN-A9898-AD090',
            'release_date'=>'20-02-1989'
        ]]);
        $decodedResponse = json_decode($response->getBody()->getContents(), true);
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testUpdate()
    {
        $client = new Client(['base_uri' =>'http://localhost/shoppinal/index.php/']);
        $response = $client->request('POST', 'books/update', [
            'form_params' => [
                'id'=>'3',
                'title' => 'test',
                'author'=> 'test author',
                'isbn'=>'ISBN-A9898-AD090',
                'release_date'=>'20-02-1989'
            ]
        ]);
        $decodedResponse = json_decode($response->getBody()->getContents(), true);
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testDelete()
    {
        $client = new Client(['base_uri' =>'http://localhost/shoppinal/index.php/']);
        $response = $client->get('books/delete?id=4');
        $decodedResponse = json_decode($response->getBody()->getContents(), true);
        $this->assertEquals(200, $response->getStatusCode());
    }
}

?>