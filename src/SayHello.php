<?php

namespace App;

class SayHello {
    public function __invoke(){
        printf(json_encode([
            'statusCode' => 200,
            'body' => json_encode(['status' => 'Hello']),
        ]));
    }
}
